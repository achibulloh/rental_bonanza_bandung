@extends('dashboard.layouts.index')
@section('title', 'Pesanan Aktif')

@section('style')
    <style>
        /* --- VARIABLES & GLOBAL --- */
        :root {
            --primary: #FFC400;
            --text-dark: #333;
            --text-gray: #777;
            --bg-light: #f8f9fa;
            --sidebar-width: 280px;
            /* Status Colors */
            --green: #22C55E;
            --yellow: #F59F00;
            --blue: #1C7ED6;
            --grey: #e9ecef;
            --grey-text: #adb5bd;
        }

        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 30px 40px;
            transition: 0.3s;
            font-family: 'Poppins', sans-serif;
        }

        .mobile-header { display: none; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .hamburger { font-size: 1.5rem; cursor: pointer; color: var(--text-dark); }

        /* --- HEADER STATS --- */
        .stats-banner {
            background-color: var(--primary);
            border-radius: 12px;
            padding: 20px 30px;
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(255, 196, 0, 0.15);
        }
        .stats-banner h2 { font-size: 15px; margin-bottom: 5px; font-weight: 500; color: #000; }
        .stats-banner .count { font-size: 36px; font-weight: 700; color: #000; line-height: 1; }
        .stats-banner i { font-size: 28px; color: #000; opacity: 0.7; }

        /* --- ORDER CARD --- */
        .order-card {
            background: #fff;
            border-radius: 16px;
            margin-bottom: 30px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.03);
            border: 1px solid #eee;
            overflow: hidden;
            position: relative;
            border-top: 6px solid var(--primary);
        }

        /* HEADER CARD */
        .card-header-top {
            padding: 25px 30px 10px;
            display: flex; justify-content: space-between; align-items: flex-start;
        }
        .car-title h3 { font-size: 18px; font-weight: 700; color: var(--text-dark); margin-bottom: 4px; }
        .booking-id { font-size: 13px; color: var(--text-gray); }
        .status-badge {
            padding: 8px 16px; border-radius: 30px; font-size: 12px; font-weight: 600;
            display: inline-flex; align-items: center; gap: 6px;
        }

        /* CONTENT SPLIT (Image & Info) */
        .card-content-split {
            display: flex; gap: 30px; padding: 15px 30px 30px;
        }
        .car-image-wrapper {
            width: 320px; height: 200px; border-radius: 12px; overflow: hidden; background: #f0f0f0; flex-shrink: 0;
        }
        .car-image-wrapper img { width: 100%; height: 100%; object-fit: cover; }
        .car-info-wrapper { flex: 1; display: flex; flex-direction: column; justify-content: space-between; }

        /* Status Box inside Card */
        .status-alert-box {
            padding: 15px 20px; border-radius: 8px; margin-bottom: 20px;
            display: flex; align-items: flex-start; gap: 12px;
        }
        .status-alert-box i { font-size: 18px; margin-top: 3px; }
        .alert-text h4 { font-size: 14px; font-weight: 600; margin-bottom: 3px; }
        .alert-text p { font-size: 13px; margin: 0; color: #555; }

        /* Details Grid */
        .details-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;
        }
        .detail-item { display: flex; gap: 12px; }
        .detail-icon { color: #aaa; font-size: 16px; margin-top: 2px; width: 20px; }
        .detail-text label { display: block; font-size: 11px; color: #999; margin-bottom: 2px; }
        .detail-text span { display: block; font-size: 14px; font-weight: 600; color: var(--text-dark); }

        /* Footer Action */
        .card-footer-action {
            display: flex; justify-content: space-between; align-items: flex-end;
            border-top: 1px solid #f5f5f5; padding-top: 20px;
        }
        .price-value { font-size: 20px; font-weight: 700; color: var(--primary); }
        .btn {
            padding: 10px 20px; border-radius: 8px; font-size: 13px; font-weight: 600;
            cursor: pointer; border: 1px solid transparent; transition: 0.2s;
        }
        .btn-outline { background: #fff; border-color: #ddd; color: #666; }
        .btn-primary { background: var(--primary); color: #000; }

        /* --- TIMELINE SECTION (MATCHING PHOTO) --- */
        .process-timeline {
            background: #fff;
            padding: 0 30px 30px 30px;
            border-top: 1px solid #eee;
            display: none; /* Hidden Default */
        }
        .process-timeline.active { display: block; animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        .timeline-header {
            font-size: 14px; font-weight: 700; margin: 25px 0 20px; color: #333;
        }

        /* Timeline Items */
        .tl-item {
            display: flex; gap: 20px; position: relative; padding-bottom: 30px;
        }
        .tl-item:last-child { padding-bottom: 0; }

        /* Vertical Line */
        .tl-line {
            position: absolute; left: 12px; top: 30px; bottom: 0;
            width: 2px; background: #e9ecef; z-index: 1;
        }
        .tl-item:last-child .tl-line { display: none; }
        /* Green Line for completed steps */
        .tl-item.completed .tl-line { background: var(--green); }

        /* Icons */
        .tl-icon {
            width: 26px; height: 26px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; z-index: 2; flex-shrink: 0;
            background: #e9ecef; color: #adb5bd; font-weight: 600;
            border: 2px solid #fff;
        }

        /* Icon Styles based on state */
        .tl-item.completed .tl-icon {
            background: var(--green); color: #fff;
        }
        .tl-item.active .tl-icon {
            background: var(--primary); color: #000;
            box-shadow: 0 0 0 4px rgba(255, 196, 0, 0.2);
        }

        /* Content */
        .tl-content { flex: 1; }
        .tl-title { font-size: 14px; font-weight: 600; color: #333; margin-bottom: 4px; display: flex; justify-content: space-between; }
        .tl-desc { font-size: 12px; color: #777; margin: 0; }
        .tl-date { font-size: 11px; color: #aaa; font-weight: normal; }

        /* ACTIVE STEP BOX (Blue/Yellow Box inside timeline) */
        .tl-active-box {
            background: #F0F9FF; /* Light Blue default */
            border: 1px solid #BAE6FD;
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
        }
        .tl-active-box h5 { margin: 0 0 5px 0; font-size: 13px; color: #0284C7; display: flex; align-items: center; gap: 6px; }
        .tl-active-box p { margin: 0; font-size: 12px; color: #555; line-height: 1.5; }

        /* Info Tambahan Footer */
        .timeline-footer-info {
            margin-top: 30px;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
        }
        .timeline-footer-info h5 { margin: 0 0 10px 0; font-size: 13px; font-weight: 700; }
        .timeline-footer-info ul { padding-left: 20px; margin: 0; }
        .timeline-footer-info li { font-size: 11px; color: #666; margin-bottom: 5px; }

        /* Responsive */
        @media (max-width: 992px) {
            .main-content { margin-left: 0; padding: 20px; }
            .card-content-split { flex-direction: column; }
            .car-image-wrapper { width: 100%; height: 180px; }
            .card-footer-action { flex-direction: column; align-items: flex-start; gap: 15px; }
            .btn { width: 100%; }
            .mobile-header { display: flex; }
        }
    </style>
@endsection

@section('content')
<main class="main-content">

    <div class="mobile-header">
        <a href="#" style="color:var(--text-dark); font-weight:700; text-decoration:none;">Bonanza Rental</a>
        <div class="hamburger" onclick="toggleSidebar()"><i class="fas fa-bars"></i></div>
    </div>

    <h1 style="font-size: 24px; font-weight: 600; margin-bottom: 25px;">Pesanan Aktif</h1>

    <div class="stats-banner">
        <div>
            <h2>Total Pesanan Aktif</h2>
            <div class="count">3</div>
        </div>
        <i class="far fa-clock"></i>
    </div>

    <div class="order-card" style="border-top-color: var(--yellow);">
        <div class="card-header-top">
            <div class="car-title">
                <h3>Toyota Avanza 2022</h3>
                <span class="booking-id">Booking ID: RMX-203392</span>
            </div>
            <div class="status-badge" style="background:#FFF9DB; color:#F59F00;">
                <i class="far fa-clock"></i> Menunggu Konfirmasi
            </div>
        </div>

        <div class="card-content-split">
            <div class="car-image-wrapper">
                <img src="https://img.freepik.com/free-photo/white-off-roader-jeep-parking_114579-4007.jpg" alt="Toyota Avanza">
            </div>

            <div class="car-info-wrapper">
                <div class="status-alert-box" style="background:#FFF9DB;">
                    <i class="fas fa-spinner fa-spin" style="color:#F59F00;"></i>
                    <div class="alert-text">
                        <h4 style="color:#F59F00;">Status Terkini</h4>
                        <p>Admin sedang mengecek ketersediaan mobil.</p>
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
                    <div>
                        <span style="font-size:11px; color:#888;">Total Pembayaran</span>
                        <div class="price-value" style="color:#F59F00;">Rp 1.400.000</div>
                    </div>
                    <div style="display:flex; gap:10px;">
                        <button class="btn btn-outline">Hubungi Admin</button>
                        <button class="btn" style="background:var(--yellow); color:#fff;" onclick="toggleTimeline(this)">Lihat Proses</button>
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
                    <div class="tl-title">Booking Dibuat <span class="tl-date">11 Des 2025, 14:30</span></div>
                    <p class="tl-desc">Pesanan Anda telah berhasil dibuat</p>
                </div>
            </div>

            <div class="tl-item active">
                <div class="tl-line"></div>
                <div class="tl-icon"><i class="fas fa-clock"></i></div>
                <div class="tl-content">
                    <div class="tl-title" style="color:#000;">Menunggu Konfirmasi Admin <span class="tl-date" style="color:#F59F00;">Sedang diproses</span></div>
                    <p class="tl-desc">Admin sedang mengecek ketersediaan mobil</p>

                    <div class="tl-active-box" style="background:#F0F9FF; border-color:#BAE6FD;">
                        <h5><i class="fas fa-shield-alt"></i> Menunggu Konfirmasi</h5>
                        <p>Admin akan mengkonfirmasi dalam 1-2 jam. Anda akan mendapat notifikasi.</p>
                    </div>
                </div>
            </div>

            <div class="tl-item">
                <div class="tl-line"></div>
                <div class="tl-icon">3</div>
                <div class="tl-content">
                    <div class="tl-title">Pembayaran</div>
                    <p class="tl-desc">Lakukan pembayaran dan upload bukti</p>
                </div>
            </div>

            <div class="tl-item">
                <div class="tl-line"></div>
                <div class="tl-icon">4</div>
                <div class="tl-content">
                    <div class="tl-title">Verifikasi Pembayaran</div>
                    <p class="tl-desc">Admin memverifikasi pembayaran Anda</p>
                </div>
            </div>

            <div class="tl-item">
                <div class="tl-line"></div>
                <div class="tl-icon">5</div>
                <div class="tl-content">
                    <div class="tl-title">Siap Diambil</div>
                    <p class="tl-desc">Mobil siap untuk diambil/diantar</p>
                </div>
            </div>

            <div class="tl-item">
                <div class="tl-line"></div>
                <div class="tl-icon">6</div>
                <div class="tl-content">
                    <div class="tl-title">Sewa Berjalan</div>
                    <p class="tl-desc">Nikmati perjalanan Anda</p>
                </div>
            </div>

            <div class="tl-item">
                <div class="tl-icon">7</div>
                <div class="tl-content">
                    <div class="tl-title">Pengembalian Mobil</div>
                    <p class="tl-desc">Kembalikan mobil sesuai jadwal</p>
                </div>
            </div>

            <div class="timeline-footer-info">
                <h5>Informasi Tambahan</h5>
                <ul>
                    <li>Pastikan dokumen yang diupload jelas dan sesuai.</li>
                    <li>Pembayaran harus dilakukan maksimal 2 jam setelah booking disetujui.</li>
                    <li>Hubungi admin jika ada pertanyaan melalui tombol "Hubungi Admin".</li>
                    <li>Mobil akan diantar/diserahkan sesuai waktu yang telah ditentukan.</li>
                </ul>
            </div>
        </div>
    </div>


    <div class="order-card" style="border-top-color: var(--green);">
        <div class="card-header-top">
            <div class="car-title">
                <h3>Honda CR-V 2023</h3>
                <span class="booking-id">Booking ID: RMX-203145</span>
            </div>
            <div class="status-badge" style="background:#E6FCF5; color:#0CA678;">
                <i class="fas fa-car-side"></i> Sewa Berjalan
            </div>
        </div>

        <div class="card-content-split">
            <div class="car-image-wrapper">
                <img src="https://img.freepik.com/free-photo/black-car-road_1150-18151.jpg" alt="Honda CR-V">
            </div>

            <div class="car-info-wrapper">
                <div class="status-alert-box" style="background:#E6FCF5;">
                    <i class="fas fa-check-circle" style="color:#0CA678;"></i>
                    <div class="alert-text">
                        <h4 style="color:#0CA678;">Status Terkini</h4>
                        <p>Mobil sedang Anda gunakan.</p>
                    </div>
                </div>

                <div class="details-grid">
                    <div class="detail-item">
                        <i class="far fa-calendar-alt detail-icon"></i>
                        <div class="detail-text"><label>Tanggal Sewa</label><span>11 Des 2025 - 13 Des</span></div>
                    </div>
                    <div class="detail-item">
                        <i class="far fa-clock detail-icon"></i>
                        <div class="detail-text"><label>Waktu</label><span>09:00 - 18:00</span></div>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-map-marker-alt detail-icon"></i>
                        <div class="detail-text"><label>Lokasi</label><span>Jl. Gatot Subroto No. 45</span></div>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-user-tie detail-icon"></i>
                        <div class="detail-text"><label>Paket</label><span>Dengan Driver</span></div>
                    </div>
                </div>

                <div class="card-footer-action">
                    <div>
                        <span style="font-size:11px; color:#888;">Total Pembayaran</span>
                        <div class="price-value" style="color:var(--primary);">Rp 1.800.000</div>
                    </div>
                    <div style="display:flex; gap:10px;">
                        <button class="btn btn-outline">Hubungi Admin</button>
                        <button class="btn btn-primary" onclick="toggleTimeline(this)">Lihat Proses</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="process-timeline">
            <div class="timeline-header">Riwayat Pesanan</div>
            <div class="tl-item completed">
                <div class="tl-line"></div>
                <div class="tl-icon"><i class="fas fa-check"></i></div>
                <div class="tl-content"><div class="tl-title">Booking Dibuat</div></div>
            </div>
            <div class="tl-item completed">
                <div class="tl-line"></div>
                <div class="tl-icon"><i class="fas fa-check"></i></div>
                <div class="tl-content"><div class="tl-title">Pembayaran Diterima</div></div>
            </div>
            <div class="tl-item active">
                <div class="tl-line"></div>
                <div class="tl-icon" style="background:var(--green); color:#fff;"><i class="fas fa-car"></i></div>
                <div class="tl-content">
                    <div class="tl-title">Sewa Berjalan</div>
                    <div class="tl-active-box" style="background:#E6FCF5; border-color:#63E6BE;">
                        <h5 style="color:#0CA678;"><i class="fas fa-car"></i> Hati-hati di Jalan</h5>
                        <p>Jika butuh bantuan darurat, hubungi Call Center kami.</p>
                    </div>
                </div>
            </div>
            <div class="tl-item">
                <div class="tl-icon">4</div>
                <div class="tl-content"><div class="tl-title">Pengembalian</div></div>
            </div>
        </div>
    </div>

</main>
@endsection

@section('script')
<script>
    function toggleTimeline(btn) {
        const card = btn.closest('.order-card');
        const timeline = card.querySelector('.process-timeline');
        if (timeline.classList.contains('active')) {
            timeline.classList.remove('active');
            btn.innerText = "Lihat Proses";
            btn.style.background = ""; // Reset style
            btn.style.color = "";
        } else {
            timeline.classList.add('active');
            btn.innerText = "Tutup Proses";
            btn.style.background = "#333"; // Dark style when active
            btn.style.color = "#fff";
        }
    }
</script>
@endsection
