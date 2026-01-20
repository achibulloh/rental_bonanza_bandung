@extends('dashboard.layouts.index')
@section('title', 'Dashboard')

@section('style')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #FFC400;
            --primary-dark: #F59E0B;
            --dark: #1e293b;
            --gray: #64748b;
            --light: #f8fafc;
            --white: #ffffff;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --radius: 16px;
        }

        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--light); color: var(--dark); }

        /* HEADER */
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .header-title h1 { font-size: 24px; font-weight: 800; color: var(--dark); margin: 0; letter-spacing: -0.5px; }
        .header-title p { color: var(--gray); margin: 5px 0 0 0; font-size: 14px; }
        .date-badge {
            background: var(--white);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            color: var(--dark);
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        /* STATS CARDS GRID */
        .stats-grid {
            display: grid;
            /* Responsive Grid: min 240px width */
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: var(--white);
            padding: 24px;
            border-radius: var(--radius);
            border: 1px solid #e2e8f0;
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; margin-bottom: 16px;
        }
        .stat-info h3 { font-size: 28px; font-weight: 800; margin: 0; color: var(--dark); line-height: 1.2; }
        .stat-info p { font-size: 12px; font-weight: 600; color: var(--gray); text-transform: uppercase; letter-spacing: 0.5px; margin: 0; }

        /* ICON COLORS */
        .bg-icon-blue { background: #eff6ff; color: var(--info); }
        .bg-icon-green { background: #ecfdf5; color: var(--success); }
        .bg-icon-red { background: #fef2f2; color: var(--danger); }
        .bg-icon-yellow { background: #fffbeb; color: var(--warning); }
        .bg-icon-purple { background: #f5f3ff; color: #8b5cf6; }

        /* LAYOUT GRID (2 Columns Desktop) */
        .dashboard-row {
            display: grid;
            grid-template-columns: 2fr 1fr; /* Left 66%, Right 33% */
            gap: 25px;
        }

        /* SECTIONS & TABLES */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            margin-top: 10px;
        }
        .section-header h2 { font-size: 18px; font-weight: 700; margin: 0; color: var(--dark); }
        .btn-link { color: var(--primary-dark); font-size: 13px; font-weight: 600; text-decoration: none; }

        .card-box {
            background: var(--white);
            border-radius: var(--radius);
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
            margin-bottom: 30px;
        }

        /* TABLE RESPONSIVE WRAPPER */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .modern-table { width: 100%; border-collapse: collapse; white-space: nowrap; }
        .modern-table th { background: #f8fafc; padding: 14px 20px; text-align: left; font-size: 11px; font-weight: 700; color: var(--gray); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #e2e8f0; }
        .modern-table td { padding: 14px 20px; border-bottom: 1px solid #f1f5f9; font-size: 13px; color: var(--dark); vertical-align: middle; }
        .modern-table tr:last-child td { border-bottom: none; }
        .modern-table tr:hover { background: #f8fafc; }

        /* BADGES */
        .status-badge { padding: 5px 10px; border-radius: 30px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; }
        .status-pending { background: #fff7ed; color: #c2410c; }
        .status-active { background: #eff6ff; color: #1d4ed8; }
        .status-completed { background: #ecfdf5; color: #047857; }
        .status-maintenance { background: #fef2f2; color: #b91c1c; }

        .btn-action { padding: 6px 12px; background: var(--dark); color: #fff; border-radius: 6px; font-size: 11px; font-weight: 600; text-decoration: none; transition: 0.2s; white-space: nowrap;}
        .btn-action:hover { background: #000; transform: translateY(-1px); }

        .car-thumb { width: 50px; height: 35px; border-radius: 6px; object-fit: cover; }

        /* MAINTENANCE LIST ITEM */
        .maintenance-item {
            padding: 15px 20px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            gap: 15px;
            align-items: center;
        }
        .maintenance-icon {
            width: 40px; height: 40px; background: var(--danger); color: #fff;
            border-radius: 10px; display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        /* --- MEDIA QUERIES (RESPONSIVE FIXES) --- */
        @media (max-width: 992px) {
            .dashboard-row {
                grid-template-columns: 1fr; /* Stack columns on tablet/mobile */
                gap: 20px;
            }
            .header-section {
                align-items: flex-start;
                margin-top: 10px; /* Extra space from mobile header */
            }
            .stat-info h3 { font-size: 24px; }
        }

        @media (max-width: 576px) {
            /* Fix Grid for Mobile Stats: 2 Columns (Side by Side) */
            .stats-grid {
                grid-template-columns: 1fr 1fr;
                gap: 15px;
            }

            .header-title h1 { font-size: 20px; }

            /* Compact Card for Mobile */
            .stat-card {
                padding: 15px;
                height: auto; /* Allow auto height */
            }
            .stat-icon {
                width: 40px; height: 40px; font-size: 18px; margin-bottom: 10px;
            }
            .stat-info h3 { font-size: 20px; margin-bottom: 2px; }
            .stat-info p { font-size: 10px; }

            .section-header h2 { font-size: 16px; }
        }
    </style>
@endsection

@section('content')

    <div class="header-section">
        <div class="header-title">
            <h1>Dashboard {{ ucfirst($roleName) }}</h1>
            <p>Halo, <strong>{{ Auth::user()->name }}</strong>! Berikut ringkasan aktivitas hari ini.</p>
        </div>
        <div class="date-badge">
            <i class="far fa-calendar-alt" style="color:var(--primary-dark)"></i> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
        </div>
    </div>

    @if(in_array($roleName, ['staff', 'admin']))

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon bg-icon-red"><i class="fas fa-bell"></i></div>
                <div class="stat-info"><p>Menunggu Approval</p><h3>{{ $data['need_approval'] ?? 0 }}</h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-icon-blue"><i class="fas fa-key"></i></div>
                <div class="stat-info"><p>Sedang Disewa</p><h3>{{ $data['active_rentals'] ?? 0 }}</h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-icon-green"><i class="fas fa-car"></i></div>
                <div class="stat-info"><p>Mobil Ready</p><h3>{{ $data['cars_ready'] ?? 0 }}</h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-icon-purple"><i class="fas fa-user-tie"></i></div>
                <div class="stat-info"><p>Driver Standby</p><h3>{{ $data['drivers_available'] ?? 0 }}</h3></div>
            </div>
        </div>

        <div class="dashboard-row">

            <div class="col-left">
                <div class="section-header">
                    <h2><i class="fas fa-undo-alt" style="color:var(--warning); margin-right:8px;"></i> Jadwal Kembali Hari Ini</h2>
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
                                        <td>{{ $return->car->name }} <span style="color:#888; font-size:11px;">({{ $return->car->license_plate }})</span></td>
                                        <td><span class="status-badge status-active">Ongoing</span></td>
                                        <td><a href="{{ url('/booking/detail/'.$return->booking_code) }}" class="btn-action">Proses</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" style="text-align:center; color:#999; padding:30px;">Tidak ada jadwal pengembalian hari ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="section-header">
                    <h2><i class="fas fa-hand-holding-heart" style="color:var(--info); margin-right:8px;"></i> Jadwal Pengambilan Hari Ini</h2>
                </div>
                <div class="card-box">
                    <div class="table-responsive">
                        <table class="modern-table">
                            <thead><tr><th>Jam</th><th>Customer</th><th>Mobil</th><th>Lokasi</th><th>Aksi</th></tr></thead>
                            <tbody>
                                @forelse($data['pickups_today'] ?? [] as $pickup)
                                    <tr>
                                        <td><span style="font-weight:800;">{{ \Carbon\Carbon::parse($pickup->pickup_time)->format('H:i') }}</span></td>
                                        <td>{{ Str::limit($pickup->user->name, 15) }}</td>
                                        <td>{{ $pickup->car->name }}</td>
                                        <td>{{ Str::limit($pickup->pickup_location, 15) }}</td>
                                        <td><a href="{{ url('/booking/detail/'.$pickup->booking_code) }}" class="btn-action" style="background:var(--success);">Serah Terima</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" style="text-align:center; color:#999; padding:30px;">Tidak ada jadwal pengambilan unit.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="section-header">
                    <h2>Booking Baru Masuk</h2>
                    <a href="{{ url('/pesanan_aktif') }}" class="btn-link">Lihat Semua</a>
                </div>
                <div class="card-box">
                    <div class="table-responsive">
                        <table class="modern-table">
                            <thead><tr><th>Tanggal</th><th>Mobil</th><th>Durasi</th><th>Total</th><th>Aksi</th></tr></thead>
                            <tbody>
                                @forelse($data['incoming_bookings'] ?? [] as $inc)
                                    <tr>
                                        <td>{{ $inc->created_at->format('d M H:i') }}</td>
                                        <td>
                                            <div style="font-weight:600;">{{ $inc->car->name }}</div>
                                            <div style="font-size:11px; color:var(--gray);">{{ Str::limit($inc->user->name, 12) }}</div>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($inc->start_date)->diffInDays($inc->end_date) }} Hari</td>
                                        <td>Rp {{ number_format($inc->grand_total,0,',','.') }}</td>
                                        <td><a href="{{ url('/pesanan_aktif') }}" class="btn-action bg-icon-yellow" style="color:#b45309;">Review</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" style="text-align:center; padding:30px; color:#999;">Belum ada booking baru.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-right">
                <div class="section-header">
                    <h2>Maintenance & Pajak</h2>
                </div>
                <div class="card-box" style="padding:0;">
                    @forelse($data['maintenance_cars'] ?? [] as $mcar)
                        <div class="maintenance-item">
                            <div class="maintenance-icon"><i class="fas fa-wrench"></i></div>
                            <div style="flex:1; min-width:0;">
                                <div style="font-weight:700; font-size:13px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $mcar->name }}</div>
                                <div style="font-size:11px; color:var(--gray);">{{ $mcar->license_plate }}</div>
                            </div>
                            <span class="status-badge status-maintenance" style="font-size:10px;">Bengkel</span>
                        </div>
                    @empty
                        <div style="text-align:center; padding:40px 20px;">
                            <i class="fas fa-check-circle" style="font-size:40px; color:var(--success); margin-bottom:10px;"></i>
                            <p style="font-size:13px; color:var(--gray);">Semua armada dalam kondisi prima.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    @endif


    @if($roleName == 'owner')
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon bg-icon-green"><i class="fas fa-money-bill-wave"></i></div>
                <div class="stat-info"><p>Pendapatan Bulan Ini</p><h3>Rp {{ number_format($data['month_revenue'] ?? 0, 0, ',', '.') }}</h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-icon-blue"><i class="fas fa-chart-line"></i></div>
                <div class="stat-info"><p>Total Transaksi</p><h3>{{ $data['total_bookings_all'] ?? 0 }}</h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-icon-purple"><i class="fas fa-users"></i></div>
                <div class="stat-info"><p>Total Pelanggan</p><h3>{{ $data['total_users'] ?? 0 }}</h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-icon-yellow"><i class="fas fa-wallet"></i></div>
                <div class="stat-info"><p>Total Revenue (All)</p><h3>Rp {{ number_format($data['total_revenue'] ?? 0, 0, ',', '.') }}</h3></div>
            </div>
        </div>

        <div class="dashboard-row">
            <div class="col-left">
                <div class="section-header"><h2><i class="fas fa-trophy" style="color:var(--warning);"></i> Top 5 Mobil Terlaris</h2></div>
                <div class="card-box">
                    <div class="table-responsive">
                        <table class="modern-table">
                            <thead><tr><th>Mobil</th><th>Disewa</th><th>Status</th></tr></thead>
                            <tbody>
                                @foreach($data['top_cars'] ?? [] as $top)
                                    <tr>
                                        <td>
                                            <div style="display:flex; align-items:center; gap:10px;">
                                                <img src="{{ asset('storage/'.($top->car->images[0] ?? '')) }}" class="car-thumb" onerror="this.src='https://via.placeholder.com/60x40'">
                                                <div>
                                                    <div style="font-weight:700;">{{ $top->car->name }}</div>
                                                    <div style="font-size:11px; color:var(--gray);">{{ $top->car->license_plate }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="font-weight:700;">{{ $top->total_rent }} Kali</td>
                                        <td>
                                            @if($top->car->status == 'available') <span class="status-badge status-completed" style="font-size:10px;">Ready</span>
                                            @else <span class="status-badge status-active" style="font-size:10px;">Jalan</span>
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
                <div class="section-header"><h2><i class="fas fa-receipt" style="color:var(--info);"></i> Transaksi Terakhir</h2></div>
                <div class="card-box">
                    <div class="table-responsive">
                        <table class="modern-table">
                            <thead><tr><th>Kode</th><th>Nominal</th><th>Tanggal</th></tr></thead>
                            <tbody>
                                @foreach($data['latest_transactions'] ?? [] as $trx)
                                    <tr>
                                        <td>
                                            <div style="font-weight:700;">{{ $trx->booking_code }}</div>
                                            <div style="font-size:11px; color:var(--gray);">{{ Str::limit($trx->user->name, 12) }}</div>
                                        </td>
                                        <td style="font-weight:700; color:var(--success);">+ {{ number_format($trx->grand_total,0,',','.') }}</td>
                                        <td style="color:var(--gray); font-size:11px;">{{ $trx->updated_at->format('d/m') }}</td>
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
                <div class="stat-info"><p>Total Transaksi</p><h3>Rp {{ number_format($data['my_spending'] ?? 0, 0, ',', '.') }}</h3></div>
            </div>
        </div>

        <div class="section-header">
            <h2>Pesanan Anda Saat Ini</h2>
            <a href="{{ url('/cari-mobil') }}" class="btn-action bg-icon-yellow" style="color:#b45309;">+ Sewa Lagi</a>
        </div>
        <div class="card-box">
            <div class="table-responsive">
                <table class="modern-table">
                    <thead><tr><th>Mobil</th><th>Durasi</th><th>Status</th><th>Total</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @forelse($data['my_active_list'] ?? [] as $mybook)
                            <tr>
                                <td>
                                    <div style="display:flex; align-items:center; gap:10px;">
                                        <div style="width:40px; height:40px; background:#f1f5f9; border-radius:8px; display:flex; align-items:center; justify-content:center;"><i class="fas fa-car"></i></div>
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
                            <tr><td colspan="5" style="text-align:center; padding:40px; color:var(--gray);">Belum ada booking aktif. Yuk sewa mobil sekarang!</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="section-header">
            <h2>Riwayat Perjalanan</h2>
            <a href="{{ url('/riwayat_booking') }}" class="btn-link">Lihat Semua</a>
        </div>
        <div class="card-box">
            <div class="table-responsive">
                <table class="modern-table">
                    <tbody>
                        @forelse($data['my_history'] ?? [] as $hist)
                            <tr>
                                <td width="50"><i class="fas fa-check-circle" style="color:var(--success); font-size:24px;"></i></td>
                                <td>
                                    <div style="font-weight:600;">{{ $hist->car->name }}</div>
                                    <div style="font-size:12px; color:var(--gray);">{{ \Carbon\Carbon::parse($hist->end_date)->format('d F Y') }}</div>
                                </td>
                                <td align="right"><span class="status-badge status-completed">Selesai</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="3" style="text-align:center; padding:20px; color:var(--gray);">Belum ada riwayat perjalanan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@endsection
