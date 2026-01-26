@extends('dashboard.layouts.index')
@section('title', 'Riwayat Booking')

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

        .main-content { flex: 1; margin-left: var(--sidebar-width); padding: 30px 40px; background: #fff; min-height: 100vh; }
        .mobile-header { display: none; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .hamburger { font-size: 1.5rem; cursor: pointer; color: var(--text-dark); }

        /* HEADER & FILTER */
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px; }
        .page-title { font-size: 1.8rem; font-weight: 700; margin: 0; }

        .filter-buttons { display: flex; gap: 10px; background: #f8f9fa; padding: 5px; border-radius: 8px; }
        .btn-filter { border: none; background: transparent; padding: 8px 16px; border-radius: 6px; font-weight: 600; color: #888; cursor: pointer; transition: 0.3s; }
        .btn-filter.active { background: var(--primary); color: #000; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .btn-filter:hover:not(.active) { color: #333; background: #e9ecef; }

        /* --- CARD STYLE (SEPERTI GAMBAR) --- */
        .booking-card {
            background: #fff;
            border: 1px solid #eee;
            border-radius: var(--radius);
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.02);
            transition: 0.3s;
        }
        .booking-card:hover { border-color: var(--primary); box-shadow: 0 8px 25px rgba(0,0,0,0.05); }

        /* HEADER CARD */
        .card-top { display: flex; justify-content: space-between; margin-bottom: 20px; border-bottom: 1px solid #f9f9f9; padding-bottom: 15px; }
        .car-name { font-size: 1.2rem; font-weight: 700; color: var(--text-dark); margin: 0; }
        .booking-id { font-size: 0.85rem; color: #888; margin-top: 2px; }

        .status-badge {
            padding: 6px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;
        }
        .bg-warning { background: #fff9db; color: #f08c00; }
        .bg-success { background: #d4edda; color: #155724; }
        .bg-danger { background: #ffe3e3; color: #e03131; }
        .bg-info { background: #e7f5ff; color: #1971c2; }

        /* BODY CARD (GRID LAYOUT) */
        .card-body-grid { display: grid; grid-template-columns: 280px 1fr; gap: 30px; align-items: start; }
        .car-image {
            width: 100%; height: 180px; object-fit: cover; border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        /* STATUS ALERT BOX (KUNING) */
        .status-alert {
            background: #fff9db; border-radius: 8px; padding: 15px;
            margin-bottom: 20px; display: flex; gap: 10px; align-items: flex-start;
        }
        .status-alert i { color: #f08c00; margin-top: 3px; }
        .status-title { font-weight: 700; font-size: 0.95rem; color: #f08c00; display: block; margin-bottom: 3px; }
        .status-desc { font-size: 0.85rem; color: #666; margin: 0; line-height: 1.4; }

        /* INFO GRID (TANGGAL, WAKTU, LOKASI) */
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px 40px; }
        .info-item { display: flex; gap: 12px; }
        .info-icon { width: 20px; color: #aaa; text-align: center; font-size: 1.1rem; }
        .info-content label { display: block; font-size: 0.75rem; color: #999; margin-bottom: 2px; }
        .info-content span { font-size: 0.9rem; font-weight: 600; color: #333; }

        /* FOOTER CARD */
        .card-footer {
            margin-top: 25px; padding-top: 20px; border-top: 1px solid #f0f0f0;
            display: flex; justify-content: space-between; align-items: center;
        }
        .total-label { font-size: 0.8rem; color: #888; display: block; }
        .total-value { font-size: 1.3rem; font-weight: 700; color: var(--primary-dark); }

        /* BUTTONS */
        .action-group { display: flex; gap: 10px; }
        .btn-act {
            padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 0.9rem;
            cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: 0.3s; border: 1px solid transparent;
        }
        .btn-primary { background: var(--primary); color: #000; border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); }

        .btn-outline { background: #fff; border-color: #ddd; color: #555; }
        .btn-outline:hover { border-color: #aaa; background: #f8f9fa; }

        .btn-danger-outline { background: #fff; border-color: #ffc9c9; color: #e03131; }
        .btn-danger-outline:hover { background: #fff5f5; }

        /* MOBILE RESPONSIVE */
        @media (max-width: 992px) {
            .main-content { margin-left: 0; padding: 20px; }
            .card-body-grid { grid-template-columns: 1fr; gap: 20px; }
            .car-image { height: 200px; }
            .card-footer { flex-direction: column; align-items: flex-start; gap: 15px; }
            .action-group { width: 100%; flex-direction: column; }
            .btn-act { width: 100%; justify-content: center; }
        }

        /* Modal & Star Rating CSS (Tetap sama) */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.6); z-index: 99999; align-items: center; justify-content: center; }
        .modal-overlay.show { display: flex !important; }
        .modal-box { background: #fff; padding: 30px; border-radius: 16px; width: 90%; max-width: 450px; text-align: center; }
        .star-rating { display: flex; flex-direction: row-reverse; justify-content: center; gap: 8px; margin: 15px 0; }
        .star-rating input { display: none; }
        .star-rating label { font-size: 32px; color: #e4e5e9; cursor: pointer; transition: 0.2s; }
        .star-rating input:checked ~ label, .star-rating label:hover, .star-rating label:hover ~ label { color: #FFC400; }
        /* --- CSS SEARCH BAR --- */
        .search-wrap {
            position: relative;
            width: 250px;
        }
        .search-input {
            width: 100%;
            padding: 10px 40px 10px 15px; /* Padding kanan besar untuk tombol icon */
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            font-size: 0.9rem;
            transition: 0.3s;
        }
        .search-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255, 196, 0, 0.1);
        }
        .search-btn {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #888;
            cursor: pointer;
            padding: 8px;
        }
        .search-btn:hover { color: var(--primary-dark); }

        /* Responsif di HP (Search bar memanjang) */
        @media (max-width: 768px) {
            .page-header > div { width: 100%; }
            .mobile-header { display: flex; }
            .search-wrap { width: 100%; margin-bottom: 10px; }
            .filter-buttons { width: 100%; overflow-x: auto; padding-bottom: 5px; }
        }
    </style>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Riwayat Booking</h1>
            <p style="color:#888; margin:5px 0 0 0;">Kelola semua pesanan rental mobil Anda di sini.</p>
        </div>

        <div style="display:flex; gap:15px; flex-wrap:wrap; align-items:center;">

            <form action="{{ route('riwayat_booking.index') }}" method="GET" class="search-wrap">
                <input type="text" name="q" class="search-input" value="{{ request('q') }}" placeholder="Cari ID Booking / Mobil...">
                <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
            </form>

            <div class="filter-buttons">
                <button class="btn-filter active" onclick="filterBooking('all', this)">Semua</button>
                <button class="btn-filter" onclick="filterBooking('ongoing', this)">Berjalan</button>
                <button class="btn-filter" onclick="filterBooking('completed', this)">Selesai</button>
                <button class="btn-filter" onclick="filterBooking('cancelled', this)">Batal</button>
            </div>
        </div>
    </div>

    @forelse($bookings as $booking)
        @php
            // Logic Status Badge & Colors
            $statusBadge = 'bg-warning';
            $statusIcon = 'fa-clock';
            $statusText = 'Menunggu Konfirmasi';
            $alertTitle = 'Status Terkini';
            $alertDesc = 'Admin sedang mengecek ketersediaan mobil.';
            $alertColor = '#fff9db'; // Kuning default
            $alertIcon = 'fa-sync-alt fa-spin';

            // Filter Helper
            $filterTag = 'ongoing'; // Default

            if($booking->status == 'approved') {
                if($booking->payment_status == 'paid') {
                    $statusBadge = 'bg-info'; $statusIcon = 'fa-thumbs-up'; $statusText = 'Siap Diambil';
                    $alertDesc = 'Pembayaran diterima. Silakan ambil mobil sesuai jadwal.';
                    $alertColor = '#dbe4ff'; $alertIcon = 'fa-check-circle';
                } else {
                    $statusBadge = 'bg-warning'; $statusIcon = 'fa-wallet'; $statusText = 'Menunggu Pembayaran';
                    $alertDesc = 'Pesanan dikonfirmasi. Segera lakukan pembayaran.';
                    $alertIcon = 'fa-exclamation-circle';
                }
            }
            elseif($booking->status == 'ongoing') {
                $statusBadge = 'bg-info'; $statusIcon = 'fa-road'; $statusText = 'Sewa Berjalan';
                $alertDesc = 'Masa sewa sedang berlangsung. Hati-hati di jalan!';
                $alertIcon = 'fa-car-side';
            }
            elseif($booking->status == 'completed') {
                $statusBadge = 'bg-success'; $statusIcon = 'fa-check'; $statusText = 'Selesai';
                $filterTag = 'completed';
                $alertDesc = 'Penyewaan telah selesai. Terima kasih telah menggunakan jasa kami.';
                $alertColor = '#d4edda'; $alertIcon = 'fa-smile';
            }
            elseif($booking->status == 'cancelled' || $booking->status == 'rejected') {
                $statusBadge = 'bg-danger'; $statusIcon = 'fa-times'; $statusText = 'Dibatalkan';
                $filterTag = 'cancelled';
                $alertDesc = 'Pesanan ini telah dibatalkan atau ditolak.';
                $alertColor = '#ffe3e3'; $alertIcon = 'fa-times-circle';
            }
        @endphp

        <div class="booking-card item-booking" data-status="{{ $filterTag }}">

            <div class="card-top">
                <div>
                    <h3 class="car-name">{{ $booking->car->brand->name ?? '' }} {{ $booking->car->name }}</h3>
                    <div class="booking-id">Booking ID: {{ $booking->booking_code }}</div>
                </div>
                <div class="status-badge {{ $statusBadge }}">
                    <i class="fas {{ $statusIcon }}"></i> {{ $statusText }}
                </div>
            </div>

            <div class="card-body-grid">
                @php
                    $img = 'img/no-image.jpg';
                    if(!empty($booking->car->images)) {
                        $raw = $booking->car->images;
                        $decoded = is_array($raw) ? $raw : json_decode($raw, true);
                        if(is_array($decoded) && count($decoded) > 0) $img = 'storage/' . $decoded[0];
                    }
                @endphp
                <img src="{{ asset($img) }}" class="car-image">

                <div>
                    <div class="status-alert" style="background: {{ $alertColor }}">
                        <i class="fas {{ $alertIcon }}"></i>
                        <div>
                            <span class="status-title">{{ $alertTitle }}</span>
                            <p class="status-desc">{{ $alertDesc }}</p>
                        </div>
                    </div>

                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-icon"><i class="far fa-calendar-alt"></i></div>
                            <div class="info-content">
                                <label>Tanggal Sewa</label>
                                <span>{{ \Carbon\Carbon::parse($booking->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}</span>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon"><i class="far fa-clock"></i></div>
                            <div class="info-content">
                                <label>Waktu</label>
                                <span>{{ substr($booking->pickup_time, 0, 5) }} WIB</span>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="info-content">
                                <label>Lokasi</label>
                                <span>{{ Str::limit($booking->pickup_location, 20) }}</span>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-car"></i></div>
                            <div class="info-content">
                                <label>Paket</label>
                                <span>{{ Str::limit($booking->package_name ?? 'Regular', 20) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div>
                    <span class="total-label">Total Pembayaran</span>
                    <span class="total-value">Rp {{ number_format($booking->grand_total, 0, ',', '.') }}</span>
                </div>

                <div class="action-group">

                    {{-- TOMBOL: HUBUNGI ADMIN (Selalu Ada) --}}
                    <a href="https://wa.me/6281234567890?text=Halo%20Admin,%20saya%20mau%20tanya%20soal%20booking%20{{ $booking->booking_code }}" target="_blank" class="btn-act btn-outline">
                        Hubungi Admin
                    </a>

                    {{-- KONDISI 1: PROSES BERJALAN (Pending/Approved/Ongoing) -> LIHAT PROSES --}}
                    <a href="{{ route('booking.detail', $booking->booking_code) }}" class="btn-act btn-outline">
                        Lihat Detail
                    </a>

                    {{-- KONDISI 2: SELESAI (Completed) -> REVIEW & SEWA LAGI --}}
                    @if($booking->status == 'completed')
                        {{-- Tombol Review --}}
                        @if(!$booking->review)
                            <button onclick="openReviewModal('{{ $booking->id }}', '{{ $booking->car->brand->name }} {{ $booking->car->name }}')" class="btn-act btn-outline">
                                <i class="far fa-star"></i> Beri Ulasan
                            </button>
                        @else
                            <div class="btn-act btn-outline" style="border-color:#28a745; color:#28a745; cursor:default;">
                                <i class="fas fa-check"></i> Sudah Diulas
                            </div>
                        @endif

                        {{-- Tombol Sewa Lagi --}}
                        <a href="{{ route('booking.index', $booking->car_id) }}" class="btn-act btn-primary">
                            Sewa Lagi
                        </a>
                    @endif

                </div>
            </div>

        </div>
    @empty
        <div style="text-align: center; padding: 60px; background: white; border-radius: 12px; margin-top: 20px;">
            <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-cart-2130356-1800917.png" alt="Kosong" style="width: 150px; opacity: 0.6; margin-bottom: 20px;">
            <h3 style="color: #555;">Belum ada riwayat pemesanan</h3>
            <a href="/" class="btn-act btn-primary" style="margin-top: 10px;">Cari Mobil Sekarang</a>
        </div>
    @endforelse

<div id="reviewModal" class="modal-overlay">
    <div class="modal-box">
        <div style="text-align: center; margin-bottom: 15px;">
            <div style="width:60px; height:60px; background:#fff9db; color:#FFC400; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-size:28px; margin-bottom:10px;">
                <i class="fas fa-star"></i>
            </div>
            <h3 style="margin:0; font-size:1.4rem;">Beri Ulasan</h3>
            <p id="reviewCarName" style="color:#666; margin:5px 0;">Mobil</p>
        </div>

        <form action="{{ route('review.store') }}" method="POST">
            @csrf
            <input type="hidden" name="booking_id" id="reviewBookingId">

            <div class="star-rating">
                <input type="radio" name="rating" id="star5" value="5"><label for="star5"><i class="fas fa-star"></i></label>
                <input type="radio" name="rating" id="star4" value="4"><label for="star4"><i class="fas fa-star"></i></label>
                <input type="radio" name="rating" id="star3" value="3"><label for="star3"><i class="fas fa-star"></i></label>
                <input type="radio" name="rating" id="star2" value="2"><label for="star2"><i class="fas fa-star"></i></label>
                <input type="radio" name="rating" id="star1" value="1"><label for="star1"><i class="fas fa-star"></i></label>
            </div>

            <textarea name="comment" class="form-control" rows="3" placeholder="Ceritakan pengalaman Anda..." style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px; margin-bottom:20px; font-family:inherit;" required></textarea>

            <div style="display:flex; gap:10px;">
                <button type="button" class="btn-act btn-outline" style="flex:1; justify-content:center;" onclick="closeReviewModal()">Batal</button>
                <button type="submit" class="btn-act btn-primary" style="flex:1; justify-content:center;">Kirim</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('script')
<script>
    // Filter Tab Logic
    function filterBooking(status, btn) {
        document.querySelectorAll('.btn-filter').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        let items = document.querySelectorAll('.item-booking');
        items.forEach(item => {
            if(status === 'all' || item.getAttribute('data-status') === status) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Modal Logic
    function openReviewModal(id, name) {
        document.getElementById('reviewBookingId').value = id;
        document.getElementById('reviewCarName').innerText = name;

        document.getElementById('reviewModal').style.display = 'flex';
        setTimeout(() => document.getElementById('reviewModal').classList.add('show'), 10);
    }

    function closeReviewModal() {
        document.getElementById('reviewModal').classList.remove('show');
        setTimeout(() => document.getElementById('reviewModal').style.display = 'none', 300);
    }

    window.onclick = function(e) {
        if(e.target == document.getElementById('reviewModal')) closeReviewModal();
    }
</script>
@endsection
