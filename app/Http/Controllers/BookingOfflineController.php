<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\User;
use App\Models\Booking;
use App\Models\CarPackage;
use App\Models\Setting;
use App\Services\WhatsappService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; // Tambahkan Log untuk debugging

class BookingOfflineController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'car'])
                    ->where('booking_type', 'Offline')
                    ->latest()
                    ->get();

        $customers = User::where('role_id', 4)->orderBy('name', 'ASC')->get();

        return view('dashboard.booking.offline.index', compact('bookings', 'customers'));
    }

    public function checkAvailability(Request $request)
    {
        if (!$request->start_date || !$request->end_date) {
            return response()->json(['status' => 'error', 'message' => 'Tanggal wajib diisi'], 400);
        }

        try {
            $start = Carbon::parse($request->start_date);
            $end   = Carbon::parse($request->end_date);

            $bookedCarIds = Booking::whereIn('status', ['approved', 'paid', 'ongoing'])
                ->where(function ($q) use ($start, $end) {
                    $q->whereBetween('start_date', [$start, $end])
                      ->orWhereBetween('end_date', [$start, $end])
                      ->orWhere(function ($sub) use ($start, $end) {
                          $sub->where('start_date', '<', $start)
                              ->where('end_date', '>', $end);
                      });
                })
                ->pluck('car_id');

            $availableCars = Car::with(['brand', 'packages'])
                                ->whereNotIn('id', $bookedCarIds)
                                ->get();

            return response()->json(['status' => 'success', 'data' => $availableCars]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|numeric|unique:users,phone',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make('12345678'),
            'role_id' => 4,
        ]);

        return response()->json(['status' => 'success', 'data' => $user]);
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'user_id' => 'required',
            'car_id' => 'required',
            'package_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'payment_method' => 'required',
        ]);

        // 2. Simpan Data (Bagian ini sudah oke)
        $car = Car::find($request->car_id);
        $package = CarPackage::find($request->package_id);
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $duration = $startDate->diffInDays($endDate) ?: 1;
        $totalPrice = $package->price * $duration;
        $bookingCode = 'BNZ-' . strtoupper(Str::random(6));

        $booking = Booking::create([
            'booking_code' => $bookingCode,
            'user_id' => $request->user_id,
            'car_id' => $car->id,
            'package_name' => $package->name,
            'car_price_per_day' => $package->price,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'pickup_time' => '09:00:00',
            'return_time' => '09:00:00',
            'pickup_location' => 'Kantor (Walk-In)',
            'address_detail' => 'Offline Booking',
            'total_rent_price' => $totalPrice,
            'grand_total' => $totalPrice,
            'status' => 'approved',
            'payment_status' => 'paid',
            'payment_method' => $request->payment_method,
            'booking_type' => 'Offline',
        ]);

        // ============================================================
        // MODE DEBUG: CEK HASIL KIRIM WA
        // ============================================================
        $customerPhone = $booking->user->phone;

        if ($customerPhone) {

            // 1. Pastikan Folder
            if (!Storage::disk('public')->exists('notas')) {
                Storage::disk('public')->makeDirectory('notas');
            }

            // 2. Generate PDF
            $settings = Setting::pluck('value', 'key')->toArray();
            $pdf = Pdf::loadView('dashboard.nota.pdf.index', [
                'booking' => $booking,
                'settings' => $settings
            ]);

            // 3. Simpan
            $fileName = 'Nota_' . $booking->booking_code . '.pdf';
            Storage::disk('public')->put('notas/' . $fileName, $pdf->output());

            // 4. Cek URL (Manual String untuk Ngrok)
            $baseUrl = config('app.url');
            $cleanBaseUrl = rtrim($baseUrl, '/');
            $fileUrl = $cleanBaseUrl . '/storage/notas/' . $fileName;

            // 5. LOGIKA SAPAAN OTOMATIS (WIB)
            // Pastikan Carbon menggunakan zona waktu Jakarta/WIB
            $hour = Carbon::now('Asia/Jakarta')->hour;

            if ($hour >= 3 && $hour < 11) {
                $salam = "Selamat Pagi";
            } elseif ($hour >= 11 && $hour < 15) {
                $salam = "Selamat Siang";
            } elseif ($hour >= 15 && $hour < 19) {
                $salam = "Selamat Sore";
            } else {
                $salam = "Selamat Malam";
            }

            // 6. Siapkan Caption WA
            $appName = $settings['app_name'] ?? 'Bonanza Rental';

            // Perhatikan bagian "{$salam}" di awal kalimat
            $caption = "{$salam} Kak *{$booking->user->name}*,\n\n"
                     . "Terima kasih telah bertransaksi di {$appName}.\n"
                     . "Berikut kami lampirkan Nota Digital Anda.\n\n"
                     . "Status: *LUNAS (PAID)*\n"
                     . "Kode: *{$booking->booking_code}*\n"
                     . "Total: *Rp " . number_format($booking->grand_total, 0, ',', '.') . "*\n\n"
                     . "Terima Kasih.";

            // 7. Panggil Service WA
            $waService = app(WhatsappService::class);
            $waService->sendDocument($customerPhone, $fileUrl, $caption);

            // --- STOP PROGRAM DISINI UNTUK MELIHAT HASIL ---
            // dd([
            //     'STATUS KIRIM' => $result,
            //     'URL PDF (Coba klik)' => $fileUrl,
            //     'NOMOR TUJUAN' => $customerPhone,
            //     'APP_URL (.env)' => env('APP_URL')
            // ]);
        }

        return redirect()->route('offline.index')->with('success', 'Transaksi Berhasil');
    }
}
