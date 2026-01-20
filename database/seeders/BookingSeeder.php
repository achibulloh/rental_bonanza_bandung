<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Car;
use App\Models\User;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run()
    {
        $car = Car::first();
        $user = User::first();

        // Harga per hari (asumsi dari mobil)
        $pricePerDay = $car ? $car->price_per_day : 500000;

        if ($car && $user) {

            // 1. ONGOING (Sedang Jalan - Hijau)
            Booking::create([
                'booking_code' => 'RMX-ONGOING',
                'car_id' => $car->id,
                'user_id' => $user->id,
                'start_date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'end_date' => Carbon::now()->addDays(1)->format('Y-m-d'),

                // --- BAGIAN YANG DIPERBAIKI ---
                'pickup_time' => '09:00:00',
                'return_time' => '09:00:00', // Tambahkan ini
                'car_price_per_day' => $pricePerDay, // Tambahkan ini
                // -----------------------------

                'pickup_location' => 'Kantor Bonanza Rental',
                'address_detail' => 'Antar ke rumah saya di Jl. Mawar No 1',

                'total_rent_price' => 1500000,
                'guarantee_type' => 'motor',
                'guarantee_cost' => 0,
                'grand_total' => 1500000,

                'payment_status' => 'paid',
                'status' => 'ongoing'
            ]);

            // 2. APPROVED (Booked - Merah)
            Booking::create([
                'booking_code' => 'RMX-BOOKED',
                'car_id' => $car->id,
                'user_id' => $user->id,
                'start_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
                'end_date' => Carbon::now()->addDays(5)->format('Y-m-d'),

                // --- BAGIAN YANG DIPERBAIKI ---
                'pickup_time' => '10:00:00',
                'return_time' => '10:00:00', // Tambahkan ini
                'car_price_per_day' => $pricePerDay, // Tambahkan ini
                // -----------------------------

                'pickup_location' => 'Kantor Bonanza Rental',
                'address_detail' => null,

                'total_rent_price' => 1500000,
                'guarantee_type' => 'deposit_money',
                'guarantee_cost' => 3000000,
                'grand_total' => 4500000,

                'payment_status' => 'paid',
                'status' => 'approved'
            ]);

            // 3. PENDING (Menunggu Konfirmasi - Kuning)
            Booking::create([
                'booking_code' => 'RMX-PENDING',
                'car_id' => $car->id,
                'user_id' => $user->id,
                'start_date' => Carbon::now()->addDays(7)->format('Y-m-d'),
                'end_date' => Carbon::now()->addDays(8)->format('Y-m-d'),

                // --- BAGIAN YANG DIPERBAIKI ---
                'pickup_time' => '13:00:00',
                'return_time' => '13:00:00', // Tambahkan ini
                'car_price_per_day' => $pricePerDay, // Tambahkan ini
                // -----------------------------

                'pickup_location' => 'Bandara Soekarno Hatta',
                'address_detail' => 'Tunggu di terminal 3',

                'total_rent_price' => 1000000,
                'guarantee_type' => 'pending',
                'guarantee_cost' => 0,
                'grand_total' => 1000000,

                'payment_status' => 'unpaid',
                'status' => 'pending'
            ]);
        }
    }
}
