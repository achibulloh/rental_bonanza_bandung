<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CarCategory extends Model
{
    protected $guarded = ['id'];

    // Kategori punya banyak Tipe
    public function types() {
        return $this->hasMany(CarType::class);
    }

    // Kategori punya banyak Mobil (Lewat Tipe)
    public function cars() {
        return $this->hasManyThrough(Car::class, CarType::class);
    }
}
