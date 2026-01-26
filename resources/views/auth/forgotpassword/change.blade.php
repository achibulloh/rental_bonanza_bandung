@extends('auth.layouts.index')
@section('title', 'Buat Password Baru')

@section('style')
<style>
    /* === LAYOUT CENTER === */
    .auth-section {
        display: flex; align-items: center; justify-content: center;
        min-height: calc(100vh - 80px); padding: 20px; width: 100%;
    }
    .auth-card {
        background: #ffffff; width: 100%; max-width: 450px;
        padding: 40px 35px; border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.08);
        text-align: center; border: 1px solid #fff8dd; margin: 0 auto;
    }

    /* === HEADER ICON (Gembok) === */
    .icon-header {
        width: 80px; height: 80px; background: #fff8dd; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 2.2rem; color: var(--primary); margin: 0 auto 20px;
        box-shadow: 0 4px 15px rgba(255, 196, 0, 0.15);
    }

    h2 { margin-bottom: 10px; color: var(--text-dark); font-weight: 800; font-size: 1.6rem; }
    p.desc { color: var(--text-gray); font-size: 0.95rem; margin-bottom: 30px; line-height: 1.6; }

    /* === FORM INPUTS === */
    .form-group { text-align: left; margin-bottom: 20px; }

    .form-label {
        font-size: 0.9rem; font-weight: 600; color: var(--text-dark);
        margin-bottom: 8px; display: block;
    }

    .input-wrapper { position: relative; }

    .form-control {
        width: 100%; padding: 14px 15px; padding-right: 45px; /* Space for eye icon */
        border: 2px solid #e5e7eb; border-radius: 12px; outline: none;
        transition: 0.3s; font-size: 1rem; background: #f9fafb;
    }
    .form-control:focus {
        border-color: var(--primary); background: #fff;
        box-shadow: 0 0 0 4px rgba(255, 196, 0, 0.15);
    }

    /* === EYE ICON TOGGLE === */
    .toggle-password {
        position: absolute; right: 15px; top: 50%; transform: translateY(-50%);
        color: #9ca3af; cursor: pointer; font-size: 1.1rem; transition: 0.3s;
    }
    .toggle-password:hover { color: var(--primary); }

    /* === BUTTON === */
    .btn-submit {
        width: 100%; padding: 15px; border: none; border-radius: 12px;
        color: #fff; font-weight: 700; font-size: 1rem; cursor: pointer;
        background: linear-gradient(135deg, #FFC400, #F59E0B);
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3); transition: 0.3s; margin-top: 10px;
    }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(245, 158, 11, 0.4); }
</style>
@endsection

@section('content')
<section class="auth-section">
    <div class="auth-card">
        <div class="icon-header">
            <i class="fas fa-lock"></i>
        </div>

        <h2>Buat Password Baru</h2>
        <p class="desc">
            Verifikasi berhasil! Silakan buat password baru yang kuat untuk mengamankan akun Anda.
        </p>

        {{-- TAMPILKAN ERROR VALIDASI --}}
        @if($errors->any())
            <div style="background: #fee2e2; color: #ef4444; padding: 12px; border-radius: 8px; font-size: 0.9rem; margin-bottom: 20px; border: 1px solid #fecaca; text-align: left;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            {{-- INPUT PASSWORD BARU --}}
            <div class="form-group">
                <label class="form-label">Password Baru</label>
                <div class="input-wrapper">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 6 karakter" required autofocus>
                    <i class="fas fa-eye toggle-password" onclick="togglePass('password', this)"></i>
                </div>
            </div>

            {{-- KONFIRMASI PASSWORD --}}
            <div class="form-group">
                <label class="form-label">Konfirmasi Password</label>
                <div class="input-wrapper">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi password baru" required>
                    <i class="fas fa-eye toggle-password" onclick="togglePass('password_confirmation', this)"></i>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                Simpan Password Baru <i class="fas fa-save" style="margin-left: 5px;"></i>
            </button>
        </form>
    </div>
</section>
@endsection

@section('scripts')
<script>
    // FUNGSI LIHAT/SEMBUNYIKAN PASSWORD
    function togglePass(inputId, icon) {
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
