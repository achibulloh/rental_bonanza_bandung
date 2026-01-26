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
        // Cek agar tidak error jika tabel sudah ada
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('phone')->nullable()->unique(); // No HP (Nullable agar tidak wajib saat register awal)
                $table->string('address')->nullable();
                $table->date('birth_date')->nullable();
                $table->enum('gender', ['male', 'female'])->nullable();
                $table->string('avatar')->nullable();          // Foto Profil
                $table->string('otp')->nullable();
                $table->boolean('is_active')->default(0);    // Status Akun (Aktif/Nonaktif)
                $table->string('google_id')->nullable();       // ID Google (untuk login Google)
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
