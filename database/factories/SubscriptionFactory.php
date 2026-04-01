<?php

namespace Database\Factories;

use App\Models\LawFirm;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subscription>
 */
class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition(): array
    {
        $plan = fake()->randomElement(['solo', 'office', 'enterprise']);
        $status = fake()->randomElement(['active', 'active', 'active', 'expired', 'cancelled']);

        $startsAt = fake()->dateTimeBetween('-10 months', '-2 days');
        $endsAt = $status === 'active'
            ? fake()->dateTimeBetween('+1 week', '+8 months')
            : fake()->dateTimeBetween('-6 months', '-1 day');

        $amountByPlan = [
            'solo' => 2500,
            'office' => 8000,
            'enterprise' => fake()->numberBetween(35000, 120000),
        ];

        return [
            'law_firm_id' => LawFirm::factory(),
            'plan' => $plan,
            'status' => $status,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'trial_ends_at' => fake()->optional(0.35)->dateTimeBetween('-11 months', '-8 months'),
            'amount' => $amountByPlan[$plan],
            'currency' => 'DZD',
            'contract_number' => $plan === 'enterprise' ? 'CTR-' . fake()->numerify('######') : null,
            'contract_starts_at' => $plan === 'enterprise' ? fake()->dateTimeBetween('-1 year', '-2 months') : null,
            'contract_ends_at' => $plan === 'enterprise' ? fake()->dateTimeBetween('+2 months', '+2 years') : null,
            'user_limit' => match ($plan) {
                'solo' => 2,
                'office' => 5,
                default => fake()->randomElement([25, 50, 100, 200]),
            },
            'billing_cycle' => fake()->randomElement(['monthly', 'quarterly', 'yearly']),
            'enterprise_account_name' => $plan === 'enterprise' ? fake()->company() : null,
        ];
    }
}
