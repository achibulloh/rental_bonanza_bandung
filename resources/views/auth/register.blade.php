@extends('auth.layouts.index')
@section('title', 'Register')
@section('style')
    <style>
        :root {
            --primary: #FFC400;
            --dark-bg: #111111;
            --text-dark: #333333;
            --text-gray: #666666;
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

        header { background-color: var(--dark-bg); padding: 20px 0; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .nav-container { display: flex; justify-content: space-between; align-items: center; }
        .logo { color: var(--primary); font-size: 1.5rem; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 10px; }
        .btn-back { color: #fff; text-decoration: none; font-size: 0.9rem; }

        .auth-section { flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px 20px; }

        .auth-card {
            background: #ffffff;
            width: 100%;
            max-width: 600px;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid #fff8dd;
        }

        .auth-header { text-align: center; margin-bottom: 30px; }
        .auth-header h2 { font-size: 1.5rem; margin-bottom: 10px; }
        .auth-header p { color: var(--text-gray); font-size: 0.9rem; }

        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-size: 0.9rem; font-weight: 500; }

        .input-wrapper { position: relative; display: flex; align-items: center; }
        .input-wrapper i.icon { position: absolute; left: 15px; color: var(--primary); font-size: 1.1rem; z-index: 1; }

        .form-control {
            width: 100%; padding: 12px 15px 12px 45px;
            border: 1px solid var(--primary); border-radius: 8px;
            font-size: 0.95rem; outline: none; color: var(--text-dark);
        }
        .form-control:focus { box-shadow: 0 0 0 3px rgba(255, 196, 0, 0.2); }

        .toggle-password { position: absolute; right: 15px; cursor: pointer; color: #ccc; }

        .terms { display: flex; align-items: center; gap: 10px; font-size: 0.9rem; margin: 20px 0; }
        .terms input { width: 16px; height: 16px; accent-color: var(--primary); }
        .terms a { color: #d4a000; text-decoration: none; }

        .btn-submit {
            width: 100%; padding: 14px; background: linear-gradient(45deg, #FFC400, #ffb300);
            border: none; border-radius: 8px; color: #fff; font-weight: 600; font-size: 1rem; cursor: pointer;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1); transition: 0.3s;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(255, 196, 0, 0.3); }

        /* --- STYLE GOOGLE (BARU) --- */
        .divider { display: flex; align-items: center; margin: 25px 0; color: #aaa; font-size: 0.85rem; }
        .divider::before, .divider::after { content: ""; flex: 1; height: 1px; background: #eee; margin: 0 10px; }

        .btn-google {
            width: 100%; padding: 12px; background: #fff; border: 1px solid #ddd;
            border-radius: 8px; display: flex; align-items: center; justify-content: center; gap: 10px;
            color: #555; font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: 0.3s; text-decoration: none;
        }
        .btn-google:hover { background: #f9f9f9; border-color: #ccc; transform: translateY(-1px); }
        .btn-google svg { width: 20px; height: 20px; }

        .auth-footer { text-align: center; margin-top: 25px; font-size: 0.9rem; color: var(--text-gray); }
        .auth-footer a { color: var(--primary); text-decoration: none; font-weight: 600; }

        @media (max-width: 480px) { .auth-card { padding: 30px 20px; } }
    </style>
@endsection

@section('content')
    <section class="auth-section">
        <div class="auth-card">
            <div class="auth-header">
                <h2>Daftar Akun Pelanggan</h2>
                <p>Lengkapi data diri Anda untuk mulai menyewa mobil dengan mudah.</p>
            </div>

            <form method="POST" action="{{ route('auth_prosses_register') }}">
                @csrf

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <div class="input-wrapper">
                        <i class="far fa-user icon"></i>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan nama lengkap Anda" value="{{ old('name') }}" required>
                    </div>
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <div class="input-wrapper">
                        <i class="far fa-envelope icon"></i>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan email aktif Anda" value="{{ old('email') }}" required>
                    </div>
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label>Nomor Telepon</label>
                    <div class="input-wrapper">
                        <i class="fas fa-phone icon"></i>
                        <input type="number" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="Masukkan nomor telepon / WhatsApp" value="{{ old('phone') }}" required>
                    </div>
                    @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock icon"></i>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="regPass" placeholder="Buat password (min. 8 karakter)" required>
                        <i class="far fa-eye toggle-password" onclick="togglePassword('regPass', this)"></i>
                    </div>
                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock icon"></i>
                        <input type="password" name="password_confirmation" class="form-control" id="regConfirmPass" placeholder="Ulangi password Anda" required>
                        <i class="far fa-eye toggle-password" onclick="togglePassword('regConfirmPass', this)"></i>
                    </div>
                </div>

                <div class="terms">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms" style="margin:0; font-weight:400;">Saya menyetujui <a href="#">syarat & ketentuan</a></label>
                </div>
                @error('terms') <small class="text-danger d-block mb-2">{{ $message }}</small> @enderror

                <button type="submit" class="btn-submit">Daftar Sekarang</button>
            </form>

            <div class="divider">atau daftar dengan</div>

            <a href="{{ route('auth.google.register') }}" class="btn-google">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48">
                    <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/>
                    <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/>
                    <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/>
                    <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/>
                </svg>
                Daftar dengan Google
            </a>
            <div class="auth-footer">
                Sudah punya akun? <a href="{{ route('login') }}">Login</a>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
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
