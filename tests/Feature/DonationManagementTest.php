<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Models\InventoryItem;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonationManagementTest extends TestCase
{
    use RefreshDatabase;

    private function user(): User
    {
        $role = Role::create(['name' => 'DRRM Officer', 'slug' => 'mdrrmo']);

        return User::create([
            'name' => 'Donation Tester',
            'email' => 'donation@example.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'status' => 'active',
        ]);
    }

    private function donation(User $user): Donation
    {
        return Donation::create([
            'donor_name' => 'Relief Foundation',
            'donor_email' => 'foundation@example.com',
            'type' => 'in-kind',
            'items_description' => 'Rice sacks',
            'created_by' => $user->id,
        ]);
    }

    public function test_verified_in_kind_donation_adds_inventory_and_records_one_movement(): void
    {
        $user = $this->user();
        $donation = $this->donation($user);
        $item = InventoryItem::create([
            'name' => 'Rice',
            'category' => 'food',
            'quantity' => 10,
            'unit' => 'sacks',
            'minimum_threshold' => 2,
            'is_active' => true,
            'created_by' => $user->id,
        ]);

        $this->actingAs($user)->put(route('donations.update', $donation), [
            'donor_name' => $donation->donor_name,
            'donor_email' => $donation->donor_email,
            'type' => 'in-kind',
            'items_description' => $donation->items_description,
            'status' => 'received',
        ]);

        $response = $this->actingAs($user)->put(route('donations.update', $donation), [
            'donor_name' => $donation->donor_name,
            'donor_email' => $donation->donor_email,
            'type' => 'in-kind',
            'items_description' => $donation->items_description,
            'status' => 'verified',
            'inventory_item_id' => $item->id,
            'inventory_quantity' => 5,
        ]);

        $response->assertRedirect(route('donations.index'));
        $this->assertDatabaseHas('donations', [
            'id' => $donation->id,
            'status' => 'verified',
            'inventory_item_id' => $item->id,
            'inventory_quantity' => 5,
        ]);
        $this->assertDatabaseHas('inventory_items', ['id' => $item->id, 'quantity' => 15]);
        $this->assertDatabaseHas('inventory_movements', [
            'inventory_item_id' => $item->id,
            'type' => 'stock_in',
            'quantity' => 5,
            'source_id' => $donation->id,
        ]);
    }

    public function test_invalid_status_jump_is_rejected(): void
    {
        $user = $this->user();
        $donation = $this->donation($user);

        $response = $this->actingAs($user)->put(route('donations.update', $donation), [
            'donor_name' => $donation->donor_name,
            'donor_email' => $donation->donor_email,
            'type' => 'in-kind',
            'items_description' => $donation->items_description,
            'status' => 'distributed',
        ]);

        $response->assertSessionHasErrors('status');
        $this->assertDatabaseHas('donations', ['id' => $donation->id, 'status' => 'pending']);
    }

    public function test_matching_recent_duplicate_is_rejected(): void
    {
        $user = $this->user();
        $this->donation($user);

        $response = $this->actingAs($user)->post(route('donations.store'), [
            'donor_name' => 'Relief Foundation',
            'donor_email' => 'foundation@example.com',
            'type' => 'in-kind',
            'items_description' => 'Rice sacks',
        ]);

        $response->assertSessionHasErrors('donor_name');
        $this->assertSame(1, Donation::count());
    }

    public function test_public_tracking_does_not_expose_private_donor_contact_fields(): void
    {
        $user = $this->user();
        $donation = $this->donation($user);
        $donation->update(['donor_contact' => '09123456789']);

        $response = $this->getJson('/api/v1/donations/track/' . $donation->tracking_code);

        $response->assertOk()
            ->assertJsonMissing(['donor_email' => 'foundation@example.com'])
            ->assertJsonMissing(['donor_contact' => '09123456789']);
    }
}
