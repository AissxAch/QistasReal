<?php

namespace Database\Factories;

use App\Models\AuditLog;
use App\Models\LawFirm;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AuditLog>
 */
class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    public function definition(): array
    {
        $action = fake()->randomElement(['created', 'updated', 'deleted', 'viewed', 'exported', 'logged_in']);
        $modelType = fake()->randomElement([
            'App\\Models\\CaseModel',
            'App\\Models\\Client',
            'App\\Models\\CourtSession',
            'App\\Models\\Task',
            'App\\Models\\Payment',
            'App\\Models\\Subscription',
            'App\\Models\\User',
        ]);

        return [
            'law_firm_id' => LawFirm::factory(),
            'user_id' => fake()->boolean(92) ? User::factory() : null,
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => fake()->numberBetween(1, 500),
            'old_values' => $action === 'updated' ? ['status' => fake()->randomElement(['pending', 'active'])] : null,
            'new_values' => in_array($action, ['created', 'updated'], true)
                ? ['status' => fake()->randomElement(['active', 'closed', 'completed'])]
                : null,
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
        ];
    }
}
