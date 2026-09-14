<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementAuditTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $slug): User
    {
        $role = Role::create(['name' => ucfirst(str_replace('_', ' ', $slug)), 'slug' => $slug]);

        return User::create([
            'name' => 'Admin Tester',
            'email' => $slug.'@example.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'status' => 'active',
        ]);
    }

    public function test_user_management_actions_are_audited(): void
    {
        $admin = $this->user('super_admin');
        $staffRole = Role::create(['name' => 'LGU Staff', 'slug' => 'lgu_staff']);

        $response = $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'Managed User',
            'email' => 'managed@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_id' => $staffRole->id,
            'status' => 'active',
        ]);

        $response->assertRedirect(route('users.index'));
        $managed = User::where('email', 'managed@example.com')->firstOrFail();
        $this->assertDatabaseHas('audit_logs', ['action' => 'created', 'module' => 'users', 'record_id' => $managed->id]);

        $this->actingAs($admin)->put(route('users.update', $managed), [
            'name' => 'Updated User',
            'email' => 'managed@example.com',
            'role_id' => $staffRole->id,
            'status' => 'active',
        ])->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('audit_logs', ['action' => 'updated', 'module' => 'users', 'record_id' => $managed->id]);

        $this->actingAs($admin)->patch(route('users.toggle-status', $managed))->assertRedirect();
        $this->assertDatabaseHas('audit_logs', ['action' => 'status_changed', 'module' => 'users', 'record_id' => $managed->id]);
    }

    public function test_ordinary_users_cannot_manage_users(): void
    {
        $staff = $this->user('lgu_staff');

        $this->actingAs($staff)->get(route('users.index'))->assertRedirect(route('dashboard'));
    }

    public function test_user_guide_matches_role_visible_pages(): void
    {
        $admin = $this->user('super_admin');
        $this->actingAs($admin)->get(route('user-guide'))
            ->assertOk()
            ->assertSee('User Management')
            ->assertSee('Audit Trail');

        $staff = $this->user('lgu_staff');
        $this->actingAs($staff)->get(route('user-guide'))
            ->assertOk()
            ->assertDontSee('User Management')
            ->assertDontSee('Audit Trail');

        $donor = $this->user('donor');
        $this->actingAs($donor)->get(route('user-guide'))
            ->assertOk()
            ->assertSee('My Donations')
            ->assertSee('Make a Donation')
            ->assertDontSee('Emergency Supplies');
    }
}
