<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('detail_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');

            // ====================================================
            // 1. DATA CHECK-IN (PENGAMBILAN)
            // ====================================================
            $table->dateTime('actual_start_date')->nullable();
            $table->integer('start_km')->nullable();
            $table->integer('start_fuel_level')->nullable(); // 0-100
            $table->text('start_condition_notes')->nullable();

            // Dokumentasi Foto Check-in
            $table->string('photo_front_in')->nullable();
            $table->string('photo_left_in')->nullable();
            $table->string('photo_right_in')->nullable();
            $table->string('photo_back_in')->nullable();
            $table->string('photo_additional_in')->nullable();      // Foto detail (baret/interior)
            $table->string('photo_car_and_customer_in')->nullable(); // Foto serah terima
            $table->string('video_condition_in')->nullable();       // Video kondisi

            // Checklist & Tanda Tangan Check-in
            $table->json('checklist_in')->nullable();
            $table->longText('signature_customer_in')->nullable();
            $table->longText('signature_officer_in')->nullable();
            $table->foreignId('officer_id_in')->nullable()->constrained('users')->onDelete('set null'); // Petugas In

            // ====================================================
            // 2. DATA CHECK-OUT (PENGEMBALIAN)
            // ====================================================
            $table->dateTime('actual_end_date')->nullable();
            $table->integer('end_km')->nullable();
            $table->integer('end_fuel_level')->nullable(); // 0-100

            // Dokumentasi Foto Check-out (Penting untuk bukti)
            $table->string('photo_front_out')->nullable();
            $table->string('photo_left_out')->nullable();
            $table->string('photo_right_out')->nullable();
            $table->string('photo_back_out')->nullable();
            $table->string('photo_additional_out')->nullable();
            $table->string('video_condition_out')->nullable();

            // Rincian Biaya & Denda
            $table->decimal('fine_overtime', 15, 2)->default(0);   // Denda Telat
            $table->decimal('fine_fuel', 15, 2)->default(0);       // Denda BBM
            $table->decimal('fine_damage', 15, 2)->default(0);     // Denda Kerusakan
            $table->decimal('fine_lost_items', 15, 2)->default(0); // Denda Kehilangan

            $table->decimal('total_fine', 15, 2)->default(0);      // Total Semua Denda
            $table->decimal('grand_total_final', 15, 2)->default(0); // Total Sewa + Denda
            $table->decimal('remaining_payment', 15, 2)->default(0); // Sisa Bayar (Pelunasan)

            // Tanda Tangan Check-out
            $table->longText('signature_customer_out')->nullable();
            $table->longText('signature_officer_out')->nullable();
            $table->foreignId('officer_id_out')->nullable()->constrained('users')->onDelete('set null'); // Petugas Out

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_bookings');
    }
};
