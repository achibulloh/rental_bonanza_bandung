<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ $settings['app_name'] ?? 'Bonanza Rental' }}</title>

    {{-- Fonts & Icons --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* === GLOBAL VARIABLES === */
        :root {
            --primary: #FFC400;
            --primary-hover: #e0ac00;
            --dark-bg: #111111;
            --text-dark: #333333;
            --text-gray: #666666;
            --bg-page: #fffcf5;
            --white: #ffffff;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }

        body {
            background-color: var(--bg-page);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* === HEADER STYLES === */
        header {
            background-color: var(--dark-bg);
            padding: 15px 0;
            position: sticky; /* Agar header tetap di atas saat scroll */
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border-bottom: 1px solid rgba(255, 196, 0, 0.1);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Logo Styling */
        .logo {
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--primary);
            font-weight: 700;
            font-size: 1.4rem;
        }

        .logo-img {
            height: 75px; /* Tinggi fix agar proporsional */
            width: auto;
            object-fit: contain;
        }

        /* Tombol Kembali */
        .btn-back {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 50px;
            background: rgba(255,255,255,0.05);
            transition: all 0.3s ease;
        }

        .btn-back i { transition: transform 0.3s; }

        .btn-back:hover {
            background-color: var(--primary);
            color: rgba(255,255,255,0.8);
            box-shadow: 0 0 15px rgba(255, 196, 0, 0.4);
        }

        .btn-back:hover i { transform: translateX(-3px); }

        /* Responsive Logo di HP */
        @media (max-width: 480px) {
            .logo-img { height: 55px; }
            .btn-back span { display: none; } /* Sembunyikan teks 'Kembali' di HP kecil, sisa Icon */
            .btn-back { padding: 8px; border-radius: 50%; width: 35px; height: 35px; justify-content: center; }
        }
    </style>

    @yield('style')
</head>
<body>

    <header>
        <div class="container nav-container">
            {{-- Logo Area --}}
            <a href="{{ route('index') }}" class="logo">
                @if(isset($settings['logo_web']) && $settings['logo_web'])
                    <img class="logo-img" src="{{ asset('storage/' . $settings['logo_web']) }}" alt="Bonanza Rental">
                @else
                    <i class="fas fa-car-side"></i> Bonanza
                @endif
            </a>

            {{-- Tombol Kembali --}}
            <a href="{{ url()->previous() }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> <span>Kembali</span>
            </a>
        </div>
    </header>

    @yield('content')

    @yield('scripts')

    {{-- Toastr Container (Jika pakai library toastr) --}}
    <input type="text" value="@toastr_js @toastr_render" hidden>
</body>
</html>
