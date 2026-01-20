<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Menu;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // 1. Reset tabel permission dan relasinya agar bersih
        Schema::disableForeignKeyConstraints();
        DB::table('role_has_permissions')->truncate();
        DB::table('permissions')->truncate();
        Schema::enableForeignKeyConstraints();

        // 2. Definisi Mapping Permission per Menu
        // Format: 'route_name_menu' => [Daftar Permission]
        $config = [
            // A. Menu Dashboard
            'dashboard' => [
                ['name' => 'dashboard.view_statistics', 'label' => 'Lihat Statistik Total'],
                ['name' => 'dashboard.view_revenue', 'label' => 'Lihat Pendapatan'],
                ['name' => 'dashboard.view_all_bookings', 'label' => 'Lihat Semua Booking'],
                ['name' => 'dashboard.view_my_bookings', 'label' => 'Lihat Booking Sendiri'],
            ],

            // B. Manajemen Hak Akses (Full CRUD)
            'manajemen_akses' => [
                ['name' => 'access.view', 'label' => 'Lihat Hak Akses'],
                ['name' => 'access.create', 'label' => 'Tambah Data Akses'],
                ['name' => 'access.edit', 'label' => 'Edit Data Akses'],
                ['name' => 'access.delete', 'label' => 'Hapus Data Akses'],
            ],

            // C. Manajemen User (Full CRUD)
            'manajemen_user' => [
                ['name' => 'users.view', 'label' => 'Lihat Data User'],
                ['name' => 'users.create', 'label' => 'Tambah User Baru'],
                ['name' => 'users.edit', 'label' => 'Edit Data User'],
                ['name' => 'users.delete', 'label' => 'Hapus User'],
            ],

            // D. Manajemen Mobil (Full CRUD) - Menggabungkan 'cari_mobil' dan 'cars.index' jika tujuannya sama, atau dipisah jika menu berbeda.
            // Asumsi: Menu utama manajemen mobil di admin
            'cars.index' => [
                ['name' => 'cars.index', 'label' => 'Lihat Data Mobil'],
                ['name' => 'cars.store', 'label' => 'Tambah Mobil'],
                ['name' => 'cars.update', 'label' => 'Edit Mobil'],
                ['name' => 'cars.destroy', 'label' => 'Hapus Mobil'],
                // Permission terkait Kategori dan Tipe Mobil biasanya masuk sub-menu atau modul mobil
                ['name' => 'categories.index', 'label' => 'Lihat Data Categories'],
                ['name' => 'categories.store', 'label' => 'Tambah Categories'],
                ['name' => 'categories.update', 'label' => 'Edit Categories'],
                ['name' => 'categories.destroy', 'label' => 'Hapus Categories'],
                ['name' => 'types.index', 'label' => 'Lihat Data Types'],
                ['name' => 'types.store', 'label' => 'Tambah Types'],
                ['name' => 'types.update', 'label' => 'Edit Types'],
                ['name' => 'types.destroy', 'label' => 'Hapus Types'],
            ],

            // E. Manajemen Brand
            'brands.index' => [
                ['name' => 'brands.index', 'label' => 'Lihat Data Brand'],
                ['name' => 'brands.store', 'label' => 'Tambah Brand'],
                ['name' => 'brands.update', 'label' => 'Edit Brand'],
                ['name' => 'brands.destroy', 'label' => 'Hapus Brand'],
            ],

            // F. Riwayat Booking
            'riwayat_booking' => [
                ['name' => 'history.view', 'label' => 'Lihat Riwayat'],
                ['name' => 'history.export', 'label' => 'Export/Cetak Riwayat'],
            ],

            // G. Pesanan Aktif (Admin/Staff View)
            'pesanan_aktif' => [
                ['name' => 'orders.view', 'label' => 'Lihat Pesanan Aktif'],
                ['name' => 'orders.process', 'label' => 'Proses Pesanan (Approve/Reject)'],
            ],

             // H. Cari Mobil (Customer View)
             'cari_mobil.index' => [
                ['name' => 'cari_mobil.index', 'label' => 'Akses Cari Mobil'],
                ['name' => 'cari_mobil.booking', 'label' => 'Form Booking'],
                ['name' => 'cari_mobil.store', 'label' => 'Simpan Booking'],
            ],

             // I. Booking Process (Customer Flow)
             'booking.review' => [
                ['name' => 'booking.review', 'label' => 'Akses Booking'],
                ['name' => 'booking.store', 'label' => 'Simpan Booking'],
                ['name' => 'booking.track', 'label' => 'Tracking Booking'],
                ['name' => 'booking.save_guarantee', 'label' => 'Simpan Jaminan'],
            ],

            // J. Kalender Armada
            'calendar.index' => [
                ['name' => 'calendar.view', 'label' => 'Lihat Kalender Armada'],
            ],

            // K. Pengaturan
            'pengaturan' => [
                ['name' => 'settings.view', 'label' => 'Lihat Pengaturan'],
                ['name' => 'settings.update', 'label' => 'Ubah Pengaturan Website'],
            ],

            // L. Bantuan
            'bantuan' => [
                ['name' => 'help.view', 'label' => 'Akses Halaman Bantuan'],
            ],

            // M. Approval Booking
            'approval.index' => [
                ['name' => 'approval.index',   'label' => 'Lihat Daftar Persetujuan'],
                ['name' => 'approval.approve', 'label' => 'Setujui Booking'],
                ['name' => 'approval.reject',  'label' => 'Tolak Booking'],
            ],

            // N. Reply Message (Customer Support)
            'reply_message.index' => [
                ['name' => 'reply_message.index', 'label' => 'Lihat Daftar Pesan Masuk'],
                ['name' => 'reply_message.reply', 'label' => 'Balas Pesan User'],
            ],

            // O. Laporan
            'report.index' => [
                ['name' => 'report.index', 'label' => 'Lihat Laporan'],
                ['name' => 'report.export', 'label' => 'Export Laporan (PDF/Excel)'],
            ],
            // P. Serah Terima Armada
            'serah_terima.index' => [
                ['name' => 'serah_terima.index', 'label' => 'Lihat Menu Serah Terima'],
                ['name' => 'serah_terima.process', 'label' => 'Proses Check-in & Check-out'],
            ],
        ];

        // 3. Loop dan Insert ke Database
        foreach ($config as $route_name => $perms) {
            // Cari menu berdasarkan route_name
            // Pastikan data Menu sudah ada di database (jalankan MenuSeeder sebelum ini)
            $menu = Menu::where('route_name', $route_name)->first();

            if ($menu) {
                foreach ($perms as $perm) {
                    Permission::create([
                        'menu_id' => $menu->id,
                        'name' => $perm['name'],
                        'label' => $perm['label'],
                        'guard_name' => 'web',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } else {
                // Opsional: Log jika menu tidak ditemukan untuk debugging
                // echo "Menu dengan route '$route_name' tidak ditemukan. Permission dilewati.\n";
            }
        }

        // 4. Auto Assign ke Role Admin (Super User)
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $allPermissions = Permission::pluck('id');
            $admin->permissions()->sync($allPermissions);
        }

        // 5. Assign ke Role Owner
        $owner = Role::where('name', 'owner')->first();
        if ($owner) {
            $ownerPerms = Permission::whereIn('name', [
                // Dashboard
                'dashboard.view_statistics',
                'dashboard.view_revenue',
                'dashboard.view_all_bookings',
                // User & Akses (View Only mungkin?)
                'users.view',
                // Mobil & Brand (Full Access)
                'cars.index', 'cars.store', 'cars.update', 'cars.destroy',
                'brands.index', 'brands.store', 'brands.update', 'brands.destroy',
                'categories.index', 'categories.store', 'categories.update', 'categories.destroy',
                'types.index', 'types.store', 'types.update', 'types.destroy',
                // Pesanan & Riwayat
                'orders.view',
                'history.view', 'history.export',
                // Kalender
                'calendar.view',
                // Pengaturan
                'settings.view',
                // Bantuan
                'help.view'
            ])->pluck('id');
            $owner->permissions()->sync($ownerPerms);
        }

        // 6. Assign ke Role Pegawai (Staff)
        $staff = Role::where('name', 'pegawai')->first(); // Sesuaikan nama role: 'staff' atau 'pegawai'
        if ($staff) {
            $staffPerms = Permission::whereIn('name', [
                // Dashboard (Operasional)
                'dashboard.view_statistics', // Mungkin terbatas
                'dashboard.view_all_bookings',
                // Mobil (View & Edit status mungkin, tapi di sini ambil dari config diatas)
                'cars.index',
                'calendar.view',
                // Pesanan
                'orders.view', 'orders.process',
                // Riwayat
                'history.view',
                // Bantuan
                'help.view'
            ])->pluck('id');
            $staff->permissions()->sync($staffPerms);
        }

        // 7. Assign ke Role Customer
        $customer = Role::where('name', 'customer')->first();
        if ($customer) {
            $customerPerms = Permission::whereIn('name', [
                // Dashboard Pribadi
                'dashboard.view_my_bookings',
                // Cari Mobil & Booking
                'cari_mobil.index', 'cari_mobil.booking', 'cari_mobil.store',
                'booking.review', 'booking.store', 'booking.track', 'booking.save_guarantee',
                // Bantuan
                'help.view'
            ])->pluck('id');
            $customer->permissions()->sync($customerPerms);
        }
    }
}
