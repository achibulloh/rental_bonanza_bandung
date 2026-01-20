<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\CarType;
use App\Models\CarCategory;
use App\Models\CarBrand;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CarManagementController extends Controller
{
    public function index()
    {
        $cars = Car::with(['type.category', 'brand'])->latest()->get();
        $categories = CarCategory::withCount('types')->get();
        $types = CarType::with('category')->latest()->get();
        $brands = CarBrand::latest()->get();

        $typesGrouped = CarType::with('category')->get()->groupBy(function($item) {
            return $item->category->name;
        });

        return view('dashboard.manajemen_mobil.index', compact('cars', 'categories', 'types', 'brands', 'typesGrouped'));
    }

    // --- LOGIC MOBIL ---
    public function storeCar(Request $request) {
        $request->validate([
            'car_brand_id' => 'required', // <--- PERBAIKAN DISINI
            'name' => 'required',
            'model' => 'required',
            'car_type_id' => 'required',
            'license_plate' => 'required',
            'year' => 'required|numeric',
            'price_per_day' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'transmission' => 'required',
            'fuel_type' => 'required',
            'engine_capacity' => 'required',
            'horsepower' => 'required',
            'seating_capacity' => 'required|numeric',
            'luggage_capacity' => 'required|numeric',
            'color' => 'required',
            'fuel_consumption' => 'required',
        ]);

        try {
            $data = $request->except(['image']);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('cars', 'public');
                $data['images'] = [$path];
            }

            $data['rating'] = 5.0;
            $data['status'] = 'available';

            Car::create($data);

            return back()->with('success', 'Mobil berhasil ditambahkan');

        } catch (\Exception $e) {
            if (isset($path)) Storage::disk('public')->delete($path);
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function updateCar(Request $request, $id) {
        $car = Car::findOrFail($id);
        $request->validate([
            'car_brand_id' => 'required', // <--- PERBAIKAN DISINI JUGA
            'name' => 'required',
            'model' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'transmission' => 'required',
            'fuel_type' => 'required',
            'engine_capacity' => 'required',
            'horsepower' => 'required',
            'seating_capacity' => 'required|numeric',
            'luggage_capacity' => 'required|numeric',
            'color' => 'required',
            'fuel_consumption' => 'required',
        ]);

        try {
            $data = $request->except(['image']);

            if ($request->hasFile('image')) {
                $oldImage = null;
                // Ambil gambar lama dari array
                if (!empty($car->images) && is_array($car->images)) {
                    $oldImage = $car->images[0] ?? null;
                } elseif (!empty($car->images) && is_string($car->images)) {
                     // Handle legacy data kalau masih string
                     $decoded = json_decode($car->images, true);
                     $oldImage = is_array($decoded) ? $decoded[0] : $car->images;
                }

                if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }
                $path = $request->file('image')->store('cars', 'public');
                $data['images'] = [$path];
            }

            $car->update($data);
            return back()->with('success', 'Mobil berhasil diperbarui');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroyCar($id) {
        $car = Car::findOrFail($id);
        // Logic hapus gambar sama seperti update
        $oldImage = null;
        if (!empty($car->images)) {
             if(is_array($car->images)) $oldImage = $car->images[0] ?? null;
             else {
                 $decoded = json_decode($car->images, true);
                 $oldImage = is_array($decoded) ? $decoded[0] : $car->images;
             }
        }

        if ($oldImage && Storage::disk('public')->exists($oldImage)) {
            Storage::disk('public')->delete($oldImage);
        }
        $car->delete();
        return back()->with('success', 'Mobil berhasil dihapus');
    }

    // --- LOGIC BRAND ---
    public function storeBrand(Request $request) {
        $request->validate(['name' => 'required|unique:car_brands,name']);
        CarBrand::create(['name' => $request->name, 'slug' => Str::slug($request->name)]);
        return back()->with('success', 'Brand ditambahkan');
    }
    public function updateBrand(Request $request, $id) {
        $brand = CarBrand::findOrFail($id);
        $request->validate(['name' => 'required|unique:car_brands,name,'.$id]);
        $brand->update(['name' => $request->name, 'slug' => Str::slug($request->name)]);
        return back()->with('success', 'Brand diupdate');
    }
    public function destroyBrand($id) {
        $brand = CarBrand::findOrFail($id);
        if(Car::where('car_brand_id', $id)->exists()){
            return back()->with('error', 'Gagal! Brand ini masih dipakai oleh mobil.');
        }
        $brand->delete();
        return back()->with('success', 'Brand dihapus');
    }

    // --- LOGIC KATEGORI ---
    public function storeCategory(Request $request) {
        $request->validate(['name' => 'required']);
        CarCategory::create(['name' => $request->name, 'slug' => Str::slug($request->name)]);
        return back()->with('success', 'Kategori ditambahkan');
    }
    public function updateCategory(Request $request, $id) {
        $cat = CarCategory::findOrFail($id);
        $cat->update(['name' => $request->name, 'slug' => Str::slug($request->name)]);
        return back()->with('success', 'Kategori diupdate');
    }
    public function destroyCategory($id) {
        if (CarType::where('car_category_id', $id)->exists()) return back()->with('error', 'Gagal! Kategori masih punya Tipe Mobil.');
        CarCategory::destroy($id);
        return back()->with('success', 'Kategori dihapus');
    }

    // --- LOGIC TIPE ---
    public function storeType(Request $request) {
        $request->validate(['name' => 'required', 'car_category_id' => 'required']);
        CarType::create(['car_category_id' => $request->car_category_id, 'name' => $request->name, 'slug' => Str::slug($request->name)]);
        return back()->with('success', 'Tipe ditambahkan');
    }
    public function updateType(Request $request, $id) {
        $type = CarType::findOrFail($id);
        $type->update(['car_category_id' => $request->car_category_id, 'name' => $request->name, 'slug' => Str::slug($request->name)]);
        return back()->with('success', 'Tipe diupdate');
    }
    public function destroyType($id) {
        if (Car::where('car_type_id', $id)->exists()) return back()->with('error', 'Gagal! Tipe ini masih dipakai Mobil.');
        CarType::destroy($id);
        return back()->with('success', 'Tipe dihapus');
    }
}
