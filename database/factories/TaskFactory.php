<?php

namespace Database\Factories;

use App\Models\CaseModel;
use App\Models\LawFirm;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'law_firm_id' => LawFirm::factory(),
            'case_id' => fake()->boolean(80) ? CaseModel::factory() : null,
            'assigned_to' => fake()->boolean(90) ? User::factory() : null,
            'title' => fake()->randomElement([
                'تحضير مذكرة دفاع',
                'متابعة ملف التنفيذ',
                'الاتصال بالموكل وتحديث الحالة',
                'جمع الوثائق الناقصة',
                'حجز موعد خبير',
                'مراجعة عقد وتسليم ملاحظات',
            ]),
            'description' => fake()->optional(0.7)->realTextBetween(60, 180),
            'due_date' => fake()->optional(0.85)->dateTimeBetween('-3 weeks', '+8 weeks'),
            'due_time' => fake()->optional(0.75)->time('H:i:s'),
            'priority' => fake()->randomElement(['low', 'medium', 'medium', 'high']),
            'status' => fake()->randomElement(['pending', 'in_progress', 'completed', 'cancelled']),
        ];
    }
}
