<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'birth_date',
        'gender',
        'avatar',
        'password',// <--- Pastikan ini ada
        'google_id',  // <--- Pastikan ini ada
        'role_id',    // <--- WAJIB ADA DI SINI AGAR BISA DISIMPAN
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];// 1. Relasi User ke Role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // 2. Helper untuk mengambil menu milik user yang sedang login
    public function getAccessibleMenus()
    {
        if (!$this->role) {
            return collect([]); // Jika tidak punya role, return kosong
        }

        // Ambil menu berdasarkan role user, urutkan berdasarkan 'order'
        return $this->role->menus()->where('is_active', true)->orderBy('order')->get();
    }

    // 3. Helper Cek Permission (Opsional untuk Controller)
    public function hasPermission($permissionName)
    {
        if (!$this->role) return false;
        return $this->role->permissions()->where('name', $permissionName)->exists();
    }

    // Relasi ke Dokumen Pengguna
    public function documents()
    {
        return $this->hasMany(UserDocument::class);
    }

    public function officerOut()
    {
        return $this->belongsTo(User::class, 'officer_id_out');
    }
    public function activeTransaction()
    {
        // Pastikan Anda sudah membuat Model Booking (App\Models\Booking)
        // Kita cek berdasarkan 'driver_id'
        return $this->hasOne(Booking::class, 'driver_id')
                    ->whereIn('status', ['approved', 'ongoing']) // Status yang dianggap sibuk
                    ->latest();
    }

    // Opsional: Jika ingin melihat histori semua tugas driver
    public function driverTransactions()
    {
        return $this->hasMany(Booking::class, 'driver_id');
    }
}
