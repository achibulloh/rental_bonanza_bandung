<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') Bonanza Rental Mobil Bandung</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- Analytics --}}
    @if (!empty($settings['GoogleSchConsol']))
        <meta name="google-site-verification" content="{{ $settings['GoogleSchConsol'] }}" />
    @endif
    <!-- Google Tag Manager -->
    @if (!empty($settings['GoogleTag']))
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','{{ $settings['GoogleTag'] }}');</script>
    @endif
    <!-- End Google Tag Manager -->
    <style>
        /* --- CSS VARIABLES & RESET --- */
        :root {
            --primary: #FFC400; /* Warna Kuning Emas */
            --primary-hover: #e0ac00;
            --dark-bg: #111111; /* Hitam Header */
            --text-light: #f4f4f4;
            --text-dark: #333333;
            --gray: #888888;
            --white: #ffffff;
            --border-radius: 8px;
            --container-width: 1200px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--white);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        a { text-decoration: none; color: inherit; transition: 0.3s; }
        ul { list-style: none; }

        .container {
            max-width: var(--container-width);
            margin: 0 auto;
            padding: 0 20px;
        }

        /* TOMBOL UMUM */
        .btn {
            display: inline-block;
            padding: 10px 24px;
            border-radius: var(--border-radius);
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: 0.3s;
            text-align: center;
        }

        .btn-primary {
            background-color: var(--primary);
            color: var(--dark-bg);
        }
        .btn-primary:hover { background-color: var(--primary-hover); }

        .btn-outline {
            border: 1px solid var(--text-dark);
            background: transparent;
        }
        .btn-outline-light {
            border: 1px solid var(--white);
            color: var(--white);
        }
        .btn-outline:hover { background: #eee; }

        /* --- NAVBAR / HEADER --- */
        header {
            background-color: var(--dark-bg); /* Header Hitam */
            padding: 20px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap; /* PENTING: Agar menu bisa turun ke bawah saat mobile */
        }

        .logo {
            color: var(--primary);
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 1001; /* Agar logo selalu di atas */
        }
        .logo .imgg {
            width: 125px;
            height: auto;
        }

        /* Desktop Menu Styles */
        .nav-links {
            display: flex;
            gap: 30px;
        }

        .nav-links a {
            color: var(--text-light);
            font-size: 0.9rem;
            font-weight: 500;
        }
        .nav-links a:hover { color: var(--primary); }

        .nav-buttons { display: flex; gap: 15px; align-items: center; }
        .btn-login { color: var(--text-light); font-weight: 500; }
        .btn-login:hover { color: var(--primary); }

        .mobile-menu-btn {
            display: none; /* Sembunyi di desktop */
            color: var(--white);
            font-size: 1.5rem;
            cursor: pointer;
            z-index: 1001;
        }

        /* --- HERO SECTION --- */
        .hero {
            background-color: var(--dark-bg);
            color: var(--white);
            padding: 60px 0 100px;
            position: relative;
        }

        .hero-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: center;
        }

        .hero-text h1 {
            font-size: 3.5rem;
            line-height: 1.2;
            margin-bottom: 20px;
        }
        .hero-text h1 span { color: var(--primary); }
        .hero-text p { color: var(--gray); margin-bottom: 30px; }

        .promo-badge {
            background: rgba(255, 196, 0, 0.2);
            color: var(--primary);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            display: inline-block;
            margin-bottom: 15px;
        }

        .search-box {
            background: var(--white);
            padding: 20px;
            border-radius: var(--border-radius);
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            color: var(--text-dark);
        }

        .input-group { flex: 1; display: flex; flex-direction: column; min-width: 150px; }
        .input-group label { font-size: 0.8rem; margin-bottom: 5px; color: var(--gray); }
        .input-group input { border: 1px solid #ddd; padding: 10px; border-radius: 4px; }
        .search-box .btn { margin-top: auto; }

        .hero-stats {
            margin-top: 40px;
            display: flex;
            gap: 40px;
        }
        .stat-item h3 { color: var(--primary); font-size: 1.5rem; }
        .stat-item p { color: var(--gray); font-size: 0.8rem; }

        .hero-image {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        }
        .hero-image img { width: 100%; height: auto; display: block; }

        /* Mengatur wadah tombol agar di tengah & ada jarak */
        .view-all-btn {
            text-align: center; /* Membuat tombol berada di posisi tengah horizontal */
            margin-top: 50px;   /* Memberi jarak (space) yang cukup dari gambar mobil di atasnya */
            margin-bottom: 20px; /* Opsional: Jarak ke bawah */
            width: 100%;        /* Memastikan wadah mengambil lebar penuh layar */
            display: block;
        }

        /* Opsional: Efek hover khusus untuk tombol hitam ini */
        .btn-black {
            background-color: #000;
            color: #fff;
            padding: 12px 30px; /* Sedikit lebih besar agar gagah */
            border: 2px solid #000;
        }
        .btn-black:hover {
            background-color: transparent;
            color: #000;
        }

        /* --- OTHER SECTIONS (Singkat untuk menghemat tempat, sama seperti sebelumnya) --- */
        .section-padding { padding: 80px 0; }
        .section-header { text-align: center; margin-bottom: 50px; }
        .section-header .subtitle { color: var(--primary); font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
        .section-header h2 { font-size: 2.5rem; margin-top: 10px; }

        /* Cars */
        .cars-section { background: #F8F9FA; }
        .cars-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px; }
        .car-card { background: var(--white); border-radius: 12px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        .car-image { position: relative; height: 200px; }
        .car-image img { width: 100%; height: 100%; object-fit: cover; }
        .car-tag { position: absolute; top: 15px; right: 15px; background: var(--primary); padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; }
        .car-details { padding: 20px; }
        .car-title { font-size: 1.2rem; margin-bottom: 5px; }
        .car-price { color: var(--primary); font-weight: 700; margin-bottom: 15px; font-size: 1.1rem; }
        .car-specs { display: flex; justify-content: space-between; margin-bottom: 20px; color: var(--gray); font-size: 0.85rem; }
        .car-actions { display: grid; grid-template-columns: 1fr auto; gap: 10px; }

        /* Features */
        .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; }
        .feature-card { background: var(--white); border: 1px solid #eee; padding: 30px; border-radius: 12px; }
        .feature-icon { width: 50px; height: 50px; background: rgba(255, 196, 0, 0.1); color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 20px; font-size: 1.2rem; }

        /* CTA */
        .cta-section { padding: 40px 20px; }
        .cta-box { background: var(--dark-bg); border-radius: 20px; padding: 60px; text-align: center; color: var(--white); }
        .cta-buttons { display: flex; justify-content: center; gap: 20px; margin-top: 30px; }

        /* Testimonials */
        /* .testimonials-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; }
        .testimonial-card { background: var(--white); padding: 30px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); } */
        /* --- TESTIMONIAL SLIDER CSS --- */
        .testimonial-slider-container {
            overflow: hidden; /* Menyembunyikan kartu yang ada di luar layar */
            width: 100%;
            position: relative;
        }

        .testimonial-track {
            display: flex;
            transition: transform 0.5s ease-in-out; /* Efek geser halus */
            width: 100%;
        }

        .testimonial-card {
            /* Mengganti lebar agar responsif */
            flex: 0 0 100%; /* Default Mobile: 1 kartu penuh */
            max-width: 100%;
            padding: 0 15px;
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            box-sizing: border-box; /* Penting agar padding tidak menambah lebar */
        }

        .testimonial-content {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            padding: 30px; /* Jarak tulisan ke pinggir kotak putih */
            height: 100%; /* Agar tinggi kartu seragam */
            border: 1px solid #f0f0f0; /* Opsional: border halus */
        }

        /* Tampilan Tablet (2 Kartu) */
        @media (min-width: 768px) {
            .testimonial-card {
                flex: 0 0 50%;
                max-width: 50%;
            }
            .logo .imgg {
                width: 100px;
                height: auto;
            }
        }

        /* Tampilan Desktop (3 Kartu) */
        @media (min-width: 1024px) {
            .testimonial-card {
                flex: 0 0 33.333%;
                max-width: 33.333%;
            }
            .logo .imgg {
                width: 100px;
                height: auto;
            }
        }

        /* Dots Indikator (Opsional, agar terlihat cantik) */
        .slider-dots {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
        }
        .dot {
            width: 10px;
            height: 10px;
            background: #ddd;
            border-radius: 50%;
            cursor: pointer;
            transition: 0.3s;
        }
        .dot.active {
            background: var(--primary);
            transform: scale(1.3);
        }
        .stars { color: var(--primary); margin-bottom: 15px; }
        .user-profile { display: flex; align-items: center; gap: 15px; margin-top: 20px; }
        .user-img { width: 50px; height: 50px; border-radius: 50%; background: #ddd; object-fit: cover; }

        .trust-stats { display: flex; justify-content: space-around; margin-top: 60px; border-top: 1px solid #eee; padding-top: 40px; text-align: center; }
        .trust-item h3 { color: var(--primary); font-size: 2rem; }

        /* Footer */
        footer { background: #000; color: var(--gray); padding: 80px 0 20px; font-size: 0.9rem; }
        .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1.5fr; gap: 40px; margin-bottom: 60px; }
        .footer-logo { color: var(--primary); font-size: 1.3rem; font-weight: 700; margin-bottom: 20px; display: block; }
        .footer-links li { margin-bottom: 10px; }
        .social-icons { display: flex; gap: 15px; margin-top: 20px; }
        .copyright { border-top: 1px solid #222; padding-top: 20px; display: flex; justify-content: space-between; }

        /* --- RESPONSIVE FIXES (BAGIAN PENTING) --- */
        @media (max-width: 992px) {
            .hero-text h1 { font-size: 2.5rem; }
            .hero-content { grid-template-columns: 1fr; text-align: center; }
            .search-box { justify-content: center; }
            .hero-stats { justify-content: center; }
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 768px) {
            /* 1. Header & Menu */
            .mobile-menu-btn { display: block; }

            /* Sembunyikan menu secara default di mobile */
            .nav-links, .nav-buttons {
                display: none;
                width: 100%; /* Agar memenuhi lebar kontainer */
                text-align: center;
                background-color: var(--dark-bg);
            }

            /* Style saat menu AKTIF (diklik) */
            .nav-container.active {
                padding-bottom: 20px; /* Ruang tambahan di bawah */
            }

            .nav-container.active .nav-links {
                display: flex;
                flex-direction: column;
                gap: 20px;
                padding-top: 30px;
                padding-bottom: 20px;
                border-top: 1px solid #222; /* Garis pemisah halus */
                order: 3; /* Pastikan urutannya di bawah logo */
            }

            .nav-container.active .nav-buttons {
                display: flex;
                flex-direction: column; /* Tombol jadi atas-bawah */
                gap: 15px;
                order: 4; /* Di bawah link */
                width: 100%;
                padding-bottom: 10px;
            }

            /* Perbaikan Tombol di Mobile */
            .nav-buttons .btn {
                width: 100%; /* Tombol lebar penuh */
                display: block;
            }

            /* Link Login di mobile biar rapi */
            .btn-login {
                margin-bottom: 10px;
                display: block;
            }

            /* 2. Hero Adjustments */
            .hero { padding-top: 30px; }
            .search-box { flex-direction: column; }
            .search-box .btn { width: 100%; }

            /* 3. Section Adjustments */
            .section-header h2 { font-size: 2rem; }

            /* 4. Footer & CTA */
            .cta-buttons { flex-direction: column; }
            .trust-stats { flex-direction: column; gap: 30px; }
            .footer-grid { grid-template-columns: 1fr; }
            .copyright { flex-direction: column; gap: 10px; text-align: center; }
        }
    </style>
    @yield('style')
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    @if (!empty($settings['GoogleTag']))
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $settings['GoogleTag'] }}"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif
    <!-- End Google Tag Manager (noscript) -->

    <header>
        <div class="container nav-container" id="navbar">
            <a href="/" class="logo">
                @if(!empty($settings['logo_web']))
                    <img class="imgg" src="{{ asset('storage/' . $settings['logo_web']) }}" alt="{{ $settings['app_name'] ?? 'Bonanza Rental Mobil Bandung' }}">
                @endif
            </a>

            <div class="mobile-menu-btn" onclick="toggleMenu()">
                <i class="fas fa-bars"></i>
            </div>

            <ul class="nav-links">
                <li><a href="{{ route('index') }}">Beranda</a></li>
                <li><a href="#armada">Mobil</a></li>
                <li><a href="#layanan">Layanan</a></li>
                <li><a href="#testimoni">Testimoni</a></li>
                <li><a href="#kontak">Kontak</a></li>
            </ul>

            <div class="nav-buttons">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashbaord') }}" class="btn btn-primary">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-login">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </header>

    @yield('content')

    <footer id="kontak">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <a href="#" class="footer-logo">
                        <img src="{{ asset('storage/' . $settings['logo_web']) }}" alt="{{ $settings['app_name'] ?? 'Bonanza Rental Mobil Bandung' }}" style="height: 125px; width: auto;">
                    </a>
                    @if(!empty($settings['app_desc']))
                        <p>{{ $settings['app_desc'] }}</p>
                    @endif
                    <div class="social-icons">
                        @if(!empty($settings['social_fb']))
                            <a href="{{ $settings['social_fb'] }}"><i class="fab fa-facebook"></i></a>
                        @endif

                        @if(!empty($settings['social_ig']))
                            <a href="{{ $settings['social_ig'] }}"><i class="fab fa-instagram"></i></a>
                        @endif

                        @if(!empty($settings['social_tiktok']))
                            <a href="{{ $settings['social_tiktok'] }}"><i class="fab fa-tiktok"></i></a>
                        @endif
                        @if(!empty($settings['whatsapp']))
                            <a href="{{ $settings['whatsapp'] }}"><i class="fab fa-whatsapp"></i></a>
                        @endif
                    </div>
                </div>
                <div class="footer-col">
                    <h4>Menu</h4>
                    <ul class="footer-links">
                        <li><a href="#">Beranda</a></li>
                        <li><a href="#armada">Daftar Mobil</a></li>
                        <li><a href="#layanan">Layanan</a></li>
                        <li><a href="#testimoni">Testimoni</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Layanan Kami</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('index.layanan') }}">Paket Rental</a></li>
                        <li><a href="{{ route('index.garasispeed') }}">Garasi Speed</a></li>
                        <li><a href="{{ route('index.poolnanza') }}">Poolnanza Billiard</a></li>
                        <li><a href="#">Warbon</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Hubungi Kami</h4>
                    <ul class="footer-links">
                        <li style="display:flex; gap:10px;"><i class="fas fa-map-marker-alt" style="margin-top:5px; color:var(--primary)"></i> <a href="https://maps.app.goo.gl/7qzBQNKNRgmrzcxcA"> {{ $settings['address'] ?? '-' }}</a></li>
                        <li style="display:flex; gap:10px;"><i class="fas fa-phone-alt" style="margin-top:5px; color:var(--primary)"></i>{{ $settings['phone'] ?? '-' }}</li>
                        <li style="display:flex; gap:10px;"><i class="fas fa-envelope" style="margin-top:5px; color:var(--primary)"></i> {{ $settings['email'] ?? '-' }}</li>
                        <li style="display:flex; gap:10px;"><i class="fab fa-whatsapp" style="margin-top:5px; color:var(--primary)"></i><a href="https://wa.me/{{ $settings['whatsapp'] ?? '' }}" target="_blank"> Chat via WhatsApp</a></li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p>© 2024 Bonanza Rental Mobil Bandung. Hak Cipta Dilindungi.</p>
                <div style="display:flex; gap:20px;">
                    <a href="#">Kebijakan Privasi</a>
                    <a href="#">Syarat & Ketentuan</a>
                    <a href="#">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>
    <script>
        function toggleMenu() {
            // Mengambil elemen navbar
            const navbar = document.getElementById('navbar');
            // Menambah/menghapus class 'active' untuk memicu CSS
            navbar.classList.toggle('active');
        }

        // --- SCRIPT SLIDER TESTIMONI OTOMATIS ---
        const track = document.getElementById('sliderTrack');
        const cards = document.querySelectorAll('.testimonial-card');
        const dotsContainer = document.getElementById('sliderDots');

        let currentIndex = 0;
        const totalCards = cards.length;
        let cardsPerView = 1; // Default Mobile
        let autoSlideInterval;

        // Fungsi update jumlah kartu per tampilan (Responsif)
        function updateCardsPerView() {
            if (window.innerWidth >= 1024) {
                cardsPerView = 3; // Desktop
            } else if (window.innerWidth >= 768) {
                cardsPerView = 2; // Tablet
            } else {
                cardsPerView = 1; // Mobile
            }
            createDots(); // Update dots sesuai jumlah slide yang mungkin
            updateSlidePosition();
        }

        // Buat Dots Indikator
        function createDots() {
            dotsContainer.innerHTML = '';
            const maxIndex = totalCards - cardsPerView;
            const dotCount = (maxIndex >= 0) ? maxIndex + 1 : 1;

            for (let i = 0; i < dotCount; i++) {
                const dot = document.createElement('div');
                dot.classList.add('dot');
                if (i === currentIndex) dot.classList.add('active');
                dot.addEventListener('click', () => {
                    currentIndex = i;
                    updateSlidePosition();
                    resetAutoSlide(); // Reset timer saat diklik manual
                });
                dotsContainer.appendChild(dot);
            }
        }

        // Fungsi Geser Slider
        function updateSlidePosition() {
            // Cek batas index agar tidak lewat
            const maxIndex = totalCards - cardsPerView;
            if (currentIndex > maxIndex) currentIndex = 0;
            if (currentIndex < 0) currentIndex = maxIndex;

            // Hitung persentase geser
            // Jika Desktop (3 kartu) = geser 33.33%
            // Jika Mobile (1 kartu) = geser 100%
            const widthPercentage = 100 / cardsPerView;
            track.style.transform = `translateX(-${currentIndex * widthPercentage}%)`;

            // Update warna Dots
            const dots = document.querySelectorAll('.dot');
            dots.forEach((dot, index) => {
                if (index === currentIndex) dot.classList.add('active');
                else dot.classList.remove('active');
            });
        }

        // Fungsi Otomatis Geser
        function startAutoSlide() {
            autoSlideInterval = setInterval(() => {
                const maxIndex = totalCards - cardsPerView;
                if (currentIndex < maxIndex) {
                    currentIndex++;
                } else {
                    currentIndex = 0; // Kembali ke awal
                }
                updateSlidePosition();
            }, 3000); // Bergeser setiap 3 detik (3000ms)
        }

        function resetAutoSlide() {
            clearInterval(autoSlideInterval);
            startAutoSlide();
        }

        // Event Listeners
        window.addEventListener('resize', updateCardsPerView);

        // Inisialisasi awal
        updateCardsPerView();
        startAutoSlide();
    </script>
    @yield('scripts')
</body>
</html>
