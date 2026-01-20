<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;

class SerahTerimaController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // --- DATA CHECK-IN ---

        // 1. Siap Check-in (Hari Ini)
        $readyToCheckin = Booking::with(['user', 'car'])
            ->where('status', 'approved')
            ->where('payment_status', 'paid')
            ->whereDate('start_date', $today)
            ->orderBy('start_date', 'asc')
            ->get();

        // 2. Check-in yang Dibatalkan (Hari Ini)
        $canceledCheckinCount = Booking::where('status', 'cancelled')
            ->whereDate('start_date', $today)
            ->count();

        // 3. Check-in Akan Datang (Besok dst)
        $upcomingCheckinCount = Booking::where('status', 'approved')
            ->where('payment_status', 'paid')
            ->whereDate('start_date', '>', $today)
            ->count();


        // --- DATA CHECK-OUT ---

        // 4. Kembali Hari Ini
        $todayCheckouts = Booking::with(['user', 'car'])
            ->where('status', 'ongoing')
            ->whereDate('end_date', $today)
            ->orderBy('end_date', 'asc')
            ->get();

        // 5. Statistik Global Check-out
        $allActive = Booking::where('status', 'ongoing')->get();
        $lateCount = $allActive->filter(function($b) use ($today) {
            return Carbon::parse($b->end_date)->lt($today);
        })->count();

        // Sedang berjalan (On Track) = Total Active - Yang Telat
        $onTrackCount = $allActive->count() - $lateCount;


        // --- MENYATUKAN STATISTIK ---
        $stats = [
            // Stats Tab Check-in
            'checkin_ready'    => $readyToCheckin->count(),
            'checkin_cancel'   => $canceledCheckinCount,
            'checkin_upcoming' => $upcomingCheckinCount,

            // Stats Tab Check-out
            'checkout_active'  => $allActive->count(), // Total unit diluar
            'checkout_ontrack' => $onTrackCount,       // Sedang berjalan (aman)
            'checkout_late'    => $lateCount,          // Terlambat
        ];

        return view('dashboard.serah_terima.index', compact('readyToCheckin', 'todayCheckouts', 'stats'));
    }

    public function showCheckIn($booking_code)
    {
        // Cari data booking berdasarkan kode
        $booking = Booking::with(['user', 'car'])->where('booking_code', $booking_code)->firstOrFail();

        // Kirim data ke view baru
        return view('dashboard.serah_terima.checkin.index', compact('booking'));
    }

    public function showCheckOut($booking_code)
    {
        $booking = Booking::with(['user', 'car', 'detail'])->where('booking_code', $booking_code)->firstOrFail();

        return view('dashboard.serah_terima.checkout.index', compact('booking'));
    }

    public function storeCheckIn(Request $request, $id)
    {
        // 1. Validasi Input
        $request->validate([
            'km_awal' => 'required|numeric',
            'waktu_serah_terima' => 'required|date',
            // Validasi Foto Utama
            'photo_front' => 'image|max:5120',
            'photo_left' => 'image|max:5120',
            'photo_right' => 'image|max:5120',
            'photo_back' => 'image|max:5120',

            // Validasi Foto Customer & Video (Sesuai Request)
            'photo_car_and_customer' => 'nullable|image|max:5120', // Foto max 5MB
            'video_condition' => 'nullable|mimes:mp4,mov,avi,3gp|max:51200', // Video max 50MB
            'signature_customer_svg' => 'required',
            'signature_officer_svg' => 'required',
        ]);

        $booking = Booking::findOrFail($id);

        // 2. Siapkan Data Dasar
        $dataToSave = [
            'booking_id' => $booking->id,
            'actual_start_date' => $request->waktu_serah_terima,
            'start_km' => $request->km_awal,
            'start_fuel_level' => $request->bbm_awal,
            'start_condition_notes' => $request->kondisi_awal,
            'checklist_in' => $request->checklist ?? [],
            'signature_customer_in' => $request->signature_customer_svg,
            'signature_officer_in'  => $request->signature_officer_svg,
            'officer_id_in' => \Illuminate\Support\Facades\Auth::id(),
        ];

        // 3. Upload Foto Utama (4 Sisi)
        $sides = ['front', 'left', 'right', 'back'];
        foreach ($sides as $side) {
            if ($request->hasFile("photo_{$side}")) {
                $dataToSave["photo_{$side}_in"] = $request->file("photo_{$side}")->store("checkin/{$booking->id}", 'public');
            }
        }

        // 4. Upload Foto Bersama Customer (photo_car_and_customer_in)
        if ($request->hasFile('photo_car_and_customer')) {
            $dataToSave['photo_car_and_customer_in'] = $request->file('photo_car_and_customer')->store("checkin/{$booking->id}/customer", 'public');
        }

        // 5. Upload Video Kondisi (video_condition_in)
        if ($request->hasFile('video_condition')) {
            $dataToSave['video_condition_in'] = $request->file('video_condition')->store("checkin/{$booking->id}/video", 'public');
        }

        // 6. Simpan ke Database DetailBooking
        \App\Models\DetailBooking::updateOrCreate(
            ['booking_id' => $booking->id],
            $dataToSave
        );

        // 7. Update Status Booking Utama
        $booking->update(['status' => 'ongoing']);

        return redirect()->route('serah_terima.index')
            ->with('success', 'Check-in berhasil! Foto serah terima dan video telah tersimpan.');
    }

    public function storeCheckOut(Request $request, $id)
    {
        // 1. Validasi
        $request->validate([
            'km_akhir' => 'required|numeric',
            'waktu_pengembalian' => 'required|date',
            'bbm_akhir' => 'required|numeric|min:0|max:100',
            'photo_front' => 'required|image|max:5120',
            'photo_left' => 'required|image|max:5120',
            'photo_right' => 'required|image|max:5120',
            'photo_back' => 'required|image|max:5120',
            'signature_customer_svg' => 'required',
            'signature_officer_svg' => 'required',
        ], [
            // Custom Pesan Error agar lebih profesional
            'km_akhir.required' => 'Kilometer akhir wajib diisi.',
            'signature_customer_svg.required' => 'Tanda tangan penyewa wajib diisi.',
            'signature_officer_svg.required' => 'Tanda tangan petugas wajib diisi.',
        ]);

        $booking = Booking::with('detail')->findOrFail($id);

        // 2. BERSIHKAN FORMAT RUPIAH (Hapus "Rp" dan "Titik")
        // Contoh: "Rp 1.500.000" -> 1500000
        $fineOvertime = (int) preg_replace('/[^0-9]/', '', $request->fine_overtime);
        $fineFuel = (int) preg_replace('/[^0-9]/', '', $request->fine_fuel);
        $fineDamage = (int) preg_replace('/[^0-9]/', '', $request->fine_damage);
        $fineLost = (int) preg_replace('/[^0-9]/', '', $request->fine_lost);

        // *Opsional: Hitung ulang overtime di backend untuk keamanan ganda
        // (Bisa dihapus jika Anda percaya penuh pada input frontend)
        $actualTime = Carbon::parse($request->waktu_pengembalian);
        $planTime = Carbon::parse($booking->end_date);
        if ($actualTime->gt($planTime)) {
            $hours = ceil($actualTime->floatDiffInHours($planTime));
            $calcFine = $hours * ($booking->total_price * 0.10);
            // Gunakan hasil hitungan backend jika input frontend berbeda jauh/salah
            if($calcFine > $fineOvertime) $fineOvertime = $calcFine;
        }

        // 3. Hitung Total & Sisa
        $totalFine = $fineOvertime + $fineFuel + $fineDamage + $fineLost;
        $newGrandTotal = $booking->total_price + $totalFine;
        $alreadyPaid = $booking->down_payment ?? 0;
        $remainingPayment = $newGrandTotal - $alreadyPaid;

        // 4. Siapkan Array Data
        $dataToSave = [
            'actual_end_date' => $request->waktu_pengembalian,
            'end_km' => $request->km_akhir,
            'end_fuel_level' => $request->bbm_akhir,

            // Data Denda
            'fine_overtime' => $fineOvertime,
            'fine_fuel' => $fineFuel,
            'fine_damage' => $fineDamage,
            'fine_lost_items' => $fineLost,
            'total_fine' => $totalFine,
            'grand_total_final' => $newGrandTotal,
            'remaining_payment' => $remainingPayment,

            // Tanda Tangan & Petugas
            'signature_customer_out' => $request->signature_customer_svg,
            'signature_officer_out' => $request->signature_officer_svg,
            'officer_id_out' => Auth::id(),
        ];

        // 5. Upload Foto & Video
        $folder = "checkout/{$booking->id}";
        foreach (['front', 'left', 'right', 'back'] as $side) {
            if ($request->hasFile("photo_{$side}")) {
                $dataToSave["photo_{$side}_out"] = $request->file("photo_{$side}")->store($folder, 'public');
            }
        }
        // Upload Opsional
        if ($request->hasFile('photo_additional')) {
            $dataToSave['photo_additional_out'] = $request->file('photo_additional')->store("$folder/details", 'public');
        }
        if ($request->hasFile('video_condition')) {
            $dataToSave['video_condition_out'] = $request->file('video_condition')->store("$folder/videos", 'public');
        }

        // 6. Simpan ke Database
        // Update Detail
        $booking->detail()->update($dataToSave);

        // Update Booking Utama (Status Selesai)
        $booking->update([
            'status' => 'completed',
            'payment_status' => ($remainingPayment <= 0) ? 'paid' : 'partial',
        ]);

        return redirect()->route('dashboard.serah_terima.index')
            ->with('success', 'Check-out Berhasil! Kendaraan telah dikembalikan.');
    }
}
