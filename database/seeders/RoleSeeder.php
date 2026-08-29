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
            ['name' => 'MDRRMO Staff',       'slug' => 'mdrrmo'],
            ['name' => 'LGU Staff',          'slug' => 'lgu_staff'],
            ['name' => 'Volunteer',          'slug' => 'volunteer'],
            ['name' => 'Supplier',           'slug' => 'supplier'],
            ['name' => 'Donor',              'slug' => 'donor'],
            ['name' => 'Resident',           'slug' => 'resident'],
            ['name' => 'Evacuation Manager', 'slug' => 'evac_manager'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['slug' => $role['slug']], $role);
        }
    }
}