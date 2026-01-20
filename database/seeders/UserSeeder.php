<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 1. Ambil Data Role dari Database
        $adminRole = Role::where('name', 'admin')->first();
        $ownerRole = Role::where('name', 'owner')->first();
        $staffRole = Role::where('name', 'staff')->first();
        $custRole  = Role::where('name', 'customer')->first();

        // 2. Buat User untuk masing-masing Role
        $users = [
            [
                'name' => 'Administrator Utama',
                'email' => 'admin@bonanza.com',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
                'phone' => '081221515809',
                'avatar' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Bapak Pemilik',
                'email' => 'owner@bonanza.com',
                'password' => Hash::make('password'),
                'role_id' => $ownerRole->id,
                'phone' => '081298765432',
                'avatar' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Staff Operasional',
                'email' => 'staff@bonanza.com',
                'password' => Hash::make('password'),
                'role_id' => $staffRole->id,
                'phone' => '085678901234',
                'avatar' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Pelanggan Setia',
                'email' => 'customer@gmail.com',
                'password' => Hash::make('password'),
                'role_id' => $custRole->id,
                'phone' => '089988776655',
                'avatar' => null,
                'is_active' => true,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
