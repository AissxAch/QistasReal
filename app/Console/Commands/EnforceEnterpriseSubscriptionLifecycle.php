<?php

namespace App\Console\Commands;

use App\Models\LawFirm;
use App\Models\Notification;
use App\Models\Subscription;
use App\Models\User;
use App\Services\NotificationTemplateService;
use Illuminate\Console\Command;

class EnforceEnterpriseSubscriptionLifecycle extends Command
{
    protected $signature = 'subscriptions:enforce-enterprise-lifecycle';

    protected $description = 'Expire enterprise subscriptions at contract end and suspend them after grace period';

    private const GRACE_DAYS = 7;

    public function handle(): int
    {
        $updatedCount = 0;

        $subscriptions = Subscription::withoutGlobalScopes()
            ->with('lawFirm:id,name')
            ->where('plan', 'enterprise')
            ->whereNotNull('contract_ends_at')
            ->whereIn('status', ['active', 'trial', 'expired'])
            ->get();

        foreach ($subscriptions as $subscription) {
            $contractEnd = $subscription->contract_ends_at->copy()->startOfDay();
            $today = now()->startOfDay();
            $daysPastEnd = $contractEnd->diffInDays($today, false);

            if ($daysPastEnd < 0) {
                continue;
            }

            $owners = User::query()
                ->where('law_firm_id', $subscription->law_firm_id)
                ->where('role', 'owner')
                ->get();

            if ($daysPastEnd >= self::GRACE_DAYS) {
                if ($subscription->status !== 'suspended') {
                    $subscription->update([
                        'status' => 'suspended',
                        'ends_at' => $subscription->contract_ends_at,
                    ]);

                    $this->syncLawFirmStatus($subscription, 'suspended');
                    $updatedCount++;
                }

                foreach ($owners as $owner) {
                    $alreadySent = Notification::query()
                        ->where('user_id', $owner->id)
                        ->where('type', 'enterprise_contract_suspended')
                        ->where('data->subscription_id', $subscription->id)
                        ->exists();

                    if (!$alreadySent) {
                        NotificationTemplateService::enterpriseContractSuspended($owner, $subscription);
                    }
                }

                continue;
            }

            if ($subscription->status !== 'expired') {
                $subscription->update([
                    'status' => 'expired',
                    'ends_at' => $subscription->contract_ends_at,
                ]);

                $this->syncLawFirmStatus($subscription, 'expired');
                $updatedCount++;
            }

            foreach ($owners as $owner) {
                $alreadySent = Notification::query()
                    ->where('user_id', $owner->id)
                    ->where('type', 'enterprise_contract_expired')
                    ->where('data->subscription_id', $subscription->id)
                    ->exists();

                if (!$alreadySent) {
                    NotificationTemplateService::enterpriseContractExpired($owner, $subscription, self::GRACE_DAYS);
                }
            }
        }

        $this->info("Enterprise lifecycle updates applied: {$updatedCount}");

        return self::SUCCESS;
    }

    private function syncLawFirmStatus(Subscription $subscription, string $status): void
    {
        $lawFirm = LawFirm::find($subscription->law_firm_id);

        if (!$lawFirm) {
            return;
        }

        $lawFirm->update([
            'subscription_status' => $status,
            'subscription_ends_at' => $subscription->contract_ends_at,
        ]);
    }
}
