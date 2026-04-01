<?php

namespace App\Actions\Fortify;

use App\Models\LawFirm;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'law_firm_name' => ['required', 'string', 'max:255'],
            'law_firm_phone' => ['nullable', 'string', 'max:50'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return DB::transaction(function () use ($input) {
            $lawFirm = LawFirm::create([
                'name' => $input['law_firm_name'],
                'email' => $input['email'],
                'phone' => $input['law_firm_phone'] ?? null,
                'subscription_status' => 'expired',
                'subscription_ends_at' => null,
            ]);

            return User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'law_firm_id' => $lawFirm->id,
                'role' => 'owner',
                'activated_at' => now(),
            ]);
        });
    }
}
