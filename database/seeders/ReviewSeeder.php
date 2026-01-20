<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Car;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Faker\Factory as Faker;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID'); // Menggunakan Data Indonesia

        // Cek mobil
        $cars = Car::all();
        if ($cars->count() == 0) {
            $this->command->error('Error: Tidak ada data Mobil. Jalankan CarSeeder dulu!');
            return;
        }

        // Daftar Template Komentar agar terlihat natural
        $commentsList = [
            'Pelayanan sangat memuaskan, mobil bersih dan wangi.',
            'Driver ramah, perjalanan jadi menyenangkan.',
            'Harga terjangkau untuk kualitas mobil sebagus ini.',
            'Proses booking cepat dan mudah, tidak ribet.',
            'Mobil kondisi prima, AC dingin banget.',
            'Sangat recommended buat liburan keluarga.',
            'Admin responsif, membantu banget pas mau reschedule.',
            'Pengalaman sewa mobil terbaik sejauh ini.',
            'Kondisi mobil seperti baru, sangat nyaman.',
            'Terima kasih Bonanza, liburan kami jadi lancar.',
            'Cukup puas, meski pengantaran agak telat sedikit.',
            'Oke banget, next time pasti sewa di sini lagi.',
            'Fasilitas lengkap, sesuai dengan deskripsi di web.',
            'Pelayanan oke, mobil irit bensin.',
            'Sangat profesional dan terpercaya.',
            'Proses booking cepat tanpa ribet. Harga juga bersaing dibanding rental lain.',
            'Pelayanan sangat memuaskan! Mobil bersih dan wangi. Recommended banget buat liburan keluarga.'
        ];

        // LOOPING 20 KALI UNTUK MEMBUAT 20 CUSTOMER
        for ($i = 0; $i < 20; $i++) {

            // 1. Generate Data Random
            $name = $faker->name;
            $email = $faker->unique()->safeEmail; // Email pasti unik

            // Rating acak (cenderung memberi nilai bagus 4 atau 5)
            $rating = $faker->randomElement([4, 4, 5, 5, 5, 5, 5]);

            // Pilih komentar acak dari list di atas
            $comment = $faker->randomElement($commentsList);

            // A. BUAT USER (CUSTOMER)
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'role_id' => 4, // Role Customer
                ]
            );

            // B. PILIH MOBIL & HITUNG HARGA
            $car = $cars->random();
            $days = rand(1, 4); // Durasi sewa 1-4 hari
            $pricePerDay = $car->packages->first() ? $car->packages->first()->price : 500000;
            $totalPrice = $pricePerDay * ($days + 1);

            // Tanggal acak dalam 2 bulan terakhir
            $startDate = Carbon::now()->subDays(rand(5, 60));
            $endDate = $startDate->copy()->addDays($days);

            // C. BUAT BOOKING (Completed)
            $booking = Booking::create([
                'booking_code' => 'RMX-' . strtoupper(Str::random(6)),
                'user_id' => $user->id,
                'car_id' => $car->id,
                'package_name' => $car->packages->first() ? $car->packages->first()->name : 'Reguler',
                'car_price_per_day' => $pricePerDay,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'pickup_time' => '08:00:00',
                'return_time' => '20:00:00',
                'pickup_location' => 'Kantor Bonanza',
                'total_rent_price' => $totalPrice,
                'grand_total' => $totalPrice,
                'status' => 'completed',
                'payment_status' => 'paid',
                // 'payment_method' => 'transfer',  <-- Tetap dihapus/komentar agar tidak error
                'address_detail' => $faker->address, // Alamat acak Indonesia
            ]);

            // D. BUAT REVIEW
            Review::create([
                'user_id' => $user->id,
                'car_id' => $car->id,
                'booking_id' => $booking->id,
                'rating' => $rating,
                'comment' => $comment,
                'created_at' => $endDate->addDay() // Review dibuat besoknya
            ]);
        }

        $this->command->info('Sukses! 20 User Customer, Booking, & Review berhasil dibuat.');
    }
}
