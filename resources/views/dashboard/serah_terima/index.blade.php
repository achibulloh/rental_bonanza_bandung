@extends('dashboard.layouts.index')
@section('title', 'Serah Terima Mobil')

@section('style')
    <style>
        /* === VARIABLES === */
        :root {
            --primary-yellow: #FFC400;
            --primary-yellow-hover: #ffca28;
            --text-dark: #212529; --text-muted: #9fa6b2; --bg-light: #f3f4f6; --white: #FFFFFF; --border-color: #e5e7eb;
            /* Status Colors */
            --badge-bg-yellow: #FFF9C4; --badge-text-yellow: #FBC02D;
            --badge-bg-red: #FFEBEE; --badge-text-red: #E53935;
            --badge-bg-blue: #E3F2FD; --badge-text-blue: #1E88E5;
            --badge-bg-green: #E8F5E9; --badge-text-green: #2E7D32;
        }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-light); color: var(--text-dark); }

        /* === LAYOUT RESPONSIVE === */
        .main-content { margin-left: 260px; flex: 1; padding: 30px 40px; transition: margin-left 0.3s ease; }
        @media (max-width: 992px) { .main-content { margin-left: 0; padding: 20px; } }

        /* === PAGE HEADER === */
        .page-header h1 { font-size: 24px; font-weight: 700; margin-bottom: 5px; }
        .page-header p { color: var(--text-muted); margin-bottom: 25px; }

        /* === TABS === */
        .page-tabs { display: flex; gap: 20px; margin-bottom: 30px; border-bottom: 2px solid #e5e7eb; overflow-x: auto; }
        .tab-btn { background: none; border: none; padding: 12px 5px; font-size: 15px; font-weight: 600; color: var(--text-muted); cursor: pointer; position: relative; transition: 0.3s; white-space: nowrap; }
        .tab-btn.active { color: var(--text-dark); }
        .tab-btn.active::after { content: ''; position: absolute; bottom: -2px; left: 0; width: 100%; height: 3px; background-color: var(--primary-yellow); }

        /* === STATS GRID === */
        .stats-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
        @media (max-width: 768px) { .stats-grid-3 { grid-template-columns: 1fr; } }

        .stat-card { background: var(--white); border-radius: 12px; padding: 20px; border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; }
        .stat-info h3 { font-size: 13px; color: var(--text-muted); margin: 0 0 5px 0; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-value { font-size: 24px; font-weight: 800; margin: 0; color: var(--text-dark); }

        .icon-box { width: 45px; height: 45px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
        .bg-blue { background: #E3F2FD; color: #1976D2; }
        .bg-red { background: #FFEBEE; color: #D32F2F; }
        .bg-green { background: #E8F5E9; color: #388E3C; }
        .bg-orange { background: #FFF3E0; color: #F57C00; } /* Tambahan untuk canceled/upcoming */

        /* === CARD UI === */
        .grid-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(380px, 1fr)); gap: 25px; }
        @media (max-width: 576px) { .grid-container { grid-template-columns: 1fr; } }

        .booking-card { background: var(--white); border: 1px solid var(--border-color); border-radius: 16px; padding: 25px; transition: transform 0.2s, box-shadow 0.2s; display: flex; flex-direction: column; justify-content: space-between; }
        .booking-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }

        .card-head { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; }
        .b-code { font-size: 18px; font-weight: 800; color: #111; margin-bottom: 4px; display: block; }
        .b-package { font-size: 13px; color: var(--text-muted); font-weight: 500; }

        .b-badge { padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge-waiting { background: var(--badge-bg-yellow); color: var(--badge-text-yellow); }
        .badge-late { background: var(--badge-bg-red); color: var(--badge-text-red); }
        .badge-active { background: var(--badge-bg-blue); color: var(--badge-text-blue); }

        .card-body-info { margin-bottom: 25px; }
        .info-row { display: flex; align-items: flex-start; margin-bottom: 18px; }
        .info-row:last-child { margin-bottom: 0; }
        .info-icon { width: 24px; margin-right: 12px; text-align: center; color: #9CA3AF; font-size: 16px; margin-top: 2px; }
        .info-text-group { display: flex; flex-direction: column; }
        .info-main { font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 2px; }
        .info-sub { font-size: 13px; color: #9CA3AF; }

        .btn-card-action { width: 100%; background-color: var(--primary-yellow); color: #000; border: none; padding: 14px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: background 0.2s; text-decoration: none; }
        .btn-card-action:hover { background-color: var(--primary-yellow-hover); }
        .btn-card-action.btn-red { background-color: #FEE2E2; color: #B91C1C; }
        .btn-card-action.btn-red:hover { background-color: #FECACA; }

        /* FORM & HELPER */
        .hidden { display: none !important; }
        .fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

        /* FORM INNER STYLES (REUSED) */
        .section-card-form { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 25px; margin-bottom: 25px; }
        .form-grid-layout { display: grid; grid-template-columns: 1.5fr 1fr; gap: 25px; }
        @media (max-width: 992px) { .form-grid-layout { grid-template-columns: 1fr; } }
        .form-label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #374151; }
        .form-input { width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; }
    </style>
@endsection

@section('content')

    <main class="main-content">

        <div class="page-header">
            <h1>Serah Terima Kendaraan (Hari Ini)</h1>
            <p>
                Jadwal pengambilan (Check-in) dan pengembalian (Check-out) unit untuk
                <span style="font-weight: 600; color: var(--text-dark);">
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </span>.
            </p>
        </div>

        {{-- ALERT MESSAGES --}}
        @if(session('success'))
            <div style="background: #D1FAE5; color: #065F46; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 500;">
                <i class="fas fa-check-circle margin-right: 8px;"></i> {{ session('success') }}
            </div>
        @endif

        <div class="page-tabs">
            <button class="tab-btn active" onclick="switchMainTab('checkin')">Check-in Hari Ini</button>
            <button class="tab-btn" onclick="switchMainTab('checkout')">Check-out Hari Ini</button>
        </div>

        <div id="tab-content-checkin" class="fade-in">

            <div id="view-checkin-list">

                {{-- STATISTIK CHECK-IN (Siap, Dibatalkan, Akan Datang) --}}
                <div class="stats-grid-3">
                    <div class="stat-card">
                        <div class="stat-info">
                            <h3>Siap Check-in</h3>
                            <p class="stat-value">{{ $stats['checkin_ready'] }}</p>
                        </div>
                        <div class="icon-box bg-green"><i class="fas fa-key"></i></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-info">
                            <h3>Dibatalkan</h3>
                            <p class="stat-value" style="color: #D32F2F;">{{ $stats['checkin_cancel'] }}</p>
                        </div>
                        <div class="icon-box bg-red"><i class="far fa-times-circle"></i></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-info">
                            <h3>Akan Datang</h3>
                            <p class="stat-value" style="color: #1976D2;">{{ $stats['checkin_upcoming'] }}</p>
                        </div>
                        <div class="icon-box bg-blue"><i class="far fa-calendar-alt"></i></div>
                    </div>
                </div>

                <div class="grid-container">
                    @forelse($readyToCheckin as $row)
                        <div class="booking-card">
                            <div class="card-head">
                                <div>
                                    <span class="b-code">{{ $row->booking_code }}</span>
                                    <span class="b-package">Paket Sewa</span>
                                </div>
                                <span class="b-badge badge-waiting">Menunggu Check-in</span>
                            </div>

                            <div class="card-body-info">
                                <div class="info-row">
                                    <div class="info-icon"><i class="far fa-user"></i></div>
                                    <div class="info-text-group">
                                        <span class="info-main">{{ $row->user->name }}</span>
                                        <span class="info-sub">{{ $row->user->phone ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="info-icon"><i class="fas fa-car"></i></div>
                                    <div class="info-text-group">
                                        <span class="info-main">{{ $row->car->name }}</span>
                                        <span class="info-sub">{{ $row->car->license_plate }}</span>
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="info-icon"><i class="far fa-clock"></i></div>
                                    <div class="info-text-group">
                                        <span class="info-main">
                                            Jadwal: {{ \Carbon\Carbon::parse($row->start_date)->format('H:i') }} WIB
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('serah_terima.show_checkin', $row->booking_code) }}"
                            class="btn-card-action"
                            style="text-decoration: none;"> <i class="far fa-check-circle"></i> Check-in Mobil
                            </a>
                        </div>
                    @empty
                        <div style="grid-column: 1/-1; text-align: center; padding: 50px; background: #fff; border-radius: 12px; border: 1px dashed #ddd;">
                            <p style="color: #999;">Tidak ada jadwal Check-in untuk hari ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        <div id="tab-content-checkout" class="hidden fade-in">

            <div id="view-checkout-list">

                {{-- STATISTIK CHECK-OUT (Sedang Berjalan, Terlambat, Total Aktif) --}}
                <div class="stats-grid-3">
                    <div class="stat-card">
                        <div class="stat-info">
                            <h3>Sedang Berjalan</h3>
                            <p class="stat-value">{{ $stats['checkout_ontrack'] }}</p>
                        </div>
                        <div class="icon-box bg-blue"><i class="fas fa-road"></i></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-info">
                            <h3>Terlambat</h3>
                            <p class="stat-value" style="color: #D32F2F;">{{ $stats['checkout_late'] }}</p>
                        </div>
                        <div class="icon-box bg-red"><i class="far fa-clock"></i></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-info">
                            <h3>Total Booking Aktif</h3>
                            <p class="stat-value">{{ $stats['checkout_active'] }}</p>
                        </div>
                        <div class="icon-box bg-green"><i class="fas fa-chart-line"></i></div>
                    </div>
                </div>

                <div class="grid-container">
                    @forelse($todayCheckouts as $row)
                    @php
                        $endDate = \Carbon\Carbon::parse($row->end_date);
                        $now = \Carbon\Carbon::now();
                        $isLate = $endDate->isPast();
                        $lateDuration = '';

                        if ($isLate) {
                            // Ambil selisih waktu
                            $diff = $endDate->diff($now);

                            // LOGIKA FORMAT DURASI
                            if ($diff->d > 0) {
                                // KONDISI 1: Jika telat lebih dari 1 HARI (Contoh: "2 Hari 5 Jam")
                                $lateDuration = $diff->d . ' Hari' . ($diff->h > 0 ? ' ' . $diff->h . ' Jam' : '');
                            } elseif ($diff->h > 0) {
                                // KONDISI 2: Jika telat JAM & MENIT (Contoh: "2 Jam 30 Menit")
                                $lateDuration = $diff->h . ' Jam' . ($diff->i > 0 ? ' ' . $diff->i . ' Menit' : '');
                            } else {
                                // KONDISI 3: Jika telat hanya MENIT (Contoh: "45 Menit")
                                $lateDuration = $diff->i . ' Menit';
                            }
                        }
                    @endphp

                        <div class="booking-card">
                            <div class="card-head">
                                <div>
                                    <span class="b-code">{{ $row->booking_code }}</span>
                                    <span class="b-package">Jadwal Kembali Hari Ini</span>
                                </div>

                                <div style="text-align: right;">
                                    @if($isLate)
                                        {{-- Tampilkan Durasi Keterlambatan --}}
                                        <div style="font-size: 11px; font-weight: 700; color: #D32F2F; margin-bottom: 3px;">
                                            Telat {{ $lateDuration }}
                                        </div>
                                        <span class="b-badge badge-late">Terlambat</span>
                                    @else
                                        <span class="b-badge badge-active">Sedang Berjalan</span>
                                    @endif
                                </div>
                            </div>

                            <div class="card-body-info">
                                <div class="info-row">
                                    <div class="info-icon"><i class="far fa-user"></i></div>
                                    <div class="info-text-group">
                                        <span class="info-main">{{ $row->user->name }}</span>
                                        <span class="info-sub">{{ $row->user->phone ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="info-icon"><i class="fas fa-car"></i></div>
                                    <div class="info-text-group">
                                        <span class="info-main">{{ $row->car->name }}</span>
                                        <span class="info-sub">{{ $row->car->license_plate }}</span>
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="info-icon"><i class="far fa-clock"></i></div>
                                    <div class="info-text-group">
                                        <span class="info-main">
                                            Batas: {{ $endDate->format('H:i') }} WIB
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('serah_terima.show_checkout', $row->booking_code) }}"
                            class="btn-card-action {{ $isLate ? 'btn-red' : '' }}"
                            style="text-decoration: none;">

                                @if($isLate)
                                    <i class="fas fa-exclamation-triangle"></i> Proses Denda & Kembali
                                @else
                                    <i class="fas fa-undo-alt"></i> Check-out Mobil
                                @endif
                            </a>
                        </div>
                    @empty
                        <div style="grid-column: 1/-1; text-align: center; padding: 50px; background: #fff; border-radius: 12px; border: 1px dashed #ddd;">
                            <p style="color: #999;">Tidak ada jadwal pengembalian mobil untuk hari ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </main>
@endsection

@section('script')
    <script>
        // 1. Switch Tab Logic
        function switchMainTab(tabName) {
            document.getElementById('tab-content-checkin').classList.add('hidden');
            document.getElementById('tab-content-checkout').classList.add('hidden');

            const buttons = document.querySelectorAll('.tab-btn');
            buttons.forEach(btn => btn.classList.remove('active'));

            document.getElementById('tab-content-' + tabName).classList.remove('hidden');

            // Activate button style
            const btnsArr = Array.from(buttons);
            if(tabName === 'checkin') btnsArr[0].classList.add('active');
            if(tabName === 'checkout') btnsArr[1].classList.add('active');

            closeForm(tabName);
        }

    </script>
@endsection
