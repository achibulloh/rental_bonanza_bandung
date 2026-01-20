<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Car;
use App\Models\CarPackage;
use App\Models\Setting;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\WhatsappService;

class BookingFlowController extends Controller
{
    public function index($carId)
    {
        $car = Car::with(['brand', 'packages'])->findOrFail($carId);
        return view('dashboard.booking.index', ['step' => 1, 'car' => $car, 'booking' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'car_id' => 'required',
            'package_id' => 'required',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'pickup_time' => 'required',
            'pickup_location' => 'required',
        ]);

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);

        // Cek Bentrok
        $isBooked = Booking::where('car_id', $request->car_id)
            ->whereIn('status', ['pending', 'approved', 'ongoing'])
            ->where(function($query) use ($start, $end) {
                $query->whereBetween('start_date', [$start, $end])
                      ->orWhereBetween('end_date', [$start, $end])
                      ->orWhere(function($sub) use ($start, $end) {
                          $sub->where('start_date', '<=', $start)->where('end_date', '>=', $end);
                      });
            })->exists();

        if ($isBooked) {
            return back()->withInput()->with('error', 'Mobil SUDAH DIPESAN pada tanggal tersebut.');
        }

        $car = Car::findOrFail($request->car_id);
        $package = CarPackage::findOrFail($request->package_id);
        $days = $start->diffInDays($end) + 1;
        $total = $days * $package->price;

        $booking = Booking::create([
            'booking_code' => 'BNZ-' . strtoupper(Str::random(6)),
            'user_id' => auth()->id(),
            'car_id' => $car->id,
            'package_name' => $package->name,
            'car_price_per_day' => $package->price,
            'start_date' => $start,
            'end_date' => $end,
            'pickup_time' => $request->pickup_time,
            'return_time' => $request->pickup_time,
            'pickup_location' => $request->pickup_location,
            'address_detail' => $request->address_detail,
            'total_rent_price' => $total,
            'grand_total' => $total,
            'guarantee_type' => 'pending',
            'status' => 'pending'
        ]);

        return redirect()->route('booking.track', $booking->booking_code);
    }

    // === LOGIKA UTAMA ===
    public function checkStatus($code)
    {
        $booking = Booking::with(['car.brand', 'user'])->where('booking_code', $code)->firstOrFail();

        // 1. AUTO EXPIRED
        if ($booking->status == 'approved' && $booking->payment_status == 'unpaid') {
            $deadline = Carbon::parse($booking->updated_at)->addMinutes(15);
            if (Carbon::now()->greaterThan($deadline)) {
                $booking->update(['status' => 'cancelled', 'payment_status' => 'expired']);
                return redirect()->route('riwayat_booking.index')->with('error', 'Waktu habis.');
            }
        }

        // 2. CEK STATUS PAID -> PROSES KIRIM WA
        if ($booking->payment_status == 'paid') {

            $fileName = 'Nota_' . $booking->booking_code . '.pdf';

            // Hapus file lama jika ada (untuk testing)
            if (Storage::disk('public')->exists('notas/' . $fileName)) {
                Storage::disk('public')->delete('notas/' . $fileName);
            }

            // --- MULAI PROSES ---

            // A. Buat Folder
            if (!Storage::disk('public')->exists('notas')) {
                Storage::disk('public')->makeDirectory('notas');
            }

            // B. Generate PDF
            $settings = Setting::pluck('value', 'key')->toArray();
            $pdf = Pdf::loadView('dashboard.nota.pdf.index', [
                'booking' => $booking,
                'settings' => $settings
            ]);

            // C. Simpan File
            Storage::disk('public')->put('notas/' . $fileName, $pdf->output());

            // D. Kirim WA
            $result = ['status' => false, 'message' => 'No Phone Number']; // Default awal array
                if ($booking->user->phone) {
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
                $fileUrl = asset('storage/notas/' . $fileName); // WhatsappService akan otomatis ubah ke HTTPS

                $appName = $settings['app_name'] ?? 'Bonanza Rental';
                $caption = "{$salam} Kak *{$booking->user->name}*,\n\n"
                        . "Terima kasih telah bertransaksi di {$appName}.\n"
                        . "Berikut kami lampirkan Nota Digital Anda.\n\n"
                        . "Status: *LUNAS (PAID)*\n"
                        . "Kode: *{$booking->booking_code}*\n"
                        . "Total: *Rp " . number_format($booking->grand_total, 0, ',', '.') . "*\n\n"
                        . "Terima Kasih.";

                $waService = app(WhatsappService::class);
                $result = $waService->sendDocument($booking->user->phone, $fileUrl, $caption);
            }

            return redirect()->route('riwayat_booking.index')
                ->with('success', 'Pembayaran Lunas! Nota dikirim.');
        }

        // 3. TAMPILAN VIEW
        $paymentMethod = Setting::where('key', 'payment_method')->value('value') ?? 'midtrans';
        $bankInfo = [
            'bank' => Setting::where('key', 'namabank')->value('value') ?? 'BCA',
            'rek'  => Setting::where('key', 'nomorrekening')->value('value') ?? '000',
            'name' => Setting::where('key', 'namarekening')->value('value') ?? 'Admin'
        ];

        if ($booking->status == 'approved') {
            if ($paymentMethod != 'transfer' && empty($booking->snap_token)) {
                $this->generateMidtransToken($booking);
                $booking->refresh();
            }
            $timeLeft = max(0, Carbon::parse($booking->updated_at)->addMinutes(15)->diffInSeconds(Carbon::now()));

            return view('dashboard.booking.index', [
                'step' => 4,
                'booking' => $booking,
                'car' => $booking->car,
                'paymentMethod' => $paymentMethod,
                'bankInfo' => $bankInfo,
                'timeLeft' => $timeLeft
            ]);
        }

        if ($booking->status == 'pending') {
            $step = ($booking->guarantee_type == 'pending') ? 2 : 3;
            return view('dashboard.booking.index', ['step' => $step, 'booking' => $booking, 'car' => $booking->car]);
        }

        return redirect()->route('riwayat_booking.index');
    }

    public function uploadProof(Request $request, $code)
    {
        $request->validate(['payment_proof' => 'required|image|max:2048']);
        $booking = Booking::where('booking_code', $code)->firstOrFail();
        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('proofs', 'public');
            $booking->update(['payment_proof' => $path]);
        }
        return back()->with('success', 'Upload Berhasil');
    }

    public function updateGuarantee(Request $request, $code)
    {
        $booking = Booking::where('booking_code', $code)->firstOrFail();
        $g_cost = ($request->guarantee_type == 'deposit_money') ? 3000000 : 0;
        $booking->update([
            'guarantee_type' => $request->guarantee_type,
            'guarantee_cost' => $g_cost,
            'grand_total' => $booking->total_rent_price + $g_cost
        ]);
        return redirect()->route('booking.track', $code);
    }

    private function generateMidtransToken($booking)
    {
        $serverKey = Setting::where('key', 'serverkey')->value('value');
        if (empty($serverKey)) return;

        Config::$serverKey = $serverKey;
        Config::$isProduction = Setting::where('key', 'isProduction')->value('value') === '1';
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $booking->booking_code . '-' . rand(100, 999),
                'gross_amount' => (int)$booking->grand_total
            ],
            'customer_details' => [
                'first_name' => $booking->user->name,
                'email' => $booking->user->email,
                'phone' => $booking->user->phone
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $booking->update(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            Log::error("Midtrans Error: " . $e->getMessage());
        }
    }
}
