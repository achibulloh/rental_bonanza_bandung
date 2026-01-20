<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Services\WhatsappService; // Panggil Service

class ApprovalController extends Controller
{
    protected $waService;

    // Inject Service
    public function __construct(WhatsappService $waService)
    {
        $this->waService = $waService;
    }

    public function index()
    {
        $bookings = Booking::with(['user', 'car'])
            ->where(function($query) {
                $query->where('status', 'pending');
                $query->orWhere(function($q) {
                    $q->where('status', 'approved')
                      ->where('payment_status', 'unpaid')
                      ->whereNotNull('payment_proof');
                });
            })
            ->latest()
            ->get();

        return view('dashboard.approval.index', compact('bookings'));
    }

    // 1. APPROVE BOOKING (Terima pesanan awal)
    public function approveBooking($id)
    {
        $booking = Booking::with(['user', 'car'])->findOrFail($id);
        $booking->update(['status' => 'approved']);

        // Kirim WA
        $pesan = "*Halo {$booking->user->name},*\n\n"
               . "Permintaan sewa mobil Anda telah *DISETUJUI* ✅\n"
               . "Unit: {$booking->car->brand->name} {$booking->car->name}\n"
               . "Kode Booking: *{$booking->booking_code}*\n\n"
               . "Silakan segera lakukan pembayaran dan upload bukti transfer di dashboard Anda.\n\n"
               . "_Terima Kasih_";

        $this->waService->sendMessage($booking->user->phone, $pesan);

        return redirect()->back()->with('success', 'Booking diterima. Notifikasi WA dikirim.');
    }

    // 2. APPROVE PAYMENT (Validasi Lunas)
    public function approvePayment($id)
    {
        $booking = Booking::with(['user', 'car'])->findOrFail($id);
        $booking->update([
            'payment_status' => 'paid',
            // 'status' => 'ongoing' // Opsional: jika ingin langsung ubah status sewa
        ]);

        // Kirim WA
        $pesan = "*PEMBAYARAN DITERIMA ✅*\n\n"
               . "Halo {$booking->user->name},\n"
               . "Pembayaran untuk Booking ID *{$booking->booking_code}* telah kami validasi (LUNAS).\n\n"
               . "Mobil: {$booking->car->brand->name} {$booking->car->name}\n"
               . "Silakan ambil unit sesuai jadwal sewa.\n\n"
               . "_Hati-hati di jalan!_";

        $this->waService->sendMessage($booking->user->phone, $pesan);

        return redirect()->back()->with('success', 'Pembayaran divalidasi Lunas. Notifikasi WA dikirim.');
    }

    // 3. REJECT (Tolak Booking / Bukti)
    public function reject(Request $request, $id)
    {
        $request->validate(['rejection_reason' => 'required|string']);
        $booking = Booking::with(['user', 'car'])->findOrFail($id);
        $alasan = $request->rejection_reason;

        $pesan = "";

        // Case A: Tolak Booking Awal
        if($booking->status == 'pending') {
            $booking->update([
                'status' => 'rejected',
                'note' => $alasan
            ]);

            $pesan = "*MOHON MAAF ❌*\n\n"
                   . "Halo {$booking->user->name},\n"
                   . "Permintaan sewa mobil Anda *DITOLAK*.\n"
                   . "Unit: {$booking->car->brand->name} {$booking->car->name}\n\n"
                   . "Alasan: _{$alasan}_\n\n"
                   . "Silakan cari unit lain atau hubungi admin.";
        }
        // Case B: Tolak Bukti Bayar
        else {
            $booking->update([
                'payment_proof' => null,
                'payment_status' => "unpaid",
                'note' => 'Bukti Ditolak: ' . $alasan
            ]);

            $pesan = "*BUKTI TRANSFER DITOLAK ⚠️*\n\n"
                   . "Halo {$booking->user->name},\n"
                   . "Bukti pembayaran untuk kode *{$booking->booking_code}* tidak dapat kami validasi.\n\n"
                   . "Alasan: _{$alasan}_\n\n"
                   . "Silakan upload ulang bukti transfer yang valid melalui dashboard.";
        }

        // Kirim WA
        $this->waService->sendMessage($booking->user->phone, $pesan);

        return redirect()->back()->with('success', 'Permintaan ditolak. Notifikasi WA dikirim.');
    }
}
