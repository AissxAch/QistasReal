<?php

namespace Database\Factories;

use App\Models\CaseModel;
use App\Models\LawFirm;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CaseModel>
 */
class CaseModelFactory extends Factory
{
    protected $model = CaseModel::class;

    public function definition(): array
    {
        $types = ['مدنية', 'جزائية', 'تجارية', 'إدارية', 'عائلية', 'عقارية', 'عمالية'];
        $degrees = ['ابتدائي', 'استئناف', 'نقض', 'تنفيذ'];
        $statuses = ['active', 'active', 'active', 'suspended', 'closed', 'archived'];
        $priorities = ['low', 'medium', 'medium', 'high'];

        $startDate = fake()->dateTimeBetween('-2 years', 'now');
        $feesTotal = fake()->numberBetween(30000, 600000);
        $feesPaid = fake()->numberBetween(0, $feesTotal);

        return [
            'law_firm_id' => LawFirm::factory(),
            'case_number' => 'DZ-' . now()->format('Y') . '-' . fake()->unique()->numerify('#####'),
            'title' => fake()->randomElement([
                'نزاع تجاري بين شركتين',
                'دعوى فسخ عقد إيجار',
                'قضية تعويض عن حادث مرور',
                'طعن في قرار إداري',
                'نزاع عمالي حول إنهاء عقد',
                'قضية قسمة تركة',
            ]),
            'court' => fake()->randomElement([
                'محكمة الجزائر',
                'محكمة وهران',
                'محكمة قسنطينة',
                'مجلس قضاء سكيكدة',
                'مجلس الدولة',
            ]),
            'case_type' => fake()->randomElement($types),
            'degree' => fake()->randomElement($degrees),
            'status' => fake()->randomElement($statuses),
            'priority' => fake()->randomElement($priorities),
            'fees_total' => $feesTotal,
            'fees_paid' => $feesPaid,
            'fees_remaining' => $feesTotal - $feesPaid,
            'description' => fake()->optional(0.75)->realTextBetween(100, 250),
            'start_date' => $startDate,
            'next_session_date' => fake()->optional(0.8)->dateTimeBetween('now', '+5 months'),
        ];
    }
}
