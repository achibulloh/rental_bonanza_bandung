<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Setting;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Konfigurasi Server Key Midtrans
        $serverKey = Setting::where('key', 'serverkey')->value('value');
        $isProduction = Setting::where('key', 'isProduction')->value('value');

        Config::$serverKey = $serverKey;
        Config::$isProduction = ($isProduction === 'true' || $isProduction === true || $isProduction === '1');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        try {
            // 2. Terima Notifikasi dari Midtrans
            $notif = new Notification();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid Notification'], 400);
        }

        $transactionStatus = $notif->transaction_status;
        $type = $notif->payment_type;
        $orderId = $notif->order_id; // Contoh: RMX-KODE-123
        $fraud = $notif->fraud_status;

        // 3. Pecah Order ID untuk ambil Kode Booking Asli
        // Karena format kita: KODEBOOKING-RANDOM (misal: RMX-AB123-999)
        // Kita perlu ambil bagian depannya saja.
        $parts = explode('-', $orderId);
        // Gabungkan kembali bagian kode booking (antisipasi jika kode booking mengandung dash)
        // Asumsi format booking_code Anda unik dan ada di database
        // Cara paling aman: cari booking yang snap_token-nya cocok atau cari via contains

        // Versi Simpel: Kita cari berdasarkan booking_code yang terkandung di order_id
        // Atau cari booking yang grand_total-nya sama (kurang akurat)
        // Cara terbaik di controller booking sebelumnya: simpan order_id lengkap di tabel bookings jika mau presisi.

        // Tapi untuk sekarang, kita coba cari berdasarkan kode booking:
        // Ambil 2 bagian pertama (RMX dan KODEUNIK)
        $realBookingCode = $parts[0] . '-' . $parts[1];

        $booking = Booking::where('booking_code', $realBookingCode)->first();

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        // 4. Logika Update Status Berdasarkan Respon Midtrans
        if ($transactionStatus == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $booking->update(['payment_status' => 'pending']);
                } else {
                    $booking->update(['payment_status' => 'paid']);
                }
            }
        } else if ($transactionStatus == 'settlement') {
            // SUKSES BAYAR (Transfer Bank, GoPay, dll masuk sini)
            $booking->update(['payment_status' => 'paid']);

        } else if ($transactionStatus == 'pending') {
            $booking->update(['payment_status' => 'unpaid']); // Masih menunggu

        } else if ($transactionStatus == 'deny') {
            $booking->update(['payment_status' => 'failed', 'status' => 'cancelled']);

        } else if ($transactionStatus == 'expire') {
            $booking->update(['payment_status' => 'expired', 'status' => 'cancelled']);

        } else if ($transactionStatus == 'cancel') {
            $booking->update(['payment_status' => 'failed', 'status' => 'cancelled']);
        }

        return response()->json(['message' => 'Callback received successfully']);
    }
}
