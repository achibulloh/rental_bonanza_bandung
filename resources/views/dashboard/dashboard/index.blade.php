@extends('dashboard.layouts.index')
@section('title', 'Dashboard')

@section('style')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* === 1. STYLE GLOBAL & VARIABLES (PREMIUM LOOK) === */
        :root {
            --primary: #FFC400;
            --primary-dark: #F59E0B;
            --dark: #0f172a; /* Slate 900 - Lebih Gelap Mewah */
            --gray: #64748b;  /* Slate 500 */
            --light: #f8fafc; /* Slate 50 */
            --white: #ffffff;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --radius: 16px;
            --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
        }

        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--light); color: var(--dark); }

        /* HEADER SECTION (YANG DIPERBAIKI) */
        .header-section {
            display: flex; justify-content: space-between; align-items: flex-end;
            margin-bottom: 35px; flex-wrap: wrap; gap: 20px; padding-top: 10px;
        }
        .header-title h1 {
            font-size: 28px; font-weight: 800; color: var(--dark);
            margin: 0; letter-spacing: -0.5px; line-height: 1.2;
        }
        .header-title p {
            color: var(--gray); margin: 8px 0 0 0; font-size: 15px; font-weight: 500;
        }
        .date-badge {
            background: var(--white); padding: 12px 24px; border-radius: 50px;
            font-size: 14px; font-weight: 700; color: var(--dark);
            box-shadow: var(--shadow); display: flex; align-items: center; gap: 10px;
            border: 1px solid #e2e8f0; white-space: nowrap;
        }

        /* STATS CARDS */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px; margin-bottom: 40px;
        }
        .stat-card {
            background: var(--white); padding: 24px; border-radius: var(--radius);
            border: 1px solid #f1f5f9; position: relative; overflow: hidden;
            transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            display: flex; flex-direction: column; justify-content: space-between; height: 100%;
        }
        .stat-card:hover { transform: translateY(-5px); box-shadow: var(--shadow); border-color: var(--primary); }
        .stat-icon { width: 54px; height: 54px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 20px; }
        .stat-info h3 { font-size: 32px; font-weight: 800; margin: 0; color: var(--dark); line-height: 1; }
        .stat-info p { font-size: 13px; font-weight: 700; color: var(--gray); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; }

        /* COLORS */
        .bg-icon-blue { background: #eff6ff; color: var(--info); }
        .bg-icon-green { background: #ecfdf5; color: var(--success); }
        .bg-icon-red { background: #fef2f2; color: var(--danger); }
        .bg-icon-yellow { background: #fffbeb; color: var(--warning); }
        .bg-icon-purple { background: #f5f3ff; color: #8b5cf6; }

        /* LAYOUT */
        .dashboard-row { display: grid; grid-template-columns: 2.2fr 1fr; gap: 30px; }

        /* COMPONENTS */
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; margin-top: 10px; }
        .section-header h2 { font-size: 18px; font-weight: 800; margin: 0; color: var(--dark); }
        .btn-link { color: var(--primary-dark); font-size: 14px; font-weight: 700; text-decoration: none; transition: 0.2s; }

        .card-box { background: var(--white); border-radius: var(--radius); border: 1px solid #e2e8f0; overflow: hidden; box-shadow: var(--shadow); margin-bottom: 30px; }

        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .modern-table { width: 100%; border-collapse: collapse; white-space: nowrap; }
        .modern-table th { background: #f8fafc; padding: 18px 24px; text-align: left; font-size: 11px; font-weight: 800; color: var(--gray); text-transform: uppercase; border-bottom: 1px solid #e2e8f0; }
        .modern-table td { padding: 18px 24px; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: var(--dark); vertical-align: middle; font-weight: 500; }
        .modern-table tr:hover { background: #fcfcfc; }

        /* BADGES & BUTTONS */
        .status-badge { padding: 6px 12px; border-radius: 30px; font-size: 11px; font-weight: 800; display: inline-flex; align-items: center; gap: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
        .status-pending { background: #fff7ed; color: #c2410c; }
        .status-active { background: #eff6ff; color: #1d4ed8; }
        .status-completed { background: #ecfdf5; color: #047857; }
        .status-maintenance { background: #fef2f2; color: #b91c1c; }

        .btn-action { padding: 10px 18px; background: var(--dark); color: #fff; border-radius: 10px; font-size: 12px; font-weight: 700; text-decoration: none; transition: all 0.2s; display: inline-block; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .btn-action:hover { background: #000; transform: translateY(-2px); }

        .car-thumb { width: 50px; height: 35px; border-radius: 8px; object-fit: cover; border: 1px solid #e2e8f0; }
        .maintenance-item { padding: 20px 24px; border-bottom: 1px solid #f1f5f9; display: flex; gap: 15px; align-items: center; }
        .maintenance-icon { width: 45px; height: 45px; background: #fee2e2; color: #ef4444; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 18px; }

        /* DRIVER CARD */
        .driver-hero { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-radius: var(--radius); padding: 35px; color: white; display: flex; align-items: center; justify-content: space-between; margin-bottom: 35px; position: relative; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.3); }
        .driver-action-btn { background: var(--primary); color: #0f172a; padding: 14px 28px; border-radius: 12px; font-weight: 800; text-decoration: none; display: flex; align-items: center; gap: 10px; z-index: 2; }

        /* RESPONSIVE */
        @media (max-width: 992px) {
            .dashboard-row { grid-template-columns: 1fr; gap: 20px; }
            .header-section { margin-top: 30px; }
            .driver-hero { flex-direction: column; align-items: flex-start; gap: 20px; }
            .driver-action-btn { width: 100%; justify-content: center; }
        }
        @media (max-width: 576px) {
            .stats-grid { grid-template-columns: 1fr 1fr; gap: 15px; } /* 2 Kolom di HP */
            .stat-card { padding: 15px; min-height: 120px; }
            .stat-icon { width: 40px; height: 40px; font-size: 18px; margin-bottom: 12px; }
            .stat-info h3 { font-size: 22px; margin-bottom: 2px; }
            .stat-info p { font-size: 10px; }
            .date-badge { display: none; }
        }
    </style>
@endsection

@section('content')

    <div class="header-section">
        <div class="header-title">
            {{-- LOGIKA SAPAAN BERDASARKAN ROLE --}}
            @if($roleName == 'admin')
                <h1>Dashboard Administrator 🛡️</h1>
                <p>Halo, <strong>{{ Auth::user()->name }}</strong>! Kelola operasional dan keuangan bisnis Anda di sini.</p>

            @elseif($roleName == 'staff')
                <h1>Dashboard Operasional 📋</h1>
                <p>Halo, <strong>{{ Auth::user()->name }}</strong>! Semangat bekerja, fokus pada pelayanan pelanggan hari ini.</p>

            @elseif($roleName == 'owner')
                <h1>Owner Dashboard 👑</h1>
                <p>Selamat Datang, <strong>{{ Auth::user()->name }}</strong>. Berikut ringkasan performa bisnis rental Anda.</p>

            @elseif($roleName == 'driver')
                <h1>Halo, Driver {{ explode(' ', Auth::user()->name)[0] }}! 🚕</h1>
                <p>Hati-hati di jalan. Cek tugas pengantaran dan kondisi kendaraanmu.</p>

            @elseif($roleName == 'customer')
                <h1>Selamat Datang, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h1>
                <p>Ingin pergi ke mana hari ini? Temukan mobil impianmu sekarang.</p>

            @else
                <h1>Dashboard</h1>
                <p>Selamat datang di Bonanza Rental.</p>
            @endif
        </div>

        <div class="date-badge">
            <i class="far fa-calendar-alt" style="color:var(--primary-dark)"></i>
            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
        </div>
    </div>


    @if($roleName == 'admin')
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon bg-icon-green"><i class="fas fa-money-bill-wave"></i></div>
                <div class="stat-info"><p>Total Revenue</p><h3>Rp {{ number_format($data['total_revenue'] ?? 0, 0, ',', '.') }}</h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-icon-purple"><i class="fas fa-users"></i></div>
                <div class="stat-info"><p>Total User</p><h3>{{ $data['total_users'] ?? 0 }}</h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-icon-red"><i class="fas fa-bell"></i></div>
                <div class="stat-info"><p>Perlu Approval</p><h3>{{ $data['need_approval'] ?? 0 }}</h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-icon-blue"><i class="fas fa-layer-group"></i></div>
                <div class="stat-info"><p>Total Booking</p><h3>{{ $data['total_bookings_all'] ?? 0 }}</h3></div>
            </div>
        </div>

        <div class="dashboard-row">
            <div class="col-left">
                <div class="section-header">
                    <h2><i class="fas fa-clipboard-list" style="color:var(--primary-dark);"></i> Persetujuan Booking</h2>
                    <a href="{{ url('/pesanan_aktif') }}" class="btn-link">Lihat Semua</a>
                </div>
                <div class="card-box">
                    <div class="table-responsive">
                        <table class="modern-table">
                            <thead><tr><th>Tanggal</th><th>Customer</th><th>Mobil</th><th>Total</th><th>Status</th></tr></thead>
                            <tbody>
                                @forelse($data['incoming_bookings'] ?? [] as $inc)
                                    <tr>
                                        <td>{{ $inc->created_at->format('d/m H:i') }}</td>
                                        <td>{{ Str::limit($inc->user->name, 15) }}</td>
                                        <td>{{ $inc->car->name }}</td>
                                        <td style="font-weight:800;">Rp {{ number_format($inc->grand_total,0,',','.') }}</td>
                                        <td><span class="status-badge status-pending">Pending</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" style="text-align:center; padding:40px; color:#999;">Tidak ada permintaan pending.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-right">
                <div class="section-header"><h2>Status Armada</h2></div>
                <div class="card-box" style="padding:0;">
                    <div class="maintenance-item">
                        <div class="maintenance-icon bg-icon-green" style="color:var(--success);"><i class="fas fa-car-side"></i></div>
                        <div style="flex:1;">
                            <div style="font-weight:700;">Mobil Ready</div>
                            <div style="font-size:12px; color:var(--gray);">Siap jalan</div>
                        </div>
                        <h3 style="margin:0;">{{ $data['cars_ready'] ?? 0 }}</h3>
                    </div>
                    <div class="maintenance-item">
                        <div class="maintenance-icon bg-icon-blue" style="color:var(--info);"><i class="fas fa-key"></i></div>
                        <div style="flex:1;">
                            <div style="font-weight:700;">Sedang Disewa</div>
                            <div style="font-size:12px; color:var(--gray);">Di tangan customer</div>
                        </div>
                        <h3 style="margin:0;">{{ $data['active_rentals'] ?? 0 }}</h3>
                    </div>
                    <div class="maintenance-item">
                        <div class="maintenance-icon bg-icon-red"><i class="fas fa-wrench"></i></div>
                        <div style="flex:1;">
                            <div style="font-weight:700;">Maintenance</div>
                            <div style="font-size:12px; color:var(--gray);">Di bengkel</div>
                        </div>
                        <h3 style="margin:0;">{{ count($data['maintenance_cars'] ?? []) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if($roleName == 'staff')
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon bg-icon-yellow"><i class="fas fa-undo"></i></div>
                <div class="stat-info"><p>Kembali Hari Ini</p><h3>{{ count($data['returns_today'] ?? []) }}</h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-icon-blue"><i class="fas fa-hand-holding"></i></div>
                <div class="stat-info"><p>Ambil Hari Ini</p><h3>{{ count($data['pickups_today'] ?? []) }}</h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-icon-purple"><i class="fas fa-user-tie"></i></div>
                <div class="stat-info"><p>Driver Ready</p><h3>{{ $data['drivers_available'] ?? 0 }}</h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-icon-red"><i class="fas fa-car-crash"></i></div>
                <div class="stat-info"><p>Unit Bengkel</p><h3>{{ count($data['maintenance_cars'] ?? []) }}</h3></div>
            </div>
        </div>

        <div class="dashboard-row">
            <div class="col-left">
                <div class="section-header">
                    <h2><i class="fas fa-clock" style="color:var(--warning);"></i> Jadwal Pengembalian (Returns)</h2>
                </div>
                <div class="card-box">
                    <div class="table-responsive">
                        <table class="modern-table">
                            <thead><tr><th>Jam</th><th>Customer</th><th>Mobil</th><th>Status</th><th>Aksi</th></tr></thead>
                            <tbody>
                                @forelse($data['returns_today'] ?? [] as $return)
                                    <tr>
                                        <td><span style="font-weight:800; color:var(--dark);">{{ \Carbon\Carbon::parse($return->return_time)->format('H:i') }}</span></td>
                                        <td>{{ Str::limit($return->user->name, 15) }}</td>
                                        <td>{{ $return->car->name }} <span style="font-size:11px; color:#888;">({{ $return->car->license_plate }})</span></td>
                                        <td><span class="status-badge status-active">Jalan</span></td>
                                        <td><a href="{{ url('/booking/detail/'.$return->booking_code) }}" class="btn-action">Proses</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" style="text-align:center; padding:40px; color:#999;">Aman! Tidak ada pengembalian hari ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-right">
                <div class="section-header">
                    <h2><i class="fas fa-key" style="color:var(--info);"></i> Jadwal Ambil</h2>
                </div>
                <div class="card-box">
                    <div class="table-responsive">
                        <table class="modern-table">
                            <thead><tr><th>Jam</th><th>Mobil</th><th>Aksi</th></tr></thead>
                            <tbody>
                                @forelse($data['pickups_today'] ?? [] as $pickup)
                                    <tr>
                                        <td><span style="font-weight:700;">{{ \Carbon\Carbon::parse($pickup->pickup_time)->format('H:i') }}</span></td>
                                        <td>
                                            <div style="font-weight:700;">{{ $pickup->car->name }}</div>
                                            <div style="font-size:11px; color:var(--gray);">{{ Str::limit($pickup->user->name, 10) }}</div>
                                        </td>
                                        <td><a href="{{ url('/booking/detail/'.$pickup->booking_code) }}" class="btn-action bg-icon-green" style="color:#065f46;">Serah</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" style="text-align:center; padding:30px; color:#999;">Tidak ada jadwal ambil.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if($roleName == 'owner')
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon bg-icon-green"><i class="fas fa-money-bill-wave"></i></div>
                <div class="stat-info"><p>Income Bulan Ini</p><h3>Rp {{ number_format($data['month_revenue'] ?? 0, 0, ',', '.') }}</h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-icon-yellow"><i class="fas fa-wallet"></i></div>
                <div class="stat-info"><p>Total Revenue</p><h3>Rp {{ number_format($data['total_revenue'] ?? 0, 0, ',', '.') }}</h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-icon-blue"><i class="fas fa-chart-line"></i></div>
                <div class="stat-info"><p>Total Transaksi</p><h3>{{ $data['total_bookings_all'] ?? 0 }}</h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-icon-purple"><i class="fas fa-users"></i></div>
                <div class="stat-info"><p>Total Pelanggan</p><h3>{{ $data['total_users'] ?? 0 }}</h3></div>
            </div>
        </div>

        <div class="dashboard-row">
            <div class="col-left">
                <div class="section-header"><h2><i class="fas fa-trophy" style="color:var(--primary);"></i> Top 5 Mobil Terlaris</h2></div>
                <div class="card-box">
                    <div class="table-responsive">
                        <table class="modern-table">
                            <thead><tr><th>Mobil</th><th>Disewa</th><th>Status</th></tr></thead>
                            <tbody>
                                @foreach($data['top_cars'] ?? [] as $top)
                                    <tr>
                                        <td>
                                            <div style="display:flex; align-items:center; gap:12px;">
                                                <img src="{{ asset('storage/'.($top->car->images[0] ?? '')) }}" class="car-thumb" onerror="this.src='https://via.placeholder.com/60x40'">
                                                <div>
                                                    <div style="font-weight:700;">{{ $top->car->name }}</div>
                                                    <div style="font-size:11px; color:var(--gray);">{{ $top->car->license_plate }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="font-weight:700;">{{ $top->total_rent }}x</td>
                                        <td>
                                            @if($top->car->status == 'available') <span class="status-badge status-completed">Ready</span>
                                            @else <span class="status-badge status-active">Jalan</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-right">
                <div class="section-header"><h2>Transaksi Terakhir</h2></div>
                <div class="card-box">
                    <div class="table-responsive">
                        <table class="modern-table">
                            <thead><tr><th>Kode</th><th>Nominal</th></tr></thead>
                            <tbody>
                                @foreach($data['latest_transactions'] ?? [] as $trx)
                                    <tr>
                                        <td>
                                            <div style="font-weight:700;">{{ $trx->booking_code }}</div>
                                            <div style="font-size:11px; color:var(--gray);">{{ Str::limit($trx->user->name, 10) }}</div>
                                        </td>
                                        <td style="font-weight:700; color:var(--success);">+ {{ number_format($trx->grand_total,0,',','.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if($roleName == 'customer')
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon bg-icon-blue"><i class="fas fa-car-side"></i></div>
                <div class="stat-info"><p>Booking Aktif</p><h3>{{ $data['my_active_count'] ?? 0 }}</h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-icon-yellow"><i class="fas fa-clock"></i></div>
                <div class="stat-info"><p>Menunggu</p><h3>{{ $data['my_pending_count'] ?? 0 }}</h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-icon-green"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info"><p>Selesai</p><h3>{{ $data['my_total'] - $data['my_active_count'] - $data['my_pending_count'] }}</h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-icon-purple"><i class="fas fa-wallet"></i></div>
                <div class="stat-info"><p>Pengeluaran</p><h3>Rp {{ number_format($data['my_spending'] ?? 0, 0, ',', '.') }}</h3></div>
            </div>
        </div>

        <div class="section-header">
            <h2>Pesanan Anda Saat Ini</h2>
            <a href="{{ url('/cari-mobil') }}" class="btn-action bg-icon-yellow" style="color:#000;">+ Sewa Mobil</a>
        </div>
        <div class="card-box">
            <div class="table-responsive">
                <table class="modern-table">
                    <thead><tr><th>Mobil</th><th>Durasi</th><th>Status</th><th>Total</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @forelse($data['my_active_list'] ?? [] as $mybook)
                            <tr>
                                <td>
                                    <div style="display:flex; align-items:center; gap:12px;">
                                        <div style="width:40px; height:40px; background:#f1f5f9; border-radius:10px; display:flex; align-items:center; justify-content:center;"><i class="fas fa-car"></i></div>
                                        <div>
                                            <div style="font-weight:700;">{{ $mybook->car->name }}</div>
                                            <div style="font-size:11px; color:var(--gray);">{{ $mybook->booking_code }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($mybook->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($mybook->end_date)->format('d M') }}</td>
                                <td>
                                    @if($mybook->status == 'pending') <span class="status-badge status-pending">Menunggu</span>
                                    @elseif($mybook->status == 'ongoing') <span class="status-badge status-active">Sedang Jalan</span>
                                    @else <span class="status-badge status-completed">Disetujui</span>
                                    @endif
                                </td>
                                <td style="font-weight:700;">Rp {{ number_format($mybook->grand_total,0,',','.') }}</td>
                                <td><a href="{{ url('/booking/track/'.$mybook->booking_code) }}" class="btn-action">Detail</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" style="text-align:center; padding:40px; color:var(--gray);">
                                <i class="fas fa-folder-open" style="font-size:32px; margin-bottom:10px; opacity:0.5;"></i><br>
                                Belum ada booking aktif saat ini.
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif


    @if($roleName == 'driver')

        @if(!empty($data['driver_tasks_today']) && count($data['driver_tasks_today']) > 0)
            <div class="driver-hero">
                <div class="driver-content">
                    <h2 style="font-weight:800; margin-bottom:5px;">Tugas Aktif</h2>
                    <p style="font-size:16px;">Mengantar <strong>{{ $data['driver_tasks_today'][0]->user->name }}</strong></p>
                    <p style="margin-top:10px;"><i class="fas fa-map-marker-alt" style="color:#fbbf24;"></i> {{ Str::limit($data['driver_tasks_today'][0]->pickup_location, 40) }}</p>
                    <p><i class="fas fa-clock" style="color:#fbbf24;"></i> {{ \Carbon\Carbon::parse($data['driver_tasks_today'][0]->pickup_time)->format('H:i') }} WIB</p>
                </div>
                <a href="{{ url('/driver/tugas/'.$data['driver_tasks_today'][0]->booking_code) }}" class="driver-action-btn">
                    Lihat Rincian <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        @endif

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon bg-icon-blue"><i class="fas fa-route"></i></div>
                <div class="stat-info"><p>Tugas Hari Ini</p><h3>{{ count($data['driver_tasks_today'] ?? []) }}</h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-icon-yellow"><i class="fas fa-clock"></i></div>
                <div class="stat-info"><p>Pending</p><h3>{{ $data['driver_pending_count'] ?? 0 }}</h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-icon-green"><i class="fas fa-flag-checkered"></i></div>
                <div class="stat-info"><p>Selesai (Bln)</p><h3>{{ $data['driver_completed_count'] ?? 0 }}</h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-icon-purple"><i class="fas fa-star"></i></div>
                <div class="stat-info"><p>Rating</p><h3>{{ $data['driver_rating'] ?? '5.0' }}</h3></div>
            </div>
        </div>

        <div class="dashboard-row">
            <div class="col-left">
                <div class="section-header"><h2>Jadwal Pengantaran</h2></div>
                <div class="card-box">
                    <div class="table-responsive">
                        <table class="modern-table">
                            <thead><tr><th>Waktu</th><th>Customer</th><th>Mobil</th><th>Tujuan</th><th>Aksi</th></tr></thead>
                            <tbody>
                                @forelse($data['driver_tasks_today'] ?? [] as $task)
                                    <tr>
                                        <td><span style="font-weight:700;">{{ \Carbon\Carbon::parse($task->pickup_time)->format('H:i') }}</span></td>
                                        <td>{{ Str::limit($task->user->name, 12) }}</td>
                                        <td>{{ $task->car->name }}</td>
                                        <td>{{ Str::limit($task->pickup_location, 20) }}</td>
                                        <td><a href="{{ url('/driver/tugas/'.$task->booking_code) }}" class="btn-action bg-icon-blue" style="color:#1d4ed8;">Detail</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" style="text-align:center; padding:30px; color:#999;">Tidak ada tugas hari ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-right">
                <div class="section-header"><h2>Riwayat Terakhir</h2></div>
                <div class="card-box">
                    <div class="table-responsive">
                        <table class="modern-table">
                            <tbody>
                                @forelse($data['driver_history'] ?? [] as $hist)
                                    <tr>
                                        <td width="40"><i class="fas fa-check-circle" style="color:var(--success); font-size:20px;"></i></td>
                                        <td>
                                            <div style="font-weight:600; font-size:13px;">{{ $hist->user->name }}</div>
                                            <div style="font-size:11px; color:var(--gray);">{{ \Carbon\Carbon::parse($hist->end_date)->format('d M Y') }}</div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" style="text-align:center; padding:20px; color:#999;">Belum ada riwayat.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

@section('script')
<script>
    function toggleSidebar() {
        document.querySelector('.sidebar').classList.toggle('active');
    }
</script>
@endsection
