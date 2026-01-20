<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Car;
use App\Models\CarPackage;

class CarPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Ambil semua data mobil (misal ada 10 mobil)
        $cars = Car::all();

        if ($cars->isEmpty()) {
            $this->command->info('Tidak ada data mobil. Pastikan CarSeeder sudah dijalankan.');
            return;
        }

        // 2. Loop setiap mobil untuk dibuatkan 4 paket
        foreach ($cars as $car) {

            // Harga dasar sewa mobil (default 300rb jika null)
            $basePrice = $car->price_per_day > 0 ? $car->price_per_day : 300000;

            // --- PAKET 1: LEPAS KUNCI ---
            CarPackage::create([
                'car_id' => $car->id,
                'name' => 'Lepas Kunci',
                'price' => $basePrice,
                'description' => 'Unit Only. Tanpa Supir & BBM. Syarat: E-KTP & SIM A Asli.'
            ]);

            // --- PAKET 2: DENGAN DRIVER ---
            // Asumsi: Jasa Supir = 150.000
            CarPackage::create([
                'car_id' => $car->id,
                'name' => 'Dengan Driver',
                'price' => $basePrice + 150000,
                'description' => 'Unit + Jasa Driver (Max 12 Jam). Belum termasuk BBM, Tol & Parkir.'
            ]);

            // --- PAKET 3: ALL IN DALAM KOTA BANDUNG ---
            // Asumsi: Jasa Supir + BBM Dalam Kota = 350.000
            CarPackage::create([
                'car_id' => $car->id,
                'name' => 'ALL IN DALAM KOTA BANDUNG',
                'price' => $basePrice + 350000,
                'description' => 'Unit + Driver + BBM + Tol + Parkir (Area Kota Bandung). Bebas Ribet.'
            ]);

            // --- PAKET 4: ALL IN LUAR KOTA BANDUNG ---
            // Asumsi: Jasa Supir + BBM Luar Kota = 550.000
            CarPackage::create([
                'car_id' => $car->id,
                'name' => 'ALL IN LUAR KOTA BANDUNG',
                'price' => $basePrice + 550000,
                'description' => 'Unit + Driver + BBM (Luar Kota Bandung/Wisata Lembang/Ciwidey).'
            ]);
        }
    }
}
