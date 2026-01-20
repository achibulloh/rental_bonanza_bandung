<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Tabel BRAND (Merk) - Baru
        // Contoh: Toyota, Honda, BMW
        Schema::create('car_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // ex: Toyota
            $table->string('slug');
            $table->timestamps();
        });

        // 2. Tabel KATEGORI (Induk)
        // Contoh: Sport Car, Family Car, Offroad
        Schema::create('car_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ex: Sport Car
            $table->string('slug');
            $table->timestamps();
        });

        // 3. Tabel TIPE (Anak) - Terhubung ke Kategori
        // Contoh: Premium, LCGC, Luxury, Electric
        Schema::create('car_types', function (Blueprint $table) {
            $table->id();
            // Relasi ke Kategori
            $table->foreignId('car_category_id')->constrained('car_categories')->onDelete('cascade');
            $table->string('name'); // ex: Premium
            $table->string('slug');
            $table->timestamps();
        });

        // 4. Tabel MOBIL - Terhubung ke Tipe & Brand
        Schema::create('cars', function (Blueprint $table) {
            $table->id();

            // Relasi ke Brand (PENTING: Ganti string brand jadi relasi ID)
            $table->foreignId('car_brand_id')->constrained('car_brands')->onDelete('cascade');

            // Relasi ke Tipe (Otomatis juga tahu kategorinya via tipe)
            $table->foreignId('car_type_id')->constrained('car_types')->onDelete('cascade');

            // Info Utama
            // $table->string('brand');  <-- INI DIHAPUS KARENA SUDAH JADI RELASI DI ATAS
            $table->string('name');             // ex: M2, Avanza
            $table->string('model');            // ex: M2 Competition, Veloz Q
            $table->integer('year');            // 2023
            $table->string('license_plate');
            $table->decimal('price_per_day', 15, 2);
            $table->enum('status', ['available', 'rented', 'maintenance'])->default('available');

            // Spesifikasi Detail
            $table->string('transmission');     // Automatic
            $table->string('fuel_type');        // Bensin
            $table->string('engine_capacity');  // 3.0L
            $table->string('horsepower');       // 405 HP
            $table->integer('seating_capacity');
            $table->integer('luggage_capacity');
            $table->string('color');
            $table->string('fuel_consumption');

            // Kolom JSON (Fitur & Gambar)
            $table->json('features')->nullable();
            $table->json('images')->nullable();

            $table->decimal('rating', 3, 1)->default(5.0);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        // Drop urutan terbalik agar tidak error foreign key
        Schema::dropIfExists('cars');
        Schema::dropIfExists('car_types');
        Schema::dropIfExists('car_categories');
        Schema::dropIfExists('car_brands');
    }
};
