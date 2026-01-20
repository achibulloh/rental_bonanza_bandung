<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Transaksi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            padding: 20px;
            font-size: 11px;
        }
        .container { max-width: 100%; margin: 0 auto; background-color: white; padding: 10px; }

        /* Header */
        .header {
            width: 100%; border-bottom: 2px solid #e0e0e0;
            padding-bottom: 20px; margin-bottom: 30px;
        }
        /* Menggunakan Table di Header agar Layout PDF Stabil */
        .header-table { width: 100%; }

        .logo-container {
            width: 50px; height: 50px;
            background-color: #FFC107;
            border-radius: 8px;
            text-align: center;
            vertical-align: middle;
        }
        .logo-text { font-size: 24px; line-height: 50px; display: block; }
        .logo-img { width: 100%; height: 100%; object-fit: contain; border-radius: 8px; }

        .company-details { padding-left: 15px; vertical-align: top; }
        .company-details h1 { font-size: 18px; font-weight: bold; margin-bottom: 3px; color: #333; margin-top: 0; }
        .tagline { font-size: 11px; color: #666; margin-bottom: 5px; margin-top: 0; font-style: italic; }
        .address, .contact, .email { font-size: 11px; color: #666; line-height: 1.4; margin: 0; }

        .report-info { text-align: right; vertical-align: top; }
        .report-info h2 { font-size: 16px; font-weight: bold; margin: 0 0 10px 0; color: #333; }
        .period p { margin: 2px 0; font-size: 12px; color: #666; }
        .date-text { font-weight: 600; color: #333; }

        /* Titles & Tables */
        .section-title { font-size: 14px; font-weight: bold; margin-bottom: 15px; color: #333; }

        .transaction-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 11px; }
        .transaction-table th { padding: 10px 8px; text-align: left; background-color: #f8f9fa; font-weight: 600; color: #333; border-bottom: 2px solid #dee2e6; }
        .transaction-table td { padding: 8px; border-bottom: 1px solid #e9ecef; vertical-align: middle; }

        /* Helpers */
        .code { color: #1976d2; font-weight: 500; font-size: 10px; }
        .amount { text-align: right; font-weight: 500; white-space: nowrap; }

        /* Badges */
        .status-badge { display: inline-block; padding: 3px 8px; border-radius: 12px; font-size: 10px; font-weight: bold; text-transform: uppercase; }
        .status-paid { background-color: #d4edda; color: #155724; }
        .status-unpaid { background-color: #f8d7da; color: #721c24; }
        .status-expired { background-color: #e2e3e5; color: #383d41; }

        /* Total */
        .total-section { padding: 15px 20px; background-color: #fff9e6; border: 2px solid #FFC107; border-radius: 6px; margin-bottom: 40px; overflow: hidden; }
        .total-label { font-size: 14px; font-weight: bold; color: #333; float: left; }
        .total-amount { font-size: 18px; font-weight: bold; color: #FFC107; text-align: right; float: right; }

        /* Footer */
        .footer { margin-top: 50px; padding-top: 20px; border-top: 1px solid #e0e0e0; }
        .footer-left { float: left; font-size: 11px; color: #666; }
        .footer-right { float: right; text-align: center; font-size: 11px; color: #666; width: 200px; }
        .signature-space { height: 60px; margin: 10px 0; }
        .company-name-sign { font-weight: 600; color: #333; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <table class="header-table">
            <tr>
                <td width="60%">
                    <table width="100%">
                        <tr>
                            <td width="60">
                                <div class="logo-container">
                                    @if($company['logo'])
                                        <img src="{{ $company['logo'] }}" class="logo-img">
                                    @else
                                        <span class="logo-text">🚗</span>
                                    @endif
                                </div>
                            </td>
                            <td class="company-details">
                                <h1>{{ $company['name'] }}</h1>
                                <p class="tagline">Rental Mobil Terpercaya</p>
                                <p class="address">{{ $company['address'] }}</p>
                                <p class="contact">Telp: {{ $company['phone'] }}</p>
                                <p class="email">Email: {{ $company['email'] }}</p>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="40%" class="report-info">
                    <h2>LAPORAN TRANSAKSI</h2>
                    <div class="period">
                        <p>Periode:</p>
                        <p class="date-text">{{ $startDate->translatedFormat('d F Y') }}</p>
                        <p style="text-align: right; margin: 2px 0;">s/d</p>
                        <p class="date-text">{{ $endDate->translatedFormat('d F Y') }}</p>
                    </div>
                </td>
            </tr>
        </table>

        <h3 class="section-title">Detail Transaksi</h3>

        <table class="transaction-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kode</th>
                    <th>Pelanggan</th>
                    <th>Mobil</th>
                    <th>Status</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $row)
                <tr>
                    <td>{{ $row->created_at->format('d-m-Y') }}</td>
                    <td class="code">{{ $row->booking_code }}</td>
                    <td>{{ $row->user->name ?? 'Guest' }}</td>
                    <td>{{ $row->car->name ?? '-' }}</td>
                    <td>
                        @if($row->payment_status == 'paid')
                            <span class="status-badge status-paid">Paid</span>
                        @elseif($row->payment_status == 'unpaid')
                            <span class="status-badge status-unpaid">Unpaid</span>
                        @else
                            <span class="status-badge status-expired">{{ ucfirst($row->payment_status) }}</span>
                        @endif
                    </td>
                    <td class="amount">Rp {{ number_format($row->grand_total, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; color: #777;">
                        Tidak ada data transaksi pada periode ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-label">TOTAL PENDAPATAN (PAID)</div>
            <div class="total-amount">
                Rp {{ number_format($totalIncome, 0, ',', '.') }}
            </div>
        </div>

        <div class="footer">
            <div class="footer-left">
                <p>Dicetak oleh sistem pada:</p>
                <p style="color: #333; font-weight: bold;">{{ now()->translatedFormat('d F Y') }},</p>
                <p style="color: #333; font-weight: bold;">{{ now()->format('H:i:s') }} WIB</p>
            </div>
            <div class="footer-right">
                <p class="regards">Hormat kami,</p>
                <div class="signature-space"></div>
                <p class="company-name-sign">{{ $company['name'] }}</p>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
</body>
</html>
