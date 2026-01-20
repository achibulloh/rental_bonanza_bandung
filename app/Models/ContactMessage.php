<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'user_id', 'subject', 'message', 'image_path',
        'reply_message', 'replied_by', 'replied_at', 'status'
    ];

    // Relasi ke Pengirim (Customer)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Pembalas (Admin/Pegawai)
    public function replier()
    {
        return $this->belongsTo(User::class, 'replied_by');
    }
}
