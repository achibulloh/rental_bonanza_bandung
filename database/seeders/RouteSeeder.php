<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RouteSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('app_routes')->truncate();
        Schema::enableForeignKeyConstraints();

        $routes = [
            // ================== 1. HALAMAN UTAMA (GET) ==================
            ['url' => '/dashboard', 'controller' => 'DashboardController@index', 'route_name' => 'dashboard', 'method' => 'GET'],
            ['url' => '/manajemen-akses', 'controller' => 'AccessControlController@index', 'route_name' => 'manajemen_akses', 'method' => 'GET'], // Updated Controller
            ['url' => '/menus/reorder', 'controller' => 'AccessControlController@reorder', 'route_name' => 'menus.reorder', 'method' => 'POST'],
            ['url' => '/manajemen-user', 'controller' => 'UserManagementController@index', 'route_name' => 'users.index', 'method' => 'GET'],
            ['url' => '/users', 'controller' => 'UserManagementController@store', 'route_name' => 'users.store', 'method' => 'POST'],
            ['url' => '/users/{id}', 'controller' => 'UserManagementController@update', 'route_name' => 'users.update', 'method' => 'PUT'],
            ['url' => '/users/{id}', 'controller' => 'UserManagementController@destroy', 'route_name' => 'users.destroy', 'method' => 'DELETE'],

            ['url' => '/myprofile/{id}', 'controller' => 'ProfileController@index', 'route_name' => 'profile.index', 'method' => 'GET'],
            ['url' => '/profile/update', 'controller' => 'ProfileController@update', 'route_name' => 'profile.update', 'method' => 'PUT'],
            ['url' => '/profile/password', 'controller' => 'ProfileController@updatePassword', 'route_name' => 'profile.password', 'method' => 'PUT'],
            ['url' => '/profile/document', 'controller' => 'ProfileController@uploadDocument', 'route_name' => 'profile.upload_document', 'method' => 'POST'],

            ['url' => '/riwayat_booking', 'controller' => 'RiwayatController@index', 'route_name' => 'riwayat_booking.index', 'method' => 'GET'],
            ['url' => '/pesanan_aktif', 'controller' => 'DashboardController@pesanan_aktif', 'route_name' => 'pesanan_aktif', 'method' => 'GET'],
            ['url' => '/review/store', 'controller' => 'ReviewController@store', 'route_name' => 'review.store', 'method' => 'POST'],
            ['url' => '/booking/detail/{code}', 'controller' => 'RiwayatController@show', 'route_name' => 'booking.detail', 'method' => 'GET'],
            ['url' => '/offline-booking', 'controller' => 'BookingOfflineController@index', 'route_name' => 'offline.index', 'method' => 'GET'],
            ['url' => '/offline-booking/store', 'controller' => 'BookingOfflineController@store', 'route_name' => 'offline.store', 'method' => 'POST'],
            ['url' => '/offline-booking/user-store', 'controller' => 'BookingOfflineController@storeUser', 'route_name' => 'offline.user.store', 'method' => 'POST'],
            ['url' => '/offline-booking/check-availability', 'controller' => 'BookingOfflineController@checkAvailability', 'route_name' => 'offline.check.availability', 'method' => 'GET'],
            ['url' => '/approval', 'controller' => 'ApprovalController@index', 'route_name' => 'approval.index', 'method' => 'GET'],
            ['url' => '/approval/{id}/approve-booking', 'controller' => 'ApprovalController@approveBooking', 'route_name' => 'approval.approve_booking', 'method' => 'POST'],
            ['url' => '/approval/{id}/approve-payment', 'controller' => 'ApprovalController@approvePayment', 'route_name' => 'approval.approve_payment', 'method' => 'POST'],
            ['url' => '/approval/{id}/reject', 'controller' => 'ApprovalController@reject', 'route_name' => 'approval.reject', 'method' => 'POST'],

            ['url' => '/pengaturan', 'controller' => 'SettingController@index', 'route_name' => 'settings.index', 'method' => 'GET'],
            ['url' => '/pengaturan', 'controller' => 'SettingController@update', 'route_name' => 'settings.update', 'method' => 'POST'],
            ['url' => '/bantuan', 'controller' => 'HelpController@index', 'route_name' => 'bantuan.index', 'method' => 'GET'],
            ['url' => '/bantuan/kirim', 'controller' => 'HelpController@store', 'route_name' => 'bantuan.store', 'method' => 'POST'],

            ['url' => '/laporan', 'controller' => 'ReportController@index', 'route_name' => 'report.index', 'method' => 'GET'],
            ['url' => '/laporan/export-pdf', 'controller' => 'ReportController@exportPdf', 'route_name' => 'report.exportPdf', 'method' => 'GET'],
            ['url' => '/laporan/export-excel', 'controller' => 'ReportController@exportExcel', 'route_name' => 'report.exportExcel', 'method' => 'GET'],

            ['url' => '/verifikasi/dokumen', 'controller' => 'VerificationController@index', 'route_name' => 'verification.index', 'method' => 'GET'],
            ['url' => '/verifikasi/approve/{id}', 'controller' => 'VerificationController@approve', 'route_name' => 'verification.approve', 'method' => 'POST'],
            ['url' => '/verifikasi/reject/{id}', 'controller' => 'VerificationController@reject', 'route_name' => 'verification.reject', 'method' => 'POST'],


            ['url' => '/balas-pesan', 'controller' => 'ReplyMessageController@index', 'route_name' => 'reply_message.index', 'method' => 'GET'],
            ['url' => '/balas-pesan/reply/{id}', 'controller' => 'ReplyMessageController@sendReply', 'route_name' => 'reply_message.sendReply', 'method' => 'POST'],
            ['url' => '/logout', 'controller' => 'DashboardController@logout', 'route_name' => 'logout', 'method' => 'GET'],

            // Hallaman Serah Terima
            [
                'url' => '/serah-terima',
                'controller' => 'SerahTerimaController@index',
                'route_name' => 'serah_terima.index',
                'method' => 'GET'
            ],
            [
                'url' => '/check-in/{booking_code}',
                'controller' => 'SerahTerimaController@showCheckIn',
                'route_name' => 'serah_terima.show_checkin',
                'method' => 'GET'
            ],
            [
                'url' => '/check-out/{booking_code}',
                'controller' => 'SerahTerimaController@showCheckOut',
                'route_name' => 'serah_terima.show_checkout',
                'method' => 'GET'
            ],
            [
                'url' => '/serah-terima/check-in/{id}',
                'controller' => 'SerahTerimaController@storeCheckIn',
                'route_name' => 'serah_terima.store_checkin',
                'method' => 'POST'
            ],
            [
                'url' => '/serah-terima/check-out/{id}',
                'controller' => 'SerahTerimaController@storeCheckOut',
                'route_name' => 'serah_terima.store_checkout',
                'method' => 'POST'
            ],

            // ================== 2. CRUD ROLES ==================
            [
                'url' => '/roles',
                'controller' => 'AccessControlController@storeRole',
                'route_name' => 'roles.store',
                'method' => 'POST'
            ],
            [
                'url' => '/roles/{id}',
                'controller' => 'AccessControlController@updateRole',
                'route_name' => 'roles.update',
                'method' => 'PUT'
            ],
            [
                'url' => '/roles/{id}',
                'controller' => 'AccessControlController@destroyRole',
                'route_name' => 'roles.destroy',
                'method' => 'DELETE'
            ],

            // ================== 3. CRUD MENUS ==================
            [
                'url' => '/menus',
                'controller' => 'AccessControlController@storeMenu',
                'route_name' => 'menus.store',
                'method' => 'POST'
            ],
            [
                'url' => '/menus/{id}',
                'controller' => 'AccessControlController@updateMenu',
                'route_name' => 'menus.update',
                'method' => 'PUT'
            ],
            [
                'url' => '/menus/{id}',
                'controller' => 'AccessControlController@destroyMenu',
                'route_name' => 'menus.destroy',
                'method' => 'DELETE'
            ],

            // ================== 4. CRUD APP ROUTES ==================
            [
                'url' => '/app-routes',
                'controller' => 'AccessControlController@storeRoute',
                'route_name' => 'routes.store',
                'method' => 'POST'
            ],
            [
                'url' => '/app-routes/{id}',
                'controller' => 'AccessControlController@updateRoute',
                'route_name' => 'routes.update',
                'method' => 'PUT'
            ],
            [
                'url' => '/app-routes/{id}',
                'controller' => 'AccessControlController@destroyRoute',
                'route_name' => 'routes.destroy',
                'method' => 'DELETE'
            ],
            [
                'url' => '/permissions',
                'controller' => 'AccessControlController@storePermission',
                'route_name' => 'permissions.store',
                'method' => 'POST'
            ],
            [
                'url' => '/permissions/update-matrix',
                'controller' => 'AccessControlController@updatePermissionMatrix',
                'route_name' => 'permissions.updateMatrix',
                'method' => 'POST'
            ],
            [
                'url' => '/permissions/save-matrix',
                'controller' => 'AccessControlController@saveMatrix',
                'route_name' => 'permissions.saveMatrix',
                'method' => 'POST'
            ],
            [
                'url' => '/manajemen-mobil',
                'controller' => 'CarManagementController@index',
                'route_name' => 'cars.index',
                'method' => 'GET'
            ],
            [
                'url' => '/cars',
                'controller' => 'CarManagementController@storeCar',
                'route_name' => 'cars.store',
                'method' => 'POST'
            ],
            [
                'url' => '/cars/{id}',
                'controller' => 'CarManagementController@updateCar',
                'route_name' => 'cars.update',
                'method' => 'PUT'
            ],
            [
                'url' => '/cars/{id}',
                'controller' => 'CarManagementController@destroyCar',
                'route_name' => 'cars.destroy',
                'method' => 'DELETE'
            ],
            [
                'url' => '/brands',
                'controller' => 'CarManagementController@storeBrand',
                'route_name' => 'brands.store',
                'method' => 'POST'
            ],
            [
                'url' => '/brands/{id}',
                'controller' => 'CarManagementController@updateBrand',
                'route_name' => 'brands.update',
                'method' => 'PUT'
            ],
            [
                'url' => '/brands/{id}',
                'controller' => 'CarManagementController@destroyBrand',
                'route_name' => 'brands.destroy',
                'method' => 'DELETE'
            ],
            [
                'url' => '/cari-mobil',
                'controller' => 'CariMobilController@index',
                'route_name' => 'cari_mobil.index',
                'method' => 'GET'
            ],
            [
                'url' => '/cari-mobil/detail/{id}',
                'controller' => 'DetailMobilController@index',
                'route_name' => 'dashboard.detail_mobil.index',
                'method' => 'GET'
            ],
            [
                'url' => '/booking-mobil/{id}',
                'controller' => 'CariMobilController@createBooking',
                'route_name' => 'cari_mobil.booking',
                'method' => 'GET'
            ],
            [
                'url' => '/booking-mobil',
                'controller' => 'CariMobilController@storeBooking',
                'route_name' => 'cari_mobil.store',
                'method' => 'POST'
            ],

            // === 3. CRUD KATEGORI ===
            [
                'url' => '/categories',
                'controller' => 'CarManagementController@storeCategory',
                'route_name' => 'categories.store',
                'method' => 'POST'
            ],
            [
                'url' => '/categories/{id}',
                'controller' => 'CarManagementController@updateCategory',
                'route_name' => 'categories.update',
                'method' => 'PUT'
            ],
            [
                'url' => '/categories/{id}',
                'controller' => 'CarManagementController@destroyCategory',
                'route_name' => 'categories.destroy',
                'method' => 'DELETE'
            ],
            [
                'url' => '/kalender-armada',
                'controller' => 'CalendarController@index',
                'route_name' => 'calendar.index',
                'method' => 'GET'
            ],
            [
                'url' => '/api/calendar/events',
                'controller' => 'CalendarController@getEvents',
                'route_name' => 'calendar.events',
                'method' => 'GET'
            ],

            // === 4. CRUD TIPE MOBIL ===
            [
                'url' => '/types',
                'controller' => 'CarManagementController@storeType',
                'route_name' => 'types.store',
                'method' => 'POST'
            ],
            [
                'url' => '/types/{id}',
                'controller' => 'CarManagementController@updateType',
                'route_name' => 'types.update',
                'method' => 'PUT'
            ],
            [
                'url' => '/types/{id}',
                'controller' => 'CarManagementController@destroyType',
                'route_name' => 'types.destroy',
                'method' => 'DELETE'
            ],// 1. Tampilkan Halaman Booking (Step 1)
            [
                'url' => '/booking/process/{carId}',
                'controller' => 'BookingFlowController@index',
                'route_name' => 'booking.index',
                'method' => 'GET'
            ],

            // 2. Simpan Data Booking (Step 1 -> Database)
            [
                'url' => '/booking/store',
                'controller' => 'BookingFlowController@store',
                'route_name' => 'booking.store',
                'method' => 'POST'
            ],

            // 3. Cek Status / Tracking (Hub Otomatis Step 2, 3, 4)
            [
                'url' => '/booking/track/{code}',
                'controller' => 'BookingFlowController@checkStatus',
                'route_name' => 'booking.track',
                'method' => 'GET'
            ],

            // 4. Update Pilihan Jaminan (Step 2 -> 3)
            [
                'url' => '/booking/guarantee/{code}',
                'controller' => 'BookingFlowController@updateGuarantee',
                'route_name' => 'booking.update_guarantee',
                'method' => 'POST'
            ],
            [
                'url' => '/booking/upload-proof/{code}',
                'controller' => 'BookingFlowController@uploadProof',
                'route_name' => 'booking.upload_proof',
                'method' => 'POST'
            ],
            [
                'url' => '/midtrans-callback',
                'controller' => 'MidtransCallbackController@handle',
                'route_name' => 'midtrans.callback',
                'method' => 'POST'
            ],
            [
                'url' => '/drivers',
                'controller' => 'DriverController@index',
                'route_name' => 'drivers.index',
                'method' => 'GET'
            ],
            [
                'url' => '/drivers/assign',
                'controller' => 'DriverController@assign',
                'route_name' => 'drivers.assign',
                'method' => 'POST'
            ],
            [
                'url' => '/drivers/{id}',
                'controller' => 'DriverController@update',
                'route_name' => 'drivers.update',
                'method' => 'PUT'
            ],
            [
                'url' => '/drivers/cancel-job',
                'controller' => 'DriverController@cancelJob',
                'route_name' => 'drivers.cancel_job',
                'method' => 'POST'
            ],
            [
                'url' => '/tasks',
                'controller' => 'TaskController@index',
                'route_name' => 'tasks.index',
                'method' => 'GET'
            ],
            [
                'url' => '/tasks/{id}/status',
                'controller' => 'TaskController@updateStatus',
                'route_name' => 'tasks.update_status',
                'method' => 'POST'
            ],
            [
                'url' => '/tasks/history',
                'controller' => 'TaskController@history',
                'route_name' => 'tasks.history',
                'method' => 'GET'
            ],
        ];

        foreach ($routes as $route) {
            DB::table('app_routes')->insert(array_merge($route, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }
    }
}
