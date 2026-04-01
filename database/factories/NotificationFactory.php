<?php

namespace Database\Factories;

use App\Models\LawFirm;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Notification>
 */
class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        $type = fake()->randomElement([
            'session_reminder',
            'task_due',
            'payment_received',
            'case_updated',
            'system_alert',
        ]);

        return [
            'law_firm_id' => LawFirm::factory(),
            'user_id' => User::factory(),
            'type' => $type,
            'title' => match ($type) {
                'session_reminder' => 'تذكير بجلسة قادمة',
                'task_due' => 'مهمة تقترب من الموعد النهائي',
                'payment_received' => 'تم تسجيل دفعة جديدة',
                'case_updated' => 'تحديث على إحدى القضايا',
                default => 'تنبيه من النظام',
            },
            'body' => fake()->sentence(14),
            'data' => [
                'entity_id' => fake()->numberBetween(1, 500),
                'url' => '/dashboard',
            ],
            'read_at' => fake()->boolean(60) ? fake()->dateTimeBetween('-10 days', 'now') : null,
        ];
    }
}
