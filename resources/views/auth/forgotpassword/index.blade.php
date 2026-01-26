@extends('auth.layouts.index')
@section('title', 'Lupa Password')

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
    .icon-header {
        width: 80px; height: 80px; background: #fff8dd; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 2.2rem; color: var(--primary); margin: 0 auto 20px;
    }
    h2 { margin-bottom: 10px; color: var(--text-dark); font-weight: 800; font-size: 1.6rem; }
    p.desc { color: var(--text-gray); font-size: 0.95rem; margin-bottom: 30px; line-height: 1.6; }

    .form-control {
        width: 100%; padding: 14px 15px; border: 2px solid #e5e7eb;
        border-radius: 12px; outline: none; transition: 0.3s;
        font-size: 1rem; background: #f9fafb; margin-bottom: 20px;
    }
    .form-control:focus { border-color: var(--primary); background: #fff; }

    .btn-submit {
        width: 100%; padding: 15px; border: none; border-radius: 12px;
        color: #fff; font-weight: 700; font-size: 1rem; cursor: pointer;
        background: linear-gradient(135deg, #FFC400, #F59E0B);
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3); transition: 0.3s;
    }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(245, 158, 11, 0.4); }

    .back-link {
        display: inline-flex; align-items: center; gap: 8px; margin-top: 25px;
        color: var(--text-gray); text-decoration: none; font-weight: 600; font-size: 0.9rem;
    }
    .back-link:hover { color: var(--primary); }
</style>
@endsection

@section('content')
<section class="auth-section">
    <div class="auth-card">
        <div class="icon-header"><i class="fas fa-search"></i></div>

        <h2>Cari Akun Anda</h2>
        <p class="desc">
            Masukkan <strong>Email</strong> atau <strong>Nomor WhatsApp</strong> yang terdaftar.
            Kami akan mengirimkan kode OTP untuk reset password.
        </p>

        @if(session('error'))
            <div style="background: #fee2e2; color: #ef4444; padding: 12px; border-radius: 8px; font-size: 0.9rem; margin-bottom: 20px; border: 1px solid #fecaca; text-align: left; display: flex; gap: 10px; align-items: center;">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div style="text-align: left;">
                <label style="font-weight: 600; margin-bottom: 5px; display:block;">Email / No. WhatsApp</label>
                <input type="text" name="email" class="form-control" placeholder="Contoh: 0812xxx atau user@email.com" required autofocus value="{{ old('email') }}">
            </div>

            <button type="submit" class="btn-submit">
                Kirim Kode OTP <i class="fas fa-arrow-right" style="margin-left: 5px;"></i>
            </button>
        </form>

        <a href="{{ route('login') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Halaman Login
        </a>
    </div>
</section>
@endsection
