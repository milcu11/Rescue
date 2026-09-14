<?php

namespace Tests\Feature;

use App\Models\EvacuationCenter;
use App\Models\Evacuee;
use App\Models\Notification;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EvacuationManagementTest extends TestCase
{
    use RefreshDatabase;

    private function user(): User
    {
        $role = Role::create(['name' => 'Evacuation Manager', 'slug' => 'evac_manager']);

        return User::create([
            'name' => 'Evacuation Tester',
            'email' => 'evacuation@example.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'status' => 'active',
        ]);
    }

    private function center(User $user, string $name, int $capacity = 10): EvacuationCenter
    {
        return EvacuationCenter::create([
            'name' => $name,
            'barangay' => 'San Juan',
            'address' => 'Test address',
            'capacity' => $capacity,
            'status' => 'active',
            'created_by' => $user->id,
        ]);
    }

    public function test_checkin_rejects_duplicate_active_household_and_over_capacity(): void
    {
        $user = $this->user();
        $center = $this->center($user, 'Center A', 3);

        $this->actingAs($user)->post(route('evacuation.checkin', $center), [
            'name' => 'Ana Santos',
            'family_group' => 'Santos',
            'family_members' => 2,
            'id_presented' => 'ID-001',
        ]);
        $this->assertDatabaseHas('evacuees', [
            'name' => 'Ana Santos',
            'id_presented' => 'ID-001',
            'status' => 'checked_in',
        ]);

        $duplicate = $this->actingAs($user)->post(route('evacuation.checkin', $center), [
            'name' => 'Ana Santos',
            'family_group' => 'Santos',
            'family_members' => 1,
            'id_presented' => 'ID-001',
        ]);
        $duplicate->assertSessionHasErrors('name');

        $capacity = $this->actingAs($user)->post(route('evacuation.checkin', $center), [
            'name' => 'Ben Cruz',
            'family_members' => 2,
        ]);
        $capacity->assertSessionHasErrors('family_members');
        $this->assertSame(2, Evacuee::where('status', 'checked_in')->sum('family_members'));
    }

    public function test_center_creation_uses_exact_coordinates_supplied_by_staff(): void
    {
        $user = $this->user();

        $response = $this->actingAs($user)->post(route('evacuation.store'), [
            'name' => 'Pinugay Covered Court',
            'barangay' => 'Pinugay',
            'address' => 'Pinugay, Baras, Rizal',
            'capacity' => 100,
            'status' => 'open',
            'latitude' => '14.563210',
            'longitude' => '121.285430',
        ]);

        $response->assertRedirect(route('evacuation.index'));
        $this->assertDatabaseHas('evacuation_centers', [
            'name' => 'Pinugay Covered Court',
            'barangay' => 'Pinugay',
            'latitude' => 14.563210,
            'longitude' => 121.285430,
        ]);
    }

    public function test_checked_in_evacuee_can_be_transferred_between_centers(): void
    {
        $user = $this->user();
        $source = $this->center($user, 'Center A');
        $target = $this->center($user, 'Center B');
        $evacuee = Evacuee::create([
            'evacuation_center_id' => $source->id,
            'name' => 'Maria Reyes',
            'family_members' => 2,
            'status' => 'checked_in',
            'checked_in_at' => now(),
            'recorded_by' => $user->id,
        ]);

        $response = $this->actingAs($user)->patch(route('evacuation.transfer', [$source, $evacuee]), [
            'target_center_id' => $target->id,
            'notes' => 'Closer to family support.',
        ]);

        $response->assertRedirect(route('evacuation.show', $target));
        $this->assertDatabaseHas('evacuees', [
            'id' => $evacuee->id,
            'evacuation_center_id' => $target->id,
            'status' => 'checked_in',
        ]);
        $this->assertSame(0, $source->fresh()->syncOccupancy());
        $this->assertSame(2, $target->fresh()->syncOccupancy());
    }

    public function test_checked_in_evacuee_can_be_checked_out(): void
    {
        $user = $this->user();
        $center = $this->center($user, 'Center A', 10);
        $evacuee = Evacuee::create([
            'evacuation_center_id' => $center->id,
            'name' => 'Checkout Family',
            'family_members' => 3,
            'status' => 'checked_in',
            'checked_in_at' => now(),
            'recorded_by' => $user->id,
        ]);
        $center->update(['current_occupancy' => 3]);

        $response = $this->actingAs($user)->patch(route('evacuation.checkout', [$center, $evacuee]));

        $response->assertRedirect(route('evacuation.show', $center));
        $this->assertDatabaseHas('evacuees', [
            'id' => $evacuee->id,
            'status' => 'checked_out',
        ]);
        $this->assertSame(0, $center->fresh()->current_occupancy);
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'updated',
            'module' => 'evacuation',
            'record_id' => $evacuee->id,
        ]);
    }

    public function test_near_capacity_alert_is_created(): void
    {
        $user = $this->user();
        $center = $this->center($user, 'Center A', 10);

        $this->actingAs($user)->post(route('evacuation.checkin', $center), [
            'name' => 'Near Capacity Family',
            'family_members' => 8,
        ]);

        $this->assertDatabaseHas('notifications', [
            'type' => 'near_capacity',
            'title' => 'Evacuation center nearing capacity',
        ]);
    }

    public function test_dashboard_occupancy_uses_active_checkins(): void
    {
        $user = $this->user();
        $center = $this->center($user, 'Center A', 20);
        Evacuee::create([
            'evacuation_center_id' => $center->id,
            'name' => 'Active Family',
            'family_members' => 4,
            'status' => 'checked_in',
            'checked_in_at' => now(),
            'recorded_by' => $user->id,
        ]);
        $center->update(['current_occupancy' => 99]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk()->assertSee('4');
    }

    public function test_legacy_registered_record_with_checkin_time_counts_as_occupied(): void
    {
        $user = $this->user();
        $center = $this->center($user, 'Legacy Center', 20);
        Evacuee::create([
            'evacuation_center_id' => $center->id,
            'name' => 'Legacy Family',
            'family_members' => 3,
            'status' => 'registered',
            'checked_in_at' => now(),
            'recorded_by' => $user->id,
        ]);

        $this->assertSame(3, $center->fresh()->syncOccupancy());
    }
}
