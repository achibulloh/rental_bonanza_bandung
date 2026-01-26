@extends('auth.layouts.index')
@section('title', 'Verifikasi OTP')

@section('style')
    <style>
        /* === STYLE KHUSUS HALAMAN OTP === */

        /* Pastikan parent container menggunakan flex untuk centering */
        /* Jika class .auth-section sudah ada di layout utama, style ini akan menimpa/melengkapi */
        .auth-section {
            display: flex;
            align-items: center;     /* Center Vertikal */
            justify-content: center; /* Center Horizontal */
            min-height: calc(100vh - 80px); /* Kurangi tinggi header jika ada */
            padding: 20px;
            width: 100%;
        }

        /* Container Form */
        .auth-card {
            background: #ffffff;
            width: 100%;
            max-width: 420px; /* Lebar ideal untuk form OTP */
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
            text-align: center;
            border: 1px solid #fff8dd;
            /* Margin auto tidak diperlukan jika parent sudah flex center, tapi bagus untuk fallback */
            margin: 0 auto;
        }

        /* Header Teks */
        .auth-header h2 {
            font-size: 1.6rem;
            margin-bottom: 10px;
            color: var(--text-dark);
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .auth-header p {
            color: var(--text-gray);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        /* Highlight Nomor HP */
        .phone-badge {
            display: inline-block;
            background: #fff8dd;
            color: #d97706;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.9rem;
            margin-top: 5px;
            border: 1px dashed #f59e0b;
        }

        /* --- OTP INPUT BOXES (4 DIGIT) --- */
        .otp-container {
            display: flex;
            justify-content: center;
            gap: 15px; /* Jarak antar kotak */
            margin: 30px 0;
        }

        .otp-input {
            width: 60px;
            height: 65px;
            font-size: 1.8rem;
            font-weight: 700;
            text-align: center;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            background: #f9fafb;
            color: var(--text-dark);
            transition: all 0.2s ease-in-out;
            outline: none;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
        }

        /* Efek saat diklik/fokus */
        .otp-input:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(255, 196, 0, 0.15);
            transform: translateY(-2px);
        }

        /* --- TOMBOL VERIFIKASI --- */
        .btn-submit {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            background: linear-gradient(135deg, #FFC400, #F59E0B);
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.4);
        }
        .btn-submit:active { transform: translateY(0); }

        /* --- RESEND & TIMER --- */
        .resend-wrapper { margin-top: 30px; font-size: 0.9rem; color: var(--text-gray); }

        .timer-count { font-weight: 700; color: #d97706; }

        .btn-resend {
            display: inline-flex; align-items: center; gap: 5px;
            margin-top: 8px;
            background: none; border: none;
            font-weight: 600; font-size: 0.9rem;
            color: #9ca3af; /* Disabled color */
            cursor: not-allowed; text-decoration: none;
            transition: 0.3s;
        }

        .btn-resend.active {
            color: var(--primary-hover);
            cursor: pointer;
        }
        .btn-resend.active:hover { text-decoration: underline; }

        /* RESPONSIVE HP */
        @media (max-width: 400px) {
            .otp-input { width: 50px; height: 55px; font-size: 1.4rem; gap: 10px; }
            .auth-card { padding: 30px 20px; }
        }
    </style>
@endsection

@section('content')
<section class="auth-section">
    <div class="auth-card">

        <div class="auth-header">
            <h2>Verifikasi Akun</h2>
            <p>Masukkan <strong>4 digit</strong> kode OTP yang telah kami kirim ke WhatsApp:</p>
            <div class="phone-badge">
                <i class="fab fa-whatsapp"></i> {{ Session::get('verification_phone') }}
            </div>
        </div>

        {{-- NOTIFIKASI --}}
        @if(session('error'))
            <div style="background: #fee2e2; color: #ef4444; padding: 10px; border-radius: 8px; font-size: 0.85rem; margin-bottom: 20px; border: 1px solid #fecaca; text-align: left; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div style="background: #ecfdf5; color: #059669; padding: 10px; border-radius: 8px; font-size: 0.85rem; margin-bottom: 20px; border: 1px solid #a7f3d0; text-align: left; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('otp.process') }}" id="otpForm">
            @csrf

            <div class="otp-container">
                {{-- 4 INPUT BOXES SAJA --}}
                <input type="text" class="otp-input" maxlength="1" id="d1" oninput="inputFocus(this, 'd2')" onkeydown="inputBackspace(this, null)" autofocus pattern="\d*" inputmode="numeric">
                <input type="text" class="otp-input" maxlength="1" id="d2" oninput="inputFocus(this, 'd3')" onkeydown="inputBackspace(this, 'd1')" pattern="\d*" inputmode="numeric">
                <input type="text" class="otp-input" maxlength="1" id="d3" oninput="inputFocus(this, 'd4')" onkeydown="inputBackspace(this, 'd2')" pattern="\d*" inputmode="numeric">
                <input type="text" class="otp-input" maxlength="1" id="d4" oninput="inputFocus(this, null)" onkeydown="inputBackspace(this, 'd3')" pattern="\d*" inputmode="numeric">
            </div>

            {{-- Input Hidden untuk dikirim ke Controller --}}
            <input type="hidden" name="otp" id="realOtp">

            <button type="button" onclick="submitOtp()" class="btn-submit">
                Verifikasi Sekarang <i class="fas fa-arrow-right" style="margin-left:5px; font-size: 0.9rem;"></i>
            </button>
        </form>

        <div class="resend-wrapper">
            <span id="timerText">Kirim ulang dalam <span class="timer-count" id="countdown">01:00</span></span>
            <br>
            <a href="{{ route('otp.resend') }}" class="btn-resend" id="resendLink">
                <i class="fas fa-redo-alt"></i> Kirim Ulang Kode
            </a>
        </div>

    </div>
</section>
@endsection

@section('scripts')
<script>
    // --- 1. LOGIKA PINDAH FOKUS OTOMATIS (4 DIGIT) ---
    function inputFocus(elm, nextId) {
        // Hanya izinkan angka
        elm.value = elm.value.replace(/[^0-9]/g, '');

        // Pindah ke next input jika ada isinya
        if (elm.value.length >= 1 && nextId) {
            document.getElementById(nextId).focus();
        }
    }

    // --- 2. LOGIKA BACKSPACE (MUNDUR) ---
    function inputBackspace(elm, prevId) {
        if (event.key === 'Backspace' && elm.value === '' && prevId) {
            document.getElementById(prevId).focus();
        }
    }

    // --- 3. GABUNGKAN INPUT SAAT SUBMIT ---
    function submitOtp() {
        let code = '';
        // Loop 4 kali saja
        for(let i=1; i<=4; i++) {
            code += document.getElementById('d'+i).value;
        }

        if(code.length < 4) {
            // Alert sederhana
            alert('Harap masukkan 4 digit kode OTP.');
            return;
        }

        document.getElementById('realOtp').value = code;
        document.getElementById('otpForm').submit();
    }

    // --- 4. TIMER MUNDUR 60 DETIK ---
    let timeLeft = 60;
    const timerText = document.getElementById('timerText');
    const countdownEl = document.getElementById('countdown');
    const resendLink = document.getElementById('resendLink');

    // Matikan link saat awal
    resendLink.removeAttribute('href');

    const timer = setInterval(() => {
        if (timeLeft <= 0) {
            clearInterval(timer);
            // Waktu Habis: Tampilkan Tombol Resend
            timerText.style.display = 'none';
            resendLink.classList.add('active');
            resendLink.setAttribute('href', "{{ route('otp.resend') }}");
            resendLink.innerHTML = '<i class="fas fa-redo-alt"></i> Kirim Ulang Kode Sekarang';
        } else {
            // Hitung menit:detik
            let m = Math.floor(timeLeft / 60);
            let s = timeLeft % 60;
            countdownEl.innerText = `${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`;
            timeLeft--;
        }
    }, 1000);
</script>
@endsection
