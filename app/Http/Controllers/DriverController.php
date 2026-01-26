<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;

class DriverController extends Controller
{
    public function index()
    {
        // 1. Ambil Data Driver & Transaksi Aktifnya
        $drivers = User::where('role_id', 5)
                    ->with(['activeTransaction.car'])
                    ->get();

        // 2. Ambil Booking yang MENUNGGU Driver (Status approved tapi belum ada driver)
        $availableBookings = Booking::where('status', 'approved')
                                    ->whereNull('driver_id')
                                    ->with('car')
                                    ->get();

        // 3. HITUNG STATISTIK (Ini yang Anda minta jangan hilang)
        $total    = $drivers->count();
        $bertugas = $drivers->filter(fn($d) => $d->activeTransaction !== null)->count();
        $tersedia = $total - $bertugas;

        $stats = [
            'total'    => $total,
            'tersedia' => $tersedia,
            'bertugas' => $bertugas,
        ];

        return view('dashboard.drivers.index', compact('drivers', 'stats', 'availableBookings'));
    }

    // Assign Driver
    public function assign(Request $request)
    {
        if (!auth()->user()->can('drivers.assign')) abort(403);

        $request->validate([
            'driver_id'  => 'required|exists:users,id',
            'booking_id' => 'required|exists:bookings,id',
        ]);

        Booking::where('id', $request->booking_id)->update([
            'driver_id' => $request->driver_id
        ]);

        return back()->with('success', 'Driver berhasil ditugaskan!');
    }

    // Edit Data Driver
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('drivers.update')) abort(403);

        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'required|numeric',
            'email' => 'required|email|unique:users,email,'.$id,
        ]);

        User::where('role_id', 5)->findOrFail($id)->update([
            'name'  => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Data driver diperbarui!');
    }

    // Batalkan Pekerjaan (Driver jadi Tersedia lagi)
    public function cancelJob(Request $request)
    {
        if (!auth()->user()->can('drivers.cancel')) abort(403);

        $booking = Booking::findOrFail($request->booking_id);

        // Kosongkan driver_id pada booking tersebut
        $booking->update(['driver_id' => null]);

        return back()->with('success', 'Pekerjaan dibatalkan. Driver kini Tersedia.');
    }
}
