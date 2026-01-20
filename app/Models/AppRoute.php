<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppRoute extends Model
{
    use HasFactory;// Nama tabel di database (sesuai migration yang kita buat)
    protected $table = 'app_routes';

    // Kolom yang tidak boleh diisi massal (hanya ID yang diproteksi)
    protected $guarded = ['id'];
}
