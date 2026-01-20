<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = ['id'];

    // Relasi: Role punya banyak User
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Relasi: Role punya akses ke banyak Menu (Pivot Table)
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'role_has_menus', 'role_id', 'menu_id');
    }

    // Relasi: Role punya banyak Permission (Pivot Table)
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions', 'role_id', 'permission_id');
    }
}
