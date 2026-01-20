<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Booking - {{ $booking->booking_code }}</title>
    <style>
        body {
            font-family: sans-serif; /* Font standar agar aman di PDF */
            font-size: 13px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Container utama width 100% */
        .container {
            width: 100%;
            padding: 20px;
            background: #fff;
        }

        /* Helper Utility */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        .text-muted { color: #666; }
        .mt-20 { margin-top: 20px; }
        .mb-10 { margin-bottom: 10px; }

        /* HEADER LAYOUT MENGGUNAKAN TABLE */
        .header-table {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
        }

        .brand-name {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
            color: #000;
        }

        .booking-code {
            color: #d4a017;
            font-size: 18px;
            font-weight: bold;
        }

        /* STATUS BADGE */
        .status-paid {
            background-color: #e8f5e9;
            color: #28a745;
            border: 1px solid #28a745;
            padding: 5px 10px;
            border-radius: 4px;
            display: inline-block;
            font-weight: bold;
            font-size: 12px;
        }
        .status-unpaid {
            background-color: #fff8e1;
            color: orange;
            border: 1px solid orange;
            padding: 5px 10px;
            border-radius: 4px;
            display: inline-block;
            font-weight: bold;
            font-size: 12px;
        }

        /* SECTION TITLE */
        .section-title {
            background-color: #eee;
            padding: 8px 10px;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        /* INFO TABLE (PENGGANTI GRID) */
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 4px;
            vertical-align: top;
        }
        .label-col {
            width: 140px;
            color: #666;
        }
        .sep-col {
            width: 10px;
        }

        /* TABLE RINCIAN PEMBAYARAN */
        .payment-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .payment-table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #ddd;
            padding: 10px;
            text-align: left;
            font-size: 12px;
        }
        .payment-table td {
            border-bottom: 1px solid #eee;
            padding: 10px;
            font-size: 12px;
        }
        .total-row td {
            background-color: #fffdf0;
            font-weight: bold;
            font-size: 14px;
            border-top: 2px solid #ddd;
        }

        /* TERMS */
        .terms-box {
            font-size: 11px;
            color: #666;
            line-height: 1.5;
            border: 1px dashed #ccc;
            padding: 10px;
            margin-bottom: 30px;
        }

        /* FOOTER */
        .footer-table {
            width: 100%;
            margin-top: 50px;
        }
    </style>
</head>
<body>

    <div class="container">

        <table class="header-table">
            <tr>
                <td width="60%" valign="top">
                    <h1 class="brand-name">{{ $settings['app_name'] ?? 'Bonanza Rental' }}</h1>
                    <p style="margin: 5px 0; font-size: 12px; color: #666;">
                        {{ $settings['app_tagline'] ?? 'Rental Mobil Terpercaya' }}<br>
                        {{ $settings['address'] ?? 'Alamat Belum Diatur' }}<br>
                        Telp: {{ $settings['phone'] ?? '-' }}
                    </p>
                </td>
                <td width="40%" valign="top" class="text-right">
                    <div style="font-size: 11px; color: #999; margin-bottom: 5px;">KODE BOOKING</div>
                    <div class="booking-code">{{ $booking->booking_code }}</div>

                    <div style="margin-top: 10px;">
                        @if($booking->payment_status == 'paid')
                            <span class="status-paid">LUNAS</span>
                        @else
                            <span class="status-unpaid">BELUM LUNAS</span>
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <div class="section-title">DATA PENYEWA</div>
        <table class="info-table">
            <tr>
                <td class="label-col">Nama Lengkap</td>
                <td class="sep-col">:</td>
                <td>{{ $booking->user->name ?? 'Guest' }}</td>
            </tr>
            <tr>
                <td class="label-col">No. Telepon</td>
                <td class="sep-col">:</td>
                <td>{{ $booking->user->phone ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-col">Email</td>
                <td class="sep-col">:</td>
                <td>{{ $booking->email ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-col">Alamat</td>
                <td class="sep-col">:</td>
                <td>{{ $booking->address_detail ?? '-' }}</td>
            </tr>
        </table>

        @php
            $start = \Carbon\Carbon::parse($booking->start_date);
            $end = \Carbon\Carbon::parse($booking->end_date);
            $duration = $start->diffInDays($end);
            if($duration == 0) $duration = 1;
        @endphp

        <div class="section-title">DETAIL RENTAL</div>
        <table class="info-table">
            <tr>
                <td class="label-col">Unit Mobil</td>
                <td class="sep-col">:</td>
                <td class="bold">{{ $booking->car->brand->name ?? '' }} {{ $booking->car->name }}</td>
            </tr>
            <tr>
                <td class="label-col">Nomor Polisi</td>
                <td class="sep-col">:</td>
                <td>{{ $booking->car->license_plate ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-col">Tanggal Ambil</td>
                <td class="sep-col">:</td>
                <td>{{ $start->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label-col">Tanggal Kembali</td>
                <td class="sep-col">:</td>
                <td>{{ $end->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label-col">Durasi Sewa</td>
                <td class="sep-col">:</td>
                <td>{{ $duration }} Hari</td>
            </tr>
        </table>

        <div class="section-title">RINCIAN PEMBAYARAN</div>

        @php
            $pricePerDay = $booking->car->price_per_day ?? 0;
            $totalSewa   = $pricePerDay * $duration;
            $biayaLain   = $booking->grand_total - $totalSewa;
        @endphp

        <table class="payment-table">
            <thead>
                <tr>
                    <th width="40%">Keterangan</th>
                    <th width="25%" class="text-right">Harga Satuan</th>
                    <th width="10%" class="text-center">Durasi</th>
                    <th width="25%" class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        Sewa Mobil <strong>{{ $booking->car->name }}</strong><br>
                        <small class="text-muted">{{ $start->format('d/m') }} - {{ $end->format('d/m') }}</small>
                    </td>
                    <td class="text-right">Rp {{ number_format($pricePerDay, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $duration }} Hari</td>
                    <td class="text-right">Rp {{ number_format($totalSewa, 0, ',', '.') }}</td>
                </tr>

                @if($biayaLain > 0)
                <tr>
                    <td colspan="3" class="text-right">Biaya Tambahan / Pajak</td>
                    <td class="text-right">Rp {{ number_format($biayaLain, 0, ',', '.') }}</td>
                </tr>
                @endif

                <tr class="total-row">
                    <td colspan="3" class="text-right">TOTAL PEMBAYARAN</td>
                    <td class="text-right" style="color: #d4a017;">Rp {{ number_format($booking->grand_total, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="terms-box">
            <strong>Syarat & Ketentuan:</strong><br>
            • Uang jaminan akan dikembalikan setelah mobil diterima dalam kondisi baik.<br>
            • Keterlambatan pengembalian dikenakan denda.<br>
            • BBM dan kerusakan menjadi tanggung jawab penyewa.
        </div>

        <table class="footer-table">
            <tr>
                <td width="50%" valign="bottom">
                    <small style="color: #999;">
                        Dicetak otomatis pada:<br>
                        {{ now()->translatedFormat('d F Y, H:i') }} WIB
                    </small>
                </td>
                <td width="50%" align="center" valign="bottom">
                    <p style="margin-bottom: 50px;">Hormat Kami,</p>
                    <p class="bold">{{ $settings['app_name'] ?? 'Bonanza Rental' }}</p>
                </td>
            </tr>
        </table>

    </div>

</body>
</html>
