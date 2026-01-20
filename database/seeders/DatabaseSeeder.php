<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// --- TAMBAHKAN IMPORT INI AGAR TIDAK MERAH ---
use Database\Seeders\RoleSeeder;
use Database\Seeders\MenuSeeder;
use Database\Seeders\RouteSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleMenuSeeder;
use Database\Seeders\CarSeeder;
use Database\Seeders\SettingSeeder;
use Database\Seeders\BookingSeeder;
use Database\Seeders\CarPackageSeeder;
use Database\Seeders\ReviewSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            MenuSeeder::class,
            RouteSeeder::class,
            RoleMenuSeeder::class,
            UserSeeder::class,
            PermissionSeeder::class,
            CarSeeder::class,
            SettingSeeder::class,
            BookingSeeder::class,
            CarPackageSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
