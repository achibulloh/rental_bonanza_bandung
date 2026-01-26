<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title') - Bonanza Rental</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #FFC400;
            --primary-hover: #e0ac00;
            --dark-bg: #111111;
            --text-dark: #333333;
            --text-gray: #888888;
            --light-gray: #f4f6f9;
            --white: #ffffff;
            --sidebar-width: 280px;
            --header-height: 60px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }

        body {
            background-color: var(--light-gray);
            color: var(--text-dark);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* --- TAMBAHAN 1: MENCEGAH SCROLL SAAT MENU BUKA --- */
        body.no-scroll {
            overflow: hidden !important;
            height: 100vh;
        }

        /* --- MOBILE HEADER --- */
        .mobile-header {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%;
            height: var(--header-height);
            background: var(--white);
            padding: 0 20px;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            z-index: 1050;
        }

        .toggle-btn {
            font-size: 1.4rem; color: var(--text-dark); border: none; background: none; cursor: pointer; padding: 5px;
        }

        .mobile-brand {
            font-weight: 700; font-size: 1.1rem; color: var(--text-dark); text-decoration: none; display: flex; align-items: center; gap: 8px;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--white);
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            height: 100dvh;
            padding: 25px 20px;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #eee;
            z-index: 1100;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(0);
            overflow-y: hidden;
        }

        .brand {
            display: flex; align-items: center; gap: 10px;
            font-size: 1.2rem; font-weight: 700; color: var(--text-dark);
            margin-bottom: 30px; text-decoration: none; flex-shrink: 0;
        }
        .brand i { color: var(--primary); font-size: 1.5rem; }
        .brand span { font-size: 0.75rem; font-weight: 400; color: var(--text-gray); display: block; margin-top: 2px;}

        .nav-menu {
            list-style: none;
            flex-grow: 1;
            overflow-y: auto;
            margin-bottom: 15px;
            padding-right: 5px;
            -webkit-overflow-scrolling: touch;
        }
        .nav-menu::-webkit-scrollbar { width: 4px; }
        .nav-menu::-webkit-scrollbar-thumb { background-color: #ddd; border-radius: 4px; }

        .nav-item { margin-bottom: 5px; }
        .nav-link {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 15px;
            color: var(--text-gray); text-decoration: none;
            border-radius: 10px; font-weight: 500; font-size: 0.95rem;
            transition: all 0.2s ease;
        }
        .nav-link i { width: 20px; text-align: center; font-size: 1rem; }
        .nav-link.active {
            background-color: var(--primary); color: var(--dark-bg);
            font-weight: 600; box-shadow: 0 4px 12px rgba(255, 196, 0, 0.25);
        }
        .nav-link:hover:not(.active) { background-color: #f8f9fa; color: var(--primary); }

        .user-profile-sidebar {
            padding-top: 15px; border-top: 1px solid #eee; flex-shrink: 0;
            padding-bottom: calc(10px + env(safe-area-inset-bottom));
        }
        .sd-user-info { display: flex; align-items: center; gap: 10px; margin-bottom: 15px; }
        .sd-avatar {
            width: 38px; height: 38px; border-radius: 50%;
            background: var(--primary); color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; flex-shrink: 0;
        }
        .user-details h4 { font-size: 0.85rem; margin-bottom: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 140px;}
        .user-details p { font-size: 0.7rem; color: var(--text-gray); margin: 0;}

        .btn-logout {
            width: 100%; padding: 10px;
            background: #ffe3e3; color: #e03131; border: none;
            border-radius: 8px; font-weight: 600; cursor: pointer;
            font-size: 0.9rem;
        }

        /* --- MAIN CONTENT --- */
        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            padding: 30px;
            transition: margin-left 0.3s;
            min-height: 100vh;
        }

        /* --- OVERLAY --- */
        .overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); z-index: 1090;
            opacity: 0; visibility: hidden; transition: all 0.3s;
            backdrop-filter: blur(2px);
        }
        .overlay.active { opacity: 1; visibility: visible; }

        /* --- RESPONSIVE LOGIC --- */
        @media (max-width: 992px) {
            .mobile-header { display: flex; }

            .sidebar {
                transform: translateX(-100%);
                box-shadow: 5px 0 15px rgba(0,0,0,0.1);
            }
            .sidebar.active { transform: translateX(0); }

            .main-content {
                margin-left: 0;
                margin-top: 30px;
                width: 100%;
                padding: 20px;
                /* TAMBAHAN 2: Padding Top agar judul tidak ketutup header fixed */
                padding-top: 40px !important;
            }
        }

        @media (max-width: 380px) {
            :root { --sidebar-width: 260px; }
            .user-details { max-width: 100px; }
        }
    </style>

    {{-- CSS Modal Logout (Tetap Sama) --}}
    <style>
        .modal-overlay {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.5); z-index: 9999;
            justify-content: center; align-items: center; opacity: 0; transition: opacity 0.3s ease;
        }
        .modal-overlay.show { display: flex; opacity: 1; }
        .modal-box-logout {
            background: #fff; width: 85%; max-width: 350px; padding: 25px;
            border-radius: 16px; text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transform: translateY(20px); transition: transform 0.3s ease;
        }
        .modal-overlay.show .modal-box-logout { transform: translateY(0); }
        .modal-icon-wrapper {
            width: 70px; height: 70px; background-color: #FFF9DB; color: #FFC400;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 28px; margin: 0 auto 15px;
        }
        .modal-title-logout { font-size: 1.2rem; font-weight: 700; margin-bottom: 8px; color: #333; }
        .modal-desc-logout { font-size: 0.85rem; color: #777; margin-bottom: 20px; line-height: 1.4; }
        .modal-actions { display: flex; gap: 10px; justify-content: center; }
        .btn-modal {
            flex: 1; padding: 10px; border-radius: 8px; font-weight: 600; cursor: pointer;
            font-size: 0.9rem; border: none; transition: 0.2s;
        }
        .btn-modal-cancel { background-color: #f1f3f5; color: #555; }
        .btn-modal-confirm { background-color: #ffe3e3; color: #e03131; text-decoration: none; display: flex; align-items: center; justify-content: center;}
    </style>

    @yield('style')
</head>
<body>

    <div class="overlay" onclick="toggleSidebar()"></div>

    <header class="mobile-header">
        <a href="/" class="mobile-brand">
            <i class="fas fa-car-side" style="color: var(--primary);"></i> Bonanza Rental
        </a>
        <button class="toggle-btn" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
    </header>

    <aside class="sidebar" id="sidebar">
        <a href="/" class="brand">
            <i class="fas fa-car-side"></i>
            <div>Bonanza Rental
                <span>
                    @if(auth()->check() && auth()->user()->role)
                        {{ ucfirst(auth()->user()->role->label) }} Portal
                    @else
                        Guest Portal
                    @endif
                </span>
            </div>
        </a>

        <ul class="nav-menu">
            @if(auth()->check())
                @foreach(auth()->user()->getAccessibleMenus() as $menu)
                    <li class="nav-item">
                        <a href="{{ url($menu->url) }}"
                           class="nav-link {{ request()->is(trim($menu->url, '/')) || request()->routeIs($menu->route_name) ? 'active' : '' }}">
                            <i class="{{ $menu->icon }}"></i>
                            {{ $menu->name }}
                        </a>
                    </li>
                @endforeach
            @endif
        </ul>

        @if(auth()->check())
        <div class="user-profile-sidebar">
            <a href="{{ route('profile.index', Auth::id()) }}" style="text-decoration: none; color: inherit;">
                <div class="sd-user-info">
                    <div class="sd-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                    <div class="user-details">
                        <h4>{{ auth()->user()->name }}</h4>
                        <p>{{ Str::limit(auth()->user()->email, 18) }}</p>
                    </div>
                </div>
            </a>
            <button class="btn-logout" onclick="openLogoutModal()">
                <i class="fas fa-sign-out-alt" style="margin-right: 5px;"></i> Logout
            </button>
        </div>
        @endif
    </aside>

    <main class="main-content">
        @yield('content')
    </main>

    <div id="logoutModal" class="modal-overlay">
        <div class="modal-box-logout">
            <div class="modal-icon-wrapper">
                <i class="fas fa-sign-out-alt"></i>
            </div>
            <h3 class="modal-title-logout">Konfirmasi Keluar</h3>
            <p class="modal-desc-logout">Apakah Anda yakin ingin mengakhiri sesi ini?</p>

            <div class="modal-actions">
                <button class="btn-modal btn-modal-cancel" onclick="closeLogoutModal()">Batal</button>
                <a href="{{ route('logout') }}" class="btn-modal btn-modal-confirm">Ya, Keluar</a>
            </div>
        </div>
    </div>

    @yield('script')

    <script>
        // Toggle Sidebar Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.overlay');
            const body = document.body;

            // Toggle status active
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');

            // --- TAMBAHAN 3: LOGIKA KUNCI SCROLL ---
            if (sidebar.classList.contains('active')) {
                body.classList.add('no-scroll'); // Tambah class saat menu buka
            } else {
                body.classList.remove('no-scroll'); // Hapus class saat menu tutup
            }
        }

        // Modal Logout Logic
        function openLogoutModal() {
            document.getElementById('logoutModal').classList.add('show');
            document.body.classList.add('no-scroll'); // Kunci scroll
        }

        function closeLogoutModal() {
            document.getElementById('logoutModal').classList.remove('show');

            // Cek kondisi: Jangan nyalakan scroll jika sidebar masih terbuka di HP
            const sidebar = document.getElementById('sidebar');
            if (!sidebar.classList.contains('active') || window.innerWidth > 992) {
                document.body.classList.remove('no-scroll');
            }
        }

        // Tutup modal jika klik area gelap
        window.onclick = function(event) {
            const modal = document.getElementById('logoutModal');
            if (event.target == modal) {
                closeLogoutModal();
            }
        }
    </script>
</body>
</html>
