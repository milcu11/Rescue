<?php

namespace Tests\Feature;

use App\Models\InventoryItem;
use App\Models\InventoryMovement;
use App\Models\Notification;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryManagementTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $slug = 'lgu_staff'): User
    {
        $role = Role::create(['name' => ucfirst(str_replace('_', ' ', $slug)), 'slug' => $slug]);

        return User::create([
            'name' => 'Inventory Tester',
            'email' => $slug . '@example.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'status' => 'active',
        ]);
    }

    private function item(User $user, int $quantity = 10): InventoryItem
    {
        return InventoryItem::create([
            'name' => 'Rice',
            'category' => 'food',
            'quantity' => $quantity,
            'unit' => 'sacks',
            'minimum_threshold' => 2,
            'is_active' => true,
            'created_by' => $user->id,
        ]);
    }

    public function test_stock_in_updates_quantity_and_records_movement(): void
    {
        $user = $this->user();
        $item = $this->item($user);

        $response = $this->actingAs($user)->post(route('inventory.stock-in', $item), [
            'quantity' => 5,
            'notes' => 'Supplier delivery',
        ]);

        $response->assertRedirect(route('inventory.edit', $item));
        $this->assertDatabaseHas('inventory_items', ['id' => $item->id, 'quantity' => 15]);
        $this->assertDatabaseHas('inventory_movements', [
            'inventory_item_id' => $item->id,
            'type' => 'stock_in',
            'quantity' => 5,
            'quantity_before' => 10,
            'quantity_after' => 15,
            'user_id' => $user->id,
            'source_type' => 'manual_stock_in',
        ]);
    }

    public function test_super_admin_can_deactivate_and_reactivate_an_item(): void
    {
        $user = $this->user('super_admin');
        $item = $this->item($user);

        $this->actingAs($user)->patch(route('inventory.toggle-active', $item));
        $this->assertDatabaseHas('inventory_items', ['id' => $item->id, 'is_active' => false]);

        $this->actingAs($user)->patch(route('inventory.toggle-active', $item));
        $this->assertDatabaseHas('inventory_items', ['id' => $item->id, 'is_active' => true]);
    }

    public function test_item_entering_thirty_day_expiry_window_creates_alert(): void
    {
        $user = $this->user();
        $item = $this->item($user);

        $item->update(['expires_at' => now()->addDays(20)->toDateString()]);

        $this->assertDatabaseHas('notifications', [
            'type' => 'near_expiration',
            'title' => 'Item expiring soon',
        ]);
    }
}
