<?php

namespace Tests\Feature;

use App\Models\LawFirm;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoreSmokeAndPermissionsTest extends TestCase
{
    use RefreshDatabase;

    private function createFirm(array $overrides = []): LawFirm
    {
        return LawFirm::create(array_merge([
            'name' => 'مكتب الاختبار',
            'email' => fake()->unique()->safeEmail(),
            'phone' => '0550000000',
        ], $overrides));
    }

    private function createUser(LawFirm $firm, string $role = 'lawyer', array $overrides = []): User
    {
        return User::factory()->create(array_merge([
            'law_firm_id' => $firm->id,
            'role' => $role,
        ], $overrides));
    }

    public function test_owner_can_access_team_index(): void
    {
        $firm = $this->createFirm();
        $owner = $this->createUser($firm, 'owner');

        $response = $this->actingAs($owner)->get(route('team.index'));

        $response->assertOk();
    }

    public function test_non_owner_cannot_access_team_management(): void
    {
        $firm = $this->createFirm();
        $lawyer = $this->createUser($firm, 'lawyer');

        $response = $this->actingAs($lawyer)->get(route('team.index'));

        $response->assertForbidden();
    }

    public function test_core_authenticated_routes_are_reachable(): void
    {
        $firm = $this->createFirm();
        $owner = $this->createUser($firm, 'owner');

        $this->actingAs($owner)->get(route('dashboard'))->assertOk();
        $this->actingAs($owner)->get(route('cases.index'))->assertOk();
        $this->actingAs($owner)->get(route('clients.index'))->assertOk();
        $this->actingAs($owner)->get(route('tasks.index'))->assertOk();
        $this->actingAs($owner)->get(route('sessions.index'))->assertOk();
        $this->actingAs($owner)->get(route('notifications.index'))->assertOk();
    }

    public function test_mark_all_notifications_as_read(): void
    {
        $firm = $this->createFirm();
        $owner = $this->createUser($firm, 'owner');

        Notification::create([
            'law_firm_id' => $firm->id,
            'user_id' => $owner->id,
            'type' => 'task_due',
            'title' => 'مهمة مستحقة',
            'body' => 'لديك مهمة مستحقة اليوم',
            'data' => ['entity_type' => 'task', 'entity_id' => 1],
            'read_at' => null,
        ]);

        $this->actingAs($owner)
            ->post(route('notifications.mark-read'))
            ->assertRedirect();

        $this->assertDatabaseMissing('notifications', [
            'user_id' => $owner->id,
            'read_at' => null,
        ]);
    }
}
