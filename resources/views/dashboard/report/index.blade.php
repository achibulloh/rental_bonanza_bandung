@extends('dashboard.layouts.index')
@section('title', 'Laporan Transaksi')
@section('style')
<style>
    /* === 1. VARIABLES & LAYOUT === */
    :root {
        --primary: #FFC400; /* Kuning (Sesuai Request) */
        --primary-dark: #e0ac00;
        --secondary: #2d3748;
        --text-muted: #718096;
        --bg-light: #f7fafc;
        --border-color: #e2e8f0;
        --sidebar-width: 280px;
    }

    .main-content {
        flex: 1;
        margin-left: var(--sidebar-width);
        padding: 30px 40px;
        min-height: 100vh;
        background-color: var(--bg-light);
        font-family: 'Poppins', sans-serif;
        transition: margin-left 0.3s ease;
    }


    /* [FIX UTAMA] RESPONSIF HP: Hapus margin sidebar agar konten pas di layar */
    @media (max-width: 991px) {
        .main-content {
            margin-left: 0;
            padding: 20px;
        }
    }

    /* === 2. HEADER & BUTTONS === */
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px; }
    .page-title { font-size: 1.75rem; font-weight: 700; color: var(--secondary); margin-bottom: 5px; }
    .page-subtitle { color: var(--text-muted); font-size: 0.95rem; margin: 0; }

    .btn-export {
        padding: 10px 20px; border-radius: 10px; font-weight: 600; font-size: 0.9rem;
        transition: all 0.3s ease; border: none; display: inline-flex; align-items: center; gap: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-decoration: none; color: #fff;
    }
    .btn-pdf { background: #fee2e2; color: #b91c1c; }
    .btn-pdf:hover { background: #ef4444; color: #fff; transform: translateY(-2px); }
    .btn-excel { background: #dcfce7; color: #15803d; }
    .btn-excel:hover { background: #22c55e; color: #fff; transform: translateY(-2px); }

    /* === 3. STAT CARDS (GRID) === */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr); /* 3 Kolom */
        gap: 20px;
        margin-bottom: 30px;
    }
    @media (max-width: 991px) {
        .stats-grid { grid-template-columns: 1fr; } /* 1 Kolom di HP */
    }

    .stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 25px;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
        height: 100%;

        /* KUNCI AGAR IKON DI SAMPING KANAN */
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: transform 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08);
        border-color: var(--primary);
    }

    /* Bagian Teks (Kiri) */
    .stat-content { display: flex; flex-direction: column; }
    .stat-label {
        color: var(--text-muted); font-size: 0.85rem; font-weight: 600;
        text-transform: uppercase; margin-bottom: 5px; letter-spacing: 0.5px;
    }
    .stat-value {
        font-size: 1.6rem; font-weight: 800; color: var(--secondary); line-height: 1.2;
    }

    /* Bagian Icon (Kanan & Besar) */
    .stat-icon {
        width: 60px; height: 60px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem; flex-shrink: 0; margin-left: 15px;
    }
    .bg-icon-money { background: #fffbeb; color: #d97706; }
    .bg-icon-count { background: #eff6ff; color: #2563eb; }
    .bg-icon-pending { background: #fff1f2; color: #e11d48; }

    /* === 4. FILTER BOX (GRID 4 KOLOM SEJAJAR) === */
    .filter-box {
        background: #fff; padding: 25px; border-radius: 16px;
        border: 1px solid var(--border-color); margin-bottom: 30px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
    }

    /* Layout Grid untuk Form: 4 Kolom Rata */
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        align-items: end; /* Tombol rata bawah sejajar input */
    }
    /* Di HP jadi 1 kolom agar tidak gepeng */
    @media (max-width: 991px) {
        .filter-grid { grid-template-columns: 1fr; }
    }

    .form-group { display: flex; flex-direction: column; }
    .form-group label { font-weight: 600; font-size: 0.9rem; color: var(--secondary); margin-bottom: 8px; display: block; }

    .form-control-custom {
        width: 100%; height: 48px; padding: 10px 15px; border-radius: 10px; border: 1px solid var(--border-color);
        font-size: 0.95rem; outline: none; background-color: #fff; transition: 0.2s;
    }
    .form-control-custom:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(255, 196, 0, 0.15); }

    .btn-apply {
        width: 100%; height: 48px; background: var(--primary); color: #000; border: none; border-radius: 10px;
        font-weight: 700; font-size: 0.95rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: 0.2s;
    }
    .btn-apply:hover { background: var(--primary-dark); transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.1); }

    /* Quick Filter (Sejajar) */
    .quick-filter-wrapper { display: flex; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 25px; }
    .quick-filter-label { font-weight: 600; color: var(--secondary); margin: 0; font-size: 0.95rem; white-space: nowrap; }
    .quick-filter-btn {
        padding: 8px 20px; border: 1px solid var(--border-color); background: #f8fafc; color: var(--text-muted);
        border-radius: 30px; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: 0.2s;
    }
    .quick-filter-btn:hover { background: #e2e8f0; }
    .quick-filter-btn.active { background: var(--primary); color: #000; border-color: var(--primary); box-shadow: 0 4px 10px rgba(255, 196, 0, 0.3); }

    /* === 5. TABLE STYLE (RESPONSIVE FIX) === */
    .table-responsive {
        /* [FIX UTAMA] Agar tabel bisa discroll horizontal di HP */
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        width: 100%;
    }

    .table-custom {
        width: 100%; border-collapse: separate; border-spacing: 0;
        /* [FIX UTAMA] Lebar minimum agar tabel tidak gepeng di HP */
        min-width: 800px;
    }

    .table-custom th {
        background: #f8fafc; color: #64748b; font-weight: 600; text-transform: uppercase;
        font-size: 0.8rem; letter-spacing: 0.5px; padding: 15px; border-bottom: 2px solid #eee;
        white-space: nowrap; /* Judul kolom tidak turun baris */
    }
    .table-custom td {
        padding: 15px; vertical-align: middle; border-bottom: 1px solid #eee; color: #333; font-size: 0.9rem;
        white-space: nowrap; /* Isi data tidak turun baris */
    }

    /* Badges */
    .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; display: inline-block; }
    .badge-paid { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
    .badge-unpaid { background: #fff7ed; color: #c2410c; border: 1px solid #fdba74; }
    .badge-expired { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }

    /* === 6. PAGINATION === */
    .pagination-container {
        display: flex; justify-content: space-between; align-items: center;
        margin-top: 25px; padding-top: 20px; border-top: 1px solid var(--border-color); flex-wrap: wrap; gap: 10px;
    }
    .btn-page { padding: 8px 16px; background: #fff; border: 1px solid var(--border-color); border-radius: 8px; font-weight: 600; }
</style>
@endsection

@section('content')
<main class="main-content">

    {{-- HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Laporan Transaksi</h1>
            <p class="page-subtitle">Ringkasan performa bisnis dan analisa pendapatan Anda.</p>
        </div>
        <div class="d-flex gap-3">
            <a href="{{ route('report.exportPdf', request()->all()) }}" class="btn-export btn-pdf">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
            <a href="{{ route('report.exportExcel', request()->all()) }}" class="btn-export btn-excel">
                <i class="fas fa-file-excel"></i> Excel
            </a>
        </div>
    </div>

    {{-- STATS (SEJAJAR KE KANAN & RESPONSIVE) --}}
    <div class="stats-grid">

        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-label">Total Pendapatan (Paid)</div>
                <div class="stat-value">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
            </div>
            <div class="stat-icon bg-icon-money">
                <i class="fas fa-wallet"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-label">Total Transaksi</div>
                <div class="stat-value">{{ $totalTransactions }} <span style="font-size: 0.9rem; font-weight: 500; color: #999;">Booking</span></div>
            </div>
            <div class="stat-icon bg-icon-count">
                <i class="fas fa-shopping-cart"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-label">Belum Dibayar</div>
                <div class="stat-value">{{ $pendingTransactions }} <span style="font-size: 0.9rem; font-weight: 500; color: #999;">Booking</span></div>
            </div>
            <div class="stat-icon bg-icon-pending">
                <i class="fas fa-clock"></i>
            </div>
        </div>

    </div>

    {{-- FILTER BOX --}}
    <div class="filter-box">
        <form action="{{ route('report.index') }}" method="GET" id="filterForm">

            {{-- Input Hidden Period --}}
            <input type="hidden" name="period" id="periodInput" value="{{ request('period') }}">

            {{-- Filter Cepat (Di Atas Grid) --}}
            <div class="quick-filter-wrapper">
                {{-- Label di kiri --}}
                <label class="quick-filter-label">
                    <i class="fas fa-magic me-2 text-warning"></i> Filter Cepat Periode:
                </label>

                {{-- Tombol di kanan (sebaris) --}}
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="quick-filter-btn {{ !request('period') || request('period') == 'all' ? 'active' : '' }}" onclick="setDate('all')">Semua</button>
                    <button type="button" class="quick-filter-btn {{ request('period') == 'today' ? 'active' : '' }}" onclick="setDate('today')">Hari Ini</button>
                    <button type="button" class="quick-filter-btn {{ request('period') == 'week' ? 'active' : '' }}" onclick="setDate('week')">Minggu Ini</button>
                    <button type="button" class="quick-filter-btn {{ request('period') == 'month' ? 'active' : '' }}" onclick="setDate('month')">Bulan Ini</button>
                    <button type="button" class="quick-filter-btn {{ request('period') == 'year' ? 'active' : '' }}" onclick="setDate('year')">Tahun Ini</button>
                    <button type="button" class="quick-filter-btn {{ request('period') == 'last_year' ? 'active' : '' }}" onclick="setDate('last_year')">Tahun Lalu</button>
                </div>
            </div>

            {{-- GRID SYSTEM (4 KOLOM SEJAJAR) --}}
            <div class="filter-grid">

                {{-- 1. Dari Tanggal --}}
                <div class="form-group">
                    <label>Dari Tanggal</label>
                    <input type="date" name="start_date" id="start_date" class="form-control-custom"
                           value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                </div>

                {{-- 2. Sampai Tanggal --}}
                <div class="form-group">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="end_date" id="end_date" class="form-control-custom"
                           value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                </div>

                {{-- 3. Status Pembayaran --}}
                <div class="form-group">
                    <label>Status Pembayaran</label>
                    <select name="status" class="form-control-custom" style="cursor: pointer;">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid (Lunas)</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid (Belum Bayar)</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired (Kedaluwarsa)</option>
                    </select>
                </div>

                {{-- 4. Tombol Action --}}
                <div class="form-group">
                    {{-- Dummy label dengan visibility hidden agar tombol sejajar ke bawah --}}
                    <label style="visibility: hidden;">Action</label>
                    <button type="submit" class="btn-apply" onclick="clearPeriod()">
                        <i class="fas fa-filter"></i> Terapkan Manual
                    </button>
                </div>

            </div>
        </form>
    </div>

    {{-- TABEL DATA --}}
    <div class="card-box" style="background: #fff; padding: 0; border-radius: 12px; border: 1px solid #eee; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-custom w-100 mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Kode & Pelanggan</th>
                        <th>Mobil & Durasi</th>
                        <th>Metode</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                {{-- ID untuk JS Pagination --}}
                <tbody id="reportTableBody">
                    @forelse($bookings as $index => $row)
                    @php
                        $start = \Carbon\Carbon::parse($row->start_date);
                        $end = \Carbon\Carbon::parse($row->end_date);
                        $days = $start->diffInDays($end) ?: 1;
                        $statusDb = strtolower($row->payment_status);
                    @endphp

                    {{-- Class report-item untuk JS selector --}}
                    <tr class="report-item">
                        <td class="row-number fw-bold text-center"></td>
                        <td>{{ $row->created_at->translatedFormat('d M Y') }}<br><small class="text-muted">{{ $row->created_at->format('H:i') }}</small></td>
                        <td>
                            <div style="font-weight: 600; color: #d97706;">{{ $row->booking_code }}</div>
                            <small>{{ $row->user->name ?? 'Guest' }}</small>
                        </td>
                        <td>
                            <div style="font-weight: 500;">{{ $row->car->name ?? '-' }}</div>
                            <small class="text-muted"><i class="fas fa-clock"></i> {{ $days }} Hari</small>
                        </td>
                        <td>{{ ucfirst($row->payment_method ?? 'Manual') }}</td>
                        <td style="font-weight: bold;">Rp {{ number_format($row->grand_total, 0, ',', '.') }}</td>
                        <td>
                            @if($statusDb == 'paid')
                                <span class="status-badge badge-paid">Paid</span>
                            @elseif($statusDb == 'unpaid' || $statusDb == 'pending')
                                <span class="status-badge badge-unpaid">Unpaid</span>
                            @else
                                <span class="status-badge badge-expired">Expired</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr id="noDataRaw">
                        <td colspan="7" class="text-center py-5 text-muted">Tidak ada data transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION CONTROLS (CLIENT SIDE JS) --}}
        <div class="pagination-container" id="paginationControls" style="padding: 20px;">
            <div class="page-info">
                Menampilkan <span id="showStart" class="fw-bold">0</span> - <span id="showEnd" class="fw-bold">0</span> dari <span id="totalData" class="fw-bold">0</span> data
            </div>
            <div class="d-flex gap-2">
                <button class="btn-page" id="btnPrev" onclick="changePage(-1)"><i class="fas fa-chevron-left"></i> Prev</button>
                <button class="btn-page" id="btnNext" onclick="changePage(1)">Next <i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </div>
</main>

{{-- === JAVASCRIPT === --}}
<script>
    // --- 1. CONFIG PAGINATION ---
    let currentPage = 1;
    const itemsPerPage = 20;
    let allRows = [];

    // --- 2. DATE LOGIC & SUBMIT ---
    function setDate(type) {
        const today = new Date();
        let start = new Date();
        let end = new Date();

        if (type === 'all') {
            // Logika SEMUA: Set tanggal dari jauh di masa lalu sampai jauh di masa depan
            // Contoh: 1 Jan 2020 sampai 5 tahun ke depan
            start = new Date(2020, 0, 1);
            end = new Date(today.getFullYear() + 5, 11, 31);
        }
        else if (type === 'today') {
            start = today; end = today;
        }
        else if (type === 'week') {
            const day = today.getDay() || 7;
            if (day !== 1) start.setHours(-24 * (day - 1));
            end.setDate(start.getDate() + 6);
        }
        else if (type === 'month') {
            start = new Date(today.getFullYear(), today.getMonth(), 1);
            end = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        }
        else if (type === 'year') {
            start = new Date(today.getFullYear(), 0, 1);
            end = new Date(today.getFullYear(), 11, 31);
        }
        else if (type === 'last_year') {
            start = new Date(today.getFullYear() - 1, 0, 1);
            end = new Date(today.getFullYear() - 1, 11, 31);
        }

        // Koreksi timezone agar tanggal tidak mundur
        const offset = start.getTimezoneOffset() * 60000;
        const localStart = new Date(start.getTime() - offset);
        const localEnd = new Date(end.getTime() - offset);

        // Isi Value Input
        document.getElementById('start_date').value = localStart.toISOString().split('T')[0];
        document.getElementById('end_date').value = localEnd.toISOString().split('T')[0];

        // Set Hidden Input Period agar tombol tetap kuning saat reload
        document.getElementById('periodInput').value = type;

        // Auto Submit
        document.getElementById('filterForm').submit();
    }

    // Jika user pilih manual date/tombol terapkan, hapus active period
    function clearPeriod() {
        document.getElementById('periodInput').value = '';
    }

    // --- 3. CLIENT SIDE PAGINATION LOGIC ---
    function initTable() {
        allRows = Array.from(document.querySelectorAll(".report-item"));
        renderTable();
    }

    function renderTable() {
        const controls = document.getElementById("paginationControls");
        const noDataRaw = document.getElementById("noDataRaw");

        if(noDataRaw && allRows.length > 0) noDataRaw.style.display = "none";

        if (allRows.length === 0) {
            controls.style.display = "none";
            return;
        } else {
            controls.style.display = "flex";
        }

        // Calculate Slice
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;

        // Loop all rows to Show/Hide based on page index
        allRows.forEach((row, index) => {
            if (index >= start && index < end) {
                row.style.display = ""; // Show
                // Update Numbering
                row.querySelector(".row-number").innerText = index + 1;
            } else {
                row.style.display = "none"; // Hide
            }
        });

        updateControls();
    }

    function updateControls() {
        const total = allRows.length;
        const start = (currentPage - 1) * itemsPerPage + 1;
        const end = Math.min(start + itemsPerPage - 1, total);
        const totalPages = Math.ceil(total / itemsPerPage);

        document.getElementById("showStart").innerText = start;
        document.getElementById("showEnd").innerText = end;
        document.getElementById("totalData").innerText = total;

        document.getElementById("btnPrev").disabled = (currentPage === 1);
        document.getElementById("btnNext").disabled = (currentPage === totalPages);
    }

    function changePage(direction) {
        currentPage += direction;
        renderTable();
    }

    // --- 4. INIT ---
    document.addEventListener("DOMContentLoaded", function() {
        initTable();
    });
</script>
@endsection
