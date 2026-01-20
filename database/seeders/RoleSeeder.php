<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Matikan cek foreign key sementara agar bisa truncate
        Schema::disableForeignKeyConstraints();
        DB::table('roles')->truncate();
        Schema::enableForeignKeyConstraints();

        $roles = [
            ['name' => 'admin', 'label' => 'Administrator', 'description' => 'Akses Penuh Sistem'],
            ['name' => 'owner', 'label' => 'Owner', 'description' => 'Pemilik Bisnis'],
            ['name' => 'staff', 'label' => 'Pegawai', 'description' => 'Staff Operasional'],
            ['name' => 'customer', 'label' => 'Customer', 'description' => 'Pelanggan Rental'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insert(array_merge($role, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }
    }
}
