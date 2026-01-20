<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\CarCategory;
use App\Models\Booking;
use Carbon\Carbon;

class CariMobilController extends Controller
{
    public function index(Request $request)
    {
        // 1. AUTO UPDATE STATUS (Penting: Jalankan sebelum query)
        $this->autoUpdateStatus();

        // 2. Ambil Kategori untuk Dropdown
        $categories = CarCategory::all();

        // 3. TENTUKAN TANGGAL FILTER
        // Jika user input tanggal, pakai itu. Jika tidak (halaman awal), pakai HARI INI & BESOK.
        if ($request->filled('start_date')) {
            $start = Carbon::parse($request->start_date)->format('Y-m-d');
        } else {
            $start = Carbon::now()->format('Y-m-d'); // Default Hari Ini
        }

        if ($request->filled('end_date')) {
            $end = Carbon::parse($request->end_date)->format('Y-m-d');
        } else {
            $end = Carbon::now()->addDay()->format('Y-m-d'); // Default Besok (+1 Hari)
        }

        // 4. QUERY DASAR MOBIL
        // Ambil semua mobil kecuali yang sedang maintenance
        $query = Car::with(['type.category', 'brand'])
                    ->where('status', '!=', 'maintenance');

        // 5. FILTER KATEGORI (Opsional)
        if ($request->has('category_id') && $request->category_id != '') {
            $query->whereHas('type', function($q) use ($request) {
                $q->where('car_category_id', $request->category_id);
            });
        }

        // 6. FILTER KETERSEDIAAN (INTI LOGIKA)
        // Cari ID mobil yang SUDAH DIBOOKING pada rentang tanggal $start s/d $end
        $bookedCarIds = Booking::where(function($q) use ($start, $end) {
            // Logika Bentrok Tanggal (Overlapping)
            $q->whereBetween('start_date', [$start, $end])
              ->orWhereBetween('end_date', [$start, $end])
              ->orWhere(function($sub) use ($start, $end) {
                  $sub->where('start_date', '<', $start)
                      ->where('end_date', '>', $end);
              });
        })
        // Cek booking yang statusnya 'approved', 'ongoing', atau 'pending' (memblokir jadwal)
        ->whereIn('status', ['approved', 'ongoing', 'pending'])
        ->pluck('car_id');

        // 7. EXCLUDE MOBIL YANG DIBOOKING
        // Tampilkan mobil yang ID-nya TIDAK ADA di daftar booking di atas
        $query->whereNotIn('id', $bookedCarIds);

        // Eksekusi Query
        $cars = $query->latest()->get();

        // Kirim data ke View
        // Kita kirimkan juga variable $start dan $end agar view tahu tanggal yang sedang dipakai filter
        return view('dashboard.cari_mobil.index', compact('cars', 'categories', 'start', 'end'));
    }

    // === FUNGSI OTOMATIS UBAH STATUS (TIDAK PERLU DIUBAH) ===
    private function autoUpdateStatus()
    {
        $today = Carbon::now()->format('Y-m-d');

        // A. Booking Selesai (End Date < Hari Ini) -> Ubah ke Completed & Available
        $expiredBookings = Booking::where('end_date', '<', $today)
            ->whereIn('status', ['ongoing', 'approved'])
            ->get();

        foreach ($expiredBookings as $booking) {
            $booking->update(['status' => 'completed']);

            // Cek apakah ada booking lanjutan besok?
            $nextBooking = Booking::where('car_id', $booking->car_id)
                ->where('start_date', '>=', $today)
                ->whereIn('status', ['approved', 'ongoing'])
                ->exists();

            if (!$nextBooking) {
                $booking->car->update(['status' => 'available']);
            }
        }

        // B. Booking Mulai Hari Ini (Start Date <= Hari Ini <= End Date) -> Ubah ke Ongoing & Rented
        $activeBookings = Booking::where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->where('status', 'approved') // Hanya yang sudah lunas/acc
            ->get();

        foreach ($activeBookings as $booking) {
            $booking->update(['status' => 'ongoing']);
            $booking->car->update(['status' => 'rented']);
        }
    }

    // ... (Function createBooking dan storeBooking tetap sama) ...
    public function createBooking($id)
    {
        $car = Car::with(['brand', 'type.category'])->findOrFail($id);
        return view('dashboard.cari_mobil.booking', compact('car'));
    }

    public function storeBooking(Request $request)
    {
        $request->validate([
            'car_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'payment_method' => 'required',
            'customer_name' => 'required',
            'customer_phone' => 'required',
        ]);

        $car = Car::findOrFail($request->car_id);

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $days = $start->diffInDays($end) + 1;
        $total_price = $days * $car->price_per_day;

        // Cek Bentrok (Mencegah double booking)
        $isBooked = Booking::where('car_id', $car->id)
            ->where(function($q) use ($start, $end) {
                 $q->whereBetween('start_date', [$start, $end])
                   ->orWhereBetween('end_date', [$start, $end]);
            })
            ->whereIn('status', ['approved', 'ongoing', 'pending']) // Pending juga memblokir
            ->exists();

        if ($isBooked) {
            return back()->with('error', 'Maaf, mobil ini baru saja dipesan orang lain di tanggal tersebut.');
        }

        Booking::create([
            'car_id' => $car->id,
            'user_id' => auth()->id(),
            'start_date' => $start,
            'end_date' => $end,
            'total_price' => $total_price,
            'payment_method' => $request->payment_method,
            'payment_status' => 'unpaid',
            'status' => 'pending' // Default Pending menunggu pembayaran/ACC
        ]);

        return redirect()->route('pesanan_aktif.index')->with('success', 'Booking Berhasil! Silahkan lakukan pembayaran.');
    }
}
