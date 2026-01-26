@extends('index.layouts.index')
@section('title', 'Poolnanza Billiard - Tempat Main Billiard Terbaik Bandung')

@section('style')
<style>
    /* === 1. GLOBAL VARIABLES === */
    :root {
        --primary: #FFC400;       /* Emas */
        --primary-dark: #d4a000;
        --dark-bg: #111;          /* Hitam Background Utama */
        --card-dark: #1a1a1a;     /* Hitam Card */
        --text-light: #eee;
        --text-gray: #aaa;
        --accent-green: #2ecc71;  /* Hijau Meja Billiard */
    }

    /* Override Body ke Dark Mode khusus halaman ini */
    body { background-color: var(--dark-bg); font-family: 'Poppins', sans-serif; color: var(--text-light); }

    /* === 2. HERO SECTION === */
    .pool-hero {
        background: linear-gradient(to bottom, rgba(0,0,0,0.3), var(--dark-bg)), url('https://images.unsplash.com/photo-1575361204480-aadea25e6e68?q=80&w=1471&auto=format&fit=crop');
        background-size: cover;
        background-position: center;
        height: 500px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        padding: 20px;
        position: relative;
    }

    .pool-hero h1 {
        font-size: 3.5rem;
        font-weight: 900;
        margin-bottom: 10px;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 3px;
        text-shadow: 0 0 20px rgba(255, 196, 0, 0.3);
        line-height: 1.2;
    }

    .pool-hero p {
        font-size: 1.2rem;
        max-width: 700px;
        color: #ccc;
        margin-bottom: 30px;
        font-weight: 300;
    }

    /* BADGES */
    .hero-badges {
        display: flex;
        gap: 15px;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        width: 100%;
    }

    .status-badge, .ig-badge {
        padding: 10px 25px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255,255,255,0.1);
    }

    .status-badge { background: rgba(46, 204, 113, 0.2); color: var(--accent-green); border-color: var(--accent-green); }
    .ig-badge { background: rgba(255,255,255,0.1); color: #fff; text-decoration: none; transition: 0.3s; }
    .ig-badge:hover { background: var(--primary); color: #000; border-color: var(--primary); }

    /* === 3. CONTAINER === */
    .container-pool { max-width: 1100px; margin: 0 auto; padding: 60px 20px; }

    /* === 4. FACILITIES (DARK CARDS) === */
    .section-title { text-align: center; margin-bottom: 50px; }
    .section-title h2 { font-size: 2.2rem; color: #fff; font-weight: 800; margin-bottom: 10px; text-transform: uppercase; }
    .section-title span { color: var(--primary); }
    .section-title p { color: var(--text-gray); }

    .facilities-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 25px;
        margin-bottom: 80px;
    }

    .facility-box {
        background: var(--card-dark);
        padding: 30px 20px;
        border-radius: 15px;
        text-align: center;
        border: 1px solid #333;
        transition: 0.3s;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .facility-box:hover { transform: translateY(-10px); border-color: var(--primary); box-shadow: 0 10px 30px rgba(255, 196, 0, 0.1); }

    .fac-icon {
        font-size: 2.5rem;
        color: var(--primary);
        margin-bottom: 20px;
        display: block;
    }
    .fac-title { font-size: 1.1rem; font-weight: 700; color: #fff; margin-bottom: 5px; }
    .fac-desc { font-size: 0.85rem; color: #888; line-height: 1.5; }

    /* === 5. PRICING TABLE (ELEGANT) === */
    .pricing-wrapper {
        background: #151515;
        border-radius: 20px;
        padding: 50px;
        border: 1px solid #333;
        position: relative;
        overflow: hidden;
    }
    /* Glow Effect */
    .pricing-wrapper::before {
        content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%;
        background: radial-gradient(circle, rgba(255,196,0,0.05) 0%, transparent 70%);
        pointer-events: none;
    }

    .pricing-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        position: relative; z-index: 2;
    }

    .price-card {
        background: var(--card-dark);
        border-radius: 15px;
        padding: 30px;
        border-left: 5px solid #444;
    }
    .price-card.happy-hour { border-color: var(--accent-green); }
    .price-card.regular { border-color: var(--primary); }

    .price-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .price-title { font-size: 1.3rem; font-weight: 800; text-transform: uppercase; }
    .price-icon { font-size: 2rem; }

    .price-value { font-size: 2.5rem; font-weight: 800; color: #fff; margin-bottom: 5px; }
    .price-unit { font-size: 1rem; color: #888; font-weight: 400; }

    .price-desc { font-size: 0.9rem; color: #ccc; margin-top: 15px; padding-top: 15px; border-top: 1px dashed #444; }
    .price-desc i { color: var(--primary); margin-right: 8px; }

    /* === 6. PROMO BANNER === */
    .promo-banner {
        background: var(--primary);
        color: var(--dark-bg);
        border-radius: 15px;
        padding: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 50px;
        flex-wrap: wrap; gap: 20px;
    }
    .promo-text h3 { font-size: 1.5rem; font-weight: 800; margin: 0; }
    .promo-text p { font-size: 1rem; margin: 5px 0 0; font-weight: 500; }

    .btn-booking {
        background: var(--dark-bg); color: var(--primary);
        padding: 12px 35px; border-radius: 50px; font-weight: 700; text-decoration: none;
        display: inline-flex; align-items: center; gap: 10px; transition: 0.3s;
    }
    .btn-booking:hover { background: #fff; color: var(--dark-bg); transform: scale(1.05); }

    /* RESPONSIVE */
    @media (max-width: 992px) {
        .facilities-grid { grid-template-columns: 1fr 1fr; }
        .pricing-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 576px) {
        .pool-hero h1 { font-size: 2.2rem; }
        .hero-badges { flex-direction: column; width: 100%; }
        .status-badge, .ig-badge { width: 100%; text-align: center; }
        .facilities-grid { grid-template-columns: 1fr; }
        .promo-banner { text-align: center; justify-content: center; }
    }
</style>
@endsection

@section('content')

    {{-- HERO SECTION --}}
    <div class="pool-hero">
        <div class="hero-badges">
            <div class="status-badge">
                <i class="fas fa-clock"></i> Buka Setiap Hari: 10.00 - 02.00 WIB
            </div>
            <a href="https://instagram.com/poolnanza" target="_blank" class="ig-badge">
                <i class="fab fa-instagram"></i> Follow @poolnanza
            </a>
        </div>

        <h1>POOLNANZA<br>BILLIARD</h1>
        <p>Rasakan sensasi bermain di meja standar turnamen dengan suasana premium yang nyaman dan asik.</p>
    </div>

    <div class="container-pool">

        {{-- FACILITIES GRID --}}
        <div class="section-title">
            <h2>The Ultimate <span>Experience</span></h2>
            <p>Fasilitas terbaik untuk menunjang performa permainan Anda.</p>
        </div>

        <div class="facilities-grid">
            <div class="facility-box">
                <i class="fas fa-bullseye fac-icon"></i>
                <div class="fac-title">Meja 9-Feet</div>
                <div class="fac-desc">Meja ukuran internasional dengan laken (karpet) kualitas turnamen yang presisi.</div>
            </div>
            <div class="facility-box">
                <i class="fas fa-wind fac-icon"></i>
                <div class="fac-title">Full AC</div>
                <div class="fac-desc">Ruangan dingin bebas gerah, bikin fokus membidik bola makin tajam.</div>
            </div>
            <div class="facility-box">
                <i class="fas fa-music fac-icon"></i>
                <div class="fac-title">Cozy Vibe</div>
                <div class="fac-desc">Suasana modern, pencahayaan pas, dan playlist musik yang asik buat nongkrong.</div>
            </div>
            <div class="facility-box">
                <i class="fas fa-utensils fac-icon"></i>
                <div class="fac-title">Cafe & Resto</div>
                <div class="fac-desc">Lapar saat main? Pesan Nasi Goreng Gila atau Kopi Susu andalan kami.</div>
            </div>
        </div>

        {{-- PRICING SECTION --}}
        <div class="section-title">
            <h2>Table <span>Rates</span></h2>
            <p>Harga terjangkau dengan kualitas bintang lima.</p>
        </div>

        <div class="pricing-wrapper">
            <div class="pricing-grid">

                {{-- HAPPY HOUR --}}
                <div class="price-card happy-hour">
                    <div class="price-head">
                        <div class="price-title" style="color:var(--accent-green)">Happy Hour</div>
                        <i class="fas fa-sun price-icon" style="color:var(--accent-green)"></i>
                    </div>
                    <div class="price-value">Rp 35.000 <span class="price-unit">/ jam</span></div>
                    <div class="price-desc">
                        <div><i class="far fa-clock"></i> Senin - Jumat (10.00 - 17.00)</div>
                        <div style="margin-top:5px;"><i class="fas fa-check"></i> Berlaku untuk Umum & Pelajar</div>
                    </div>
                </div>

                {{-- REGULAR HOUR --}}
                <div class="price-card regular">
                    <div class="price-head">
                        <div class="price-title" style="color:var(--primary)">Regular</div>
                        <i class="fas fa-moon price-icon" style="color:var(--primary)"></i>
                    </div>
                    <div class="price-value">Rp 50.000 <span class="price-unit">/ jam</span></div>
                    <div class="price-desc">
                        <div><i class="far fa-clock"></i> 17.00 - Tutup & Weekend</div>
                        <div style="margin-top:5px;"><i class="fas fa-fire"></i> Suasana malam lebih seru</div>
                    </div>
                </div>

            </div>

            {{-- PROMO BANNER --}}
            <div class="promo-banner">
                <div class="promo-text">
                    <h3>MAIN 3 JAM, GRATIS ICED TEA!</h3>
                    <p>Promo khusus hari ini. Booking meja sekarang sebelum penuh.</p>
                </div>
                <a href="https://wa.me/6288999988909?text=Halo%20Admin,%20mau%20booking%20meja%20billiard%20di%20Poolnanza" class="btn-booking">
                    <i class="fab fa-whatsapp"></i> Booking Table
                </a>
            </div>
        </div>

    </div>

@endsection
