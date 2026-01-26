@extends('index.layouts.index')
@section('title', 'Layanan Kami - Bonanza Rental')

@section('style')
<style>
    /* === 1. GLOBAL STYLE === */
    :root {
        --primary: #FFC400;       /* Warna Emas Utama */
        --primary-hover: #e0ac00;
        --dark-bg: #111;          /* Hitam Pekat Header/Footer */
        --card-bg: #fff;
        --text-dark: #333;
        --text-gray: #666;
    }

    body {
        background-color: #f8f9fa;
        font-family: 'Poppins', sans-serif;
        color: var(--text-dark);
    }

    /* === 2. HERO SECTION (BANNER ATAS) === */
    .service-hero {
        background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?q=80&w=1470&auto=format&fit=crop');
        background-size: cover;
        background-position: center;
        height: 350px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        color: #fff;
        padding: 20px;
    }

    .service-hero h1 {
        font-size: 2.8rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .service-hero p {
        font-size: 1.1rem;
        max-width: 600px;
        line-height: 1.6;
        color: #ddd;
    }

    /* === 3. CONTAINER UTAMA === */
    .container-service {
        max-width: 1200px;
        margin: 0 auto;
        padding: 60px 20px;
    }

    .section-title {
        text-align: center;
        margin-bottom: 50px;
    }
    .section-title h2 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 10px;
    }
    .section-title .divider {
        width: 80px;
        height: 4px;
        background: var(--primary);
        margin: 0 auto;
        border-radius: 2px;
    }

    /* === 4. GRID LAYANAN (CARD) === */
    .service-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); /* Responsif 4 Kolom -> 2 -> 1 */
        gap: 30px;
    }

    .service-card {
        background: var(--card-bg);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid #eee;
        display: flex;
        flex-direction: column;
        height: 100%; /* Agar tinggi card sama rata */
    }

    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        border-color: var(--primary);
    }

    .card-img-top {
        height: 200px;
        width: 100%;
        object-fit: cover;
    }

    .card-body {
        padding: 25px;
        flex-grow: 1; /* Isi sisa ruang agar footer tombol di bawah rata */
        text-align: center;
    }

    .icon-wrapper {
        width: 70px;
        height: 70px;
        background: var(--dark-bg);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: -60px auto 20px; /* Posisi Overlap ke atas gambar */
        border: 4px solid #fff;
        color: var(--primary);
        font-size: 1.8rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .card-title {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: var(--text-dark);
    }

    .card-text {
        font-size: 0.95rem;
        color: var(--text-gray);
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .feature-list {
        list-style: none;
        padding: 0;
        margin-bottom: 25px;
        text-align: left;
        font-size: 0.9rem;
        color: #555;
    }
    .feature-list li {
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .feature-list li i {
        color: var(--primary);
    }

    .btn-service {
        display: inline-block;
        padding: 10px 30px;
        background: var(--primary);
        color: #000;
        font-weight: 600;
        text-decoration: none;
        border-radius: 50px;
        transition: 0.3s;
        border: 2px solid var(--primary);
    }
    .btn-service:hover {
        background: transparent;
        color: var(--primary);
        border-color: var(--primary);
    }

    /* === 5. CTA SECTION (BAWAH) === */
    .cta-section {
        background: var(--dark-bg);
        color: #fff;
        padding: 60px 20px;
        text-align: center;
        margin-top: 50px;
    }
    .cta-section h3 {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--primary);
    }
    .cta-section p {
        color: #ccc;
        margin-bottom: 30px;
    }
    .btn-whatsapp {
        background: #25D366;
        color: #fff;
        padding: 12px 35px;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-size: 1rem;
        transition: 0.3s;
    }
    .btn-whatsapp:hover {
        background: #1ebc57;
        transform: scale(1.05);
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .service-hero h1 { font-size: 2rem; }
        .service-hero { height: 300px; }
        .service-grid { grid-template-columns: 1fr; } /* Stack 1 kolom di HP */
    }
</style>
@endsection

@section('content')

    {{-- HERO BANNER --}}
    <div class="service-hero">
        <h1>Layanan Kami</h1>
        <p>Pilihan solusi transportasi terbaik untuk kebutuhan pribadi, bisnis, hingga liburan keluarga Anda di Bandung.</p>
    </div>

    {{-- CONTENT UTAMA --}}
    <div class="container-service">

        <div class="section-title">
            <h2>Solusi Perjalanan Anda</h2>
            <div class="divider"></div>
        </div>

        <div class="service-grid">

            {{-- 1. RENTAL HARIAN --}}
            <div class="service-card">
                <img src="https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?q=80&w=1470&auto=format&fit=crop" class="card-img-top" alt="Rental Harian">
                <div class="card-body">
                    <div class="icon-wrapper"><i class="fas fa-car-side"></i></div>
                    <h3 class="card-title">Rental Harian</h3>
                    <p class="card-text">Sewa mobil lepas kunci (self-drive) untuk kebebasan perjalanan Anda. Pilihan unit beragam dan kondisi prima.</p>

                    <ul class="feature-list">
                        <li><i class="fas fa-check-circle"></i> Durasi 12 / 24 Jam</li>
                        <li><i class="fas fa-check-circle"></i> Unit Terbaru & Bersih</li>
                        <li><i class="fas fa-check-circle"></i> Syarat Mudah & Cepat</li>
                    </ul>

                    <a href="{{ route('index_mobil') }}" class="btn-service">Pilih Mobil</a>
                </div>
            </div>

            {{-- 2. RENTAL + DRIVER --}}
            <div class="service-card">
                <img src="https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?q=80&w=1470&auto=format&fit=crop" class="card-img-top" alt="Rental Plus Driver">
                <div class="card-body">
                    <div class="icon-wrapper"><i class="fas fa-user-tie"></i></div>
                    <h3 class="card-title">Rental + Driver</h3>
                    <p class="card-text">Nikmati perjalanan santai tanpa lelah menyetir. Driver profesional kami siap mengantar ke tujuan Anda.</p>

                    <ul class="feature-list">
                        <li><i class="fas fa-check-circle"></i> Driver Berpengalaman</li>
                        <li><i class="fas fa-check-circle"></i> Tahu Rute Jalan</li>
                        <li><i class="fas fa-check-circle"></i> Ramah & Sopan</li>
                    </ul>

                    <a href="https://wa.me/6288999988909?text=Halo%20Admin,%20saya%20tertarik%20sewa%20mobil%20dengan%20driver" class="btn-service">Hubungi Kami</a>
                </div>
            </div>

            {{-- 3. RENTAL BULANAN --}}
            <div class="service-card">
                <img src="https://images.unsplash.com/photo-1560179707-f14e90ef3dab?q=80&w=1375&auto=format&fit=crop" class="card-img-top" alt="Rental Bulanan">
                <div class="card-body">
                    <div class="icon-wrapper"><i class="fas fa-building"></i></div>
                    <h3 class="card-title">Rental Corporate</h3>
                    <p class="card-text">Solusi kendaraan operasional perusahaan atau sewa jangka panjang (bulanan/tahunan) dengan harga spesial.</p>

                    <ul class="feature-list">
                        <li><i class="fas fa-check-circle"></i> Harga Kompetitif</li>
                        <li><i class="fas fa-check-circle"></i> Maintenance Rutin</li>
                        <li><i class="fas fa-check-circle"></i> Mobil Pengganti Ready</li>
                    </ul>

                    <a href="https://wa.me/6288999988909?text=Halo%20Admin,%20saya%20ingin%20tanya%20paket%20rental%20bulanan" class="btn-service">Info Penawaran</a>
                </div>
            </div>

            {{-- 4. PAKET WISATA --}}
            <div class="service-card">
                <img src="https://images.unsplash.com/photo-1596423735880-bf8787523dd5?q=80&w=1374&auto=format&fit=crop" class="card-img-top" alt="Paket Wisata">
                <div class="card-body">
                    <div class="icon-wrapper"><i class="fas fa-map-marked-alt"></i></div>
                    <h3 class="card-title">Paket Wisata</h3>
                    <p class="card-text">Jelajahi keindahan Bandung tanpa repot. Paket All-in (Mobil + BBM + Driver) ke destinasi favorit.</p>

                    <ul class="feature-list">
                        <li><i class="fas fa-check-circle"></i> City Tour Bandung</li>
                        <li><i class="fas fa-check-circle"></i> Wisata Lembang / Ciwidey</li>
                        <li><i class="fas fa-check-circle"></i> Drop Bandara / Hotel</li>
                    </ul>

                    <a href="https://wa.me/6288999988909?text=Halo%20Admin,%20saya%20mau%20booking%20paket%20wisata" class="btn-service">Booking Paket</a>
                </div>
            </div>

        </div>
    </div>

@endsection
