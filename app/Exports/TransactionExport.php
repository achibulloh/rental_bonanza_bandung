<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionExport implements FromCollection, WithHeadings, WithMapping
{
    protected $start_date;
    protected $end_date;

    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function collection()
    {
        return Booking::with('user', 'car')
            ->whereBetween('created_at', [$this->start_date . ' 00:00:00', $this->end_date . ' 23:59:59'])
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal Transaksi',
            'Kode Booking',
            'Nama Pelanggan',
            'Email',
            'Mobil',
            'Total Bayar',
            'Status Pembayaran',
        ];
    }

    public function map($booking): array
    {
        return [
            $booking->created_at->format('d-m-Y H:i'),
            $booking->booking_code,
            $booking->user->name ?? 'Guest',
            $booking->user->email ?? '-',
            $booking->car->name ?? '-',
            $booking->grand_total,
            $booking->payment_status,
        ];
    }
}
