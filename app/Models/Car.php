<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $guarded = ['id'];

    protected $fillable = [
        'car_brand_id', // <--- PENTING: Ganti 'brand' jadi ini
        'name',
        'model',
        'car_type_id',
        'license_plate',
        'year',
        'price_per_day',
        'status',
        'images',
        'features',
        'transmission',
        'fuel_type',
        'engine_capacity',
        'horsepower',
        'seating_capacity',
        'luggage_capacity',
        'color',
        'fuel_consumption',
        'rating',
        'description'
    ];

    protected $casts = [
        'features' => 'array',
        'images' => 'array',
    ];

    // RELASI KE BRAND
    public function brand()
    {
        return $this->belongsTo(CarBrand::class, 'car_brand_id');
    }


    // Mobil milik satu Tipe
    public function type() {
        return $this->belongsTo(CarType::class, 'car_type_id');
    }

    public function packages()
    {
        return $this->hasMany(CarPackage::class);
    }

    // Helper: Ambil nama kategori langsung dari mobil
    public function getCategoryNameAttribute() {
        return $this->type->category->name ?? '-';
    }

    // Ambil foto pertama saja dari array images
    public function getThumbnailAttribute()
    {
        // Cek apakah kolom images ada isinya
        if (!empty($this->images)) {

            // Jika tipe datanya Array (karena casting di atas), ambil index 0
            if (is_array($this->images)) {
                return $this->images[0] ?? null;
            }

            // Jaga-jaga jika tipe datanya masih String JSON, decode dulu
            $decoded = json_decode($this->images, true);
            if (is_array($decoded)) {
                return $decoded[0] ?? null;
            }

            // Jika ternyata disimpan sebagai string biasa (bukan array)
            return $this->images;
        }

        // Jika tidak ada gambar sama sekali, kembalikan null atau default
        return 'cars/default.jpg';
    }

    public function getCategoryAttribute()
    {
        // Jika type ada, ambil category dari type tersebut
        return $this->type ? $this->type->category : null;
    }
}
