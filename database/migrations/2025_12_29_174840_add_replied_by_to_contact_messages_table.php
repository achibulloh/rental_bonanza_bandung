<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            // ID User Pengirim (Customer)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->string('subject');
            $table->text('message');
            $table->string('image_path')->nullable(); // Foto upload user

            // Kolom Balasan Admin
            $table->text('reply_message')->nullable();
            $table->foreignId('replied_by')->nullable()->constrained('users')->onDelete('set null'); // ID Admin yang membalas
            $table->timestamp('replied_at')->nullable();

            $table->enum('status', ['pending', 'replied'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contact_messages');
    }
};
