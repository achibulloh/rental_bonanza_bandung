<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique(); // Contoh: RMX-203392
            $table->foreignId('car_id')->constrained('cars');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('driver_id')->nullable()->constrained('users');
            // Data Sewa
            $table->date('start_date');
            $table->date('end_date');
            $table->time('pickup_time'); // Jam Ambil
            $table->time('return_time'); // Jam Kembali
            $table->string('pickup_location');
            $table->string('return_location')->nullable();
            $table->text('address_detail')->nullable();

            // Keuangan
            $table->decimal('car_price_per_day', 15, 2);
            $table->decimal('total_rent_price', 15, 2); // Harga Sewa x Hari

            // Jaminan
            $table->enum('guarantee_type', ['motor', 'deposit_money', 'pending'])->default('pending');
            $table->decimal('guarantee_cost', 15, 2)->default(0); // Misal 3 Juta

            $table->decimal('grand_total', 15, 2); // Total Rental + Jaminan (Jika ada)

            // Midtrans
            $table->string('snap_token')->nullable();
            // Bukti Transfer
            $table->string('payment_proof')->nullable();
            // Status Pembayaran
            $table->enum('payment_status', ['unpaid', 'paid', 'expired'])->default('unpaid');

            // STATUS UTAMA (Sesuai Request Anda)
            // pending   = Menunggu Konfirmasi Admin (Step 2)
            // approved  = Admin OK -> Lanjut ke Pilih Jaminan (Step 3) & Bayar (Step 4)
            // ongoing   = Mobil dibawa (Status berubah saat hari H / serah terima)
            // completed = Selesai
            // rejected  = Ditolak Admin
            // cancelled = Dibatalkan User
            $table->enum('status', ['pending', 'approved', 'ongoing', 'completed', 'cancelled', 'rejected'])->default('pending');

            // Type Booking: Online atau Offline
            $table->enum('booking_type', ['Online', 'Offline'])->default('Online');



            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
