<?php

namespace Tests\Feature;

use App\Models\InventoryItem;
use App\Models\Notification;
use App\Models\Role;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class NotificationManagementTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $slug = 'mdrrmo'): User
    {
        $role = Role::create(['name' => ucfirst($slug), 'slug' => $slug]);

        return User::create([
            'name' => 'Notification Tester',
            'email' => $slug . '@example.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'status' => 'active',
        ]);
    }

    public function test_role_targeted_notifications_are_visible_only_to_the_role(): void
    {
        $mdrrmo = $this->user('mdrrmo');
        $warehouse = $this->user('lgu_staff');
        Notification::create([
            'role_target' => 'mdrrmo',
            'type' => 'center_full',
            'title' => 'Center full',
            'message' => 'Full',
        ]);

        $this->assertCount(1, app(NotificationService::class)->recentForUser($mdrrmo->id, 'mdrrmo'));
        $this->assertCount(0, app(NotificationService::class)->recentForUser($warehouse->id, 'lgu_staff'));
    }

    public function test_opening_a_notification_marks_it_read_and_redirects(): void
    {
        $user = $this->user();
        $notification = Notification::create([
            'role_target' => 'mdrrmo',
            'type' => 'general',
            'title' => 'Action needed',
            'message' => 'Review this item.',
            'link' => route('dashboard'),
        ]);

        $response = $this->actingAs($user)->get(route('notifications.open', $notification));

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('notifications', ['id' => $notification->id, 'is_read' => true]);
    }

    public function test_expiration_command_creates_a_role_targeted_alert_without_editing_item(): void
    {
        $user = $this->user('lgu_staff');
        $item = InventoryItem::create([
            'name' => 'Medical kit',
            'category' => 'medical',
            'quantity' => 10,
            'unit' => 'boxes',
            'minimum_threshold' => 1,
            'is_active' => true,
            'created_by' => $user->id,
        ]);
        DB::table('inventory_items')->where('id', $item->id)->update([
            'expires_at' => now()->addDays(10)->toDateString(),
        ]);

        $this->artisan('notifications:check-expirations')->assertExitCode(0);

        $this->assertDatabaseHas('notifications', [
            'type' => 'near_expiration',
            'role_target' => 'lgu_staff',
        ]);
    }
}
