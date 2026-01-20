@extends('auth.layouts.index')
@section('title', 'Login')
@section('style')
    <style>
        :root {
            --primary: #FFC400;
            --primary-hover: #e0ac00;
            --dark-bg: #111111;
            --text-dark: #333333;
            --text-gray: #666666;
            --border-color: #ffeeb0;
            --bg-page: #fffcf5;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }

        body {
            background-color: var(--bg-page);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* --- HEADER --- */
        header { background-color: var(--dark-bg); padding: 20px 0; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .nav-container { display: flex; justify-content: space-between; align-items: center; }
        .logo { color: var(--primary); font-size: 1.5rem; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 10px; }
        .btn-back { color: #fff; text-decoration: none; font-size: 0.9rem; }
        .btn-back:hover { color: var(--primary); }

        /* --- AUTH SECTION --- */
        .auth-section { flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px 20px; }

        .auth-card {
            background: #ffffff;
            width: 100%;
            max-width: 500px;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            text-align: center;
            border: 1px solid #fff8dd;
        }

        .auth-header { margin-bottom: 30px; }
        .auth-header h2 { font-size: 1.5rem; margin-bottom: 10px; color: var(--text-dark); }
        .auth-header p { color: var(--text-gray); font-size: 0.9rem; }

        /* --- FORM STYLES --- */
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-group label { display: block; margin-bottom: 8px; font-size: 0.9rem; color: var(--text-dark); font-weight: 500; }

        .input-wrapper { position: relative; display: flex; align-items: center; }
        .input-wrapper i.icon { position: absolute; left: 15px; color: var(--primary); font-size: 1.1rem; z-index: 1; }

        .form-control {
            width: 100%; padding: 12px 15px 12px 45px;
            border: 1px solid var(--primary);
            border-radius: 8px; font-size: 0.95rem; outline: none; transition: 0.3s;
            color: var(--text-dark);
        }
        .form-control:focus { box-shadow: 0 0 0 3px rgba(255, 196, 0, 0.2); }

        .toggle-password { position: absolute; right: 15px; cursor: pointer; color: #ccc; }
        .toggle-password:hover { color: var(--primary); }

        .forgot-pass { display: block; text-align: right; color: var(--primary); font-size: 0.85rem; margin-top: 5px; text-decoration: none; }

        .btn-submit {
            width: 100%; padding: 14px; border: none; border-radius: 8px; color: #fff;
            font-weight: 600; font-size: 1rem; cursor: pointer; margin-top: 20px; transition: 0.3s;
            background: linear-gradient(45deg, #FFC400, #ffb300);
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(255, 196, 0, 0.3); }

        /* --- GOOGLE LOGIN STYLES (BARU) --- */
        .divider {
            display: flex; align-items: center; margin: 25px 0; color: #aaa; font-size: 0.85rem;
        }
        .divider::before, .divider::after {
            content: ""; flex: 1; height: 1px; background: #eee; margin: 0 10px;
        }

        .btn-google {
            width: 100%; padding: 12px;
            background: #fff; border: 1px solid #ddd; border-radius: 8px;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            color: #555; font-weight: 600; font-size: 0.95rem;
            cursor: pointer; transition: 0.3s; text-decoration: none;
        }
        .btn-google:hover { background: #f9f9f9; border-color: #ccc; transform: translateY(-1px); }
        .btn-google img { width: 20px; height: 20px; }
        .btn-google i { font-size: 1.1rem; color: #DB4437; } /* Warna Merah Google jika pakai icon fontawesome */

        .auth-footer { margin-top: 25px; font-size: 0.9rem; color: var(--text-gray); }
        .auth-footer a { color: var(--primary); text-decoration: none; font-weight: 600; }

        @media (max-width: 480px) { .auth-card { padding: 30px 20px; } }
    </style>
@endsection

@section('content')
    <section class="auth-section">
        <div class="auth-card">
            <div class="auth-header">
                <h2>Masuk ke Akun Anda</h2>
                <p>Silakan login untuk melanjutkan pemesanan mobil.</p>
            </div>

            <form method="POST" action="{{ route('auth_prosses_login') }}">
                @csrf
                <div class="form-group">
                    <label>Email</label>
                    <div class="input-wrapper">
                        <i class="far fa-envelope icon"></i>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Masukkan email Anda" required>
                    </div>
                    @error('email')
                        <div class="invalid-feedback" style="color: red; font-size: 0.8rem; text-align: left; margin-top: 5px;">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock icon"></i>
                        <input type="password" class="form-control" name="password" id="passwordInput" placeholder="Masukkan password Anda" required>
                        <i class="far fa-eye toggle-password" onclick="togglePassword('passwordInput')"></i>
                    </div>
                    <a href="/lupa-password" class="forgot-pass">Lupa Password?</a>
                </div>

                <button type="submit" class="btn-submit">Login</button>
            </form>

            <div class="divider">atau masuk dengan</div>

            <a href="{{ route('auth.google.login') }}" class="btn-google">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48">
                    <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/>
                    <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/>
                    <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/>
                    <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/>
                </svg>
                <span>Masuk dengan Google</span>
            </a>
            <div class="auth-footer">
                Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = event.target;
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
@endsection
