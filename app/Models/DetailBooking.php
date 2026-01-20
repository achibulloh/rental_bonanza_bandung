<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailBooking extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'checklist_in' => 'array',
        // 'photo_additional_in' => 'array',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function officerIn()
    {
        return $this->belongsTo(User::class, 'officer_id_in');
    }

    public function officerOut()
    {
        return $this->belongsTo(User::class, 'officer_id_out');
    }
}
