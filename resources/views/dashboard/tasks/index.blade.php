@extends('dashboard.layouts.index')
@section('title', 'Tugas Saya')

@section('style')
<style>
    :root {
        --primary: #FFC400;
        --primary-dark: #e0ac00;
        --blue: #2563eb;
        --green: #10b981;
        --bg: #f3f4f6;
        --card: #ffffff;
        --text-main: #111827;
        --text-sub: #6b7280;
        --border: #e5e7eb;
        --radius: 18px;
    }

    /* === PAGE === */
    .page-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 30px;
    }

    .page-header {
        margin-bottom: 35px;
    }

    .page-title {
        font-size: 1.9rem;
        font-weight: 800;
        color: var(--text-main);
        letter-spacing: -0.5px;
    }

    .page-subtitle {
        color: var(--text-sub);
        font-size: 0.95rem;
        margin-top: 4px;
    }

    /* === TASK LIST === */
    .task-list {
        display: flex;
        flex-direction: column;
        gap: 28px;
    }

    /* === CARD === */
    .task-card {
        background: var(--card);
        border-radius: var(--radius);
        border: 1px solid var(--border);
        box-shadow: 0 10px 25px rgba(0,0,0,.05);
        overflow: hidden;
        position: relative;
        transition: .25s;
    }

    .task-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 35px rgba(0,0,0,.08);
    }

    /* Status Stripe */
    .status-stripe {
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 6px;
    }
    .status-stripe.approved { background: var(--blue); }
    .status-stripe.ongoing { background: var(--primary); }

    /* === HEADER === */
    .card-header {
        background: #fafafa;
        padding: 18px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .booking-id {
        font-size: .85rem;
        font-weight: 700;
        color: var(--text-sub);
        letter-spacing: 1px;
    }

    .status-badge {
        padding: 6px 14px;
        border-radius: 999px;
        font-size: .7rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-transform: uppercase;
    }

    .badge-approved {
        background: #eff6ff;
        color: #1e40af;
    }

    .badge-ongoing {
        background: #fffbeb;
        color: #92400e;
    }

    /* === BODY === */
    .card-body {
        padding: 24px;
    }

    .car-info {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 25px;
    }

    .car-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: var(--bg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }

    .car-text h3 {
        margin: 0;
        font-size: 1.15rem;
        font-weight: 800;
    }

    .car-text p {
        margin: 0;
        font-size: .9rem;
        color: var(--text-sub);
    }

    /* === ROUTE === */
    .route-timeline {
        position: relative;
        padding-left: 32px;
        margin-bottom: 24px;
    }

    .route-timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 6px;
        bottom: 28px;
        width: 2px;
        background: var(--border);
    }

    .route-item {
        position: relative;
        margin-bottom: 20px;
    }

    .route-dot {
        position: absolute;
        left: -32px;
        top: 2px;
        width: 20px;
        height: 20px;
        background: #fff;
        border-radius: 50%;
        border: 4px solid;
    }

    .route-dot.start { border-color: var(--blue); }
    .route-dot.end { border-color: var(--primary); }

    .route-label {
        font-size: .75rem;
        font-weight: 700;
        color: var(--text-sub);
        text-transform: uppercase;
    }

    .route-val {
        font-weight: 600;
        font-size: .95rem;
        margin-top: 2px;
    }

    .route-time {
        font-size: .8rem;
        color: var(--text-sub);
    }

    /* === CUSTOMER === */
    .customer-box {
        background: #f9fafb;
        border-radius: 14px;
        padding: 14px;
        border: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cust-profile {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .cust-avatar {
        width: 40px;
        height: 40px;
        background: var(--bg);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }

    .cust-info h4 {
        margin: 0;
        font-size: .9rem;
    }

    .cust-info span {
        font-size: .75rem;
        color: var(--text-sub);
    }

    .btn-call {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #e0e7ff;
        color: #1e40af;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: .2s;
    }

    .btn-call:hover {
        background: #1e40af;
        color: #fff;
    }

    /* === FOOTER ACTION === */
    .card-footer {
        padding: 20px 24px;
        border-top: 1px solid var(--border);
        background: #fff;
    }

    .btn-action {
        width: 100%;
        padding: 14px;
        border-radius: 14px;
        font-weight: 800;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: .25s;
    }

    .btn-start {
        background: var(--blue);
        color: #fff;
    }

    .btn-start:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
    }

    .btn-finish {
        background: var(--green);
        color: #fff;
    }

    .btn-finish:hover {
        background: #059669;
        transform: translateY(-2px);
    }

    /* === EMPTY === */
    .empty-state {
        background: #fff;
        border-radius: 20px;
        border: 2px dashed var(--border);
        padding: 60px 20px;
        text-align: center;
    }

    .empty-state i {
        font-size: 3.2rem;
        color: #d1d5db;
        margin-bottom: 18px;
    }

    .empty-state h3 {
        font-size: 1.2rem;
        font-weight: 800;
        margin-bottom: 6px;
    }

    .empty-state p {
        color: var(--text-sub);
        font-size: .9rem;
    }

    @media (max-width: 640px) {
        .page-container { padding: 20px 15px; }
        .page-title { font-size: 1.5rem; }
    }
</style>
@endsection

@section('content')
<div class="page-container">

    <div class="page-header">
        <h1 class="page-title">Daftar Tugas</h1>
        <p class="page-subtitle">Kelola perjalanan aktif Anda hari ini.</p>
    </div>

    <div class="task-list">
        @forelse($tasks as $task)
        <div class="task-card">

            <div class="status-stripe {{ $task->status }}"></div>

            <div class="card-header">
                <span class="booking-id">#{{ $task->booking_code }}</span>
                @if($task->status == 'approved')
                    <span class="status-badge badge-approved">Siap Dijemput</span>
                @elseif($task->status == 'ongoing')
                    <span class="status-badge badge-ongoing">Sedang Berjalan</span>
                @endif
            </div>

            <div class="card-body">

                <div class="car-info">
                    <div class="car-icon"><i class="fas fa-car"></i></div>
                    <div class="car-text">
                        <h3>{{ $task->car->name ?? '-' }}</h3>
                        <p>{{ $task->car->plat_number ?? '-' }}</p>
                    </div>
                </div>

                <div class="route-timeline">
                    <div class="route-item">
                        <div class="route-dot start"></div>
                        <div class="route-label">Jemput</div>
                        <div class="route-val">{{ $task->pickup_location }}</div>
                    </div>
                    <div class="route-item">
                        <div class="route-dot end"></div>
                        <div class="route-label">Pengembalian</div>
                        <div class="route-val">{{ $task->return_location ?? 'Lokasi Awal' }}</div>
                    </div>
                </div>

                <div class="customer-box">
                    <div class="cust-profile">
                        <div class="cust-avatar">{{ substr($task->user->name,0,1) }}</div>
                        <div class="cust-info">
                            <h4>{{ $task->user->name }}</h4>
                            <span>Customer</span>
                        </div>
                    </div>
                    @if($task->user->phone)
                        <a href="https://wa.me/{{ $task->user->phone }}" target="_blank" class="btn-call">
                            <i class="fas fa-phone"></i>
                        </a>
                    @endif
                </div>

            </div>

            <div class="card-footer">
                @if($task->status == 'approved')
                <form action="{{ route('tasks.update_status', $task->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="ongoing">
                    <button class="btn-action btn-start">
                        <i class="fas fa-location-arrow"></i> Mulai Perjalanan
                    </button>
                </form>
                @elseif($task->status == 'ongoing')
                <form action="{{ route('tasks.update_status', $task->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="completed">
                    <button class="btn-action btn-finish" onclick="return confirm('Pastikan tugas sudah selesai')">
                        <i class="fas fa-flag-checkered"></i> Selesaikan Tugas
                    </button>
                </form>
                @endif
            </div>

        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-mug-hot"></i>
            <h3>Tidak Ada Tugas Aktif</h3>
            <p>Silakan beristirahat, tugas baru akan muncul di sini.</p>
        </div>
        @endforelse
    </div>

</div>
@endsection
