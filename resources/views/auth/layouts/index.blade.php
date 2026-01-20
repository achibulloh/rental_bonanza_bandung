<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Bonanza Rental Mobil</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .logo {
            color: var(--primary);
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 1001; /* Agar logo selalu di atas */
        }
        .logo .img {
            width: 125px;
            height: auto;
        }
    </style>
    @yield('style')
</head>
<body>

    <header>
        <div class="container nav-container">
            <a href="{{ route('index') }}" class="logo">
                <img class="img" src="{{ asset('storage/' . $settings['logo_web']) }}" alt="{{ $settings['app_name'] ?? 'Bonanza Rental Mobil Bandung' }}">
            </a>
            <a href="{{ route('index') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </header>

    @yield('content')



    @yield('scripts')
    <input type="text" value="@toastr_js @toastr_render" hidden>
</body>
</html>
