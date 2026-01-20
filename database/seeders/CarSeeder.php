<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarCategory;
use App\Models\CarType;
use App\Models\CarBrand; // Tambahkan Model Brand
use App\Models\Car;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CarSeeder extends Seeder
{
    public function run()
    {
        // 1. Bersihkan tabel dulu (Urutan penting agar tidak error foreign key)
        Schema::disableForeignKeyConstraints();
        Car::truncate();
        CarType::truncate();
        CarCategory::truncate();
        CarBrand::truncate(); // Bersihkan Brand juga
        Schema::enableForeignKeyConstraints();

        // 2. Buat Brand (Merk Mobil)
        $brandHonda   = CarBrand::create(['name' => 'Honda', 'slug' => 'honda']);
        $brandToyota  = CarBrand::create(['name' => 'Toyota', 'slug' => 'toyota']);
        $brandSuzuki  = CarBrand::create(['name' => 'Suzuki', 'slug' => 'suzuki']);
        $brandMercy   = CarBrand::create(['name' => 'Mercedes-Benz', 'slug' => 'mercedes-benz']);
        $brandIsuzu   = CarBrand::create(['name' => 'Isuzu', 'slug' => 'isuzu']);

        // 3. Buat Kategori
        $catFamily  = CarCategory::create(['name' => 'Family Car', 'slug' => 'family-car']);
        $catCity    = CarCategory::create(['name' => 'City Car', 'slug' => 'city-car']);
        $catSuv     = CarCategory::create(['name' => 'SUV', 'slug' => 'suv']);
        $catBus     = CarCategory::create(['name' => 'Bus Pariwisata', 'slug' => 'bus']);
        $catComm    = CarCategory::create(['name' => 'Commercial', 'slug' => 'commercial']);

        // 4. Buat Tipe
        $typeLMPV       = CarType::create(['car_category_id' => $catFamily->id, 'name' => 'Low MPV', 'slug' => 'low-mpv']);
        $typeMPV        = CarType::create(['car_category_id' => $catFamily->id, 'name' => 'Medium MPV', 'slug' => 'medium-mpv']);
        $typeLCGC7      = CarType::create(['car_category_id' => $catFamily->id, 'name' => 'LCGC MPV', 'slug' => 'lcgc-mpv']);

        $typeHatchback  = CarType::create(['car_category_id' => $catCity->id, 'name' => 'Hatchback', 'slug' => 'hatchback']);
        $typeLCGC5      = CarType::create(['car_category_id' => $catCity->id, 'name' => 'LCGC Hatchback', 'slug' => 'lcgc-hatchback']);

        $typeCompactSUV = CarType::create(['car_category_id' => $catSuv->id, 'name' => 'Compact SUV', 'slug' => 'compact-suv']);

        $typeBigBus     = CarType::create(['car_category_id' => $catBus->id, 'name' => 'Big Bus', 'slug' => 'big-bus']);
        $typeMedBus     = CarType::create(['car_category_id' => $catBus->id, 'name' => 'Medium Bus', 'slug' => 'medium-bus']);

        $typePickup     = CarType::create(['car_category_id' => $catComm->id, 'name' => 'Pickup', 'slug' => 'pickup']);


        // 5. Insert Data Mobil (Gunakan car_brand_id)

        // --- 1. Honda Brio AT ---
        Car::create([
            'car_brand_id' => $brandHonda->id, // PENTING: Pakai ID Brand
            'car_type_id' => $typeHatchback->id,
            'name' => 'Brio',
            'model' => 'Satya E CVT',
            'year' => 2023,
            'license_plate' => 'D 1122 BB',
            'price_per_day' => 350000,
            'status' => 'available',
            'transmission' => 'Automatic (CVT)',
            'fuel_type' => 'Bensin',
            'engine_capacity' => '1.2L i-VTEC',
            'horsepower' => '90 PS',
            'seating_capacity' => 5,
            'luggage_capacity' => 2,
            'color' => 'Kuning',
            'fuel_consumption' => '14-16 km/l',
            'rating' => 4.8,
            'features' => ['AC', 'Dual Airbags', 'ABS+EBD', 'Audio Steering Switch', 'Eco Indicator'],
            'images' => ['cars/Honda Brio AT.jpg']
        ]);

        // --- 2. Toyota Calya ---
        Car::create([
            'car_brand_id' => $brandToyota->id,
            'car_type_id' => $typeLCGC7->id,
            'name' => 'Calya',
            'model' => 'G A/T',
            'year' => 2022,
            'license_plate' => 'B 2345 CDE',
            'price_per_day' => 400000,
            'status' => 'available',
            'transmission' => 'Automatic',
            'fuel_type' => 'Bensin',
            'engine_capacity' => '1.2L Dual VVT-i',
            'horsepower' => '88 PS',
            'seating_capacity' => 7,
            'luggage_capacity' => 2,
            'color' => 'Abu-abu Metalik',
            'fuel_consumption' => '12-14 km/l',
            'rating' => 4.7,
            'features' => ['AC Double Blower', 'Airbags', 'ABS', 'Touchscreen Head Unit', 'Rear Parking Sensor'],
            'images' => ['cars/Calya atau Sigra.png']
        ]);

        // --- 3. Toyota Raize Turbo ---
        Car::create([
            'car_brand_id' => $brandToyota->id,
            'car_type_id' => $typeCompactSUV->id,
            'name' => 'Raize',
            'model' => '1.0T GR Sport',
            'year' => 2023,
            'license_plate' => 'F 8899 GZ',
            'price_per_day' => 550000,
            'status' => 'available',
            'transmission' => 'CVT',
            'fuel_type' => 'Bensin',
            'engine_capacity' => '1.0L Turbo',
            'horsepower' => '98 PS',
            'seating_capacity' => 5,
            'luggage_capacity' => 3,
            'color' => 'Turquoise MM',
            'fuel_consumption' => '12-15 km/l',
            'rating' => 4.9,
            'features' => ['Turbo Engine', 'Toyota Safety Sense', 'Digital AC', 'Paddle Shift', 'Adaptive Cruise Control'],
            'images' => ['cars/Raize Turbo.jpg']
        ]);

        // --- 4. Toyota Avanza Facelift ---
        Car::create([
            'car_brand_id' => $brandToyota->id,
            'car_type_id' => $typeLMPV->id,
            'name' => 'Avanza',
            'model' => 'Veloz 1.5 AT',
            'year' => 2021,
            'license_plate' => 'D 1001 XA',
            'price_per_day' => 450000,
            'status' => 'rented',
            'transmission' => 'Automatic',
            'fuel_type' => 'Bensin',
            'engine_capacity' => '1.5L Dual VVT-i',
            'horsepower' => '104 PS',
            'seating_capacity' => 7,
            'luggage_capacity' => 3,
            'color' => 'Putih',
            'fuel_consumption' => '11-13 km/l',
            'rating' => 4.8,
            'features' => ['Double Blower AC', 'Keyless Entry', 'Start/Stop Button', 'LED Headlamp', 'Roof Monitor'],
            'images' => ['cars/Avanza facelist AT.jpg']
        ]);

        // --- 5. Toyota Agya ---
        Car::create([
            'car_brand_id' => $brandToyota->id,
            'car_type_id' => $typeLCGC5->id,
            'name' => 'Agya',
            'model' => 'TRD Sportivo',
            'year' => 2022,
            'license_plate' => 'B 3344 KK',
            'price_per_day' => 300000,
            'status' => 'available',
            'transmission' => 'Automatic',
            'fuel_type' => 'Bensin',
            'engine_capacity' => '1.2L Dual VVT-i',
            'horsepower' => '88 PS',
            'seating_capacity' => 5,
            'luggage_capacity' => 2,
            'color' => 'Merah',
            'fuel_consumption' => '15-18 km/l',
            'rating' => 4.6,
            'features' => ['Start/Stop Button', 'Touchscreen Audio', 'AC Digital', 'Dual Airbags', 'TRD Bodykit'],
            'images' => ['cars/Agya atau Ayla.jpg']
        ]);

        // --- 6. Honda Jazz ---
        Car::create([
            'car_brand_id' => $brandHonda->id,
            'car_type_id' => $typeHatchback->id,
            'name' => 'Jazz',
            'model' => 'RS CVT',
            'year' => 2020,
            'license_plate' => 'D 555 RS',
            'price_per_day' => 450000,
            'status' => 'maintenance',
            'transmission' => 'Automatic (CVT)',
            'fuel_type' => 'Bensin',
            'engine_capacity' => '1.5L i-VTEC',
            'horsepower' => '120 PS',
            'seating_capacity' => 5,
            'luggage_capacity' => 3,
            'color' => 'Oranye',
            'fuel_consumption' => '12-14 km/l',
            'rating' => 4.9,
            'features' => ['Paddle Shift', 'Cruise Control', 'Ultra Seat', 'LED Headlamp', '8" Touchscreen'],
            'images' => ['cars/tok-removebg-preview.png']
        ]);

        // --- 7. Suzuki Carry Pick Up ---
        Car::create([
            'car_brand_id' => $brandSuzuki->id,
            'car_type_id' => $typePickup->id,
            'name' => 'New Carry',
            'model' => 'Wide Deck',
            'year' => 2023,
            'license_plate' => 'F 9988 PU',
            'price_per_day' => 250000,
            'status' => 'available',
            'transmission' => 'Manual',
            'fuel_type' => 'Bensin',
            'engine_capacity' => '1.5L',
            'horsepower' => '97 PS',
            'seating_capacity' => 3,
            'luggage_capacity' => 1000,
            'color' => 'Putih',
            'fuel_consumption' => '10-12 km/l',
            'rating' => 4.7,
            'features' => ['AC', 'Power Steering', 'Radio/MP3', 'Bak Luas', 'Immobilizer'],
            'images' => ['cars/Carry pick up.jpg']
        ]);

        // --- 8. Toyota Grand Innova ---
        Car::create([
            'car_brand_id' => $brandToyota->id,
            'car_type_id' => $typeMPV->id,
            'name' => 'Innova',
            'model' => 'Grand Innova G Diesel',
            'year' => 2015,
            'license_plate' => 'B 1881 G',
            'price_per_day' => 500000,
            'status' => 'available',
            'transmission' => 'Automatic',
            'fuel_type' => 'Diesel',
            'engine_capacity' => '2.5L D-4D',
            'horsepower' => '102 PS',
            'seating_capacity' => 7,
            'luggage_capacity' => 4,
            'color' => 'Putih',
            'fuel_consumption' => '10-12 km/l',
            'rating' => 4.8,
            'features' => ['Triple Blower AC', 'Captain Seat', 'Robust Suspension', 'Turbo Diesel', 'Fog Lamp'],
            'images' => ['cars/Grand Innova.jpg']
        ]);

        // --- 9. Big Bus 48 Seat ---
        Car::create([
            'car_brand_id' => $brandMercy->id,
            'car_type_id' => $typeBigBus->id,
            'name' => 'Jetbus 3+',
            'model' => 'OH 1626 HDD',
            'year' => 2022,
            'license_plate' => 'AA 7777 XX',
            'price_per_day' => 3500000,
            'status' => 'available',
            'transmission' => 'Manual',
            'fuel_type' => 'Solar',
            'engine_capacity' => '6.3L Turbo',
            'horsepower' => '260 PS',
            'seating_capacity' => 48,
            'luggage_capacity' => 50,
            'color' => 'Putih Livery',
            'fuel_consumption' => '3-4 km/l',
            'rating' => 5.0,
            'features' => ['AC', 'Toilet', 'Karaoke/TV', 'Reclining Seat', 'Air Suspension', 'Dispenser'],
            'images' => ['cars/Big Bus 48 seat.png']
        ]);

        // --- 10. Medium Bus 33 Seat ---
        Car::create([
            'car_brand_id' => $brandIsuzu->id,
            'car_type_id' => $typeMedBus->id,
            'name' => 'Medium Bus',
            'model' => 'NQR 71',
            'year' => 2021,
            'license_plate' => 'D 7070 AA',
            'price_per_day' => 1800000,
            'status' => 'available',
            'transmission' => 'Manual',
            'fuel_type' => 'Solar',
            'engine_capacity' => '4.5L Turbo',
            'horsepower' => '125 PS',
            'seating_capacity' => 33,
            'luggage_capacity' => 20,
            'color' => 'Putih',
            'fuel_consumption' => '5-7 km/l',
            'rating' => 4.8,
            'features' => ['AC Ducting', 'Reclining Seat', 'TV/Audio', 'Bagasi Samping', 'Charger Port'],
            'images' => ['cars/Medium_seat_33_seat-removebg-preview.png']
        ]);
    }
}
