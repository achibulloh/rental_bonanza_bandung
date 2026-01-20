<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu'; // Sesuai nama tabel di migration
    protected $guarded = ['id'];

    // Relasi kebalikan (untuk cek role mana saja yang punya menu ini)
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_has_menus', 'menu_id', 'role_id');
    }
}
