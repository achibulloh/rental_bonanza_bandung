<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use App\Models\AppRoute;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccessControlController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\BookingFlowController;
use App\Http\Controllers\BookingOfflineController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Index
Route::controller(IndexController::class)->middleware('guest')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/allmobil', 'index_mobil')->name('index_mobil');
    Route::get('/detail_mobil', 'detail_mobil')->name('detail_mobil');
    Route::post('/search-car', 'search')->name('index.search');
});

// Auth
Route::controller(AuthController::class)->middleware('guest')->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/auth-prosses-login', 'auth_prosses_login')->name('auth_prosses_login');
    Route::get('/register', 'register')->name('register');
    Route::post('/auth-prosses-register', 'auth_prosses_register')->name('auth_prosses_register');
});
Route::controller(GoogleController::class)->middleware('guest')->group(function() {
    Route::get('/auth/google/login', 'loginToGoogle')->name('auth.google.login');
    Route::get('/auth/google/register', 'registerToGoogle')->name('auth.google.register');
    Route::get('/auth/google/callback', 'handleGoogleCallback')->name('handleGoogleCallback');
});

// Route::get('/check-in', function () {
//     return view('dashboard.serah_terima.checkin.index');
// });

// Route::get('/check-out', function () {
//     return view('dashboard.serah_terima.checkout.index');
// });
// Route::get('/serah-terima', function () {
//     return view('dashboard.serah_terima.index');
// });

// Dashboard
Route::middleware(['auth', 'role.check'])->group(function () {

    // Cek dulu apakah tabelnya sudah ada (untuk menghindari error saat migrate fresh)
    if (Schema::hasTable('app_routes')) {

        // Ambil semua route dari database
        $dynamicRoutes = AppRoute::all();

        foreach ($dynamicRoutes as $route) {
            // Logic untuk menentukan Controller Class namespace
            // Asumsi di database tersimpan string: "DashboardController@index"
            // Kita tambahkan namespace lengkap: "App\Http\Controllers\DashboardController@index"
            $controllerAction = 'App\\Http\\Controllers\\' . $route->controller;

            Route::match(
                [$route->method],   // Method: GET, POST, dll
                $route->url,        // URL: /dashboard
                $controllerAction   // Action: Controller@method
            )->name($route->route_name); // Name: dashboard.index
        }
    }

});

// // Route untuk testing WA saja
// Route::get('/test-wa', function () {
//     $targetPhone = '081221515809'; // GANTI DENGAN NOMOR WA ANDA (Pastikan Aktif)
//     $fileUrl = 'https://1cb86d4cd373.ngrok-free.app/storage/notas/Nota_BNZ-KRF22X.pdf'; // URL PDF yang sudah berhasil dibuka tadi

//     // Ambil setting
//     $config = \App\Models\Setting::whereIn('key', ['wa_user_code', 'wa_device_id', 'wa_secret'])->pluck('value', 'key');

//     // Format Nomor
//     $receiver = preg_replace('/[^0-9]/', '', $targetPhone);
//     if (substr($receiver, 0, 1) == '0') $receiver = '62' . substr($receiver, 1);

//     // Payload sesuai dokumentasi
//     $payload = [
//         'user_code' => $config['wa_user_code'],
//         'device_id' => $config['wa_device_id'],
//         'secret'    => $config['wa_secret'],
//         'receiver'  => $receiver,
//         'message'   => 'Tes Kirim PDF dari Laravel',
//         'media_url' => $fileUrl,
//         'fileName'  => 'Tes_Nota.pdf',
//         'enableTypingEffect' => false
//     ];

//     // Kirim Request
//     $response = \Illuminate\Support\Facades\Http::post('https://api.kirimi.id/v1/send-message', $payload);

//     // TAMPILKAN HASILNYA DI LAYAR
//     return $response->json();
// });
