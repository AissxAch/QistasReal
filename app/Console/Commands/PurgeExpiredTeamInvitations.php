<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PurgeExpiredTeamInvitations extends Command
{
    protected $signature = 'team-invitations:purge-expired';

    protected $description = 'Delete invited team members who did not activate within 7 days';

    public function handle(): int
    {
        $deletedCount = 0;

        $expiredMembers = User::query()
            ->whereNotNull('invited_at')
            ->whereNull('activated_at')
            ->whereNotNull('invitation_expires_at')
            ->where('invitation_expires_at', '<=', now())
            ->get();

        foreach ($expiredMembers as $member) {
            $snapshot = $member->only([
                'name', 'email', 'role', 'phone', 'specialty',
                'invited_at', 'invitation_expires_at', 'activated_at',
            ]);

            DB::transaction(function () use ($member, $snapshot) {
                DB::table('password_reset_tokens')->where('email', $member->email)->delete();

                AuditLog::record(
                    actor: null,
                    action: 'team_invitation_expired_deleted',
                    modelType: User::class,
                    modelId: $member->id,
                    lawFirmId: $member->law_firm_id,
                    oldValues: $snapshot,
                    newValues: [
                        'deleted_at' => now()->toDateTimeString(),
                        'reason' => 'Invitation not activated within 7 days',
                    ],
                );

                $member->delete();
            });

            $deletedCount++;
        }

        $this->info("Expired invitations deleted: {$deletedCount}");

        return self::SUCCESS;
    }
}