<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Query Dasar: Booking yang ada drivernya
        $query = Booking::whereNotNull('driver_id')
                        ->with(['car', 'user']); // Eager load relasi

        // 1. Filter Role
        if ($user->role_id == 5) {
            // Jika Driver: Hanya lihat tugas dia sendiri
            $query->where('driver_id', $user->id);
        }

        // 2. Filter Status (HANYA AKTIF)
        // approved = Baru ditugaskan (Belum jalan)
        // ongoing  = Sedang jalan
        $query->whereIn('status', ['approved', 'ongoing']);

        // Urutkan berdasarkan tanggal mulai (yang paling dekat dilayani duluan)
        $tasks = $query->orderBy('start_date', 'asc')->get();

        return view('dashboard.tasks.index', compact('tasks'));
    }

    public function updateStatus(Request $request, $id)
    {
        if (!auth()->user()->can('tasks.update')) abort(403);

        $booking = Booking::findOrFail($id);

        // Security Check: Pastikan drivernya benar
        if (auth()->user()->role_id == 5 && $booking->driver_id != auth()->id()) {
            abort(403, 'Akses Ditolak.');
        }

        $status = $request->status; // 'ongoing' atau 'completed'

        $booking->update(['status' => $status]);

        // Feedback Message
        $msg = ($status == 'ongoing') ? 'Status: Sedang Berjalan. Hati-hati!' : 'Pekerjaan Selesai. Terima Kasih!';

        return back()->with('success', $msg);
    }

    public function history(Request $request)
    {
        // Cek Permission
        // if (!auth()->user()->can('tasks.history')) abort(403);

        $query = \App\Models\Booking::where('driver_id', auth()->id())
                    ->whereIn('status', ['completed', 'cancelled', 'rejected'])
                    ->with(['car.brand', 'user']); // Load Data Mobil & Customer

        // Fitur Search (Opsional, sesuai tampilan)
        if ($request->has('q') && $request->q != '') {
            $q = $request->q;
            $query->where(function($sub) use ($q) {
                $sub->where('booking_code', 'like', "%$q%")
                    ->orWhereHas('car', fn($c) => $c->where('name', 'like', "%$q%"));
            });
        }

        $tasks = $query->latest()->get();

        return view('dashboard.riwayat_tugas.index', compact('tasks'));
    }
}
