<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Cek dulu apakah tabel settings sudah ada (untuk menghindari error saat migrate fresh)
        if (Schema::hasTable('settings')) {
            // Ambil semua setting jadikan array [key => value]
            $settings = Setting::pluck('value', 'key')->toArray();

            // Bagikan variabel $settings ke SEMUA view
            View::share('settings', $settings);
        }
        // Fix untuk database MySQL lama (opsional, tapi bagus)
        Schema::defaultStringLength(191);

        // === LOGIKA PERMISSION ===
        // Kode ini yang memberitahu Laravel cara mengecek @can
        Gate::before(function ($user, $ability) {

            // 1. Pastikan user punya role
            if (!$user->role) {
                return false;
            }

            // 2. SUPER ADMIN: Jika role admin, izinkan SEMUA (Bypass)
            if ($user->role->name === 'admin') {
                return true;
            }

            // 3. USER BIASA: Cek apakah permission ada di database role user tersebut
            // $ability adalah string seperti 'cars.edit', 'cars.view', dll
            if ($user->role->permissions->contains('name', $ability)) {
                return true;
            }

            return null; // Lanjut ke pengecekan lain jika tidak ketemu
        });
    }
}
