@extends('dashboard.layouts.index')
@section('title', 'Lacak Pesanan')

@section('style')
<style>
    :root {
        --primary: #FFC400;
        --success: #FFC400;
        --secondary: #6c757d;
        --bg-light: #f8f9fa;
        --line-color: #e9ecef;
    }

    .main-content { flex: 1; margin-left: var(--sidebar-width); padding: 30px 40px; background: #fff; min-height: 100vh; }

    /* HEADER */
    .header-detail { display: flex; align-items: center; gap: 15px; margin-bottom: 30px; border-bottom: 1px solid #eee; padding-bottom: 20px; }
    .btn-back { background: #f8f9fa; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #333; transition: 0.3s; }
    .btn-back:hover { background: #e2e6ea; }

    /* LAYOUT GRID */
    .track-layout { display: grid; grid-template-columns: 2fr 1fr; gap: 40px; }

    /* --- TIMELINE STYLES (LOGIKA VISUAL) --- */
    .timeline-wrapper { position: relative; padding-left: 10px; }

    .track-item { position: relative; padding-left: 50px; padding-bottom: 40px; }
    .track-item:last-child { padding-bottom: 0; }

    /* Garis Vertikal */
    .track-line {
        position: absolute; left: 14px; top: 35px; bottom: -5px; width: 2px; background: var(--line-color);
        z-index: 0;
    }
    .track-item:last-child .track-line { display: none; }

    /* Icon Bulat */
    .track-icon {
        position: absolute; left: 0; top: 0;
        width: 30px; height: 30px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        z-index: 1; font-size: 14px;
        background: #e9ecef; color: #999; /* Default Inactive */
    }

    /* STATUS: SELESAI (Hijau Check) */
    .track-item.completed .track-icon { background: var(--success); color: #fff; }
    .track-item.completed .track-line { background: var(--success); }

    /* STATUS: AKTIF/SEKARANG (Kuning/Pulse) */
    .track-item.active .track-icon {
        background: var(--primary); color: #000;
        box-shadow: 0 0 0 4px rgba(255, 196, 0, 0.2);
    }

    /* Konten Teks */
    .track-content h4 { font-size: 1rem; font-weight: 700; margin: 0 0 5px 0; color: #333; }
    .track-content p { font-size: 0.9rem; color: #666; margin: 0; }
    .track-date { font-size: 0.8rem; color: #999; position: absolute; right: 0; top: 0; }

    /* ALERT BOX (Kotak Biru di Referensi) */
    .info-box {
        margin-top: 15px; background: #e3f2fd; border: 1px solid #bbdefb; border-radius: 8px; padding: 15px;
        color: #0d47a1; font-size: 0.9rem;
    }
    .info-box i { margin-right: 8px; }

    /* BUTTONS IN TIMELINE */
    .track-action { margin-top: 15px; }
    .btn-pay { background: var(--primary); color: #000; padding: 10px 20px; border-radius: 8px; border: none; font-weight: 700; cursor: pointer; text-decoration: none; display: inline-block; }
    .btn-pay:hover { background: #e6b000; }

    /* --- RIGHT COLUMN (SUMMARY) --- */
    .summary-card { position: sticky; top: 20px; border: 1px solid #eee; border-radius: 12px; padding: 25px; background: #fff; box-shadow: 0 5px 20px rgba(0,0,0,0.03); }
    .car-img { width: 100%; height: 150px; object-fit: cover; border-radius: 8px; margin-bottom: 15px; }
    .summary-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 0.9rem; color: #555; }
    .total-row { border-top: 2px dashed #eee; padding-top: 15px; margin-top: 15px; font-weight: 700; font-size: 1.1rem; color: #000; display: flex; justify-content: space-between; }

    /* --- CSS KHUSUS MODAL REVIEW --- */

    /* 1. Overlay Gelap (Latar Belakang) */
    .modal-overlay {
        display: none; /* Default sembunyi */
        position: fixed;
        top: 0; left: 0;
        width: 100vw; height: 100vh;
        background: rgba(0, 0, 0, 0.6); /* Hitam transparan */
        z-index: 99999; /* Paling depan */
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    /* Class untuk memunculkan modal */
    .modal-overlay.show {
        display: flex !important;
        opacity: 1;
    }

    /* 2. Kotak Modal (Putih) */
    .modal-box {
        background: #ffffff;
        width: 90%;
        max-width: 450px; /* Lebar maksimal agar tidak terlalu lebar di PC */
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        text-align: center;
        transform: scale(0.8);
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); /* Efek membal */
    }

    .modal-overlay.show .modal-box {
        transform: scale(1);
    }

    /* 3. Header & Icon Modal */
    .modal-icon-header {
        width: 60px; height: 60px;
        background: #fff9db;
        color: #FFC400;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin-bottom: 15px;
    }

    .modal-title-text {
        font-size: 1.4rem;
        font-weight: 700;
        color: #111;
        margin: 0 0 5px 0;
    }

    .modal-desc-text {
        font-size: 0.9rem;
        color: #666;
        margin: 0 0 20px 0;
    }

    /* 4. Rating Bintang (Penting: Flex Reverse) */
    .star-rating-container {
        display: flex;
        flex-direction: row-reverse; /* Agar logika hover CSS jalan */
        justify-content: center;
        gap: 10px;
        margin-bottom: 25px;
    }

    .star-rating-container input {
        display: none; /* Sembunyikan radio button asli */
    }

    .star-rating-container label {
        font-size: 35px;
        color: #e4e5e9; /* Abu-abu default */
        cursor: pointer;
        transition: color 0.2s;
    }

    /* Warna Kuning saat Hover atau Checked */
    .star-rating-container input:checked ~ label,
    .star-rating-container label:hover,
    .star-rating-container label:hover ~ label {
        color: #FFC400;
    }

    /* 5. Text Area */
    .review-textarea {
        width: 100%;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 10px;
        font-family: inherit;
        font-size: 0.95rem;
        margin-bottom: 20px;
        resize: vertical;
        outline: none;
    }
    .review-textarea:focus {
        border-color: #FFC400;
        box-shadow: 0 0 0 3px rgba(255, 196, 0, 0.1);
    }

    /* 6. Tombol Aksi */
    .modal-action-buttons {
        display: flex;
        gap: 15px;
    }

    .btn-modal-cancel {
        flex: 1;
        padding: 12px;
        border: none;
        background: #f1f3f5;
        color: #555;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
    }
    .btn-modal-cancel:hover { background: #e9ecef; }

    .btn-modal-submit {
        flex: 1;
        padding: 12px;
        border: none;
        background: #FFC400;
        color: #000;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        transition: 0.3s;
    }
    .btn-modal-submit:hover { background: #e0ac00; transform: translateY(-2px); }

    @media (max-width: 992px) {
        .main-content { margin-left: 0; padding: 20px; }
        .track-layout { grid-template-columns: 1fr; }
        .track-date { position: static; display: block; margin-top: 5px; }
    }
</style>
@endsection

@section('content')

    <div class="header-detail">
        <a href="{{ route('riwayat_booking.index') }}" class="btn-back"><i class="fas fa-arrow-left"></i></a>
        <div>
            <h1 style="font-size: 1.5rem; margin: 0; font-weight: 700;">Proses Pesanan</h1>
            <span style="font-size: 0.9rem; color: #666;">ID Booking: #{{ $booking->booking_code }}</span>
        </div>
    </div>

    <div class="track-layout">

        <div class="timeline-wrapper">

            @php
                // LOGIKA MENENTUKAN STEP AKTIF
                // 1: Pending, 2: Approved(Unpaid), 3: Paid/Verified, 4: Ongoing, 5: Completed
                $currentStep = 1;

                if($booking->status == 'pending') $currentStep = 1;
                if($booking->status == 'approved' && $booking->payment_status != 'paid') $currentStep = 2;
                if(($booking->status == 'approved' && $booking->payment_status == 'paid') || ($booking->payment_status == 'paid' && $booking->status != 'ongoing' && $booking->status != 'completed')) $currentStep = 3;
                if($booking->status == 'ongoing') $currentStep = 4;
                if($booking->status == 'completed') $currentStep = 5;
                if($booking->status == 'cancelled' || $booking->status == 'rejected') $currentStep = 0; // Merah
            @endphp

            @if($currentStep == 0)
                <div style="background:#ffe3e3; color:#c92a2a; padding:15px; border-radius:8px; margin-bottom:20px; text-align:center; font-weight:bold;">
                    <i class="fas fa-times-circle"></i> Pesanan ini DIBATALKAN / DITOLAK
                </div>
            @endif

            <div class="track-item {{ $currentStep > 1 ? 'completed' : ($currentStep == 1 ? 'active' : '') }}">
                <div class="track-line"></div>
                <div class="track-icon">
                    @if($currentStep > 1) <i class="fas fa-check"></i> @else 1 @endif
                </div>
                <div class="track-content">
                    <h4>Booking Dibuat</h4>
                    <p>Pesanan Anda telah berhasil dibuat.</p>
                    <span class="track-date">{{ $booking->created_at->format('d M Y, H:i') }}</span>
                </div>
            </div>

            <div class="track-item {{ $currentStep > 2 ? 'completed' : ($currentStep == 2 ? 'active' : '') }}">
                <div class="track-line"></div>
                <div class="track-icon">
                    @if($currentStep > 2) <i class="fas fa-check"></i> @else 2 @endif
                </div>
                <div class="track-content">
                    <h4>Menunggu Konfirmasi Admin</h4>
                    <p>Admin sedang mengecek ketersediaan mobil.</p>

                    @if($currentStep == 1)
                    <div class="info-box">
                        <i class="fas fa-clock"></i> <strong>Menunggu Konfirmasi</strong><br>
                        Admin akan mengkonfirmasi pesanan Anda secepatnya. Mohon ditunggu.
                    </div>
                    @endif
                </div>
            </div>

            <div class="track-item {{ $currentStep > 2 ? 'completed' : ($currentStep == 2 ? 'active' : '') }}">
                <div class="track-line"></div>
                <div class="track-icon">
                    @if($currentStep > 2) <i class="fas fa-check"></i> @else 3 @endif
                </div>
                <div class="track-content">
                    <h4>Pembayaran</h4>
                    <p>Lakukan pembayaran untuk melanjutkan.</p>

                    @if($currentStep == 2)
                    <div class="track-action">
                        <div class="info-box" style="background:#fff9db; border-color:#ffe066; color:#b07c12; margin-bottom:10px;">
                            <i class="fas fa-exclamation-circle"></i> Pesanan Diterima! Silakan bayar sebelum expired.
                        </div>
                        <a href="{{ route('booking.track', $booking->booking_code) }}" class="btn-pay">
                            <i class="fas fa-wallet"></i> Bayar Sekarang
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <div class="track-item {{ $currentStep > 3 ? 'completed' : ($currentStep == 3 ? 'active' : '') }}">
                <div class="track-line"></div>
                <div class="track-icon">
                    @if($currentStep > 3) <i class="fas fa-check"></i> @else 4 @endif
                </div>
                <div class="track-content">
                    <h4>Siap Diambil / Verifikasi Selesai</h4>
                    <p>Pembayaran diterima. Mobil siap diambil sesuai jadwal.</p>
                    @if($currentStep == 3)
                        <div class="info-box" style="background:#d4edda; border-color:#c3e6cb; color:#155724;">
                            <i class="fas fa-check-circle"></i> <strong>Lunas!</strong><br>
                            Silakan datang ke lokasi pengambilan pada: <strong>{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y, H:i') }}</strong>
                        </div>
                    @endif
                </div>
            </div>

            <div class="track-item {{ $currentStep > 4 ? 'completed' : ($currentStep == 4 ? 'active' : '') }}">
                <div class="track-line"></div>
                <div class="track-icon">
                    @if($currentStep > 4) <i class="fas fa-check"></i> @else 5 @endif
                </div>
                <div class="track-content">
                    <h4>Sewa Berjalan</h4>
                    <p>Nikmati perjalanan Anda.</p>
                    @if($currentStep == 4)
                        <div class="info-box">
                            <i class="fas fa-road"></i> Hati-hati di jalan! Hubungi admin jika butuh bantuan darurat.
                        </div>
                    @endif
                </div>
            </div>

            <div class="track-item {{ $currentStep == 5 ? 'active completed' : '' }}">
                <div class="track-icon" style="{{ $currentStep == 5 ? 'background:var(--success); color:white;' : '' }}">
                    <i class="fas fa-flag-checkered"></i>
                </div>
                <div class="track-content">
                    <h4>Pengembalian Mobil</h4>
                    <p>Mobil dikembalikan dan pesanan selesai.</p>
                    @if($currentStep == 5)
                        <div class="track-action">
                            @if(!$booking->review)
                                <button onclick="openReviewModal()" class="btn-pay" style="background:#fff; border:1px solid #333; color:#333;">
                                    <i class="far fa-star"></i> Beri Ulasan
                                </button>
                            @else
                                <span style="color:var(--success); font-weight:bold;"><i class="fas fa-check"></i> Terima kasih atas ulasan Anda!</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <div class="right-col">
            <div class="summary-card">
                <h4 style="margin-top:0; margin-bottom:15px; font-weight:700;">Ringkasan Sewa</h4>

                @php
                    $img = 'img/no-image.jpg';
                    if(!empty($booking->car->images)) {
                        $raw = $booking->car->images;
                        $decoded = is_array($raw) ? $raw : json_decode($raw, true);
                        if(is_array($decoded) && count($decoded) > 0) $img = 'storage/' . $decoded[0];
                    }
                @endphp
                <img src="{{ asset($img) }}" class="car-img">

                <h3 style="font-size:1.1rem; font-weight:700; margin-bottom:5px;">{{ $booking->car->brand->name }} {{ $booking->car->name }}</h3>
                <div style="font-size:0.9rem; color:#666; margin-bottom:20px;">
                    {{ $booking->car->license_plate ?? 'Plat Belum Diisi' }}
                </div>

                <div class="summary-row">
                    <span>Durasi</span>
                    <strong>{{ \Carbon\Carbon::parse($booking->start_date)->diffInDays($booking->end_date) + 1 }} Hari</strong>
                </div>
                <div class="summary-row">
                    <span>Paket</span>
                    <strong>{{ $booking->package_name }}</strong>
                </div>

                @if($booking->address_detail)
                <div style="margin-top:15px; padding-top:15px; border-top:1px solid #eee;">
                    <div style="font-size:0.85rem; font-weight:bold; color:#333; margin-bottom:5px;">Catatan / Alamat:</div>
                    <p style="font-size:0.85rem; color:#666; line-height:1.4;">{{ $booking->address_detail }}</p>
                </div>
                @endif

                <div class="total-row">
                    <span>Total Tagihan</span>
                    <span>Rp {{ number_format($booking->grand_total) }}</span>
                </div>
            </div>
        </div>

    </div>

@if($booking->status == 'completed' && !$booking->review)
<div id="reviewModal" class="modal-overlay">
    <div class="modal-box">

        <div class="modal-icon-header"><i class="fas fa-star"></i></div>
        <h3 class="modal-title-text">Beri Ulasan</h3>
        <p class="modal-desc-text">{{ $booking->car->brand->name }} {{ $booking->car->name }}</p>

        <form action="{{ route('review.store') }}" method="POST">
            @csrf
            <input type="hidden" name="booking_id" value="{{ $booking->id }}">

            <div class="star-rating-container">
                <input type="radio" name="rating" id="st5" value="5"><label for="st5" title="Sempurna"><i class="fas fa-star"></i></label>
                <input type="radio" name="rating" id="st4" value="4"><label for="st4" title="Bagus"><i class="fas fa-star"></i></label>
                <input type="radio" name="rating" id="st3" value="3"><label for="st3" title="Biasa"><i class="fas fa-star"></i></label>
                <input type="radio" name="rating" id="st2" value="2"><label for="st2" title="Buruk"><i class="fas fa-star"></i></label>
                <input type="radio" name="rating" id="st1" value="1"><label for="st1" title="Sangat Buruk"><i class="fas fa-star"></i></label>
            </div>

            <textarea name="comment" class="review-textarea" rows="4" placeholder="Ceritakan pengalaman Anda menggunakan mobil ini..." required></textarea>

            <div class="modal-action-buttons">
                <button type="button" class="btn-modal-cancel" onclick="closeReviewModal()">Batal</button>
                <button type="submit" class="btn-modal-submit">Kirim Ulasan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openReviewModal() {
        // Tampilkan dengan delay sedikit agar animasi CSS jalan
        document.getElementById('reviewModal').style.display = 'flex';
        setTimeout(() => {
            document.getElementById('reviewModal').classList.add('show');
        }, 10);
    }

    function closeReviewModal() {
        document.getElementById('reviewModal').classList.remove('show');
        // Tunggu animasi selesai baru hide
        setTimeout(() => {
            document.getElementById('reviewModal').style.display = 'none';
        }, 300);
    }

    // Tutup jika klik di area gelap (luar kotak)
    window.onclick = function(event) {
        let modal = document.getElementById('reviewModal');
        if (event.target == modal) {
            closeReviewModal();
        }
    }
</script>
@endif
@endsection
