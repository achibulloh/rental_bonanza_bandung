<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CarType extends Model
{
    protected $guarded = ['id'];

    // Tipe milik satu Kategori
    public function category() {
        return $this->belongsTo(CarCategory::class, 'car_category_id');
    }

    // Tipe punya banyak Mobil
    public function cars() {
        return $this->hasMany(Car::class);
    }
}
