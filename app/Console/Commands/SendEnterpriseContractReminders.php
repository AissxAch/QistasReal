<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\Subscription;
use App\Models\User;
use App\Services\NotificationTemplateService;
use Illuminate\Console\Command;

class SendEnterpriseContractReminders extends Command
{
    protected $signature = 'reminders:enterprise-contracts';

    protected $description = 'Send enterprise contract expiry reminders for subscriptions ending in 30, 15, or 7 days';

    public function handle(): int
    {
        $targetDays = [30, 15, 7];
        $sentCount = 0;

        $subscriptions = Subscription::withoutGlobalScopes()
            ->with('lawFirm:id,name')
            ->where('plan', 'enterprise')
            ->whereIn('status', ['active', 'trial'])
            ->whereNotNull('contract_ends_at')
            ->get();

        foreach ($subscriptions as $subscription) {
            $daysRemaining = now()->startOfDay()->diffInDays($subscription->contract_ends_at->copy()->startOfDay(), false);

            if (!in_array($daysRemaining, $targetDays, true)) {
                continue;
            }

            $owners = User::query()
                ->where('law_firm_id', $subscription->law_firm_id)
                ->where('role', 'owner')
                ->get();

            foreach ($owners as $owner) {
                $alreadySent = Notification::query()
                    ->where('user_id', $owner->id)
                    ->where('type', 'enterprise_contract_reminder')
                    ->where('data->subscription_id', $subscription->id)
                    ->where('data->reminder_days', $daysRemaining)
                    ->whereDate('created_at', today())
                    ->exists();

                if ($alreadySent) {
                    continue;
                }

                NotificationTemplateService::enterpriseContractReminder($owner, $subscription, $daysRemaining);
                $sentCount++;
            }
        }

        $this->info("Enterprise contract reminders sent: {$sentCount}");

        return self::SUCCESS;
    }
}
