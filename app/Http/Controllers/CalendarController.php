<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Car;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        return view('dashboard.kalender.index');
    }

    public function getEvents(Request $request)
    {
        // Ambil booking yang statusnya TIDAK cancelled/rejected
        $bookings = Booking::with(['car.brand', 'user'])
                    ->whereIn('status', ['pending', 'approved', 'ongoing', 'completed'])
                    ->whereDate('start_date', '>=', $request->start)
                    ->whereDate('end_date',   '<=', $request->end)
                    ->get();

        $events = [];

        foreach ($bookings as $b) {
            // Tentukan Warna Berdasarkan Status
            $color = '#ffc107'; // Default Pending (Kuning)
            if ($b->status == 'approved') $color = '#dc3545'; // Merah (Booked)
            if ($b->status == 'ongoing')  $color = '#28a745'; // Hijau
            if ($b->status == 'completed') $color = '#6c757d'; // Abu

            // FullCalendar End Date is Exclusive (Harus +1 hari agar bar penuh sampai akhir)
            $end_date_calendar = Carbon::parse($b->end_date)->addDay()->format('Y-m-d');

            $events[] = [
                'id' => $b->id,
                'title' => $b->car->brand->name . ' ' . $b->car->name,
                'start' => $b->start_date,
                'end' => $end_date_calendar, // Untuk render bar kalender
                'backgroundColor' => $color,
                'borderColor' => $color,
                // Data Tambahan untuk Modal
                'extendedProps' => [
                    'real_end_date' => $b->end_date, // Tanggal asli untuk display teks
                    'unit' => $b->car->brand->name . ' ' . $b->car->name . ' (' . $b->car->license_plate . ')',
                    'penyewa' => $b->user->name,
                    'email' => $b->user->email,
                    'durasi' => Carbon::parse($b->start_date)->diffInDays($b->end_date) + 1 . ' Hari',
                    'status_order' => ucfirst($b->status),
                    'bg_color' => $color,
                    'payment_info' => $b->payment_status == 'paid' ? 'LUNAS' : 'Belum Lunas',
                    'total' => 'Rp ' . number_format($b->grand_total, 0, ',', '.'),
                    'acc_by' => 'Admin' // Bisa diganti logic user approve
                ]
            ];
        }

        return response()->json($events);
    }
}
