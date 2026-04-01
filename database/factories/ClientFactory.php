<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\LawFirm;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        $cities = ['الجزائر', 'وهران', 'قسنطينة', 'عنابة', 'سطيف', 'تيارت', 'بسكرة', 'جيجل'];

        return [
            'law_firm_id' => LawFirm::factory(),
            'name' => fake()->name(),
            'phone' => '+213' . fake()->numerify('5########'),
            'email' => fake()->optional(0.8)->safeEmail(),
            'address' => 'حي ' . fake()->streetName() . '، ' . fake()->randomElement($cities),
            'id_number' => fake()->numerify('##########'),
            'notes' => fake()->optional(0.65)->realTextBetween(60, 180),
        ];
    }
}
