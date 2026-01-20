<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Menu;

class RoleMenuSeeder extends Seeder
{
    public function run()
    {
        // 1. Ambil Role
        $admin = Role::where('name', 'admin')->first();
        $owner = Role::where('name', 'owner')->first();
        $staff = Role::where('name', 'staff')->first();
        $cust  = Role::where('name', 'customer')->first();

        // 2. Ambil Semua Menu
        $menus = Menu::all();

        // --- SKENARIO PEMBAGIAN MENU ---

        // A. ADMIN: Dapat SEMUA Menu
        if ($admin) {
            $admin->menus()->sync($menus->pluck('id'));
        }

        // B. OWNER: Semua KECUALI 'Manajemen Hak Akses'
        if ($owner) {
            $ownerMenus = $menus->reject(function ($menu) {
                return $menu->route_name === 'manajemen_akses';
            });
            $owner->menus()->sync($ownerMenus->pluck('id'));
        }

        // C. STAFF: Dashboard, Cari Mobil, Pesanan Aktif, Riwayat Booking
        if ($staff) {
            $staffMenus = $menus->filter(function ($menu) {
                return in_array($menu->route_name, [
                    'dashboard',
                    'cari_mobil',
                    'pesanan_aktif',
                    'riwayat_booking',
                    'manajemen_user' // Opsional jika staff boleh liat user
                ]);
            });
            $staff->menus()->sync($staffMenus->pluck('id'));
        }

        // D. CUSTOMER: Dashboard, Cari Mobil, Riwayat Booking, Bantuan
        if ($cust) {
            $custMenus = $menus->filter(function ($menu) {
                return in_array($menu->route_name, [
                    'dashboard',
                    'cari_mobil',
                    'riwayat_booking',
                    'bantuan'
                ]);
            });
            $cust->menus()->sync($custMenus->pluck('id'));
        }
    }
}
