<?php

namespace Database\Factories;

use App\Models\LawFirm;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LawFirm>
 */
class LawFirmFactory extends Factory
{
    protected $model = LawFirm::class;

    public function definition(): array
    {
        $cities = ['الجزائر العاصمة', 'وهران', 'قسنطينة', 'عنابة', 'سطيف', 'باتنة', 'تيزي وزو', 'سكيكدة'];
        $prefixes = ['مكتب', 'مجمع', 'مجموعة', 'شركة'];
        $domains = ['cabinetdz.com', 'qistas-law.dz', 'advocates-dz.com'];

        $status = fake()->randomElement(['active', 'active', 'active', 'suspended', 'expired']);

        return [
            'name' => fake()->randomElement($prefixes) . ' ' . fake()->lastName() . ' للمحاماة',
            'email' => fake()->unique()->userName() . '@' . fake()->randomElement($domains),
            'phone' => '+213' . fake()->numerify('5########'),
            'address' => 'حي ' . fake()->streetName() . '، ' . fake()->randomElement($cities),
            'logo' => null,
            'tax_number' => 'NIF-' . fake()->numerify('###############'),
            'subscription_status' => $status,
            'subscription_ends_at' => $status === 'active'
                ? now()->addMonths(fake()->numberBetween(1, 12))
                : now()->subMonths(fake()->numberBetween(1, 6)),
        ];
    }
}
