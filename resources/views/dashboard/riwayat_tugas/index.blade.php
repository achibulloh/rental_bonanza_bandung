@extends('dashboard.layouts.index')
@section('title', 'Riwayat Tugas')

@section('style')
<style>
    :root {
        --primary: #FFC400;
        --primary-dark: #e6b000;
        --text-dark: #111;
        --text-gray: #666;
        --bg-light: #f8f9fa;
        --radius: 12px;
    }

    .mobile-header { display: none; }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .page-title { font-size: 1.8rem; font-weight: 700; margin: 0; }
    .page-subtitle { color: #888; margin-top: 5px; font-size: 0.9rem; }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-grow: 1;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .search-wrap { position: relative; width: 250px; }
    .search-input {
        width: 100%;
        padding: 10px 40px 10px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        outline: none;
    }

    .search-btn {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #888;
    }

    .filter-buttons {
        display: flex;
        gap: 10px;
        background: #f8f9fa;
        padding: 5px;
        border-radius: 8px;
    }

    .btn-filter {
        border: none;
        background: transparent;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 600;
        color: #888;
        cursor: pointer;
    }

    .btn-filter.active {
        background: var(--primary);
        color: #000;
    }

    .booking-card {
        background: #fff;
        border: 1px solid #eee;
        border-radius: var(--radius);
        padding: 25px;
        margin-bottom: 25px;
    }

    .card-top {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        border-bottom: 1px solid #f1f1f1;
        padding-bottom: 15px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .car-name { font-size: 1.2rem; font-weight: 700; margin: 0; }

    .booking-id {
        font-size: 0.85rem;
        color: #888;
        text-transform: uppercase;
    }

    .status-badge {
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        gap: 6px;
    }

    .bg-success { background: #d4edda; color: #155724; }
    .bg-danger { background: #ffe3e3; color: #e03131; }

    .card-body-grid {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 30px;
    }

    .car-image {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 10px;
    }

    .status-alert {
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px 40px;
    }

    .card-footer {
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
    }

    @media (max-width: 992px) {
        .card-body-grid { grid-template-columns: 1fr; }
        .header-actions { width: 100%; }
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Riwayat Tugas</h1>
        <p class="page-subtitle">Daftar perjalanan yang telah selesai.</p>
    </div>

    <div class="header-actions">
        <form action="{{ route('tasks.history') }}" method="GET" class="search-wrap">
            <input type="text" name="q" class="search-input" value="{{ request('q') }}" placeholder="Cari Kode / Mobil...">
            <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
        </form>

        <div class="filter-buttons">
            <button class="btn-filter active" onclick="filterBooking('all', this)">Semua</button>
            <button class="btn-filter" onclick="filterBooking('completed', this)">Selesai</button>
            <button class="btn-filter" onclick="filterBooking('cancelled', this)">Batal</button>
        </div>
    </div>
</div>

@forelse($tasks as $task)
    @php
        $statusTag = $task->status === 'cancelled' ? 'cancelled' : 'completed';
        $badgeClass = $statusTag === 'completed' ? 'bg-success' : 'bg-danger';
        $statusText = $statusTag === 'completed' ? 'Selesai' : 'Dibatalkan';
    @endphp

    <div class="booking-card item-booking" data-status="{{ $statusTag }}">
        <div class="card-top">
            <div>
                <h3 class="car-name">{{ $task->car->name }}</h3>
                <div class="booking-id">Kode Tugas: #{{ $task->booking_code }}</div>
            </div>
            <div class="status-badge {{ $badgeClass }}">
                {{ $statusText }}
            </div>
        </div>

        <div class="card-body-grid">
            <img src="{{ asset('img/no-image.jpg') }}" class="car-image">
            <div class="info-grid">
                <div><strong>Customer:</strong> {{ $task->user->name }}</div>
                <div><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($task->start_date)->format('d M Y') }}</div>
            </div>
        </div>

        <div class="card-footer">
            <strong>Rp {{ number_format($task->grand_total,0,',','.') }}</strong>
            <a href="https://wa.me/6281234567890" class="btn-filter active">Hubungi Admin</a>
        </div>
    </div>

@empty
    {{-- EMPTY STATE (JANGAN DIHAPUS) --}}
    <div id="emptyState" style="text-align:center;padding:60px;background:#fff;border-radius:12px;margin-top:20px;border:1px solid #eee;">
        <i class="fas fa-history" style="font-size:4rem;color:#ddd;margin-bottom:20px;"></i>
        <h3 style="color:#555;">Belum ada riwayat tugas</h3>
        <p style="color:#888;">Tugas yang telah Anda selesaikan akan muncul di sini.</p>
    </div>
@endforelse

@endsection

@section('script')
<script>
function filterBooking(status, btn) {
    document.querySelectorAll('.btn-filter').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const items = document.querySelectorAll('.item-booking');
    const emptyState = document.getElementById('emptyState');

    let visible = 0;

    items.forEach(item => {
        if (status === 'all' || item.dataset.status === status) {
            item.style.display = 'block';
            visible++;
        } else {
            item.style.display = 'none';
        }
    });

    if (emptyState) {
        emptyState.style.display = visible === 0 ? 'block' : 'none';
    }
}
</script>
@endsection
