@extends('dashboard.layouts.index')
@section('title', 'Pengaturan Website')

@section('style')
    <style>
        :root { --primary: #FFC400; --primary-dark: #e0ac00; --text-dark: #333; --text-gray: #666; --bg-light: #f8f9fa; --sidebar-width: 280px; }
        .main-content { flex: 1; margin-left: var(--sidebar-width); padding: 30px 40px; transition: 0.3s; font-family: 'Poppins', sans-serif; }
        .mobile-header { display: none; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .hamburger { font-size: 1.5rem; cursor: pointer; color: var(--text-dark); }
        .page-header { margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; }
        .page-title h1 { font-size: 24px; font-weight: 700; margin: 0; color: var(--text-dark); }
        .page-title p { font-size: 13px; color: var(--text-gray); margin-top: 5px; }
        .settings-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 25px; }
        .card-box { background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #eee; margin-bottom: 25px; overflow: hidden; }
        .card-header-title { padding: 15px 25px; background: #fff; border-bottom: 1px solid #f0f0f0; font-weight: 600; font-size: 15px; color: var(--text-dark); display: flex; align-items: center; gap: 10px; }
        .card-header-title i { color: var(--primary); font-size: 16px; }
        .card-body-content { padding: 25px; }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 13px; font-weight: 500; color: #444; margin-bottom: 8px; }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 13px; transition: 0.3s; }
        .form-control:focus { border-color: var(--primary); outline: none; box-shadow: 0 0 0 3px rgba(255, 196, 0, 0.1); }
        textarea.form-control { resize: vertical; min-height: 100px; line-height: 1.5; }
        .row-group { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .img-preview-box { background: #f9f9f9; border: 2px dashed #ddd; border-radius: 10px; padding: 20px; text-align: center; margin-bottom: 15px; position: relative; transition: 0.3s; }
        .img-preview-box:hover { background: #fffcf0; border-color: var(--primary); }
        .preview-img { max-height: 80px; max-width: 100%; object-fit: contain; }
        .upload-btn-wrapper { position: relative; overflow: hidden; display: inline-block; margin-top: 10px; width: 100%; }
        .btn-upload { border: 1px solid #ddd; color: #555; background-color: white; padding: 8px 20px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; width: 100%; }
        .upload-btn-wrapper input[type=file] { font-size: 100px; position: absolute; left: 0; top: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
        .btn-save { background: var(--primary); color: #000; border: none; padding: 12px 30px; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px; display: flex; align-items: center; gap: 8px; transition: 0.2s; }
        .btn-save:hover { background: var(--primary-dark); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(255, 196, 0, 0.2); }
        @media (max-width: 992px) { .main-content { margin-left: 0; padding: 20px; } .settings-grid { grid-template-columns: 1fr; } .row-group { grid-template-columns: 1fr; } .mobile-header { display: flex; } }
    </style>
    <style>
        /* Wrapper Container */
        .sensitive-container {
            position: relative;
        }

        /* Class untuk efek buram */
        .is-blurred {
            filter: blur(8px);
            -webkit-filter: blur(8px);
            pointer-events: none;
            user-select: none;
            opacity: 0.5;
            transition: all 0.3s ease; /* Tambahkan transisi halus */
        }

        /* Overlay tombol "Lihat" di tengah */
        .blur-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex; /* Default flex agar terpusat */
            justify-content: center;
            align-items: center;
            z-index: 10;
        }

        /* Tombol Reveal (Lihat) */
        .btn-reveal {
            background: #FFC400;
            color: #000;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 8px;
            transition: transform 0.2s;
        }
        .btn-reveal:hover { transform: scale(1.05); background: #ffcd29; }

        /* Tombol Hide (Sembunyikan) - Default Hidden */
        .btn-hide-toggle {
            display: none; /* Sembunyi saat awal */
            position: absolute;
            top: -10px; /* Atur posisi vertikal sesuai selera */
            right: 0;
            background: #ffe3e3;
            color: #c92a2a;
            border: 1px solid #ffa8a8;
            font-size: 11px;
            padding: 5px 12px;
            border-radius: 20px;
            cursor: pointer;
            z-index: 20;
            font-weight: 600;
            align-items: center;
            gap: 5px;
            transition: all 0.2s;
        }
        .btn-hide-toggle:hover { background: #ffc9c9; }
    </style>
@endsection

@section('content')

        @if(session('success'))
            <div style="background: #e6fcf5; color: #0ca678; padding: 15px; border-radius: 8px; margin-bottom: 20px; border:1px solid #63e6be;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="page-header">
                <div class="page-title">
                    <h1>Pengaturan Website</h1>
                    <p>Kelola informasi rental, kontak, dan tampilan website utama.</p>
                </div>
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>

            <div class="settings-grid">

                <div class="left-column">

                    <div class="card-box">
                        <div class="card-header-title">
                            <i class="fas fa-building"></i> Identitas Bisnis
                        </div>
                        <div class="card-body-content">
                            <div class="row-group">
                                <div class="form-group">
                                    <label class="form-label">Nama Rental</label>
                                    <input type="text" class="form-control" name="app_name" value="{{ $settings['app_name'] ?? '' }}" placeholder="Contoh: Bonanza Rental">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Tagline / Slogan</label>
                                    <input type="text" class="form-control" name="app_tagline" value="{{ $settings['app_tagline'] ?? '' }}" placeholder="Contoh: Solusi Sewa Mobil Terbaik">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Deskripsi Singkat (Footer)</label>
                                <textarea class="form-control" name="app_desc" rows="3">{{ $settings['app_desc'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card-box">
                        <div class="card-header-title">
                            <i class="fas fa-map-marker-alt"></i> Kontak & Alamat
                        </div>
                        <div class="card-body-content">
                            <div class="form-group">
                                <label class="form-label">Alamat Lengkap</label>
                                <textarea class="form-control" name="address" rows="2">{{ $settings['address'] ?? '' }}</textarea>
                            </div>

                            <div class="row-group">
                                <div class="form-group">
                                    <label class="form-label">Nomor Telepon (Kantor)</label>
                                    <div style="position:relative;">
                                        <i class="fas fa-phone" style="position:absolute; left:12px; top:12px; color:#aaa;"></i>
                                        <input type="text" class="form-control" name="phone" value="{{ $settings['phone'] ?? '' }}" style="padding-left: 35px;">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Nomor WhatsApp (Admin)</label>
                                    <div style="position:relative;">
                                        <i class="fab fa-whatsapp" style="position:absolute; left:12px; top:12px; color:#22C55E;"></i>
                                        <input type="text" class="form-control" name="whatsapp" value="{{ $settings['whatsapp'] ?? '' }}" style="padding-left: 35px;" placeholder="Format: 628...">
                                    </div>
                                </div>
                            </div>

                            <div class="row-group">
                                <div class="form-group">
                                    <label class="form-label">Email Support</label>
                                    <div style="position:relative;">
                                        <i class="far fa-envelope" style="position:absolute; left:12px; top:12px; color:#aaa;"></i>
                                        <input type="email" class="form-control" name="email" value="{{ $settings['email'] ?? '' }}" style="padding-left: 35px;">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Jam Operasional</label>
                                    <input type="text" class="form-control" name="office_hours" value="{{ $settings['office_hours'] ?? '' }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Link Google Maps (Embed)</label>
                                <input type="text" class="form-control" name="maps_link" value="{{ $settings['maps_link'] ?? '' }}" placeholder="https://maps.google.com/...">
                                <small style="color:#888; font-size:11px;">*Masukkan link src dari embed iframe Google Maps</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-box">
                        <div class="card-header-title">
                            <i class="fas fa-credit-card"></i> Metode Pembayaran
                        </div>
                        <div class="card-body-content">
                            <div class="form-group">
                                <label class="form-label">Metode Pembayaran Utama</label>
                                <div style="position:relative;">
                                    <i class="fas fa-wallet" style="position:absolute; left:12px; top:12px; color:#aaa;"></i>
                                    <select class="form-control" name="payment_method" id="paymentSelect" style="padding-left: 35px;" onchange="togglePayment(this.value)">
                                        <option value="gateway" {{ ($settings['payment_method'] ?? '') == 'gateway' ? 'selected' : '' }}>Payment Gateway (Otomatis)</option>
                                        <option value="transfer" {{ ($settings['payment_method'] ?? '') == 'transfer' ? 'selected' : '' }}>Transfer Bank (Manual)</option>
                                    </select>
                                </div>
                            </div>

                            <div id="midtransForm" style="display: {{ ($settings['payment_method'] ?? 'gateway') == 'gateway' ? 'block' : 'none' }};">
                                <div style="background:#fff9db; padding:10px; border-radius:5px; margin-bottom:15px; font-size:12px;">
                                    <strong><i class="fas fa-info-circle"></i> Info:</strong> Pembayaran otomatis menggunakan Midtrans API.
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Merchant ID</label>
                                    <input type="text" class="form-control" name="merchantid" value="{{ $settings['merchantid'] ?? '' }}" placeholder="G12345xxxx...">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Client Key</label>
                                    <input type="text" class="form-control" name="clientkey" value="{{ $settings['clientkey'] ?? '' }}" placeholder="Mid-client-xxxxxxxxxxxxxxxx...">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Server Key</label>
                                    <input type="text" class="form-control" name="serverkey" value="{{ $settings['serverkey'] ?? '' }}" placeholder="Mid-server-xxxxxxxxxxxxxxxxxxxxxxx...">
                                </div>
                            </div>

                            <div id="transferForm" style="display: {{ ($settings['payment_method'] ?? '') == 'transfer' ? 'block' : 'none' }};">
                                <div style="background:#e6fcf5; padding:10px; border-radius:5px; margin-bottom:15px; font-size:12px;">
                                    <strong><i class="fas fa-info-circle"></i> Info:</strong> Pelanggan akan mengupload bukti transfer manual.
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Nama Bank</label>
                                    <input type="text" class="form-control" name="namabank" value="{{ $settings['namabank'] ?? '' }}" placeholder="Contoh: BCA / Mandiri">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Nama Pemilik Rekening</label>
                                    <input type="text" class="form-control" name="namarekening" value="{{ $settings['namarekening'] ?? '' }}" placeholder="Atas Nama...">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Nomor Rekening</label>
                                    <input type="number" class="form-control" name="nomorrekening" value="{{ $settings['nomorrekening'] ?? '' }}" placeholder="Contoh: 1234567890">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-box">
                        <div class="card-header-title">
                            <i class="fab fa-whatsapp" style="color: #25D366; font-size: 18px;"></i>
                            <span>WhatsApp Gateway (Kirimi.id)</span>
                        </div>
                        <div class="card-body-content">

                            {{-- BAGIAN STATUS (NORMAL) --}}
                            <div class="form-group">
                                <label class="form-label">Aktifkan WhatsApp Gateway</label>
                                <div style="position:relative;">
                                    <i class="fas fa-toggle-on" style="position:absolute; left:12px; top:12px; color:#aaa;"></i>
                                    <select class="form-control" name="wa_gateway" style="padding-left: 35px;">
                                        <option value="0" {{ ($settings['wa_gateway'] ?? '0') == '0' ? 'selected' : '' }}>Nonaktif</option>
                                        <option value="1" {{ ($settings['wa_gateway'] ?? '0') == '1' ? 'selected' : '' }}>Aktif</option>
                                    </select>
                                </div>
                            </div>

                            <div style="background: #e6fcf5; border: 1px solid #63e6be; padding: 12px 15px; border-radius: 8px; margin-bottom: 20px; font-size: 12px; color: #087f5b; line-height: 1.5;">
                                <i class="fas fa-info-circle" style="margin-right: 5px;"></i>
                                Ambil Device ID dan Private Key di:
                                <a href="https://dash.kirimi.id/device/add" target="_blank" style="font-weight: 700; color: #087f5b; text-decoration: underline;">
                                    dash.kirimi.id/device/add
                                </a>
                            </div>

                            {{-- BAGIAN RAHASIA (DEFAULT BURAM) --}}
                            <div class="sensitive-container">

                                {{-- 1. TOMBOL "SEMBUNYIKAN" (MUNCUL SAAT TIDAK BLUR) --}}
                                <button type="button" id="btnHideWa" class="btn-hide-toggle" onclick="hideWaData()">
                                    <i class="fas fa-eye-slash"></i> Sembunyikan
                                </button>

                                {{-- 2. OVERLAY "LIHAT" (MUNCUL SAAT BLUR) --}}
                                <div id="waOverlay" class="blur-overlay">
                                    <button type="button" class="btn-reveal" onclick="revealWaData()">
                                        <i class="fas fa-eye"></i> Lihat Data Rahasia
                                    </button>
                                </div>

                                {{-- 3. INPUT WRAPPER --}}
                                <div id="waSensitiveInputs" class="is-blurred">
                                    <div class="form-group">
                                        <label class="form-label">User Code</label>
                                        <input type="text" class="form-control" name="wa_user_code" value="{{ $settings['wa_user_code'] ?? '' }}" placeholder="Contoh: wdevice_xYz123...">
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Device ID</label>
                                        <input type="text" class="form-control" name="wa_device_id" value="{{ $settings['wa_device_id'] ?? '' }}" placeholder="Contoh: wdevice_xYz123...">
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Secret Key / API Token</label>
                                        <div style="position:relative;">
                                            <i class="fas fa-key" style="position:absolute; left:12px; top:12px; color:#aaa;"></i>
                                            <input type="text" class="form-control" name="wa_secret" value="{{ $settings['wa_secret'] ?? '' }}" placeholder="Masukkan API Token Kirimi.id" style="padding-left: 35px;">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            {{-- END SENSITIVE CONTAINER --}}

                        </div>
                    </div>

                </div>

                <div class="right-column">

                    <div class="card-box">
                        <div class="card-header-title">
                            <i class="far fa-image"></i> Logo Website
                        </div>
                        <div class="card-body-content">
                            <label class="form-label">Logo Utama</label>
                            <div class="img-preview-box">
                                @if(!empty($settings['logo_web']))
                                    <img src="{{ asset('storage/' . $settings['logo_web']) }}" alt="Logo" class="preview-img">
                                @else
                                    <i class="fas fa-car-side" style="font-size:40px; color:#FFC400;"></i>
                                @endif
                            </div>
                            <div class="upload-btn-wrapper">
                                <button class="btn-upload" type="button">Ganti Logo</button>
                                <input type="file" name="logo_web" accept="image/*" />
                            </div>
                            <p style="font-size:11px; color:#999; margin-top:10px; text-align:center;">Format: PNG/JPG. Max: 2MB.</p>
                        </div>
                    </div>

                    <div class="card-box">
                        <div class="card-header-title">
                            <i class="fas fa-globe"></i> Favicon
                        </div>
                        <div class="card-body-content">
                            <label class="form-label">Icon Browser</label>
                            <div class="img-preview-box" style="padding:10px;">
                                @if(!empty($settings['favicon']))
                                    <img src="{{ asset('storage/' . $settings['favicon']) }}" alt="Favicon" style="width:32px; height:32px;">
                                @else
                                    <div style="width:32px; height:32px; background:#FFC400; border-radius:4px; margin:0 auto;"></div>
                                @endif
                            </div>
                            <div class="upload-btn-wrapper">
                                <button class="btn-upload" type="button">Upload Icon</button>
                                <input type="file" name="favicon" accept="image/*" />
                            </div>
                        </div>
                    </div>

                    <div class="card-box">
                        <div class="card-header-title">
                            <i class="fas fa-share-alt"></i> Sosial Media
                        </div>
                        <div class="card-body-content">
                            <div class="form-group">
                                <label class="form-label">Instagram URL</label>
                                <input type="text" class="form-control" name="social_ig" value="{{ $settings['social_ig'] ?? '' }}" placeholder="https://instagram.com/bonanza">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Facebook URL</label>
                                <input type="text" class="form-control" name="social_fb" value="{{ $settings['social_fb'] ?? '' }}" placeholder="https://facebook.com/bonanza">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tiktok URL</label>
                                <input type="text" class="form-control" name="social_tiktok" value="{{ $settings['social_tiktok'] ?? '' }}" placeholder="https://tiktok.com/@bonanza">
                            </div>
                        </div>
                    </div>

                    <div class="card-box">
                        <div class="card-header-title">
                            <i class="fas fa-search"></i> Analytic
                        </div>
                        <div class="card-body-content">
                            <div class="form-group">
                                <label class="form-label">Google Search Console</label>
                                <input class="form-control" name="GoogleSchConsol" value="{{ $settings['GoogleSchConsol'] ?? '' }}" placeholder="Meta Tag..."></input>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Google Tag Manager ID</label>
                                <input class="form-control" name="GoogleTag" value="{{ $settings['GoogleTag'] ?? '' }}" placeholder="GTM-XXXXXXXX"></input>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
@endsection

@section('script')
<script>
    function revealWaData() {
        // 1. Hilangkan Overlay "Lihat"
        document.getElementById('waOverlay').style.display = 'none';

        // 2. Hapus Blur pada input
        document.getElementById('waSensitiveInputs').classList.remove('is-blurred');

        // 3. Tampilkan Tombol "Sembunyikan"
        document.getElementById('btnHideWa').style.display = 'inline-flex';
    }

    function hideWaData() {
        // 1. Munculkan kembali Overlay "Lihat"
        document.getElementById('waOverlay').style.display = 'flex';

        // 2. Tambahkan kembali Blur pada input
        document.getElementById('waSensitiveInputs').classList.add('is-blurred');

        // 3. Sembunyikan Tombol "Sembunyikan"
        document.getElementById('btnHideWa').style.display = 'none';
    }
    function togglePayment(val) {
        if(val === 'gateway') {
            document.getElementById('midtransForm').style.display = 'block';
            document.getElementById('transferForm').style.display = 'none';
        } else {
            document.getElementById('midtransForm').style.display = 'none';
            document.getElementById('transferForm').style.display = 'block';
        }
    }
</script>
@endsection
