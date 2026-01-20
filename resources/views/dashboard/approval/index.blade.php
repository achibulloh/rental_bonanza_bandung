@extends('dashboard.layouts.index')
@section('title', 'Persetujuan Booking')

@section('style')
    <style>
        :root {
            --primary: #FFC400;       /* Warna Utama (Kuning) */
            --primary-hover: #ffca2c; /* Kuning sedikit gelap untuk hover */
            --primary-shadow: rgba(255, 196, 0, 0.4);

            --danger: #dc3545;
            --danger-hover: #bb2d3b;

            --text-dark: #212529;
            --bg-light: #f8f9fa;
            --sidebar-width: 260px;
            --card-radius: 12px;
        }

        body { background-color: var(--bg-light); font-family: 'Poppins', sans-serif; }

        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 30px 40px;
            background: #fff;
            min-height: 100vh;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        /* --- HEADER & SEARCH --- */
        .page-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; margin-bottom: 30px; }
        .page-title h1 { font-size: 1.8rem; font-weight: 700; margin: 0; color: var(--text-dark); }
        .header-actions { display: flex; gap: 15px; flex-wrap: wrap; align-items: center; flex: 1; justify-content: flex-end; }

        /* Custom Input Style (Focus Kuning) */
        .form-control-custom {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.2s ease-in-out;
            background-color: #fcfcfc;
        }
        .form-control-custom:focus {
            background-color: #fff;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(255, 196, 0, 0.15); /* Glow Kuning */
            outline: none;
        }

        /* Search Bar Specific */
        .search-wrap { position: relative; min-width: 280px; }
        .search-input {
            width: 100%; padding: 12px 45px 12px 20px;
            border: 1px solid #e0e0e0; background: #fff;
            border-radius: 50px; outline: none; transition: 0.3s;
            font-size: 0.9rem;
        }
        .search-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255, 196, 0, 0.2);
        }
        .search-btn { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #aaa; font-size: 1.1rem; }

        /* TABS */
        .filter-tabs { display: flex; gap: 8px; background: #f8f9fa; padding: 6px; border-radius: 50px; border: 1px solid #eee; }
        .tab-btn {
            border: none; background: transparent; padding: 10px 24px; border-radius: 40px;
            font-weight: 600; color: #777; cursor: pointer; transition: 0.2s;
            font-size: 0.9rem; display: flex; align-items: center; gap: 8px;
        }
        .tab-btn:hover { color: #333; }
        .tab-btn.active {
            background: var(--primary);
            color: #000;
            box-shadow: 0 4px 12px rgba(255, 196, 0, 0.3);
        }

        /* --- CARD STYLE --- */
        .booking-card {
            background: #fff; border: 1px solid #f0f0f0; border-radius: var(--card-radius);
            padding: 25px; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            transition: 0.2s; position: relative; overflow: hidden;
            animation: fadeIn 0.4s ease;
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .booking-card:hover { transform: translateY(-3px); box-shadow: 0 12px 25px rgba(0,0,0,0.08); border-color: var(--primary); }

        .booking-card[data-type="booking"] { border-left: 5px solid #0d6efd; }
        .booking-card[data-type="payment"] { border-left: 5px solid #198754; }

        .card-top { display: flex; justify-content: space-between; margin-bottom: 15px; border-bottom: 1px solid #f8f9fa; padding-bottom: 10px; }
        .card-body-grid { display: grid; grid-template-columns: 280px 1fr; gap: 30px; }

        .user-box { background: #fafafa; padding: 20px; border-radius: 10px; text-align: center; display: flex; flex-direction: column; justify-content: center; border: 1px solid #f0f0f0; }
        .avatar { width: 60px; height: 60px; background: #e3f2fd; color: #0d6efd; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: bold; margin: 0 auto 10px; }

        .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-top: 10px; }
        .info-item label { font-size: 0.75rem; color: #888; display: block; text-transform: uppercase; margin-bottom: 2px; letter-spacing: 0.5px; }
        .info-item span { font-weight: 600; font-size: 0.95rem; color: #333; }

        .card-footer { margin-top: 20px; padding-top: 15px; border-top: 1px dashed #eee; display: flex; justify-content: space-between; align-items: center; }
        .price-tag { font-size: 1.4rem; font-weight: 800; color: #222; }

        /* --- BUTTONS STYLING --- */
        .btn-base {
            padding: 10px 24px; border-radius: 8px; font-weight: 600; border: none;
            cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            transition: all 0.2s; font-size: 0.9rem; text-decoration: none;
        }

        /* Tombol Utama (Kuning) */
        .btn-primary-custom {
            background-color: var(--primary); color: #000;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .btn-primary-custom:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px var(--primary-shadow);
        }

        /* Tombol Sukses (Hijau - Opsional jika ingin hijau) */
        .btn-success-custom { background: #198754; color: #fff; }
        .btn-success-custom:hover { background: #146c43; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(25, 135, 84, 0.3); }

        /* Tombol Tolak (Merah Outline) */
        .btn-danger-outline {
            background: #fff; border: 1px solid var(--danger); color: var(--danger);
        }
        .btn-danger-outline:hover { background: #fff5f5; color: var(--danger-hover); }

        /* Tombol Tolak Solid (Untuk Modal) */
        .btn-danger-solid {
            background: var(--danger); color: #fff;
        }
        .btn-danger-solid:hover { background: var(--danger-hover); box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3); }

        /* EMPTY STATE */
        .empty-state { display: none; text-align: center; padding: 60px 20px; width: 100%; margin-top: 20px; }
        .empty-state img { width: 150px; opacity: 0.6; margin-bottom: 20px; }
        .empty-state h3 { font-size: 1.2rem; color: #555; font-weight: 600; }

        /* --- CUSTOM MODAL --- */
        .custom-modal-overlay {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.65); z-index: 99999;
            align-items: center; justify-content: center; overflow-y: auto; padding: 20px;
            opacity: 0; transition: opacity 0.3s ease; backdrop-filter: blur(4px);
        }
        .custom-modal-overlay.show { display: flex; opacity: 1; }

        .custom-modal-box {
            background: #fff; width: 100%; max-width: 500px; border-radius: 16px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3); transform: scale(0.95); transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative; overflow: hidden;
        }
        .custom-modal-overlay.show .custom-modal-box { transform: scale(1); }
        .custom-modal-box.modal-xl { max-width: 1000px; }

        /* Modal Split View */
        .split-view { display: grid; grid-template-columns: 1fr 1.2fr; min-height: 550px; }
        .split-img {
            background: #1a1a1a; display: flex; align-items: center; justify-content: center;
            padding: 30px; position: relative;
        }
        .split-img img { max-width: 100%; max-height: 450px; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); object-fit: contain; }

        .split-info { padding: 40px; display: flex; flex-direction: column; justify-content: center; background: #fff; }

        /* Payment Details Box */
        .payment-details-box { background: #fcfcfc; border: 1px solid #eee; border-radius: 12px; padding: 20px; margin-bottom: 25px; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px dashed #e0e0e0; padding-bottom: 12px; }
        .detail-row:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        .detail-label { color: #666; font-size: 0.9rem; }
        .detail-value { font-weight: 700; color: #333; }
        .total-value-modal { font-size: 1.4rem; color: #198754; font-weight: 800; }

        /* Close Button */
        .btn-close-custom {
            position: absolute;
            top: 15px;
            right: 15px; /* Ubah dari left ke right */
            left: auto;  /* Reset left */
            background: var(--primary);
            color: #fff;
            border: none;
            width: 36px; height: 36px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; font-size: 1.2rem;
            cursor: pointer; transition: 0.2s; z-index: 10;
        }
        .btn-close-custom:hover { background: var(--primary-hover); transform: rotate(90deg); }

        .btn-close-header { background: none; border: none; font-size: 1.5rem; color: #fff; cursor: pointer; }

        /* RESPONSIF */
        @media (max-width: 992px) {
            .main-content { margin-left: 0; padding: 20px; }
            .card-body-grid { grid-template-columns: 1fr; }
            .header-actions { justify-content: flex-start; }
            .filter-tabs { width: 100%; overflow-x: auto; }
            .split-view { grid-template-columns: 1fr; }
            .split-img { min-height: 300px; padding: 30px 20px; }
            .split-info { padding: 25px; }
        }
    </style>
@endsection

@section('content')
<main class="main-content">

    <div class="page-header">
        <div class="page-title">
            <h1>📋 Verifikasi Booking</h1>
            <p>Kelola permintaan sewa & validasi pembayaran.</p>
        </div>

        <div class="header-actions">
            <div class="filter-tabs">
                <button class="tab-btn active" onclick="switchTab('booking', this)">
                    Booking Masuk
                    <span class="badge bg-dark rounded-pill ms-1">{{ $bookings->where('status', 'pending')->count() }}</span>
                </button>

                @php $paymentMethod = \App\Models\Setting::where('key', 'payment_method')->value('value'); @endphp
                @if($paymentMethod == 'transfer')
                <button class="tab-btn" onclick="switchTab('payment', this)">
                    Verifikasi Pembayaran
                    <span class="badge bg-dark rounded-pill ms-1">{{ $bookings->where('status', 'approved')->where('payment_status', 'unpaid')->whereNotNull('payment_proof')->count() }}</span>
                </button>
                @endif
            </div>

            <div class="search-wrap">
                <input type="text" id="searchInput" class="search-input" placeholder="Cari Kode / Nama..." onkeyup="searchItems()">
                <button class="search-btn"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4" style="padding: 15px; background: #d4edda; color: #155724; border-radius: 8px;">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
    @endif

    <div id="bookingListContainer">
        @foreach($bookings as $booking)
            @php
                $type = 'hidden';
                if($booking->status == 'pending') {
                    $type = 'booking';
                }
                elseif($booking->status == 'approved' && $booking->payment_status == 'unpaid' && $booking->payment_proof) {
                    $type = 'payment';
                }
            @endphp

            @if($type != 'hidden')
            <div class="booking-card item-card" data-type="{{ $type }}">
                <div class="card-top">
                    <div>
                        <h4 style="margin:0; font-weight:700;">{{ $booking->car->brand->name ?? '' }} {{ $booking->car->name }}</h4>
                        <span style="font-size:0.85rem; color:#888;">ID: <strong>{{ $booking->booking_code }}</strong></span>
                    </div>
                    <div>
                        @if($type == 'booking')
                            <span class="badge bg-primary">Perlu Persetujuan</span>
                        @else
                            <span class="badge bg-success">Cek Bukti Bayar</span>
                        @endif
                    </div>
                </div>

                <div class="card-body-grid">
                    <div class="user-box">
                        <div class="avatar">{{ substr($booking->user->name, 0, 1) }}</div>
                        <div>
                            <div style="font-weight:700;">{{ $booking->user->name }}</div>
                            <div style="font-size:0.9rem; color:#666;">{{ $booking->user->phone }}</div>
                        </div>
                    </div>
                    <div>
                        <div class="info-grid">
                            <div class="info-item"><div><label>Jadwal</label><span>{{ \Carbon\Carbon::parse($booking->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}</span></div></div>
                            <div class="info-item"><div><label>Lokasi</label><span>{{ Str::limit($booking->pickup_location, 15) }}</span></div></div>
                            <div class="info-item"><div><label>Plat</label><span>{{ $booking->car->license_plate }}</span></div></div>
                        </div>

                        @if($type == 'payment')
                        <div style="margin-top:15px; padding:12px; background:#f0fff4; border:1px solid #d1e7dd; border-radius:8px; display:flex; align-items:center; justify-content:space-between;">
                            <div class="d-flex align-items-center gap-2 text-success fw-bold"><i class="fas fa-receipt"></i> Bukti Transfer Masuk</div>
                            <button class="btn-base btn-success-custom px-3 py-1" style="font-size:0.85rem;" onclick="openCustomModal('modalPayment{{ $booking->id }}')"><i class="fas fa-eye"></i> Cek & Validasi</button>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="card-footer">
                    <div><span style="display:block; font-size:0.85rem; color:#888;">Total Transaksi</span><span class="price-tag">Rp {{ number_format($booking->grand_total, 0, ',', '.') }}</span></div>
                    <div class="action-group d-flex gap-2">
                        @if($type == 'booking')
                            @can('approval.reject')
                                <button class="btn-base btn-danger-outline" onclick="openCustomModal('modalReject{{ $booking->id }}')"><i class="fas fa-times"></i> Tolak</button>
                            @endcan
                            @can('approval.approve')
                            <form action="{{ route('approval.approve_booking', $booking->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-base btn-primary-custom"><i class="fas fa-check"></i> Terima Booking</button>
                            </form>
                            @endcan
                        @endif
                        @if($type == 'payment')
                             @can('approval.reject')
                                <button class="btn-base btn-danger-outline" onclick="openCustomModal('modalReject{{ $booking->id }}')"><i class="fas fa-times"></i> Tolak Bukti</button>
                             @endcan
                        @endif
                    </div>
                </div>
            </div>

            {{-- === MODAL VALIDASI PEMBAYARAN (Tombol Diperbaiki) === --}}
            @if($type == 'payment')
            <div id="modalPayment{{ $booking->id }}" class="custom-modal-overlay">
                <div class="custom-modal-box modal-xl">
                    <div class="split-view">

                        {{-- KIRI: GAMBAR BUKTI --}}
                        <div class="split-img">
                            <img src="{{ asset('storage/' . $booking->payment_proof) }}" alt="Bukti Transfer">
                        </div>

                        {{-- KANAN: INFO & TOMBOL AKSI --}}
                        <div class="split-info" style="position: relative; display: flex; flex-direction: column; justify-content: center; height: 100%;">

                            {{-- TOMBOL CLOSE (Pojok Kanan Atas) --}}
                            <button type="button"
                                    onclick="closeCustomModal('modalPayment{{ $booking->id }}')"
                                    style="position: absolute; top: 20px; right: 20px; border: none; background: #f8f9fa; width: 40px; height: 40px; border-radius: 50%; color: #333; font-size: 1.5rem; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.2s; z-index: 10;"
                                    onmouseover="this.style.background='#e2e6ea'"
                                    onmouseout="this.style.background='#f8f9fa'">
                                &times;
                            </button>

                            {{-- CONTENT WRAPPER --}}
                            <div>
                                <h2 class="fw-bold mb-2">Validasi Pembayaran</h2>
                                <p class="text-muted mb-4">Pastikan nominal transfer sesuai dengan tagihan.</p>

                                <div class="payment-details-box">
                                    <div class="detail-row"><span class="detail-label">Kode Booking</span><span class="detail-value">{{ $booking->booking_code }}</span></div>
                                    <div class="detail-row"><span class="detail-label">Nama Pengirim</span><span class="detail-value">{{ $booking->user->name }}</span></div>
                                    <div class="detail-row"><span class="detail-label">Total Tagihan</span><span class="total-value-modal">Rp {{ number_format($booking->grand_total, 0, ',', '.') }}</span></div>
                                </div>

                                {{-- BUTTON GRID (Pasti Sejajar 50:50) --}}
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 25px;">

                                    {{-- KIRI: BUKTI PALSU --}}
                                    <button type="button"
                                            class="btn-base btn-danger-outline"
                                            style="width: 100%; height: 50px; justify-content: center;"
                                            onclick="closeCustomModal('modalPayment{{ $booking->id }}'); openCustomModal('modalReject{{ $booking->id }}')">
                                        Bukti Palsu / Tolak
                                    </button>

                                    {{-- KANAN: VALIDASI LUNAS --}}
                                    <form action="{{ route('approval.approve_payment', $booking->id) }}" method="POST" style="width: 100%;">
                                        @csrf
                                        <button type="submit"
                                                class="btn-base btn-primary-custom"
                                                style="width: 100%; height: 50px; justify-content: center;">
                                            <i class="fas fa-check-circle me-2"></i> VALIDASI LUNAS
                                        </button>
                                    </form>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- === MODAL REJECT (Textarea & Button Fixed) === --}}
            <div id="modalReject{{ $booking->id }}" class="custom-modal-overlay">
                <div class="custom-modal-box" style="max-width: 450px;"> {{-- Sedikit diperkecil agar proporsional --}}

                    {{-- HEADER: Putih Bersih --}}
                    <div style="padding: 20px 25px 0 25px; display: flex; justify-content: space-between; align-items: center;">
                        <h4 style="margin: 0; font-weight: 700; color: #333;">Konfirmasi Penolakan</h4>

                        {{-- Tombol Close Abu-abu --}}
                        <button type="button"
                                onclick="closeCustomModal('modalReject{{ $booking->id }}')"
                                style="border: none; background: #f0f0f0; width: 35px; height: 35px; border-radius: 50%; color: #333; font-size: 1.2rem; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.2s;"
                                onmouseover="this.style.background='#e0e0e0'"
                                onmouseout="this.style.background='#f0f0f0'">
                            &times;
                        </button>
                    </div>

                    <form action="{{ route('approval.reject', $booking->id) }}" method="POST">
                        @csrf
                        <div style="padding: 25px;">

                            {{-- Label & Textarea --}}
                            <label class="mb-2 fw-bold text-dark" style="font-size: 0.95rem;">Mengapa Anda menolak permintaan ini?</label>
                            <textarea name="rejection_reason"
                                      class="form-control-custom"
                                      rows="4"
                                      placeholder="Tulis alasan penolakan secara jelas (Wajib diisi)..."
                                      required
                                      style="resize: none; background: #fafafa;"></textarea>

                            {{-- BUTTON GRID (Pasti Sejajar 50:50) --}}
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 25px;">

                                {{-- Tombol Batal --}}
                                <button type="button"
                                        class="btn-base btn-danger-outline"
                                        style="width: 100%; height: 45px; justify-content: center;"
                                        onclick="closeCustomModal('modalReject{{ $booking->id }}')">
                                    Batal
                                </button>

                                {{-- Tombol Tolak --}}
                                <button type="submit"
                                        class="btn-base btn-primary-custom"
                                        style="width: 100%; height: 45px; justify-content: center;">
                                    Tolak Sekarang
                                </button>

                            </div>

                        </div>
                    </form>
                </div>
            </div>
            @endif
        @endforeach
    </div>

    {{-- EMPTY STATE --}}
    <div id="emptyState" class="empty-state">
        <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-cart-2130356-1800917.png" alt="Kosong">
        <h3 id="emptyStateTitle">Tidak ada data persetujuan</h3>
        <p>Semua permintaan pada kategori ini sudah diproses.</p>
    </div>

</main>

<script>
    // --- 1. FILTER TABS & EMPTY STATE LOGIC ---
    function switchTab(type, btn) {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('bookingListContainer').setAttribute('data-active-tab', type);

        const cards = document.querySelectorAll('.item-card');
        let visibleCount = 0;
        cards.forEach(card => {
            if(card.getAttribute('data-type') === type) {
                card.style.display = 'block'; visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        checkEmptyState(visibleCount);
        document.getElementById('searchInput').value = '';
    }

    // --- 2. SEARCH LOGIC ---
    function searchItems() {
        const term = document.getElementById('searchInput').value.toLowerCase();
        const activeBtn = document.querySelector('.tab-btn.active');
        const activeType = activeBtn.getAttribute('onclick').match(/'([^']+)'/)[1];
        const cards = document.querySelectorAll('.item-card');
        let visibleCount = 0;

        cards.forEach(card => {
            if(card.getAttribute('data-type') === activeType) {
                const text = card.innerText.toLowerCase();
                if(text.includes(term)) {
                    card.style.display = 'block'; visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            }
        });

        const emptyTitle = document.getElementById('emptyStateTitle');
        if(visibleCount === 0 && term !== '') { emptyTitle.innerText = 'Data tidak ditemukan'; }
        else { emptyTitle.innerText = 'Tidak ada data persetujuan'; }
        checkEmptyState(visibleCount);
    }

    function checkEmptyState(count) {
        document.getElementById('emptyState').style.display = count === 0 ? 'block' : 'none';
    }

    // --- 3. CUSTOM MODAL LOGIC ---
    function openCustomModal(id) {
        const modal = document.getElementById(id);
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function closeCustomModal(id) {
        const modal = document.getElementById(id);
        modal.classList.remove('show');
        document.body.style.overflow = 'auto';
    }
    window.onclick = function(event) {
        if (event.target.classList.contains('custom-modal-overlay')) {
            event.target.classList.remove('show');
            document.body.style.overflow = 'auto';
        }
    }

    // Init First Tab
    document.addEventListener('DOMContentLoaded', function() {
        const firstTab = document.querySelector('.tab-btn');
        if(firstTab) firstTab.click();
    });
</script>
@endsection
