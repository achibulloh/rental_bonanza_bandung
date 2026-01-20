@extends('index.layouts.index')
@section('title', 'Detail Mobil - ')
@section('style')
    <style>
        :root {
            --primary: #FFC400;
            --dark-bg: #111111;
            --text-dark: #333333;
            --text-gray: #666666;
            --text-light: #999999;
            --white: #ffffff;
            --success: #2ecc71;
            --danger: #e74c3c;
            --bg-page: #f9f9f9;
        }

        /* --- MAIN CONTENT CARD --- */
        .detail-card {
            background: var(--white);
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            padding: 30px;
            margin-top: 30px;
            overflow: hidden;
        }

        /* --- GALLERY SECTION --- */
        .gallery-container { margin-bottom: 30px; position: relative; }
        .main-image {
            width: 100%;
            height: 400px;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            background: #eee;
        }
        .main-image img { width: 100%; height: 100%; object-fit: cover; }

        /* Container khusus untuk Map agar responsif */
        .map-responsive {
            width: 100%;
            height: 350px; /* Tinggi peta yang pas */
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 25px;
            border: 1px solid #e0e0e0;
        }

        .map-responsive iframe {
            width: 100%;
            height: 100%;
            border: 0;
        }

        /* Mempercantik detail lokasi di bawah peta */
        .loc-details div {
            display: flex;
            align-items: flex-start; /* Icon sejajar dengan baris pertama teks */
            gap: 15px;
            margin-bottom: 15px;
            font-size: 0.95rem;
            color: var(--text-dark);
            line-height: 1.5;
        }

        .loc-details i {
            color: var(--primary);
            font-size: 1.2rem;
            margin-top: 3px; /* Sedikit turun agar pas dengan huruf */
            width: 20px;
            text-align: center;
        }

        /* Navigation Arrows */
        .nav-arrow {
            position: absolute; top: 50%; transform: translateY(-50%);
            width: 40px; height: 40px; background: rgba(255,255,255,0.8);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: 0.3s; font-size: 1.2rem; color: #333;
        }

        .nav-arrow:hover { background: #fff; }
        .nav-prev { left: 15px; }
        .nav-next { right: 15px; }
        .slide-counter { position: absolute; bottom: 15px; right: 15px; background: rgba(0,0,0,0.6); color: #fff; padding: 4px 10px; border-radius: 12px; font-size: 0.8rem; }

        .thumbnails { display: flex; gap: 10px; margin-top: 15px; }
        .thumb {
            width: 80px; height: 60px; border-radius: 8px; overflow: hidden; cursor: pointer; opacity: 0.6; transition: 0.3s; border: 2px solid transparent;
        }
        .thumb.active { opacity: 1; border-color: var(--primary); }
        .thumb img { width: 100%; height: 100%; object-fit: cover; }

        /* --- HEADER INFO --- */
        .car-header-info { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 20px; }
        .car-title h1 { font-size: 1.8rem; margin-bottom: 5px; }
        .car-subtitle { color: var(--text-gray); font-size: 0.9rem; }
        .car-provider { font-size: 0.8rem; color: #aaa; margin-top: 5px; display: block; }

        .rating-badge {
            background: var(--primary); color: var(--dark-bg);
            padding: 5px 12px; border-radius: 20px; font-weight: 700; font-size: 0.9rem;
            display: flex; align-items: center; gap: 5px;
        }

        /* --- QUICK SPECS (Icons) --- */
        .quick-specs { display: flex; gap: 15px; flex-wrap: wrap; margin-bottom: 30px; }
        .spec-box {
            background: #f8f9fa; border-radius: 8px; padding: 15px;
            display: flex; align-items: center; gap: 12px; flex: 1; min-width: 140px;
        }
        .spec-icon {
            width: 40px; height: 40px; background: #fff; border-radius: 8px;
            display: flex; align-items: center; justify-content: center; color: var(--text-dark); font-size: 1.2rem; box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .spec-text span { display: block; font-size: 0.75rem; color: var(--text-light); }
        .spec-text strong { display: block; font-size: 0.9rem; color: var(--text-dark); }

        /* --- FEATURES GRID --- */
        .section-title { font-size: 1.2rem; font-weight: 600; margin-bottom: 15px; margin-top: 30px; }
        .features-grid {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px;
            padding: 20px; background: #fff; border: 1px solid #f0f0f0; border-radius: 12px;
        }
        .feature-item { display: flex; align-items: center; gap: 10px; font-size: 0.9rem; color: var(--text-gray); }
        .feature-item i { color: var(--success); }

        /* --- SPECIFICATION TABLE --- */
        .specs-table { width: 100%; border-collapse: separate; border-spacing: 0; font-size: 0.9rem; }
        .specs-table td { padding: 12px 0; border-bottom: 1px solid #f9f9f9; }
        .specs-table .label { color: var(--text-gray); width: 30%; }
        .specs-table .value { font-weight: 600; color: var(--text-dark); width: 20%; }
        /* Trik Layout 2 Kolom dalam Tabel */
        .specs-row { display: flex; flex-wrap: wrap; }
        .specs-col { width: 50%; padding-right: 20px; }

        /* --- POLICY SECTIONS --- */
        .policy-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 30px; }
        .policy-col h4 { font-size: 1rem; margin-bottom: 15px; }
        .text-success { color: var(--success); }
        .text-danger { color: var(--danger); }

        .policy-list { list-style: none; }
        .policy-list li { display: flex; align-items: flex-start; gap: 10px; margin-bottom: 10px; font-size: 0.9rem; color: var(--text-gray); }
        .policy-list li i { margin-top: 4px; }

        /* --- IMPORTANT INFO --- */
        .info-list { list-style: none; padding-left: 0; }
        .info-list li { position: relative; padding-left: 15px; margin-bottom: 8px; font-size: 0.9rem; color: var(--text-gray); }
        .info-list li::before { content: "•"; color: var(--primary); font-weight: bold; position: absolute; left: 0; }

        .info-sub-title { font-size: 0.95rem; font-weight: 600; margin-top: 15px; margin-bottom: 8px; display: block; color: var(--text-dark); }

        /* --- LOCATION BOX --- */
        .location-box { background: #f8f9fa; border-radius: 12px; padding: 20px; margin-top: 20px; }
        .map-placeholder { width: 100%; height: 200px; background: #e0e0e0; border-radius: 8px; margin-bottom: 15px; display: flex; align-items: center; justify-content: center; color: #888; }
        .loc-details div { display: flex; gap: 10px; margin-bottom: 10px; font-size: 0.9rem; color: var(--text-gray); }
        .loc-details i { color: var(--primary); margin-top: 3px; }

        /* --- BOTTOM ALERT --- */
        .bottom-alert {
            background: #eef7ff; border: 1px solid #cce5ff; color: #004085;
            padding: 15px; border-radius: 8px; font-size: 0.85rem; margin-top: 30px; line-height: 1.5;
        }

        /* --- STICKY FOOTER (MOBILE ONLY) --- */
        .mobile-footer {
            display: none;
            position: fixed; bottom: 0; left: 0; width: 100%;
            background: #fff; padding: 15px 20px;
            box-shadow: 0 -5px 15px rgba(0,0,0,0.1);
            z-index: 999;
            justify-content: space-between; align-items: center;
        }
        .price-total { font-size: 1.1rem; font-weight: 700; color: var(--text-dark); }
        .price-total span { font-size: 0.8rem; font-weight: 400; color: var(--text-gray); }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .mobile-menu-btn { display: block; }
            .nav-links, .btn-login { display: none; }

            .car-header-info { flex-direction: column; gap: 10px; }
            .policy-grid { grid-template-columns: 1fr; gap: 20px; }
            .specs-col { width: 100%; }
            .features-grid { grid-template-columns: 1fr 1fr; }

            /* Tampilkan Sticky Footer di HP */
            body { padding-bottom: 80px; }
            .mobile-footer { display: flex; }
        }
    </style>
@endsection
@section('content')
    <div class="container">

        <div class="detail-card">

            <div class="gallery-container">
                <div class="main-image">
                    <img src="https://images.unsplash.com/photo-1555215695-3004980adade?auto=format&fit=crop&w=1000&q=80" id="currentImg" alt="BMW M2">
                    <div class="nav-arrow nav-prev"><i class="fas fa-chevron-left"></i></div>
                    <div class="nav-arrow nav-next"><i class="fas fa-chevron-right"></i></div>
                    <span class="slide-counter">1 / 4</span>
                </div>
                <div class="thumbnails">
                    <div class="thumb active" onclick="changeImage(this, 'https://images.unsplash.com/photo-1555215695-3004980adade?auto=format&fit=crop&w=1000&q=80')">
                        <img src="https://images.unsplash.com/photo-1555215695-3004980adade?auto=format&fit=crop&w=200&q=80">
                    </div>
                    <div class="thumb" onclick="changeImage(this, 'https://images.unsplash.com/photo-1617788138017-80ad40651399?auto=format&fit=crop&w=1000&q=80')">
                        <img src="https://images.unsplash.com/photo-1617788138017-80ad40651399?auto=format&fit=crop&w=200&q=80">
                    </div>
                    <div class="thumb" onclick="changeImage(this, 'https://images.unsplash.com/photo-1503376763036-066120622c74?auto=format&fit=crop&w=1000&q=80')">
                        <img src="https://images.unsplash.com/photo-1503376763036-066120622c74?auto=format&fit=crop&w=200&q=80">
                    </div>
                    <div class="thumb" onclick="changeImage(this, 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&w=1000&q=80')">
                        <img src="https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&w=200&q=80">
                    </div>
                </div>
            </div>

            <div class="car-header-info">
                <div class="car-title">
                    <h1>BMW M2</h1>
                    <span class="car-subtitle">Sport Car - Premium</span>
                    <span class="car-provider">Disediakan oleh Bonanza Rental Mobil</span>
                </div>
                <div class="rating-badge">
                    <i class="fas fa-star"></i> 5.0
                </div>
            </div>

            <div class="quick-specs">
                <div class="spec-box">
                    <div class="spec-icon"><i class="fas fa-user-friends"></i></div>
                    <div class="spec-text"><span>Kapasitas</span><strong>4 Penumpang</strong></div>
                </div>
                <div class="spec-box">
                    <div class="spec-icon"><i class="fas fa-suitcase"></i></div>
                    <div class="spec-text"><span>Bagasi</span><strong>2 Koper</strong></div>
                </div>
                <div class="spec-box">
                    <div class="spec-icon"><i class="far fa-calendar-alt"></i></div>
                    <div class="spec-text"><span>Tahun</span><strong>2023</strong></div>
                </div>
                <div class="spec-box">
                    <div class="spec-icon"><i class="fas fa-cogs"></i></div>
                    <div class="spec-text"><span>Transmisi</span><strong>Automatic</strong></div>
                </div>
            </div>

            <div class="section-title">Fitur & Fasilitas</div>
            <div class="features-grid">
                <div class="feature-item"><i class="fas fa-check-circle"></i> AC</div>
                <div class="feature-item"><i class="fas fa-check-circle"></i> Bluetooth Audio</div>
                <div class="feature-item"><i class="fas fa-check-circle"></i> USB Port</div>
                <div class="feature-item"><i class="fas fa-check-circle"></i> Power Steering</div>
                <div class="feature-item"><i class="fas fa-check-circle"></i> Airbags</div>
                <div class="feature-item"><i class="fas fa-check-circle"></i> ABS</div>
                <div class="feature-item"><i class="fas fa-check-circle"></i> Parking Sensor</div>
                <div class="feature-item"><i class="fas fa-check-circle"></i> Cruise Control</div>
            </div>

            <div class="section-title">Spesifikasi Mobil</div>
            <div class="specs-row">
                <div class="specs-col">
                    <table class="specs-table">
                        <tr><td class="label">Merek</td><td class="value">BMW</td></tr>
                        <tr><td class="label">Tahun</td><td class="value">2023</td></tr>
                        <tr><td class="label">Bahan Bakar</td><td class="value">Bensin</td></tr>
                        <tr><td class="label">Tenaga</td><td class="value">405 HP</td></tr>
                        <tr><td class="label">Warna</td><td class="value">Putih</td></tr>
                        <tr><td class="label">Konsumsi BBM</td><td class="value">8-10 km/l</td></tr>
                    </table>
                </div>
                <div class="specs-col">
                    <table class="specs-table">
                        <tr><td class="label">Model</td><td class="value">M2 Competition</td></tr>
                        <tr><td class="label">Transmisi</td><td class="value">Automatic 8-Speed</td></tr>
                        <tr><td class="label">Kapasitas Mesin</td><td class="value">3.0L Twin-Turbo</td></tr>
                        <tr><td class="label">Kapasitas Penumpang</td><td class="value">4 Orang</td></tr>
                        <tr><td class="label">Plat Nomor</td><td class="value">B 1234 ABC</td></tr>
                        <tr><td class="label">Jenis</td><td class="value">Sport Car</td></tr>
                    </table>
                </div>
            </div>

            <hr style="border: 0; border-top: 1px solid #eee; margin: 30px 0;">

            <div class="section-title">Kebijakan Sewa</div>
            <div class="policy-grid">
                <div class="policy-col">
                    <h4 class="text-success">Termasuk</h4>
                    <ul class="policy-list">
                        <li><i class="fas fa-check-circle text-success"></i> Asuransi dasar kendaraan</li>
                        <li><i class="fas fa-check-circle text-success"></i> Supir profesional (opsional)</li>
                        <li><i class="fas fa-check-circle text-success"></i> BBM untuk 100 km pertama</li>
                        <li><i class="fas fa-check-circle text-success"></i> Pembatalan gratis hingga 24 jam sebelum</li>
                        <li><i class="fas fa-check-circle text-success"></i> Antar-jemput area Jakarta</li>
                    </ul>
                </div>
                <div class="policy-col">
                    <h4 class="text-danger">Tidak Termasuk</h4>
                    <ul class="policy-list">
                        <li><i class="fas fa-times-circle text-danger"></i> BBM setelah 100 km</li>
                        <li><i class="fas fa-times-circle text-danger"></i> Biaya parkir dan tol</li>
                        <li><i class="fas fa-times-circle text-danger"></i> Biaya supir jika menginap</li>
                        <li><i class="fas fa-times-circle text-danger"></i> Denda pelanggaran lalu lintas</li>
                        <li><i class="fas fa-times-circle text-danger"></i> Kerusakan akibat kelalaian penyewa</li>
                    </ul>
                </div>
            </div>

            <div class="section-title"><i class="fas fa-info-circle" style="color:#f39c12"></i> Informasi Penting</div>

            <span class="info-sub-title">Persyaratan Penyewa</span>
            <ul class="info-list">
                <li>Minimal berusia 21 tahun</li>
                <li>Memiliki SIM A yang masih berlaku</li>
                <li>Menyerahkan KTP asli sebagai jaminan</li>
                <li>Mengisi formulir sewa dan kontrak</li>
            </ul>

            <span class="info-sub-title">Waktu Pengambilan & Pengembalian</span>
            <ul class="info-list">
                <li>Jam operasional: 08.00 - 20.00 WIB</li>
                <li>Pengambilan di luar jam dapat diatur dengan biaya tambahan</li>
                <li>Keterlambatan pengembalian dikenakan charge 10% per jam</li>
                <li>Mobil harus dikembalikan dalam kondisi bersih</li>
            </ul>

            <span class="info-sub-title">Pembatalan & Perubahan</span>
            <ul class="info-list">
                <li>Pembatalan gratis hingga 24 jam sebelum waktu pengambilan</li>
                <li>Pembatalan kurang dari 24 jam dikenakan biaya 50%</li>
                <li>Perubahan tanggal dapat dilakukan tanpa biaya (tergantung ketersediaan)</li>
                <li>Uang deposit akan dikembalikan dalam 3-5 hari kerja</li>
            </ul>

            <div class="section-title">Lokasi Pengambilan & Pengembalian</div>
            <div class="location-box">
                <div class="map-responsive">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d224.01685150131496!2d107.62219061774135!3d-6.920835282976859!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e7e5f58b1b77%3A0x50d3e7aa7e72b576!2sBonanza%20Sewa%20Mobil%20Bandung!5e1!3m2!1sid!2sid!4v1765437050138!5m2!1sid!2sid"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>

                <div class="loc-details">
                    <div>
                        <i class="fas fa-map-marker-alt"></i>
                        <span>
                            <strong>Bonanza Rental Mobil - Kantor Pusat</strong><br>
                            Jl. Murtamad No.22, Malabar, Kec. Lengkong, Kota Bandung, Jawa Barat 40262
                        </span>
                    </div>
                    <div>
                        <i class="far fa-clock"></i>
                        <span>
                            <strong>Jam Operasional</strong><br>
                            Senin - Minggu: 08.00 - 20.00 WIB
                        </span>
                    </div>
                    <div>
                        <i class="fas fa-phone-alt"></i>
                        <span>
                            <strong>Kontak Center</strong><br>
                            +62 889-9998-8909 (WhatsApp Tersedia)
                        </span>
                    </div>
                </div>
            </div>

            <div class="bottom-alert">
                <strong>Catatan:</strong> Layanan antar jemput gratis tersedia untuk area Jakarta Pusat dan Jakarta Selatan. Untuk area lain, biaya antar-jemput dapat dikenakan sesuai jarak.
            </div>

        </div>
    </div>

    <div class="mobile-footer">
        <div class="price-total">Rp 850.000 <span>/hari</span></div>
        <button style="background:var(--primary); border:none; padding:10px 25px; border-radius:8px; font-weight:600; cursor:pointer;">Sewa Sekarang</button>
    </div>
@endsection
@section('scripts')
    <script>
        function changeImage(element, src) {
            // Ganti Gambar Utama
            document.getElementById('currentImg').src = src;

            // Atur Class Active
            const thumbnails = document.querySelectorAll('.thumb');
            thumbnails.forEach(thumb => thumb.classList.remove('active'));
            element.classList.add('active');
        }
    </script>
@endsection
