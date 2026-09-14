<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\InventoryItem;
use App\Models\InventoryMovement;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportExportTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $slug): User
    {
        $role = Role::create(['name' => ucfirst(str_replace('_', ' ', $slug)), 'slug' => $slug]);

        return User::create([
            'name' => 'Report Tester',
            'email' => $slug.'@example.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'status' => 'active',
        ]);
    }

    public function test_inventory_report_filters_and_movement_exports_are_available(): void
    {
        $user = $this->user('lgu_staff');
        $item = InventoryItem::create([
            'name' => 'Rice',
            'category' => 'food',
            'quantity' => 10,
            'unit' => 'sacks',
            'minimum_threshold' => 2,
            'created_by' => $user->id,
            'is_active' => true,
        ]);
        InventoryMovement::create([
            'inventory_item_id' => $item->id,
            'type' => 'stock_in',
            'quantity' => 10,
            'quantity_before' => 0,
            'quantity_after' => 10,
            'reference' => 'TEST-MOVEMENT-001',
            'user_id' => $user->id,
            'occurred_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('reports.index', ['category' => 'food']))
            ->assertOk()
            ->assertSee('Stock Movement Report');

        $this->actingAs($user)
            ->get(route('reports.movements.pdf', ['status' => 'stock_in']))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        $this->actingAs($user)
            ->get(route('reports.movements.excel', ['item' => $item->id]))
            ->assertOk()
            ->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_audit_report_is_restricted_to_administrative_roles(): void
    {
        $staff = $this->user('lgu_staff');
        $admin = $this->user('mdrrmo');
        AuditLog::create([
            'user_id' => $admin->id,
            'user_name' => $admin->name,
            'user_role' => 'mdrrmo',
            'action' => 'created',
            'module' => 'reports',
            'record_label' => 'Test report',
        ]);

        $this->actingAs($staff)->get(route('reports.audit.pdf'))->assertRedirect(route('dashboard'));
        $this->actingAs($admin)->get(route('reports.audit.pdf'))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }
}
