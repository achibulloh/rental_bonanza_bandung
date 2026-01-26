<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MenuSeeder extends Seeder
{
    public function run()
    {
        // Kosongkan tabel menu dulu agar tidak duplikat
        Schema::disableForeignKeyConstraints();
        DB::table('menu')->truncate();
        Schema::enableForeignKeyConstraints();

        $menus = [
            [
                'name' => 'Dashboard',
                'url' => '/dashboard',
                'route_name' => 'dashboard',
                'icon' => 'fas fa-th-large',
                'order' => 1
            ],
            [
                'name' => 'Manajemen Hak Akses',
                'url' => '/manajemen-akses',
                'route_name' => 'manajemen_akses',
                'icon' => 'fas fa-user-shield',
                'order' => 2
            ],
            [
                'name' => 'Manajemen Users',
                'url' => '/manajemen-user',
                'route_name' => 'manajemen_user',
                'icon' => 'fas fa-users',
                'order' => 3
            ],
            [
                'name' => 'Cari Mobil',
                'url' => '/cari-mobil',
                'route_name' => 'cari_mobil',
                'icon' => 'fas fa-car',
                'order' => 4
            ],
            [
                'name' => 'Riwayat Booking',
                'url' => '/riwayat_booking',
                'route_name' => 'riwayat_booking',
                'icon' => 'fas fa-history',
                'order' => 5
            ],
            [
                'name' => 'Pesanan Aktif',
                'url' => '/pesanan_aktif',
                'route_name' => 'pesanan_aktif',
                'icon' => 'fas fa-calendar-check',
                'order' => 6
            ],
            [
                'name' => 'Pengaturan',
                'url' => '/pengaturan',
                'route_name' => 'pengaturan',
                'icon' => 'fas fa-cog',
                'order' => 7
            ],
            [
                'name' => 'Bantuan & Kontak',
                'url' => '/bantuan',
                'route_name' => 'bantuan',
                'icon' => 'far fa-question-circle',
                'order' => 8
            ],
            [
                'name' => 'Manajemen Mobil',
                'url' => '/manajemen-mobil',
                'route_name' => 'cars.index',
                'icon' => 'fas fa-car-side',
                'order' => 9
            ],
            [
            'name' => 'Kalender Armada',
            'url' => '/kalender-armada',
            'route_name' => 'calendar.index',
            'icon' => 'fas fa-calendar-alt',
            'order' => 10
            ],
            [
            'name' => 'Booking Offline',
            'url' => '/offline-booking',
            'route_name' => 'offline.index',
            'icon' => 'fas fa-cash-register',
            'order' => 11
            ],
            [
            'name' => 'Persetujuan Booking',
            'url' => '/approval',
            'route_name' => 'approval.index',
            'icon' => 'fas fa-clipboard-check text-warning menu-icon',
            'order' => 12
            ],
            [
                'name'       => 'Pesan Bantuan',
                'url'        => '/balas-pesan',
                'route_name' => 'reply_message.index',
                'icon'       => 'fas fa-envelope-open-text text-info menu-icon',
                'order'      => 13
            ],
            [
                'name' => 'Laporan Transaksi',
                'url' => '/laporan',
                'route_name' => 'report.index',
                'icon' => 'fas fa-chart-line text-success menu-icon',
                'order' => 14
            ],
            [
                'name' => 'Serah Terima Armada',
                'url' => '/serah-terima',
                'route_name' => 'serah_terima.index',
                'icon' => 'fas fa-key',
                'order' => 15
            ],
            [
                'name' => 'Driver',
                'url' => '/drivers',
                'route_name' => 'drivers.index',
                'icon' => 'fas fa-id-card',
                'order' => 16
            ],
            [
                'name' => 'Daftar Tugas',
                'url' => '/tasks',
                'route_name' => 'tasks.index',
                'icon' => 'fas fa-clipboard-list',
                'order' => 17
            ],
            [
                'name' => 'Riwayat Tugas',
                'url' => '/tasks/history',
                'route_name' => 'tasks.history',
                'icon' => 'fas fa-history',
                'order' => 18
            ],
            [
                'name' => 'Verifikasi Dokumen',
                'url' => '/verifikasi/dokumen',
                'route_name' => 'verification.index',
                'icon' => 'fas fa-id-card',
                'order' => 19
            ],
        ];

        foreach ($menus as $menu) {
            DB::table('menu')->insert(array_merge($menu, [
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }
    }
}
