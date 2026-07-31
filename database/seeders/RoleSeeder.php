<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Admin',        'slug' => 'super_admin'],
            ['name' => 'DRRM Officer',        'slug' => 'drrm_officer'],
            ['name' => 'Volunteer',           'slug' => 'volunteer'],
            ['name' => 'Donor',               'slug' => 'donor'],
            ['name' => 'Warehouse Staff',     'slug' => 'warehouse_staff'],
            ['name' => 'Evacuation Manager',  'slug' => 'evacuation_manager'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['slug' => $role['slug']], $role);
        }
    }
}