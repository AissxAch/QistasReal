<?php

namespace Database\Factories;

use App\Models\CaseModel;
use App\Models\CourtSession;
use App\Models\LawFirm;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CourtSession>
 */
class CourtSessionFactory extends Factory
{
    protected $model = CourtSession::class;

    public function definition(): array
    {
        return [
            'law_firm_id' => LawFirm::factory(),
            'case_id' => CaseModel::factory(),
            'session_date' => fake()->dateTimeBetween('-2 months', '+4 months'),
            'session_time' => fake()->time('H:i:s'),
            'court' => fake()->randomElement([
                'محكمة الجزائر',
                'محكمة وهران',
                'محكمة عنابة',
                'مجلس قضاء قسنطينة',
            ]),
            'room' => 'قاعة ' . fake()->numberBetween(1, 15),
            'notes' => fake()->optional(0.6)->sentence(12),
            'status' => fake()->randomElement(['scheduled', 'scheduled', 'completed', 'postponed', 'cancelled']),
        ];
    }
}
