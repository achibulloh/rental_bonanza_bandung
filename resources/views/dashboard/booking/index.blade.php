@extends('dashboard.layouts.index')
@section('title', 'Proses Booking')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Load Midtrans JS hanya jika Step 4 DAN bukan Transfer Manual --}}
    @if($step == 4 && (!isset($paymentMethod) || $paymentMethod != 'transfer'))
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ \App\Models\Setting::get('clientkey') }}"></script>
    @endif

    <style>
        :root {
            --primary: #FFC400;
            --primary-dark: #e6b000;
            --text-dark: #111;
            --text-gray: #687176;
            --bg-light: #f7f9fa;
        }

        .main-content { flex: 1; margin-left: var(--sidebar-width); padding: 30px 40px; background: var(--bg-light); font-family: 'Poppins', sans-serif; color: var(--text-dark); min-height: 100vh; }

        /* --- STEPPER (DIPERBAIKI) --- */
        .stepper-container { background: #fff; padding: 25px 40px; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 30px; display: flex; justify-content: space-between; position: relative; }

        /* Garis Track (Abu-abu) - Menjadi wadah utama */
        .stepper-track {
            position: absolute;
            top: 50%;
            left: 65px; /* Disesuaikan agar pas di tengah lingkaran pertama */
            right: 65px; /* Disesuaikan agar pas di tengah lingkaran terakhir */
            height: 4px;
            background: #eee;
            transform: translateY(-50%);
            z-index: 0;
            border-radius: 4px;
        }

        /* Garis Fill (Kuning) - Sekarang ada DI DALAM track */
        .stepper-fill {
            height: 100%;
            background: var(--primary);
            border-radius: 4px;
            transition: width 0.4s ease;
        }

        .step-item { z-index: 1; text-align: center; background: #fff; padding: 0 10px; display: flex; flex-direction: column; align-items: center; gap: 8px; }
        .step-circle { width: 40px; height: 40px; border-radius: 50%; background: #fff; border: 2px solid #ddd; color: #bbb; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.95rem; transition: 0.3s; }
        .step-label { font-size: 0.85rem; font-weight: 600; color: #bbb; }

        .step-item.active .step-circle { border-color: var(--primary); background: var(--primary); color: #000; box-shadow: 0 0 0 4px rgba(255,196,0,0.15); }
        .step-item.active .step-label { color: #000; }
        .step-item.completed .step-circle { background: var(--primary); border-color: var(--primary); color: #000; }
        .step-item.completed .step-label { color: var(--primary-dark); }

        /* LAYOUT */
        .booking-layout { display: grid; grid-template-columns: 2fr 1.1fr; gap: 30px; align-items: start; }
        .card-box { background: #fff; border-radius: 12px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border: 1px solid #e0e0e0; margin-bottom: 20px; }
        .card-header-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; padding-bottom: 15px; border-bottom: 1px solid #eee; }
        .card-header-title i { color: var(--primary-dark); font-size: 1.2rem; }

        /* SERVICE OPTIONS */
        .service-options { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px; }
        .service-option-item { position: relative; height: 100%; }
        .service-radio { position: absolute; opacity: 0; width: 0; height: 0; }
        .service-label { display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; background: #fff; border: 2px solid #eee; padding: 20px; border-radius: 12px; cursor: pointer; transition: all 0.3s ease; height: 100%; color: #666; }
        .service-label:hover { border-color: var(--primary); background-color: #fffdf5; }
        .service-radio:checked + .service-label { border-color: var(--primary); background-color: #fff9db; color: #000; font-weight: 700; box-shadow: 0 0 0 1px var(--primary) inset; }
        .service-label i { font-size: 1.8rem; margin-bottom: 10px; color: #aaa; transition: 0.3s; }
        .service-radio:checked + .service-label i { color: #000; }

        .pkg-price { font-size: 0.9rem; margin-top: 5px; color: var(--primary-dark); font-weight: 700; }
        .pkg-desc { font-size: 0.75rem; color: #888; font-weight: 400; margin-top: 5px; }
        .form-label { font-size: 0.85rem; font-weight: 600; margin-bottom: 8px; display: block; color: #444; }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid #ced4da; border-radius: 8px; font-size: 0.95rem; background: #fff; }
        .form-control:focus { border-color: var(--primary); outline: none; box-shadow: 0 0 0 3px rgba(255,196,0,0.1); }
        .btn-main { background: var(--primary); color: #000; border: none; padding: 15px; width: 100%; border-radius: 10px; font-weight: 700; font-size: 1rem; cursor: pointer; transition: 0.3s; display: flex; justify-content: center; align-items: center; gap: 10px; }
        .btn-main:hover { background: var(--primary-dark); box-shadow: 0 5px 15px rgba(255,196,0,0.2); }

        /* BANK TRANSFER BOX */
        .bank-box { background: #f8f9fa; border: 2px dashed #ccc; padding: 20px; border-radius: 10px; text-align: center; margin-bottom: 20px; }
        .bank-name { font-weight: 800; font-size: 1.2rem; color: #0d6efd; text-transform: uppercase; margin-top: 5px; }
        .bank-rek { font-family: monospace; font-size: 1.5rem; letter-spacing: 2px; font-weight: 700; margin: 10px 0; display: block; color: #333; }
        .bank-an { font-size: 0.9rem; color: #555; }

        .sticky-summary { position: sticky; top: 20px; }
        .car-hero-img { width: 100%; height: 160px; object-fit: cover; border-radius: 10px; margin-bottom: 15px; }
        .timeline-box { position: relative; padding-left: 20px; margin-bottom: 20px; border-left: 2px solid #eee; }
        .timeline-item { position: relative; margin-bottom: 15px; }
        .timeline-item::before { content: ''; position: absolute; left: -25px; top: 5px; width: 8px; height: 8px; background: var(--primary); border-radius: 50%; }
        .t-label { font-size: 0.75rem; color: #888; text-transform: uppercase; font-weight: 600; }
        .t-val { font-size: 0.9rem; font-weight: 600; color: var(--text-dark); }
        .price-total { border-top: 2px dashed #ddd; padding-top: 15px; margin-top: 10px; display: flex; justify-content: space-between; align-items: center; }
        .total-value { font-size: 1.3rem; font-weight: 700; color: var(--primary-dark); }

        @media (max-width: 992px) {
            .booking-layout { grid-template-columns: 1fr; display: flex; flex-direction: column-reverse; }
            .main-content { margin-left: 0 !important; padding: 20px !important; }
            .stepper-container { padding: 15px; }
            .service-options { grid-template-columns: 1fr 1fr; }
            .step-circle { width: 30px; height: 30px; font-size: 0.8rem; }
            /* Mobile adjustment for stepper line */
            .stepper-track { left: 40px; right: 40px; }
        }
        @media (max-width: 480px) {
            .service-options { grid-template-columns: 1fr; }
        }

        /* --- CUSTOM MODAL DENGAN ANIMASI --- */
        .modal-overlay {
            display: none; /* Default sembunyi */
            position: fixed;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            background: rgba(0, 0, 0, 0.6);
            z-index: 2147483647 !important;
            align-items: center;
            justify-content: center;
            opacity: 0; /* Mulai transparan */
            transition: opacity 0.3s ease; /* Transisi halus */
        }

        /* Saat class .show ditambahkan via JS */
        .modal-overlay.show {
            display: flex !important;
            opacity: 1; /* Jadi terlihat */
        }

        .modal-box {
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
            position: relative;
            transform: scale(0.8); /* Mulai agak kecil */
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); /* Efek membal (bouncy) */
        }

        /* Saat overlay punya class .show, box-nya membesar normal */
        .modal-overlay.show .modal-box {
            transform: scale(1);
        }

        /* --- Style Icon & Text Tetap Sama --- */
        .modal-icon { width: 60px; height: 60px; background: #fff9db; color: #e6b000; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 30px; margin: 0 auto 20px; }
        .modal-title { font-size: 1.2rem; font-weight: 700; margin-bottom: 10px; color: #000; }
        .modal-text { font-size: 0.9rem; color: #666; margin-bottom: 25px; }
        .modal-buttons { display: flex; gap: 10px; }
        .btn-cancel { background: #f1f3f5; color: #555; border: none; padding: 12px; border-radius: 8px; font-weight: 600; cursor: pointer; flex: 1; }
        .btn-confirm { background: #FFC400; color: #000; border: none; padding: 12px; border-radius: 8px; font-weight: 700; cursor: pointer; flex: 1; transition: 0.2s; }
        .btn-confirm:hover { background: #e6b000; transform: translateY(-2px); }
    </style>
@endsection

@section('content')

    <div class="header-area" style="margin-bottom: 30px;">
        <h1 style="font-size: 1.8rem; font-weight: 700; margin-bottom: 5px;">Booking Mobil</h1>
        <div style="color: #666;">Lengkapi data perjalanan Anda untuk melanjutkan pemesanan.</div>
    </div>

    <div class="stepper-container">

        <div class="stepper-track">
            @php
                $w = '0%';
                if($step == 2) $w = '33%';
                if($step == 3) $w = '66%';
                if($step == 4) $w = '100%';
            @endphp
            <div class="stepper-fill" style="width: {{ $w }};"></div>
        </div>

        @foreach([1=>'Isi Data', 2=>'Jaminan', 3=>'Konfirmasi', 4=>'Pembayaran'] as $k => $label)
        <div class="step-item {{ $step == $k ? 'active' : ($step > $k ? 'completed' : '') }}">
            <div class="step-circle">
                @if($step > $k) <i class="fas fa-check"></i> @else {{ $k }} @endif
            </div>
            <div class="step-label">{{ $label }}</div>
        </div>
        @endforeach
    </div>

    <div class="booking-layout">
        @if(session('error'))
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        <div>
            @if($step == 1)
                <div class="card-box">
                    <div class="card-header-title"><i class="fas fa-edit"></i> Formulir Pemesanan</div>
                    <form action="{{ route('booking.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="car_id" value="{{ $car->id }}">
                        <div class="form-label" style="margin-bottom:10px;">Pilih Paket Sewa</div>
                        <div class="service-options">
                            @foreach($car->packages as $index => $pkg)
                            <div class="service-option-item">
                                <input type="radio" name="package_id" id="pkg_{{ $pkg->id }}" value="{{ $pkg->id }}" class="service-radio" {{ $index == 0 ? 'checked' : '' }}>
                                <label for="pkg_{{ $pkg->id }}" class="service-label">
                                    <i class="fas {{ (Str::contains(strtolower($pkg->name), 'supir') || Str::contains(strtolower($pkg->name), 'driver')) ? 'fa-user-tie' : (Str::contains(strtolower($pkg->name), 'all in') ? 'fa-gas-pump' : 'fa-car') }}"></i>
                                    <span>{{ $pkg->name }}</span>
                                    <div class="pkg-desc">{{ Str::limit($pkg->description, 30) }}</div>
                                    <div class="pkg-price">Rp {{ number_format($pkg->price, 0, ',', '.') }}</div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div><label class="form-label">Tanggal Mulai Sewa</label><input type="date" name="start_date" class="form-control" value="{{ request('start') }}" required></div>
                            <div><label class="form-label">Tanggal Selesai</label><input type="date" name="end_date" class="form-control" value="{{ request('end') }}" required></div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 20px; margin-bottom: 20px;">
                            <div><label class="form-label">Jam Pengambilan</label><input type="time" name="pickup_time" class="form-control" required></div>
                            <div>
                                <label class="form-label">Lokasi Pengambilan</label>
                                <select name="pickup_location" class="form-control">
                                    <option value="Kantor Bonanza">Ambil di Kantor (Gratis)</option>
                                    <option value="Bandara">Antar ke Bandara (+Biaya)</option>
                                    <option value="Stasiun">Antar ke Stasiun (+Biaya)</option>
                                    <option value="Rumah">Antar ke Rumah (+Biaya)</option>
                                </select>
                            </div>
                        </div>
                        <div style="margin-bottom: 25px;"><label class="form-label">Catatan Tambahan</label><textarea name="address_detail" class="form-control" rows="3" placeholder="Contoh: Tolong antar ke Hotel Aston kamar 202..."></textarea></div>
                        <button type="submit" class="btn-main">Lanjut ke Jaminan <i class="fas fa-arrow-right"></i></button>
                    </form>
                </div>
            @endif

            @if($step == 2)
                <div class="card-box">
                    <div class="card-header-title">
                        <i class="fas fa-shield-alt"></i> Pilih Metode Jaminan
                    </div>
                    <p style="color:#666; margin-bottom: 25px; font-size: 0.9rem;">
                        Sesuai prosedur keamanan rental, penyewa wajib menyerahkan salah satu jaminan berikut.
                    </p>

                    <form action="{{ route('booking.update_guarantee', $booking->booking_code) }}" method="POST" id="guaranteeForm">
                        @csrf
                        <input type="hidden" name="guarantee_type" id="guaranteeInput">

                        <div class="service-options">
                            <div class="service-option-item" onclick="openModal('motor', 'Titip Kendaraan', 'Anda akan menitipkan Motor + STNK Asli sebagai jaminan.')">
                                <div class="service-label" style="align-items: center; text-align: center;">
                                    <i class="fas fa-motorcycle" style="font-size: 2rem;"></i>
                                    <h4 style="font-size: 1rem; margin: 10px 0 5px;">Titip Kendaraan</h4>
                                    <span style="font-size: 0.8rem; color: #888;">Motor + STNK Asli</span>
                                    <span style="background:#d4edda; color:#155724; font-size:0.7rem; padding:3px 8px; border-radius:10px; margin-top:10px; font-weight:bold;">GRATIS</span>
                                </div>
                            </div>

                            <div class="service-option-item" onclick="openModal('deposit_money', 'Deposit Uang', 'Anda akan menyerahkan deposit Rp 3.000.000 (Refundable).')">
                                <div class="service-label" style="align-items: center; text-align: center;">
                                    <i class="fas fa-wallet" style="font-size: 2rem;"></i>
                                    <h4 style="font-size: 1rem; margin: 10px 0 5px;">Deposit Uang</h4>
                                    <span style="font-size: 0.8rem; color: #888;">Refund setelah sewa</span>
                                    <span style="background:#fff3cd; color:#856404; font-size:0.7rem; padding:3px 8px; border-radius:10px; margin-top:10px; font-weight:bold;">Rp 3.000.000</span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @endif

            @if($step == 3)
                <div class="card-box" style="text-align: center; padding: 60px 30px;">
                    <div style="width:80px; height:80px; background:#fff9db; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 25px;"><i class="fas fa-hourglass-half" style="font-size:35px; color:var(--primary-dark);"></i></div>
                    <h2 style="font-size:1.5rem; font-weight:700; margin-bottom:10px;">Menunggu Konfirmasi Admin</h2>
                    <p style="color:#666; max-width:500px; margin:0 auto 30px;">Pesanan Anda dengan kode <strong style="color:#000;">{{ $booking->booking_code }}</strong> sedang diverifikasi.</p>
                    <button onclick="window.location.reload()" class="btn-main" style="background:#fff; border:2px solid #eee; color:#555; width:auto; padding:12px 30px; display:inline-flex;"><i class="fas fa-sync-alt"></i> Refresh Status</button>
                </div>
            @endif

            @if($step == 4)
                <div class="card-box">
                    <div class="card-header-title"><i class="fas fa-credit-card"></i> Selesaikan Pembayaran</div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:10px; font-size:1rem;">
                        <span>Sewa Mobil ({{ \Carbon\Carbon::parse($booking->start_date)->diffInDays($booking->end_date) + 1 }} Hari)</span>
                        <span style="font-weight: 700;">Rp {{ number_format($booking->total_rent_price) }}</span>
                    </div>
                    @if($booking->guarantee_cost > 0)
                    <div style="display:flex; justify-content:space-between; margin-bottom:10px; font-size:1rem;">
                        <span>Deposit Jaminan</span><span style="font-weight: 700;">Rp {{ number_format($booking->guarantee_cost) }}</span>
                    </div>
                    @endif
                    <div class="price-total"><span class="total-label">Total Tagihan</span><span class="total-value">Rp {{ number_format($booking->grand_total) }}</span></div>
                    <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">

                    @if(isset($paymentMethod) && $paymentMethod == 'transfer')
                        @if($booking->payment_proof)
                            <div style="background:#d1e7dd; color:#0f5132; padding:25px; border-radius:12px; text-align:center; margin-top:20px;">
                                <i class="fas fa-check-circle" style="font-size:40px; margin-bottom:15px; display:block;"></i>
                                <h4 style="font-weight:700; margin-bottom:5px;">Bukti Transfer Terkirim</h4>
                                <p style="margin-bottom:15px;">Admin sedang memverifikasi pembayaran Anda.</p>
                                <img src="{{ asset('storage/'.$booking->payment_proof) }}" style="max-height:120px; border-radius:8px; border:2px solid #fff; box-shadow:0 2px 5px rgba(0,0,0,0.1);">
                            </div>
                        @else
                            <div class="bank-box">
                                <p style="margin:0; color:#666;">Silakan transfer ke rekening berikut:</p>
                                <div class="bank-name">{{ $bankInfo['bank'] ?? 'BANK BCA' }}</div>
                                <span class="bank-rek">{{ $bankInfo['rek'] ?? '0000000000' }}</span>
                                <div class="bank-an">a.n {{ $bankInfo['name'] ?? 'PT BONANZA RENTAL' }}</div>
                            </div>
                            <form action="{{ route('booking.upload_proof', $booking->booking_code) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <label class="form-label">Upload Bukti Transfer</label>
                                <input type="file" name="payment_proof" class="form-control" required accept="image/*">
                                <div style="font-size:0.8rem; color:#888; margin-top:5px; margin-bottom:15px;">*Format: JPG, PNG, PDF (Max 2MB)</div>
                                <button type="submit" class="btn-main"><i class="fas fa-paper-plane"></i> Kirim Bukti Pembayaran</button>
                            </form>
                        @endif
                    @else
                        <div style="background: #e3f2fd; padding: 20px; border-radius: 10px; display: flex; align-items: flex-start; gap: 15px; margin-top: 20px; margin-bottom: 25px; border-left: 5px solid #2196f3;">
                            <i class="fas fa-shield-check" style="font-size: 24px; color: #2196f3; margin-top: 5px;"></i>
                            <div>
                                <h4 style="margin: 0 0 5px 0; font-size: 1rem; color: #0d47a1;">Pembayaran Otomatis (Gateway)</h4>
                                <p style="margin: 0; font-size: 0.85rem; color: #555;">Virtual Account (BCA, Mandiri, BRI, BNI), QRIS, GoPay, ShopeePay.</p>
                            </div>
                        </div>
                        <button id="pay-btn" class="btn-main"><i class="fas fa-lock"></i> Bayar Sekarang</button>
                        <script>
                            document.getElementById('pay-btn').onclick = function(){
                                window.snap.pay('{{ $booking->snap_token }}', {
                                    onSuccess: function(){ window.location.href="{{ route('riwayat_booking.index') }}"; },
                                    onPending: function(){ alert("Menunggu Pembayaran..."); },
                                    onError: function(){ alert("Gagal!"); }
                                });
                            };
                        </script>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <div class="card-box sticky-summary">
                <div class="card-header-title" style="font-size: 1rem; margin-bottom: 15px;">Ringkasan Sewa</div>
                @php
                    $imagePath = 'img/no-image.jpg';
                    if (!empty($car->images)) {
                        if (is_array($car->images)) { $imagePath = 'storage/' . ($car->images[0] ?? ''); }
                        elseif (is_string($car->images)) { $decoded = json_decode($car->images, true); $imagePath = 'storage/' . (json_last_error() === JSON_ERROR_NONE && is_array($decoded) ? ($decoded[0] ?? '') : $car->images); }
                    }
                @endphp
                <img src="{{ asset($imagePath) }}" class="car-hero-img">
                <div style="margin-bottom: 15px;">
                    <h3 style="font-size: 1.1rem; font-weight: 700; margin: 0 0 5px 0;">{{ $car->brand->name }} {{ $car->name }}</h3>
                    <div style="font-size: 0.85rem; color: #888;">{{ $car->year }}</div>
                </div>
                <div style="display: flex; gap: 10px; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 15px;">
                    <span style="background: #f0f2f5; padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; color: #555;"><i class="fas fa-chair"></i> {{ $car->seating_capacity ?? '4' }}</span>
                    <span style="background: #f0f2f5; padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; color: #555;"><i class="fas fa-cogs"></i> {{ $car->transmission }}</span>
                </div>
                @if(isset($booking))
                    <div class="timeline-box">
                        <div class="timeline-item"><div class="t-label">Ambil</div><div class="t-val">{{ \Carbon\Carbon::parse($booking->start_date)->isoFormat('D MMMM Y') }}</div></div>
                        <div class="timeline-item"><div class="t-label">Kembali</div><div class="t-val">{{ \Carbon\Carbon::parse($booking->end_date)->isoFormat('D MMMM Y') }}</div></div>
                    </div>
                    @if($booking->package_name)<div style="background:#fff9db; padding:10px; border-radius:6px; font-size:0.8rem; margin-bottom:15px; color:#555;">Paket: <strong>{{ $booking->package_name }}</strong></div>@endif
                    <div class="price-total" style="border-top:1px solid #eee; padding-top:15px; margin-top:0;"><span style="font-size:0.9rem;">Total Bayar</span><span style="font-size:1.1rem; color:var(--primary-dark);">Rp {{ number_format($booking->grand_total) }}</span></div>
                @else
                    <div class="timeline-box">
                        <div class="timeline-item"><div class="t-label">Estimasi Ambil</div><div class="t-val">{{ request('start') ? \Carbon\Carbon::parse(request('start'))->isoFormat('D MMMM Y') : '-' }}</div></div>
                        <div class="timeline-item"><div class="t-label">Estimasi Kembali</div><div class="t-val">{{ request('end') ? \Carbon\Carbon::parse(request('end'))->isoFormat('D MMMM Y') : '-' }}</div></div>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:0.9rem;"><span>Harga Mulai</span><strong>Rp {{ number_format($car->packages->min('price')) }} / hari</strong></div>
                @endif
            </div>
        </div>
    </div>
@if($step == 2)
<div id="confirmationModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-icon"><i class="fas fa-question"></i></div>
        <div class="modal-title" id="mTitle">Konfirmasi</div>
        <div class="modal-text" id="mText">...</div>
        <div class="modal-buttons">
            <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
            <button type="button" class="btn-confirm" onclick="submitGuarantee()">Ya, Pilih</button>
        </div>
    </div>
</div>

<script>
    let selectedType = '';

    function openModal(type, title, text) {
        selectedType = type;
        document.getElementById('mTitle').innerText = title;
        document.getElementById('mText').innerText = text;

        const modal = document.getElementById('confirmationModal');

        // 1. Ubah display dulu jadi flex (tapi masih transparan)
        modal.style.display = 'flex';

        // 2. Kasih delay sedikit (10ms) baru tambah class 'show' untuk memicu animasi
        setTimeout(() => {
            modal.classList.add('show');
        }, 10);
    }

    function closeModal() {
        const modal = document.getElementById('confirmationModal');

        // 1. Hapus class show (memicu animasi keluar)
        modal.classList.remove('show');

        // 2. Tunggu animasi selesai (300ms) baru sembunyikan elemennya
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }

    function submitGuarantee() {
        if(selectedType) {
            document.getElementById('guaranteeInput').value = selectedType;
            document.getElementById('guaranteeForm').submit();
        }
    }

    window.onclick = function(event) {
        let modal = document.getElementById('confirmationModal');
        if (event.target == modal) {
            closeModal();
        }
    }
</script>
@endif
@endsection
