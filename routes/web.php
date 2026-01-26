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
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SerahTerimaController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ForgotPasswordController;

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
    Route::GET('/', 'index')->name('index');
    Route::GET('/allmobil', 'index_mobil')->name('index_mobil');
    Route::GET('/detail_mobil', 'detail_mobil')->name('detail_mobil');
    Route::post('/search-car', 'search')->name('index.search');
    Route::GET('/jenis-layanan', 'layanan')->name('index.layanan');
    Route::GET('/garasi-speed', 'speedwash')->name('index.garasispeed');
    Route::GET('/poolnanza', 'poolnanza')->name('index.poolnanza');
});

// Auth
Route::controller(AuthController::class)->middleware('guest')->group(function () {
    // Login
    Route::get('/login', 'login')->name('login');
    Route::post('/auth-prosses-login', 'auth_prosses_login')->name('auth_prosses_login');

    // OTP Routes
    Route::get('/verify-otp', 'showVerifyOtp')->name('otp.verify');
    Route::get('/otp/resend', 'resendOtp')->name('otp.resend');
    Route::post('/verify-otp', 'processVerifyOtp')->name('otp.process');

    // Register
    Route::get('/register', 'register')->name('register');
    Route::post('/auth-prosses-register', 'auth_prosses_register')->name('auth_prosses_register');
});

Route::controller(GoogleController::class)->middleware('guest')->group(function() {
    // Login Auth with Google
    Route::get('/auth/google/login', 'loginToGoogle')->name('auth.google.login');
    // Register Auth with Google
    Route::get('/auth/google/register', 'registerToGoogle')->name('auth.google.register');
    Route::get('/auth/google/callback', 'handleGoogleCallback')->name('handleGoogleCallback');
});

Route::controller(ForgotPasswordController::class)->middleware('guest')->group(function() {
    // Forgot Password
    // 1. Halaman Input Email/Phone (URL TETAP SAMA)
    Route::get('/lupa-password', 'showRequestForm')->name('password.request');
    Route::post('/lupa-password', 'sendOtp')->name('password.email');

    // 2. Halaman Input OTP (BARU - Diperlukan untuk input kode 4 digit)
    Route::get('/verifikasi-otp', 'showVerifyForm')->name('password.verify');
    Route::post('/verifikasi-otp', 'processVerify')->name('password.verify.process');
    Route::get('/reset-session-lupa-pass', 'resetSession')->name('password.reset.session');
    // 3. Halaman Ganti Password Baru (BARU - Tanpa Token URL panjang)
    Route::get('/reset-password-baru', 'showChangeForm')->name('password.reset.form');
    Route::post('/reset-password-baru', 'processChange')->name('password.update');
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
