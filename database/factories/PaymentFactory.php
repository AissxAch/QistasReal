<?php

namespace Database\Factories;

use App\Models\LawFirm;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        $status = fake()->randomElement(['completed', 'completed', 'pending', 'failed', 'refunded']);

        return [
            'law_firm_id' => LawFirm::factory(),
            'subscription_id' => fake()->boolean(85) ? Subscription::factory() : null,
            'amount' => fake()->numberBetween(2500, 120000),
            'currency' => 'DZD',
            'payment_method' => fake()->randomElement(['card', 'bank_transfer', 'cash', 'ccp']),
            'status' => $status,
            'transaction_id' => $status !== 'pending' ? 'TX-' . strtoupper(fake()->bothify('??#####??')) : null,
            'payment_data' => [
                'gateway' => fake()->randomElement(['cash', 'baridiMob', 'bank', 'stripe_mock']),
                'reference' => strtoupper(fake()->bothify('REF-####-??')),
                'processed_at' => now()->toDateTimeString(),
            ],
        ];
    }
}
