@extends('dashboard.layouts.index')
@section('title', 'Pesanan Aktif')

@section('style')
    <style>
        /* --- VARIABLES & GLOBAL --- */
        :root {
            --primary: #FFC400;      /* Kuning Utama */
            --primary-dark: #e0ac00;
            --text-dark: #222;
            --text-gray: #666;
            --bg-light: #f4f7f6;     /* Background halaman sedikit lebih bersih */
            --sidebar-width: 280px;

            /* Status Colors */
            --green: #22C55E; --green-bg: #E6FCF5; --green-text: #0CA678;
            --yellow: #F59F00; --yellow-bg: #FFF9DB; --yellow-text: #F59F00;
            --blue: #3B82F6;  --blue-bg: #EFF6FF; --blue-text: #1D4ED8;
            --btn-dark: #333;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 25px 30px; /* Padding disesuaikan agar full tapi rapi */
            min-height: 100vh;
            background-color: var(--bg-light);
            font-family: 'Poppins', sans-serif;
            box-sizing: border-box;
        }

        /* --- HEADER STATS --- */
        .stats-banner {
            background-color: var(--primary);
            border-radius: 12px;
            padding: 20px 25px;
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 30px;
            box-shadow: 0 4px 10px rgba(255, 196, 0, 0.15);
            color: #000;
        }
        .stats-banner h2 { font-size: 14px; margin-bottom: 5px; font-weight: 500; }
        .stats-banner .count { font-size: 32px; font-weight: 800; line-height: 1; }
        .stats-banner i { font-size: 24px; opacity: 0.8; }

        /* --- ORDER CARD STRUCTURE --- */
        .order-card {
            background: #fff;
            border-radius: 16px;
            margin-bottom: 35px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            border: 1px solid #eaeaea;
            overflow: hidden;
            position: relative;
            /* Border Atas Warna Warni sesuai Status */
        }

        /* 1. Header Card */
        .card-header-top {
            padding: 25px 30px 15px;
            display: flex; justify-content: space-between; align-items: flex-start;
        }
        .car-title h3 { font-size: 18px; font-weight: 700; color: var(--text-dark); margin: 0 0 5px 0; }
        .booking-id { font-size: 12px; color: #999; font-weight: 500; }

        .status-badge {
            padding: 6px 14px; border-radius: 50px; font-size: 11px; font-weight: 600;
            display: inline-flex; align-items: center; gap: 6px; letter-spacing: 0.3px;
        }

        /* 2. Content Split (Image + Info) */
        .card-content-split {
            display: flex; gap: 30px; padding: 10px 30px 30px;
        }

        .car-image-wrapper {
            width: 350px; /* Lebar foto pas */
            height: 220px;
            border-radius: 12px;
            overflow: hidden;
            flex-shrink: 0;
            background: #f8f8f8;
            border: 1px solid #eee;
        }
        .car-image-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
        .car-image-wrapper:hover img { transform: scale(1.02); }

        .car-info-wrapper { flex: 1; display: flex; flex-direction: column; justify-content: space-between; }

        /* Status Alert Box (Warna Warni) */
        .status-alert-box {
            padding: 16px 20px; border-radius: 10px; margin-bottom: 25px;
            display: flex; align-items: flex-start; gap: 12px;
        }
        .status-alert-box i { font-size: 16px; margin-top: 3px; }
        .alert-text h4 { font-size: 13px; font-weight: 700; margin: 0 0 3px 0; }
        .alert-text p { font-size: 12px; margin: 0; opacity: 0.9; line-height: 1.4; }

        /* Grid Detail */
        .details-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 20px 40px; margin-bottom: 25px;
        }
        .detail-item { display: flex; gap: 12px; }
        .detail-icon { color: #bbb; font-size: 16px; margin-top: 1px; width: 18px; }
        .detail-text label { display: block; font-size: 10px; color: #aaa; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px; font-weight: 600; }
        .detail-text span { display: block; font-size: 13px; font-weight: 600; color: var(--text-dark); }

        /* Footer Action */
        .card-footer-action {
            display: flex; justify-content: space-between; align-items: flex-end;
            border-top: 1px solid #f0f0f0; padding-top: 20px;
        }
        .price-section label { font-size: 11px; color: #999; display: block; margin-bottom: 2px; }
        .price-value { font-size: 18px; font-weight: 800; } /* Warna diatur per tema */

        .btn-group { display: flex; gap: 12px; }
        .btn {
            padding: 10px 24px; border-radius: 8px; font-size: 13px; font-weight: 600;
            cursor: pointer; border: 1px solid transparent; transition: 0.2s;
        }
        .btn-outline { background: #fff; border-color: #e0e0e0; color: #666; }
        .btn-outline:hover { border-color: #ccc; color: #333; }

        /* Tombol Toggle Default */
        .btn-toggle { background: var(--primary); color: #000; border: none; }
        .btn-toggle:hover { opacity: 0.9; }

        /* Tombol Toggle Aktif (Gelap seperti di foto) */
        .btn-toggle.active { background: #333; color: #fff; }

        /* --- TIMELINE SECTION --- */
        .process-timeline {
            background: #fff;
            padding: 0 30px 30px; /* Padding samping disamakan dengan atas agar lurus */
            display: none; /* Hidden default */
            border-top: 1px dashed #eee; /* Pemisah halus */
        }
        .process-timeline.show { display: block; animation: slideDown 0.4s ease; }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

        .timeline-header {
            font-size: 13px; font-weight: 700; color: #333; margin: 30px 0 20px;
        }

        /* Timeline Items */
        .tl-item { display: flex; gap: 20px; position: relative; padding-bottom: 35px; }
        .tl-item:last-child { padding-bottom: 0; }

        /* Garis Vertikal */
        .tl-line {
            position: absolute; left: 11px; top: 28px; bottom: 0;
            width: 2px; background: #eee; z-index: 1;
        }
        .tl-item:last-child .tl-line { display: none; }
        /* Garis Hijau kalau selesai */
        .tl-item.completed .tl-line { background: var(--green); }

        /* Icons */
        .tl-icon {
            width: 24px; height: 24px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 10px; z-index: 2; flex-shrink: 0;
            background: #eee; color: #bbb; font-weight: bold;
            border: 3px solid #fff; box-shadow: 0 0 0 1px #eee;
        }
        .tl-item.completed .tl-icon { background: var(--green); color: #fff; box-shadow: none; border-color: var(--green); }
        .tl-item.active .tl-icon {
            box-shadow: 0 0 0 3px rgba(0,0,0,0.05); border-color: #fff;
            /* Warna background diatur inline/per-tema */
        }

        /* Content */
        .tl-content { flex: 1; }
        .tl-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; }
        .tl-title { font-size: 14px; font-weight: 700; color: #333; }
        .tl-date { font-size: 11px; color: #bbb; }
        .tl-desc { font-size: 12px; color: #777; margin: 0; }

        /* Box Biru/Info di dalam Timeline (Seperti di Foto) */
        .tl-info-box {
            margin-top: 12px;
            padding: 15px 20px;
            background: #F8FAFC; border: 1px solid #E2E8F0;
            border-radius: 8px;
        }
        .tl-info-box h5 { margin: 0 0 4px 0; font-size: 12px; color: #000; font-weight: 700; display: flex; align-items: center; gap: 8px; }
        .tl-info-box p { margin: 0; font-size: 12px; color: #64748B; line-height: 1.5; }

        /* Footer Info Abu-abu (Paling Bawah) */
        .footer-note-box {
            background: #FAFAFA; border-radius: 10px; padding: 20px; margin-top: 30px;
        }
        .footer-note-box h5 { font-size: 12px; font-weight: 700; margin: 0 0 10px 0; color: #333; }
        .footer-note-box ul { padding-left: 18px; margin: 0; }
        .footer-note-box li { font-size: 11px; color: #777; margin-bottom: 5px; line-height: 1.5; }

        /* --- THEME COLORS --- */
        /* Yellow Theme */
        .theme-yellow { border-top: 5px solid var(--yellow); }
        .theme-yellow .status-badge { background: var(--yellow-bg); color: var(--yellow-text); }
        .theme-yellow .status-alert-box { background: var(--yellow-bg); }
        .theme-yellow .status-alert-box i, .theme-yellow .alert-text h4 { color: var(--yellow-text); }
        .theme-yellow .price-value { color: var(--yellow-text); }

        /* Blue Theme */
        .theme-blue { border-top: 5px solid var(--blue); }
        .theme-blue .status-badge { background: var(--blue-bg); color: var(--blue-text); }
        .theme-blue .status-alert-box { background: var(--blue-bg); }
        .theme-blue .status-alert-box i, .theme-blue .alert-text h4 { color: var(--blue-text); }
        .theme-blue .price-value { color: var(--blue-text); }

        /* Green Theme */
        .theme-green { border-top: 5px solid var(--green); }
        .theme-green .status-badge { background: var(--green-bg); color: var(--green-text); }
        .theme-green .status-alert-box { background: var(--green-bg); }
        .theme-green .status-alert-box i, .theme-green .alert-text h4 { color: var(--green-text); }
        .theme-green .price-value { color: var(--green-text); }


        /* RESPONSIVE */
        @media (max-width: 992px) {
            .main-content { margin-left: 0; padding: 15px; }
            .card-content-split { flex-direction: column; padding: 15px 20px 25px; }
            .car-image-wrapper { width: 100%; height: 200px; }
            .card-header-top { padding: 20px 20px 10px; }
            .card-footer-action { flex-direction: column; align-items: flex-start; gap: 15px; }
            .btn-group { width: 100%; }
            .btn { flex: 1; text-align: center; }
            .process-timeline { padding: 0 20px 25px; }
        }
    </style>
@endsection

@section('content')
<main class="main-content">

    <div class="stats-banner">
        <div>
            <h2>Total Pesanan Aktif</h2>
            <div class="count">3</div>
        </div>
        <i class="far fa-clock"></i>
    </div>

    <div class="order-card theme-yellow">
        <div class="card-header-top">
            <div class="car-title">
                <h3>Toyota Avanza 2022</h3>
                <span class="booking-id">Booking ID: RMX-203392</span>
            </div>
            <div class="status-badge">
                <i class="far fa-clock"></i> Menunggu Konfirmasi
            </div>
        </div>

        <div class="card-content-split">
            <div class="car-image-wrapper">
                <img src="https://img.freepik.com/free-photo/white-off-roader-jeep-parking_114579-4007.jpg" alt="Toyota Avanza">
            </div>
            <div class="car-info-wrapper">
                <div class="status-alert-box">
                    <i class="fas fa-spinner fa-spin"></i>
                    <div class="alert-text">
                        <h4>Status Terkini</h4>
                        <p>Admin sedang mengecek ketersediaan mobil untuk tanggal yang Anda pilih.</p>
                    </div>
                </div>
                <div class="details-grid">
                    <div class="detail-item">
                        <i class="far fa-calendar-alt detail-icon"></i>
                        <div class="detail-text"><label>Tanggal Sewa</label><span>15 Des 2025 - 17 Des</span></div>
                    </div>
                    <div class="detail-item">
                        <i class="far fa-clock detail-icon"></i>
                        <div class="detail-text"><label>Waktu</label><span>08:00 - 20:00</span></div>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-map-marker-alt detail-icon"></i>
                        <div class="detail-text"><label>Lokasi</label><span>Jl. Sudirman No. 123</span></div>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-car detail-icon"></i>
                        <div class="detail-text"><label>Paket</label><span>All-In Dalam Kota</span></div>
                    </div>
                </div>
                <div class="card-footer-action">
                    <div class="price-section">
                        <label>Total Pembayaran</label>
                        <div class="price-value">Rp 1.400.000</div>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-outline">Hubungi Admin</button>
                        <button class="btn btn-toggle" onclick="toggleTimeline(this)">Lihat Proses</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="process-timeline">
            <div class="timeline-header">Proses Pesanan</div>

            <div class="tl-item completed">
                <div class="tl-line"></div>
                <div class="tl-icon"><i class="fas fa-check"></i></div>
                <div class="tl-content">
                    <div class="tl-head"><span class="tl-title">Booking Dibuat</span> <span class="tl-date">11 Des 2025, 14:30</span></div>
                    <p class="tl-desc">Pesanan Anda telah berhasil dibuat.</p>
                </div>
            </div>

            <div class="tl-item active">
                <div class="tl-line"></div>
                <div class="tl-icon" style="background:var(--yellow); color:#fff;"><i class="fas fa-clock"></i></div>
                <div class="tl-content">
                    <div class="tl-head"><span class="tl-title">Menunggu Konfirmasi Admin</span> <span class="tl-date" style="color:var(--yellow)">Sedang diproses</span></div>
                    <p class="tl-desc">Admin sedang mengecek ketersediaan mobil.</p>

                    <div class="tl-info-box" style="background:#FFF9DB; border-color:#FFE066;">
                        <h5 style="color:#E67700;"><i class="fas fa-info-circle"></i> Menunggu Konfirmasi</h5>
                        <p style="color:#E67700;">Admin akan mengkonfirmasi dalam 1-2 jam. Anda akan mendapat notifikasi.</p>
                    </div>
                </div>
            </div>

            <div class="tl-item">
                <div class="tl-line"></div>
                <div class="tl-icon">3</div>
                <div class="tl-content">
                    <div class="tl-head"><span class="tl-title">Pembayaran</span></div>
                    <p class="tl-desc">Lakukan pembayaran dan upload bukti.</p>
                </div>
            </div>

            <div class="tl-item"><div class="tl-line"></div><div class="tl-icon">4</div><div class="tl-content"><span class="tl-title">Verifikasi</span></div></div>
            <div class="tl-item"><div class="tl-line"></div><div class="tl-icon">5</div><div class="tl-content"><span class="tl-title">Siap Diambil</span></div></div>
            <div class="tl-item"><div class="tl-icon">6</div><div class="tl-content"><span class="tl-title">Selesai</span></div></div>

            <div class="footer-note-box">
                <h5>Informasi Tambahan</h5>
                <ul>
                    <li>Pastikan dokumen yang diupload jelas dan sesuai.</li>
                    <li>Pembayaran harus dilakukan maksimal 2 jam setelah booking disetujui.</li>
                    <li>Hubungi admin jika ada pertanyaan melalui tombol "Hubungi Admin".</li>
                </ul>
            </div>
        </div>
    </div>


    <div class="order-card theme-blue">
        <div class="card-header-top">
            <div class="car-title">
                <h3>Tesla Model 3 2023</h3>
                <span class="booking-id">Booking ID: RMX-203156</span>
            </div>
            <div class="status-badge">
                <i class="fas fa-search-dollar"></i> Verifikasi Pembayaran
            </div>
        </div>

        <div class="card-content-split">
            <div class="car-image-wrapper">
                <img src="https://img.freepik.com/free-photo/blue-sport-sedan-parked-yard_114579-4023.jpg" alt="Tesla Model 3">
            </div>
            <div class="car-info-wrapper">
                <div class="status-alert-box">
                    <i class="fas fa-search"></i>
                    <div class="alert-text">
                        <h4>Status Terkini</h4>
                        <p>Pembayaran Anda sedang diverifikasi oleh sistem kami.</p>
                    </div>
                </div>
                <div class="details-grid">
                    <div class="detail-item"><i class="far fa-calendar-alt detail-icon"></i><div class="detail-text"><label>Tanggal Sewa</label><span>14 Des 2025 - 16 Des</span></div></div>
                    <div class="detail-item"><i class="far fa-clock detail-icon"></i><div class="detail-text"><label>Waktu</label><span>07:00 - 21:00</span></div></div>
                    <div class="detail-item"><i class="fas fa-map-marker-alt detail-icon"></i><div class="detail-text"><label>Lokasi</label><span>Jl. Kemang Raya No. 45</span></div></div>
                    <div class="detail-item"><i class="fas fa-key detail-icon"></i><div class="detail-text"><label>Paket</label><span>Lepas Kunci</span></div></div>
                </div>
                <div class="card-footer-action">
                    <div class="price-section"><label>Total Pembayaran</label><div class="price-value">Rp 2.400.000</div></div>
                    <div class="btn-group">
                        <button class="btn btn-outline">Hubungi Admin</button>
                        <button class="btn btn-toggle" onclick="toggleTimeline(this)">Lihat Proses</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="process-timeline">
            <div class="timeline-header">Proses Pesanan</div>
            <div class="tl-item completed">
                <div class="tl-line"></div><div class="tl-icon"><i class="fas fa-check"></i></div>
                <div class="tl-content"><div class="tl-head"><span class="tl-title">Booking Dikonfirmasi</span></div></div>
            </div>
            <div class="tl-item completed">
                <div class="tl-line"></div><div class="tl-icon"><i class="fas fa-check"></i></div>
                <div class="tl-content"><div class="tl-head"><span class="tl-title">Upload Bukti Bayar</span></div></div>
            </div>
            <div class="tl-item active">
                <div class="tl-line"></div>
                <div class="tl-icon" style="background:var(--blue); color:#fff;"><i class="fas fa-search-dollar"></i></div>
                <div class="tl-content">
                    <div class="tl-head"><span class="tl-title">Verifikasi Pembayaran</span></div>
                    <div class="tl-info-box" style="background:#EFF6FF; border-color:#DBEAFE;">
                        <h5 style="color:#1D4ED8;"><i class="fas fa-search"></i> Pengecekan Sistem</h5>
                        <p>Mohon tunggu maksimal 15 menit. Sistem sedang mencocokkan mutasi.</p>
                    </div>
                </div>
            </div>
            <div class="tl-item"><div class="tl-icon">4</div><div class="tl-content"><span class="tl-title">Siap Diambil</span></div></div>

            <div class="footer-note-box">
                <h5>Informasi Tambahan</h5>
                <ul><li>Jika verifikasi gagal, mohon upload ulang bukti transfer yang jelas.</li></ul>
            </div>
        </div>
    </div>


    <div class="order-card theme-green">
        <div class="card-header-top">
            <div class="car-title">
                <h3>Honda CR-V 2023</h3>
                <span class="booking-id">Booking ID: RMX-203145</span>
            </div>
            <div class="status-badge">
                <i class="fas fa-car-side"></i> Sewa Berjalan
            </div>
        </div>

        <div class="card-content-split">
            <div class="car-image-wrapper">
                <img src="https://img.freepik.com/free-photo/black-car-road_1150-18151.jpg" alt="Honda CR-V">
            </div>
            <div class="car-info-wrapper">
                <div class="status-alert-box">
                    <i class="fas fa-check-circle"></i>
                    <div class="alert-text">
                        <h4>Status Terkini</h4>
                        <p>Mobil sedang Anda gunakan. Selamat menikmati perjalanan!</p>
                    </div>
                </div>
                <div class="details-grid">
                    <div class="detail-item"><i class="far fa-calendar-alt detail-icon"></i><div class="detail-text"><label>Tanggal Sewa</label><span>11 Des 2025 - 13 Des</span></div></div>
                    <div class="detail-item"><i class="far fa-clock detail-icon"></i><div class="detail-text"><label>Waktu</label><span>09:00 - 18:00</span></div></div>
                    <div class="detail-item"><i class="fas fa-map-marker-alt detail-icon"></i><div class="detail-text"><label>Lokasi</label><span>Jl. Gatot Subroto No. 45</span></div></div>
                    <div class="detail-item"><i class="fas fa-user-tie detail-icon"></i><div class="detail-text"><label>Paket</label><span>Dengan Driver</span></div></div>
                </div>
                <div class="card-footer-action">
                    <div class="price-section"><label>Total Pembayaran</label><div class="price-value">Rp 1.800.000</div></div>
                    <div class="btn-group">
                        <button class="btn btn-outline">Hubungi Admin</button>
                        <button class="btn btn-toggle" onclick="toggleTimeline(this)">Lihat Proses</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="process-timeline">
            <div class="timeline-header">Proses Pesanan</div>
            <div class="tl-item completed">
                <div class="tl-line"></div><div class="tl-icon"><i class="fas fa-check"></i></div>
                <div class="tl-content"><div class="tl-head"><span class="tl-title">Booking Dibuat</span></div></div>
            </div>
            <div class="tl-item completed">
                <div class="tl-line"></div><div class="tl-icon"><i class="fas fa-check"></i></div>
                <div class="tl-content"><div class="tl-head"><span class="tl-title">Pembayaran Diterima</span></div></div>
            </div>
            <div class="tl-item completed">
                <div class="tl-line"></div><div class="tl-icon"><i class="fas fa-check"></i></div>
                <div class="tl-content"><div class="tl-head"><span class="tl-title">Siap Diambil</span></div></div>
            </div>
            <div class="tl-item active">
                <div class="tl-line"></div>
                <div class="tl-icon" style="background:var(--green); color:#fff;"><i class="fas fa-car-side"></i></div>
                <div class="tl-content">
                    <div class="tl-head"><span class="tl-title">Sewa Berjalan</span></div>
                    <div class="tl-info-box" style="background:#E6FCF5; border-color:#63E6BE;">
                        <h5 style="color:#0CA678;"><i class="fas fa-smile"></i> Hati-hati di Jalan</h5>
                        <p>Jika terjadi kendala darurat, segera hubungi Call Center di menu Bantuan.</p>
                    </div>
                </div>
            </div>
            <div class="tl-item"><div class="tl-icon">5</div><div class="tl-content"><span class="tl-title">Pengembalian</span></div></div>

            <div class="footer-note-box">
                <h5>Informasi Tambahan</h5>
                <ul><li>Harap mengembalikan mobil tepat waktu untuk menghindari denda keterlambatan.</li></ul>
            </div>
        </div>
    </div>

</main>
@endsection

@section('script')
<script>
    function toggleTimeline(btn) {
        // Cari elemen parent card
        const card = btn.closest('.order-card');
        const timeline = card.querySelector('.process-timeline');

        // Toggle class show
        if (timeline.classList.contains('show')) {
            timeline.classList.remove('show');
            btn.innerText = "Lihat Proses";
            btn.classList.remove('active'); // Hapus class active (kembali ke kuning)
        } else {
            timeline.classList.add('show');
            btn.innerText = "Tutup Proses";
            btn.classList.add('active'); // Tambah class active (jadi gelap)
        }
    }
</script>
@endsection
