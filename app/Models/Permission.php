<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $guarded = ['id'];

    /**
     * Relasi: Permission dimiliki oleh satu Menu (untuk grouping tampilan).
     * Contoh: permission "view_cars" masuk ke menu "Manajemen Mobil".
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Relasi: Permission dimiliki oleh banyak Role.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_has_permissions', 'permission_id', 'role_id');
    }
}
