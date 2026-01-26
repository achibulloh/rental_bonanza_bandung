<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;protected $guarded = ['id'];

    // Relasi
    public function car() { return $this->belongsTo(Car::class); }
    public function user() { return $this->belongsTo(User::class); } // Peminjam
    public function admin() { return $this->belongsTo(User::class, 'approved_by'); } // Admin

    // Accessor: Menghitung Total Hari secara Real-time
    // Cara panggil: $booking->total_days
    public function getTotalDaysAttribute()
    {
        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);

        // Ditambah 1 karena sewa tanggal 1 s/d 1 dihitung 1 hari
        return $start->diffInDays($end) + 1;
    }
    public function review()
    {
        return $this->hasOne(Review::class);
    }
    public function detail()
    {
        return $this->hasOne(DetailBooking::class);
    }
    // Relasi ke Driver
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}
