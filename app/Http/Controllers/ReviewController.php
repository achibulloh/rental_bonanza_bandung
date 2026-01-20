<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Booking;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        // Validasi: Pastikan user yang login adalah pemilik booking
        if ($booking->user_id != auth()->id()) {
            return back()->with('error', 'Anda tidak memiliki akses.');
        }

        // Validasi: Pastikan belum pernah review sebelumnya
        if (Review::where('booking_id', $booking->id)->exists()) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk pesanan ini.');
        }

        Review::create([
            'user_id' => auth()->id(),
            'car_id' => $booking->car_id,
            'booking_id' => $booking->id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return back()->with('success', 'Terima kasih! Ulasan Anda berhasil dikirim.');
    }
}
