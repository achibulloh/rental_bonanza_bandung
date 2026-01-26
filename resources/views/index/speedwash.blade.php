@extends('index.layouts.index')
@section('title', 'Bonanza Speedwash - Cuci Mobil & Motor Bandung')
@section('style')
<style>
    /* === 1. GLOBAL VARIABLES === */
    :root {
        --primary: #FFC400;
        --dark-bg: #111;
        --card-bg: #fff;
        --text-dark: #333;
        --text-gray: #666;
        --water-blue: #00a8ff;
    }

    body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; color: var(--text-dark); }

    /* === 2. HERO SECTION === */
    .wash-hero {
        background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.8)), url('https://images.unsplash.com/photo-1601362840469-51e4d8d58785?q=80&w=1470&auto=format&fit=crop');
        background-size: cover;
        background-position: center;
        height: 480px; /* Tinggi disesuaikan untuk Mobile */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        color: #fff;
        padding: 20px;
        margin-bottom: 50px;
    }

    .wash-hero h1 {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 10px;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 2px;
        text-shadow: 2px 2px 10px rgba(0,0,0,0.5);
        line-height: 1.2;
    }

    .wash-hero p {
        font-size: 1.1rem;
        max-width: 700px;
        color: #ddd;
        margin-bottom: 30px;
    }

    /* BADGES (JAM & IG) */
    .hero-badges {
        display: flex;
        gap: 15px;
        justify-content: center;
        align-items: center; /* Pastikan vertikal tengah */
        flex-wrap: wrap;
        margin-bottom: 20px;
        width: 100%;
    }

    .status-badge, .instagram-badge {
        padding: 10px 25px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        justify-content: center; /* Text di tengah badge */
        gap: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        white-space: nowrap; /* Mencegah teks turun baris */
    }

    .status-badge { background: #25D366; color: white; }

    .instagram-badge {
        background: linear-gradient(45deg, #833ab4, #fd1d1d, #fcb045);
        color: white;
        text-decoration: none;
        transition: 0.3s;
    }
    .instagram-badge:hover { transform: translateY(-3px); color: #fff; filter: brightness(1.1); }

    /* === 3. CONTAINER === */
    .container-wash { max-width: 1100px; margin: 0 auto; padding: 0 20px 80px; }

    /* === 4. FEATURES GRID === */
    .features-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 60px;
        margin-top: -80px;
    }

    .feature-box {
        background: #fff;
        padding: 30px 20px;
        border-radius: 12px;
        text-align: center; /* Text rata tengah */
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border-bottom: 4px solid var(--primary);
        transition: 0.3s;

        /* Flexbox untuk memaksa konten tengah vertikal & horizontal */
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
    }
    .feature-box:hover { transform: translateY(-5px); }

    .feature-icon {
        font-size: 2.5rem;
        color: var(--water-blue);
        margin-bottom: 15px;
        display: block; /* Pastikan blok agar margin auto jalan */
        margin-left: auto;
        margin-right: auto;
    }
    .feature-title { font-weight: 700; font-size: 1.1rem; margin-bottom: 5px; color: var(--text-dark); }
    .feature-desc { font-size: 0.85rem; color: #777; line-height: 1.5; }

    /* === 5. PRICELIST SECTION === */
    .section-header { text-align: center; margin-bottom: 40px; }
    .section-header h2 { font-size: 2.2rem; font-weight: 800; color: var(--dark-bg); margin-bottom: 10px; }
    .section-header .line { width: 60px; height: 4px; background: var(--primary); margin: 0 auto; }

    .pricing-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
    }

    .pricing-card {
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        border: 1px solid #eee;
    }

    .pricing-header {
        background: var(--dark-bg);
        padding: 25px;
        text-align: center;
        color: var(--primary);
        /* Flexbox center untuk icon header */
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .pricing-header h3 { margin: 0; font-size: 1.5rem; font-weight: 700; text-transform: uppercase; }
    .pricing-header i {
        font-size: 2.5rem; margin-bottom: 10px; display: block; color: #fff;
        margin-left: auto; margin-right: auto; /* Paksa tengah */
    }

    .price-list { padding: 30px; }
    .price-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        border-bottom: 1px dashed #ddd;
        padding-bottom: 15px;
    }
    .price-item:last-child { border-bottom: none; margin-bottom: 0; }

    .service-name { font-weight: 600; font-size: 1rem; color: #333; }
    .service-desc { font-size: 0.8rem; color: #888; display: block; margin-top: 3px; }
    .service-price { font-weight: 700; color: var(--water-blue); font-size: 1.1rem; }

    /* === 6. GALLERY & INFO === */
    .info-section {
        background: var(--dark-bg);
        border-radius: 20px;
        color: #fff;
        padding: 50px;
        margin-top: 60px;
        display: flex;
        align-items: center;
        gap: 40px;
        position: relative;
        overflow: hidden;
    }
    .info-section::before {
        content: ''; position: absolute; top: -50px; right: -50px;
        width: 200px; height: 200px; border-radius: 50%;
        background: var(--primary); opacity: 0.1;
    }

    .info-text { flex: 1; }
    .info-text h3 { font-size: 2rem; font-weight: 700; margin-bottom: 15px; color: var(--primary); }
    .info-text p { color: #ccc; line-height: 1.6; margin-bottom: 25px; }

    .info-list li { margin-bottom: 10px; display: flex; align-items: center; gap: 10px; }
    .info-list li i { color: var(--primary); }

    .info-img { flex: 1; }
    .info-img img { width: 100%; border-radius: 15px; border: 4px solid rgba(255,255,255,0.1); }

    /* === 7. BUTTONS === */
    .btn-maps {
        background: var(--primary); color: #000; padding: 12px 30px; border-radius: 50px;
        text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: 10px;
        transition: 0.3s;
    }
    .btn-maps:hover { background: #fff; color: var(--dark-bg); }

    /* === RESPONSIVE (KHUSUS HP) === */
    @media (max-width: 992px) {
        .features-grid { grid-template-columns: 1fr 1fr; margin-top: 0; }
        .pricing-container { grid-template-columns: 1fr; }
        .info-section { flex-direction: column-reverse; padding: 30px; text-align: center; }
        .info-list li { justify-content: center; } /* List di info section juga tengah */
        .btn-maps { width: 100%; justify-content: center; }
    }

    @media (max-width: 576px) {
        .wash-hero { height: auto; padding: 60px 20px; }
        .wash-hero h1 { font-size: 2rem; }

        /* Fix Icon Badges di HP */
        .hero-badges { flex-direction: column; width: 100%; }
        .status-badge, .instagram-badge { width: 100%; text-align: center; }

        /* Fix Grid Features di HP */
        .features-grid { grid-template-columns: 1fr; gap: 15px; }
        .feature-box { padding: 25px; }
    }
</style>
@endsection

@section('content')

    {{-- HERO SECTION --}}
    <div class="wash-hero">
        {{-- JAM OPERASIONAL & INSTAGRAM --}}
        <div class="hero-badges">
            <div class="status-badge">
                <i class="fas fa-clock"></i> Buka Sekarang: 09.00 - 23.00
            </div>
            <a href="https://instagram.com/speedwashgarage" target="_blank" class="instagram-badge">
                <i class="fab fa-instagram"></i> @speedwashgarage
            </a>
        </div>

        <h1>Bonanza Speedwash</h1>
        <p>Layanan cuci kendaraan premium dengan teknologi hidrolik & sabun snow wash berkualitas tinggi. Bersih, Kilat, Mengkilap.</p>
    </div>

    <div class="container-wash">

        {{-- FEATURES GRID (OVERLAP) --}}
        <div class="features-grid">
            <div class="feature-box">
                <i class="fas fa-shower feature-icon"></i>
                <div class="feature-title">Snow Wash</div>
                <div class="feature-desc">Busa salju tebal mengangkat kotoran tanpa merusak cat.</div>
            </div>
            <div class="feature-box">
                <i class="fas fa-level-up-alt feature-icon" style="color: var(--primary);"></i>
                <div class="feature-title">Sistem Hidrolik</div>
                <div class="feature-desc">Pembersihan kolong chassis maksimal bebas karat.</div>
            </div>
            <div class="feature-box">
                <i class="fas fa-couch feature-icon" style="color: #e74c3c;"></i>
                <div class="feature-title">Ruang Tunggu</div>
                <div class="feature-desc">Full AC, Free Wi-Fi, TV & Smoking Area nyaman.</div>
            </div>
            <div class="feature-box">
                <i class="fas fa-coffee feature-icon" style="color: #8e44ad;"></i>
                <div class="feature-title">Kantin Mini</div>
                <div class="feature-desc">Tersedia minuman dingin & snack sambil menunggu.</div>
            </div>
        </div>

        {{-- PRICELIST SECTION --}}
        <div class="section-header">
            <h2>Daftar Harga Layanan</h2>
            <div class="line"></div>
            <p style="margin-top:10px; color:#666;">Harga transparan dengan hasil kualitas terbaik.</p>
        </div>

        <div class="pricing-container">

            {{-- PRICELIST MOTOR --}}
            <div class="pricing-card">
                <div class="pricing-header">
                    <i class="fas fa-motorcycle"></i>
                    <h3>Motor Wash</h3>
                </div>
                <div class="price-list">
                    <div class="price-item">
                        <div>
                            <div class="service-name">Cuci Motor Kecil</div>
                            <span class="service-desc">Snow wash + Semir Ban</span>
                        </div>
                        <div class="service-price">Rp 15.000</div>
                    </div>
                    <div class="price-item">
                        <div>
                            <div class="service-name">Cuci Motor Sedang</div>
                            <span class="service-desc">Termasuk pembersihan mesin luar & kerak</span>
                        </div>
                        <div class="service-price">Rp 20.000</div>
                    </div>
                    <div class="price-item">
                        <div>
                            <div class="service-name">Cuci Motor Besar</div>
                            <span class="service-desc">Pembersihan luar & dalam (Foam)</span>
                        </div>
                        <div class="service-price">Rp 30.000</div>
                    </div>
                    <div class="price-item">
                        <div>
                            <div class="service-name">Cuci Motor Kecil + Poles</div>
                            <span class="service-desc">Pembersihan luar & dalam (Foam)</span>
                        </div>
                        <div class="service-price">Rp 20.000</div>
                    </div>
                    <div class="price-item">
                        <div>
                            <div class="service-name">Cuci Motor Sedang + Poles</div>
                            <span class="service-desc">Pembersihan luar & dalam (Foam)</span>
                        </div>
                        <div class="service-price">Rp 25.000</div>
                    </div>
                    <div class="price-item">
                        <div>
                            <div class="service-name">Cuci Motor Besar + Poles</div>
                            <span class="service-desc">Pembersihan luar & dalam (Foam)</span>
                        </div>
                        <div class="service-price">Rp 35.000</div>
                    </div>
                </div>
            </div>

            {{-- PRICELIST MOBIL --}}
            <div class="pricing-card">
                <div class="pricing-header">
                    <i class="fas fa-car"></i>
                    <h3>Car Wash</h3>
                </div>
                <div class="price-list">
                    <div class="price-item">
                        <div>
                            <div class="service-name">Cuci Mobil Kecil (Ekster)</div>
                            <span class="service-desc">Snow wash + Semir Ban</span>
                        </div>
                        <div class="service-price">Rp 25.000</div>
                    </div>
                    <div class="price-item">
                        <div>
                            <div class="service-name">Cuci Mobil Kecil (Ekster + Inter)</div>
                            <span class="service-desc">Snow wash + Semir Ban</span>
                        </div>
                        <div class="service-price">Rp 35.000</div>
                    </div>
                    <div class="price-item">
                        <div>
                            <div class="service-name">Cuci Mobil Besar (Ekster)</div>
                            <span class="service-desc">Snow wash + Semir Ban</span>
                        </div>
                        <div class="service-price">Rp 35.000</div>
                    </div>
                    <div class="price-item">
                        <div>
                            <div class="service-name">Cuci Mobil Besar (Ekster + Inter)</div>
                            <span class="service-desc">Snow wash + Semir Ban</span>
                        </div>
                        <div class="service-price">Rp 45.000</div>
                    </div>
                    <div class="price-item">
                        <div>
                            <div class="service-name">Cuci Karpet Karet</div>
                            <span class="service-desc">Snow wash + Semir Ban</span>
                        </div>
                        <div class="service-price">Rp 5.000</div>
                    </div>
                    <div class="price-item">
                        <div>
                            <div class="service-name">Cuci Mesin</div>
                            <span class="service-desc">Snow wash + Semir Ban</span>
                        </div>
                        <div class="service-price">Rp 10.000</div>
                    </div>
                </div>
            </div>

        </div>

        {{-- INFO LOKASI / FASILITAS --}}
        <div class="info-section">
            <div class="info-text">
                <h3>Tunggu Nyaman, Hasil Memuaskan</h3>
                <p>Kami memahami waktu Anda berharga. Bonanza Speedwash didukung oleh tim profesional yang bekerja cepat dan teliti. Sambil menunggu, nikmati fasilitas lounge kami.</p>

                <ul class="info-list" style="list-style: none; padding: 0; color: #ccc;">
                    <li><i class="fas fa-check"></i> Menggunakan Shampoo Wax (Kilap)</li>
                    <li><i class="fas fa-check"></i> Lap Microfiber (Anti Baret)</li>
                    <li><i class="fas fa-check"></i> Garansi Hujan (Cuci Ulang Body 1x24 Jam)*</li>
                </ul>

                <div style="margin-top: 30px;">
                    <a href="https://goo.gl/maps/placeholder" target="_blank" class="btn-maps">
                        <i class="fas fa-map-marker-alt"></i> Petunjuk Arah
                    </a>
                </div>
            </div>
            <div class="info-img">
                <img src="https://images.unsplash.com/photo-1552930294-6b595f4c2974?q=80&w=1471&auto=format&fit=crop" alt="Waiting Room">
            </div>
        </div>

    </div>

@endsection
