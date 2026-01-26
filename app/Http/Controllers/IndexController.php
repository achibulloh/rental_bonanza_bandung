<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Review;
use App\Models\Booking; // Tambahkan ini untuk cek booking
use Carbon\Carbon;      // Tambahkan ini untuk olah tanggal
use App\Models\CarType;    // Tambahkan ini untuk filter kategori

class IndexController extends Controller
{
    // Halaman Utama (Landing Page)
    public function index()
    {
        // ... (Kode index/home yang sebelumnya sudah benar biarkan saja) ...
        // Logika mobil populer & rating rata-rata
        $popularCars = Car::with(['brand', 'packages'])->latest()->take(3)->get();
        $testimonials = Review::with('user')->where('rating', '>=', 4)->latest()->take(5)->get();
        $avgRating = Review::avg('rating') ? number_format(Review::avg('rating'), 1) : '0.0';
        $totalReviews = Review::count();
        $totalTransactions = Booking::count();
        $totalCars = Car::count();

        return view('index.index', compact('popularCars', 'testimonials', 'avgRating', 'totalReviews', 'totalTransactions', 'totalCars'));
    }

    // --- FUNGSI HALAMAN LIST MOBIL (YANG ANDA MINTA) ---
    public function index_mobil(Request $request)
    {
        // 1. Ambil Tanggal
        $startDate = $request->input('start_date', Carbon::now()->format('Y-m-d'));
        $endDate   = $request->input('end_date', Carbon::tomorrow()->format('Y-m-d'));

        // 2. AMBIL DATA TYPE DARI DATABASE (Low MPV, SUV, dll)
        $types = CarType::all();

        // 3. Query Mobil dengan relasi 'type'
        $query = Car::with(['brand', 'packages', 'type']);

        // Filter Keyword
        if ($request->has('q') && $request->filled('q')) {
            $keyword = $request->q;
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', '%'.$keyword.'%')
                  ->orWhereHas('brand', function($b) use ($keyword) {
                      $b->where('name', 'like', '%'.$keyword.'%');
                  });
            });
        }

        // Filter Kategori
        if ($request->has('category') && $request->category != 'Semua' && $request->filled('category')) {
            // Filter berdasarkan nama di tabel car_types
            $query->whereHas('type', function($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        $cars = $query->get();

        // 4. Cek Ketersediaan
        foreach ($cars as $car) {
            $isBooked = Booking::where('car_id', $car->id)
                ->whereIn('status', ['approved', 'ongoing', 'paid'])
                ->where(function ($q) use ($startDate, $endDate) {
                    $q->where('start_date', '<', $endDate)
                      ->where('end_date', '>', $startDate);
                })
                ->exists();

            $car->is_available = !$isBooked;
        }

        // Kirim variabel $types ke view agar bisa di-looping jadi tombol filter
        return view('index.mobil', compact('cars', 'startDate', 'endDate', 'types'));
    }

    // Fungsi Detail Mobil (Sesuai Route Anda)
    public function detail_mobil(Request $request)
    {
        // Logika detail mobil (nanti bisa ditambahkan id di parameter)
        return view('index.detail_mobil');
    }

    // Fungsi Redirect Search dari Halaman Depan
    public function search(Request $request)
    {
        $start = $request->input('start_date');
        $end   = $request->input('end_date');

        // Redirect ke route 'index_mobil' dengan membawa parameter tanggal
        return redirect()->route('index_mobil', [
            'start_date' => $start,
            'end_date' => $end
        ]);
    }

    // Fungsi Jenis Layanan dari Halaman Depan
    public function layanan()
    {
        // Logika detail mobil (nanti bisa ditambahkan id di parameter)
        return view('index.layanan');
    }
    // Fungsi Speedwash dari Halaman Depan
    public function speedwash()
    {
        // Logika detail mobil (nanti bisa ditambahkan id di parameter)
        return view('index.speedwash');
    }
    // Fungsi Poolnanza dari Halaman Depan
    public function poolnanza()
    {
        // Logika detail mobil (nanti bisa ditambahkan id di parameter)
        return view('index.poolnanza');
    }
}
