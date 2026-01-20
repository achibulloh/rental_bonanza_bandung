<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;
use PDF;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionExport;
use Carbon\CarbonPeriod;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // A. Filter Tanggal (Default: Bulan Ini)
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate   = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();
        $status    = $request->status ?? 'all';

        // B. Query Data
        $query = Booking::with('user', 'car')
            ->whereBetween('created_at', [
                $startDate->format('Y-m-d 00:00:00'),
                $endDate->format('Y-m-d 23:59:59')
            ]);

        // C. Filter Status
        if ($status != 'all') {
            $query->where('payment_status', $status);
        }

        // D. Ambil Data
        $bookings = $query->latest()->get();

        // E. Hitung Statistik Cards
        $totalIncome = $bookings->where('payment_status', 'paid')->sum('grand_total');
        $totalTransactions = $bookings->count();
        $pendingTransactions = $bookings->where('payment_status', 'pending')->count();

        // F. DATA UNTUK GRAFIK (Chart.js)
        // 1. Grouping data yang PAID berdasarkan tanggal
        $chartData = $bookings->where('payment_status', 'paid')
            ->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('Y-m-d'); // Group per hari
            })
            ->map(function ($row) {
                return $row->sum('grand_total');
            });

        // 2. Siapkan Array Kosong untuk Tanggal & Value
        $chartDates = [];
        $chartValues = [];

        // 3. Loop setiap hari dalam periode (agar tanggal yang tidak ada transaksi tetap muncul sebagai 0)
        $period = CarbonPeriod::create($startDate, $endDate);

        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');

            // Masukkan Label (Sumbu X) dan Value (Sumbu Y)
            $chartDates[] = $date->format('d M');
            $chartValues[] = $chartData[$dateStr] ?? 0;
        }

        // G. Return View
        return view('dashboard.report.index', compact(
            'bookings', 'startDate', 'endDate', 'status',
            'totalIncome', 'totalTransactions', 'pendingTransactions',
            'chartDates', 'chartValues'
        ));
    }

    /**
     * 2. EXPORT KE PDF
     */
    public function exportPdf(Request $request)
    {
        // ==========================
        // 1. FILTER DATA TRANSAKSI
        // ==========================
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate   = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();
        $status    = $request->status ?? 'all';

        $query = Booking::with('user', 'car')
            ->whereBetween('created_at', [
                $startDate->format('Y-m-d 00:00:00'),
                $endDate->format('Y-m-d 23:59:59')
            ]);

        if ($status != 'all') {
            $query->where('payment_status', $status);
        }

        $bookings = $query->latest()->get();

        // Hitung Total Pendapatan (Hanya yang status Paid)
        $totalIncome = $bookings->where('payment_status', 'paid')->sum('grand_total');


        // ==========================
        // 2. AMBIL DATA PERUSAHAAN (DARI DB)
        // ==========================
        $settings = Setting::whereIn('key', [
            'app_name', 'address', 'phone', 'email', 'logo_web'
        ])->pluck('value', 'key');

        // Logic Logo (Cek apakah file ada di storage)
        $logoPath = null; // Default null (akan pakai emoji mobil)

        if (!empty($settings['logo_web'])) {
            $storagePath = public_path('storage/' . $settings['logo_web']);
            if (file_exists($storagePath)) {
                $logoPath = $storagePath;
            }
        }

        // Susun array Company
        $company = [
            'name'    => $settings['app_name'] ?? 'Bonanza Rental',
            'address' => $settings['address'] ?? '-',
            'phone'   => $settings['phone'] ?? '-',
            'email'   => $settings['email'] ?? '-',
            'logo'    => $logoPath
        ];


        // ==========================
        // 3. GENERATE PDF
        // ==========================
        $exporter = Auth::user(); // Siapa yang download

        $pdf = PDF::loadView('dashboard.report.pdf', compact(
            'bookings',
            'company',
            'startDate',
            'endDate',
            'totalIncome',
            'exporter'
        ));

        // Set ukuran kertas A4 Portrait
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('Laporan_Transaksi.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new TransactionExport(
            $request->start_date,
            $request->end_date,
            $request->status
        ), 'Laporan-Transaksi.xlsx');
    }
}
