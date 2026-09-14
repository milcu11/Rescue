<?php

namespace Tests\Feature;

use App\Models\EvacuationCenter;
use App\Models\InventoryItem;
use App\Models\ReliefOperation;
use App\Models\ReliefDistribution;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReliefManagementTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $slug = 'mdrrmo'): User
    {
        $role = Role::create(['name' => 'DRRM Officer', 'slug' => $slug]);

        return User::create([
            'name' => 'Relief Tester',
            'email' => $slug . '@example.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'status' => 'active',
        ]);
    }

    private function operation(User $user, string $approval = 'pending'): ReliefOperation
    {
        return ReliefOperation::create([
            'name' => 'Relief Wave 1',
            'status' => $approval === 'approved' ? 'active' : 'planned',
            'approval_status' => $approval,
            'start_date' => now()->toDateString(),
            'created_by' => $user->id,
        ]);
    }

    private function item(User $user): InventoryItem
    {
        return InventoryItem::create([
            'name' => 'Rice',
            'category' => 'food',
            'quantity' => 20,
            'unit' => 'sacks',
            'minimum_threshold' => 2,
            'is_active' => true,
            'created_by' => $user->id,
        ]);
    }

    private function center(User $user): EvacuationCenter
    {
        return EvacuationCenter::create([
            'name' => 'Relief Center',
            'barangay' => 'San Juan',
            'address' => 'Test address',
            'capacity' => 100,
            'status' => 'active',
            'created_by' => $user->id,
        ]);
    }

    private function distributionData(EvacuationCenter $center, InventoryItem $item): array
    {
        return [
            'evacuation_center_id' => $center->id,
            'inventory_item_id' => $item->id,
            'quantity_distributed' => 5,
            'beneficiaries_count' => 10,
            'notes' => 'Wave 1 release',
        ];
    }

    public function test_unapproved_operation_cannot_release_items(): void
    {
        $user = $this->user();
        $operation = $this->operation($user);
        $center = $this->center($user);
        $item = $this->item($user);

        $response = $this->actingAs($user)->post(route('relief.distribute', $operation), $this->distributionData($center, $item));

        $response->assertSessionHasErrors('distribution');
        $this->assertDatabaseHas('inventory_items', ['id' => $item->id, 'quantity' => 20]);
    }

    public function test_approved_active_operation_deducts_stock_and_creates_audit_log(): void
    {
        $user = $this->user();
        $operation = $this->operation($user);
        $center = $this->center($user);
        $item = $this->item($user);

        $this->actingAs($user)->patch(route('relief.approve', $operation));
        $response = $this->actingAs($user)->post(route('relief.distribute', $operation), $this->distributionData($center, $item));

        $response->assertRedirect(route('relief.show', $operation));
        $this->assertDatabaseHas('inventory_items', ['id' => $item->id, 'quantity' => 15]);
        $this->assertDatabaseHas('relief_distributions', [
            'relief_operation_id' => $operation->id,
            'inventory_item_id' => $item->id,
            'quantity_distributed' => 5,
            'distributed_by' => $user->id,
        ]);
        $this->assertDatabaseHas('audit_logs', [
            'module' => 'relief_operations',
            'action' => 'created',
            'record_id' => $operation->id,
        ]);
    }

    public function test_matching_recent_distribution_is_rejected(): void
    {
        $user = $this->user();
        $operation = $this->operation($user, 'approved');
        $center = $this->center($user);
        $item = $this->item($user);
        $data = $this->distributionData($center, $item);

        $this->actingAs($user)->post(route('relief.distribute', $operation), $data);
        $response = $this->actingAs($user)->post(route('relief.distribute', $operation), $data);

        $response->assertSessionHasErrors('quantity_distributed');
        $this->assertDatabaseCount('relief_distributions', 1);
        $this->assertDatabaseHas('inventory_items', ['id' => $item->id, 'quantity' => 15]);
    }

    public function test_relief_show_handles_archived_distribution_relations(): void
    {
        $user = $this->user();
        $operation = $this->operation($user, 'approved');
        $center = $this->center($user);
        $item = $this->item($user);

        ReliefDistribution::create([
            ...$this->distributionData($center, $item),
            'relief_operation_id' => $operation->id,
            'distributed_by' => $user->id,
            'distributed_at' => now(),
        ]);
        $center->delete();
        $item->delete();

        $this->actingAs($user)
            ->get(route('relief.show', $operation))
            ->assertOk()
            ->assertSee('Relief Center')
            ->assertSee('Rice')
            ->assertSee('archived');
    }
}
