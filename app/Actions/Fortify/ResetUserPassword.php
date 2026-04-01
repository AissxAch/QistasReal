<?php

namespace App\Actions\Fortify;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

class ResetUserPassword implements ResetsUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and reset the user's forgotten password.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function reset(User $user, array $input): void
    {
        Validator::make($input, [
            'password' => $this->passwordRules(),
        ])->validate();

        $wasPendingActivation = $user->invited_at !== null && $user->activated_at === null;
        $originalInvitationExpiresAt = $user->getOriginal('invitation_expires_at');

        $user->forceFill([
            'password' => Hash::make($input['password']),
            'activated_at' => $wasPendingActivation ? now() : $user->activated_at,
            'invitation_expires_at' => $wasPendingActivation ? null : $user->invitation_expires_at,
            'email_verified_at' => $user->email_verified_at ?? ($wasPendingActivation ? now() : null),
        ])->save();

        if ($wasPendingActivation) {
            AuditLog::record(
                actor: $user,
                action: 'team_member_activated',
                modelType: User::class,
                modelId: $user->id,
                lawFirmId: $user->law_firm_id,
                oldValues: [
                    'activated_at' => null,
                    'invitation_expires_at' => $originalInvitationExpiresAt,
                ],
                newValues: [
                    'activated_at' => optional($user->activated_at)?->toDateTimeString(),
                    'email_verified_at' => optional($user->email_verified_at)?->toDateTimeString(),
                ],
            );
        }
    }
}
