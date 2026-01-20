<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Car;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        toastr()->info('Anda telah keluar dari sistem.');

        return redirect('/login');
    }

    // Dashboard
    public function dashboard(){
        return view("dashboard.dashboard.index");
    }

    public function index()
    {
        $user = Auth::user();
        $roleName = $user->role->name ?? 'customer';
        $data = [];

        // ==========================================================
        // 1. DATA UMUM (GLOBAL)
        // ==========================================================
        $today = Carbon::today();

        // ==========================================================
        // 2. LOGIKA: PEGAWAI / STAFF (Operasional Harian)
        // ==========================================================
        if (in_array($roleName, ['admin', 'staff'])) {

            // --- STATS UTAMA ---
            $data['need_approval'] = Booking::where('status', 'pending')->count();
            $data['active_rentals'] = Booking::where('status', 'ongoing')->count();

            // Mobil Ready (Status Available)
            $data['cars_ready'] = Car::where('status', 'available')->count();

            // Logika Driver Tersedia (Asumsi Total Driver Tetap dikurangi yang sedang jalan dengan paket Driver)
            // Misal total driver 10. Kita hitung booking 'ongoing' yang paketnya BUKAN 'Lepas Kunci'
            $drivers_busy = Booking::where('status', 'ongoing')
                                   ->where('package_name', 'not like', '%Lepas Kunci%')
                                   ->count();
            $data['drivers_available'] = max(0, 10 - $drivers_busy); // Anggap punya 10 Driver

            // --- OPERASIONAL HARI INI (PENTING) ---

            // 1. Pengembalian Hari Ini (Unit harus kembali hari ini)
            $data['returns_today'] = Booking::where('status', 'ongoing')
                                            ->whereDate('end_date', $today)
                                            ->with(['user', 'car'])
                                            ->orderBy('return_time', 'asc')
                                            ->get();

            // 2. Jadwal Pengambilan Hari Ini (Unit harus disiapkan)
            $data['pickups_today'] = Booking::where('status', 'approved')
                                            ->whereDate('start_date', $today)
                                            ->with(['user', 'car'])
                                            ->orderBy('pickup_time', 'asc')
                                            ->get();

            // 3. Booking Baru Masuk (Perlu Konfirmasi)
            $data['incoming_bookings'] = Booking::where('status', 'pending')
                                                ->with(['user', 'car'])
                                                ->latest()
                                                ->take(5)
                                                ->get();

            // 4. Reminder Armada (Maintenance)
            $data['maintenance_cars'] = Car::where('status', 'maintenance')->get();
        }

        // ==========================================================
        // 3. LOGIKA: OWNER & ADMIN (Bisnis & Analisa)
        // ==========================================================
        if (in_array($roleName, ['admin', 'owner'])) {
            // Financials
            $data['total_revenue'] = Booking::where('payment_status', 'paid')->sum('grand_total');
            $data['month_revenue'] = Booking::where('payment_status', 'paid')
                                            ->whereMonth('created_at', Carbon::now()->month)
                                            ->whereYear('created_at', Carbon::now()->year)
                                            ->sum('grand_total');

            $data['total_bookings_all'] = Booking::count();
            $data['total_users'] = User::where('role_id', 4)->count(); // Asumsi ID 4 = Customer

            // Grafik/Tabel: Mobil Paling Laris
            $data['top_cars'] = Booking::select('car_id', DB::raw('count(*) as total_rent'))
                                       ->groupBy('car_id')
                                       ->orderByDesc('total_rent')
                                       ->with('car')
                                       ->take(5)
                                       ->get();

            // Riwayat Transaksi Terakhir (Audit Trail)
            $data['latest_transactions'] = Booking::with(['user', 'car'])
                                                  ->where('payment_status', 'paid')
                                                  ->latest()
                                                  ->take(6)
                                                  ->get();
        }

        // ==========================================================
        // 4. LOGIKA: CUSTOMER (Personal Experience)
        // ==========================================================
        if ($roleName == 'customer') {
            $data['my_total'] = Booking::where('user_id', $user->id)->count();
            $data['my_active_count'] = Booking::where('user_id', $user->id)->where('status', 'ongoing')->count();
            $data['my_pending_count'] = Booking::where('user_id', $user->id)->where('status', 'pending')->count();

            // Total Uang Dikeluarkan
            $data['my_spending'] = Booking::where('user_id', $user->id)
                                          ->where('payment_status', 'paid')
                                          ->sum('grand_total');

            // Booking Sedang Berjalan / Menunggu
            $data['my_active_list'] = Booking::where('user_id', $user->id)
                                             ->whereIn('status', ['pending', 'approved', 'ongoing'])
                                             ->with('car')
                                             ->latest()
                                             ->get();

            // Riwayat Selesai
            $data['my_history'] = Booking::where('user_id', $user->id)
                                         ->whereIn('status', ['completed', 'cancelled', 'rejected'])
                                         ->with('car')
                                         ->latest()
                                         ->take(5)
                                         ->get();
        }

        return view('dashboard.dashboard.index', compact('data', 'roleName'));
    }

    // Pesanan Aktif
    public function pesanan_aktif(){
        return view("dashboard.pesan_aktif.index");
    }
}
