@extends('auth.layouts.index')
@section('title', 'Verifikasi OTP')

@section('style')
<style>
    /* === LAYOUT CENTER === */
    .auth-section {
        display: flex; align-items: center; justify-content: center;
        min-height: calc(100vh - 80px); padding: 20px; width: 100%;
    }
    .auth-card {
        background: #fff; width: 100%; max-width: 420px;
        padding: 40px 30px; border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.08); text-align: center;
        border: 1px solid #fff8dd; margin: 0 auto;
    }

    /* === HEADER === */
    h2 { margin-bottom: 5px; color: var(--text-dark); font-weight: 800; font-size: 1.6rem; }
    p.desc { color: #666; font-size: 0.95rem; margin-bottom: 15px; }

    /* === PHONE BADGE === */
    .phone-badge {
        display: inline-flex; align-items: center; gap: 8px;
        background: #fff8dd; color: #d97706;
        font-weight: 700; padding: 8px 15px; border-radius: 50px;
        font-size: 0.95rem; margin-bottom: 20px; border: 1px dashed #f59e0b;
    }

    /* === OTP INPUTS === */
    .otp-container { display: flex; justify-content: center; gap: 12px; margin: 25px 0; }
    .otp-input {
        width: 60px; height: 65px; font-size: 1.8rem; font-weight: 700; text-align: center;
        border: 2px solid #e5e7eb; border-radius: 12px; background: #f9fafb;
        color: var(--text-dark); transition: all 0.2s; outline: none;
    }
    .otp-input:focus {
        border-color: var(--primary); background: #fff;
        box-shadow: 0 0 0 4px rgba(255, 196, 0, 0.15); transform: translateY(-2px);
    }

    /* === BUTTON === */
    .btn-submit {
        width: 100%; padding: 15px; border: none; border-radius: 12px;
        color: #fff; font-weight: 700; font-size: 1rem; cursor: pointer;
        background: linear-gradient(135deg, #FFC400, #F59E0B);
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3); transition: 0.3s;
    }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(245, 158, 11, 0.4); }

    /* === LINKS === */
    .resend-link, .change-number-link {
        display: block; font-size: 0.85rem; text-decoration: none; transition: 0.3s;
    }

    .resend-wrapper { margin-top: 20px; color: #888; }
    .resend-link { color: #ccc; cursor: not-allowed; font-weight: 600; margin-top: 5px; pointer-events: none; }
    .resend-link.active { color: var(--primary); cursor: pointer; pointer-events: auto; }
    .resend-link.active:hover { text-decoration: underline; }

    .change-number-link { margin-top: 15px; color: #999; }
    .change-number-link:hover { color: var(--text-dark); }
</style>
@endsection

@section('content')
<section class="auth-section">
    <div class="auth-card">
        <h2>Verifikasi Akun</h2>
        <p class="desc">Masukkan <strong>4 digit</strong> kode OTP yang dikirim ke:</p>

        <div class="phone-badge">
            <i class="fab fa-whatsapp"></i> {{ Session::get('reset_phone') }}
        </div>

        @if(session('error'))
            <div style="background:#fee2e2; color:#ef4444; padding:10px; border-radius:8px; margin-bottom:20px; font-size:0.85rem; border: 1px solid #fecaca;">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div style="background:#ecfdf5; color:#059669; padding:10px; border-radius:8px; margin-bottom:20px; font-size:0.85rem; border: 1px solid #a7f3d0;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.verify.process') }}" id="otpForm">
            @csrf

            <div class="otp-container">
                <input type="text" class="otp-input" maxlength="1" id="d1" oninput="inputFocus(this, 'd2')" onkeydown="inputBackspace(this, null)" autofocus pattern="\d*" inputmode="numeric">
                <input type="text" class="otp-input" maxlength="1" id="d2" oninput="inputFocus(this, 'd3')" onkeydown="inputBackspace(this, 'd1')" pattern="\d*" inputmode="numeric">
                <input type="text" class="otp-input" maxlength="1" id="d3" oninput="inputFocus(this, 'd4')" onkeydown="inputBackspace(this, 'd2')" pattern="\d*" inputmode="numeric">
                <input type="text" class="otp-input" maxlength="1" id="d4" oninput="inputFocus(this, null)" onkeydown="inputBackspace(this, 'd3')" pattern="\d*" inputmode="numeric">
            </div>

            <input type="hidden" name="otp" id="realOtp">

            <button type="button" onclick="submitOtp()" class="btn-submit">
                Verifikasi Sekarang <i class="fas fa-arrow-right" style="margin-left:5px; font-size: 0.9rem;"></i>
            </button>
        </form>

        {{-- RESEND TIMER --}}
        <div class="resend-wrapper">
            <span id="timerText">Kirim ulang dalam <strong style="color:var(--text-dark)" id="countdown">01:00</strong></span>

            {{-- Karena di Controller sendOtp pakai method POST, kita pakai form hidden untuk resend --}}
            <form id="resendForm" action="{{ route('password.email') }}" method="POST" style="display: none;">
                @csrf
                {{-- Kita kirim ulang email/hp dari session user yang tersimpan di DB --}}
                @php
                    $userResend = \App\Models\User::where('phone', Session::get('reset_phone'))->first();
                @endphp
                <input type="hidden" name="email" value="{{ $userResend->email ?? '' }}">
            </form>

            <a href="#" class="resend-link" id="resendLink" onclick="event.preventDefault(); document.getElementById('resendForm').submit();">
                <i class="fas fa-redo-alt"></i> Kirim Ulang Kode
            </a>
        </div>

        {{-- GANTI NOMOR (RESET SESSION) --}}
        <a href="{{ route('password.reset.session') }}" class="change-number-link">
            Bukan nomor Anda? <span style="color: var(--primary); font-weight: 700;">Ganti Nomor</span>
        </a>
    </div>
</section>
@endsection

@section('scripts')
<script>
    // 1. PINDAH FOKUS OTOMATIS
    function inputFocus(elm, nextId) {
        elm.value = elm.value.replace(/[^0-9]/g, ''); // Hanya Angka
        if (elm.value.length >= 1 && nextId) {
            document.getElementById(nextId).focus();
        }
    }

    // 2. BACKSPACE (MUNDUR)
    function inputBackspace(elm, prevId) {
        if (event.key === 'Backspace' && elm.value === '' && prevId) {
            document.getElementById(prevId).focus();
        }
    }

    // 3. SUBMIT FORM
    function submitOtp() {
        let code = '';
        for(let i=1; i<=4; i++) {
            code += document.getElementById('d'+i).value;
        }

        if(code.length < 4) {
            // Toastr JS manual call jika library terload, atau alert biasa
            alert('Harap masukkan 4 digit kode OTP.');
            return;
        }

        document.getElementById('realOtp').value = code;
        document.getElementById('otpForm').submit();
    }

    // 4. TIMER MUNDUR 60 DETIK
    let timeLeft = 60;
    const timerText = document.getElementById('timerText');
    const countdownEl = document.getElementById('countdown');
    const resendLink = document.getElementById('resendLink');

    const timer = setInterval(() => {
        if (timeLeft <= 0) {
            clearInterval(timer);
            // Waktu Habis: Tampilkan Tombol Resend
            timerText.style.display = 'none';
            resendLink.classList.add('active'); // Aktifkan link
        } else {
            // Update Teks
            let m = Math.floor(timeLeft / 60);
            let s = timeLeft % 60;
            countdownEl.innerText = `${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`;
            timeLeft--;
        }
    }, 1000);
</script>
@endsection
