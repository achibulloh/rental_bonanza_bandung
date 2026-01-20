<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('user_documents', function (Blueprint $table) {
            $table->id();

            // User pemilik dokumen
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->enum('document_type', [
                'KTP',
                'SIM',
                'NPWP',
                'KTM',
                'KTA',
                'SELFIE_KTP_SIM' // Representasi dari "Foto Selfie menggunakan SIM/KTP"
            ]);
            $table->string('document_number')->nullable();
            $table->string('file_path');
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            // --- KOLOM BARU: PENANGAN (VERIFIER) ---
            // Kita gunakan nullable() karena saat baru upload, belum ada yang menangani
            // constrained('users') berarti relasi ke tabel users
            // onDelete('set null') berarti jika admin dihapus, data dokumen tetap ada tapi kolom ini jadi null
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_documents');
    }
};
