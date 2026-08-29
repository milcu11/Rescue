<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'     => 'Super Admin',
                'email'    => 'admin@resqlink.ph',
                'password' => Hash::make('password'),
                'slug'     => 'super_admin',
            ],
            [
                'name'     => 'DRRM Officer',
                'email'    => 'drrm@resqlink.ph',
                'password' => Hash::make('password'),
                'slug'     => 'mdrrmo',
            ],
            [
                'name'     => 'LGU Staff',
                'email'    => 'warehouse@resqlink.ph',
                'password' => Hash::make('password'),
                'slug'     => 'lgu_staff',
            ],
            [
                'name'     => 'Evacuation Manager',
                'email'    => 'evac@resqlink.ph',
                'password' => Hash::make('password'),
                'slug'     => 'evac_manager',
            ],
            [
                'name'     => 'Donor User',
                'email'    => 'donor@resqlink.ph',
                'password' => Hash::make('password'),
                'slug'     => 'donor',
            ],
        ];

        foreach ($users as $data) {
            $role = Role::where('slug', $data['slug'])->first();
            User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['name'],
                    'password' => $data['password'],
                    'role_id'  => $role->id,
                ]
            );
        }
    }
}