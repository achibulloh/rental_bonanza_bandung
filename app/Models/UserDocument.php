<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_type',
        'document_number',
        'file_path',
        'status',
        'verified_by',
        'rejection_reason'
    ];

    // Relasi ke Pemilik Dokumen (User biasa)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Admin/Staff yang menangani (Verifier)
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
