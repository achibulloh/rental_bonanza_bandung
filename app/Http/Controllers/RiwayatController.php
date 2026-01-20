<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data booking milik user yang sedang login
        // Diurutkan dari yang terbaru
        // Include relasi 'car' dan 'brand' agar bisa ambil nama mobil
        $query = Booking::with(['car.brand'])
                    ->where('user_id', auth()->id())
                    ->orderBy('created_at', 'desc');

        // Fitur Pencarian (Opsional: Jika user ketik di search box)
        if ($request->has('q') && $request->q != '') {
            $keyword = $request->q;
            $query->where(function($q) use ($keyword) {
                $q->where('booking_code', 'like', "%$keyword%")
                  ->orWhereHas('car', function($c) use ($keyword) {
                      $c->where('name', 'like', "%$keyword%");
                  });
            });
        }

        $bookings = $query->get();

        return view('dashboard.riwayat_booking.index', compact('bookings'));
    }

    public function show($code)
    {
        $booking = Booking::with(['car.brand', 'user', 'review'])
                    ->where('booking_code', $code)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();

        return view('dashboard.riwayat_booking.detail', compact('booking'));
    }
}
