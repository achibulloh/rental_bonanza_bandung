<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('car_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained('cars')->onDelete('cascade');
            $table->string('name'); // Contoh: 'Lepas Kunci', 'Dengan Supir'
            $table->decimal('price', 15, 2); // Harga per hari untuk paket ini
            $table->text('description')->nullable(); // Contoh: 'Tanpa BBM & Tol'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_packages');
    }
};
