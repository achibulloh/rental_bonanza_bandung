@extends('dashboard.layouts.index')
@section('title', 'Check Out - Serah Terima Mobil')

@section('style')
    {{-- MENGGUNAKAN STYLE ASLI ANDA --}}
    <style>
        :root { --primary-yellow: #FFD700; --primary-yellow-hover: #e6c200; --text-dark: #212529; --text-muted: #6c757d; --bg-light: #f4f6f9; --white: #FFFFFF; --border-color: #e9ecef; --input-bg: #f9fafb; --green-bg: #e8f5e9; --green-text: #2e7d32; --red-bg: #fee2e2; --red-text: #dc2626; --blue-bg: #e3f2fd; --blue-text: #0d47a1; --orange-box-bg: #fffbf0; --orange-box-border: #ffe0b2; --btn-dark: #343a40; }
        * { box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; margin: 0; background-color: var(--bg-light); color: var(--text-dark); min-height: 100vh; }
        .mobile-header { display: none; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .hamburger { font-size: 1.5rem; cursor: pointer; color: var(--text-dark); }
        .main-content { margin-left: 260px; flex: 1; padding: 30px 40px; transition: all 0.3s ease; }
        @media (max-width: 992px) { .main-content { margin-left: 0; padding: 20px; } .mobile-header { display: flex; } }
        .back-link { display: inline-flex; align-items: center; color: var(--text-muted); text-decoration: none; font-size: 14px; margin-bottom: 20px; }
        .back-link i { margin-right: 8px; }
        .section-card { background-color: var(--white); border: 1px solid var(--border-color); border-radius: 12px; padding: 25px; box-shadow: 0 2px 4px rgba(2,2,0,0.1); margin-bottom: 25px; }
        .header-card { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 20px; }
        .form-logo-title { display: flex; align-items: center; gap: 10px; margin-bottom: 5px; }
        .form-logo-title i { font-size: 20px; color: var(--primary-yellow); }
        .form-logo-title h2 { margin: 0; font-size: 18px; }
        .header-title-main { margin: 10px 0 5px 0; font-size: 20px; font-weight: 700; }
        .booking-code { color: #f57f17; font-weight: 600; font-size: 14px; background: #fff3e0; padding: 4px 10px; border-radius: 4px; display: inline-block; }
        .header-actions { display: flex; gap: 10px; }
        .form-grid-layout { display: grid; grid-template-columns: 1.5fr 1fr; gap: 25px; align-items: start; }
        .form-col-left, .form-col-right { display: flex; flex-direction: column; width: 100%; }
        @media (max-width: 992px) { .form-grid-layout { grid-template-columns: 1fr; } .header-card { flex-direction: column; align-items: flex-start; } .header-actions { width: 100%; display: grid; grid-template-columns: 1fr 1fr; } .btn-action { justify-content: center; } .mobile-header { display: flex; } }
        @media (max-width: 480px) { .header-actions { grid-template-columns: 1fr; } .mobile-header { display: flex; } }
        .form-section-title { font-size: 15px; font-weight: 700; margin-bottom: 20px; color: var(--text-dark); border-bottom: 1px solid #f0f0f0; padding-bottom: 12px; display: flex; align-items: center; gap: 8px; }
        .input-row-group { margin-bottom: 10px; }
        .input-row { display: flex; margin-bottom: 8px; font-size: 14px; flex-wrap: wrap; }
        .input-label-fixed { width: 140px; color: var(--text-muted); flex-shrink: 0; }
        .input-value-fixed { font-weight: 500; color: var(--text-dark); flex: 1; }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500; color: var(--text-dark); }
        .form-input { width: 100%; padding: 10px 12px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; background-color: var(--white); }
        .form-input:focus { outline: none; border-color: var(--primary-yellow); }
        .form-input[readonly] { background-color: var(--input-bg); color: #555; cursor: default; }
        textarea.form-input { resize: vertical; height: 100px; }
        .comparison-box-blue { background-color: #f0f7ff; border: 1px solid #cce5ff; border-radius: 10px; padding: 20px; margin-bottom: 20px; }
        .comparison-box-green { background-color: #f0fff4; border: 1px solid #c3e6cb; border-radius: 10px; padding: 20px; margin-bottom: 20px; }
        .comp-header { font-size: 13px; font-weight: 700; color: var(--blue-text); margin-bottom: 15px; }
        .comp-header-green { color: var(--green-text); }
        .comp-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .km-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; align-items: end; }
        @media (max-width: 576px) { .comp-grid-2, .km-grid { grid-template-columns: 1fr; } .km-grid { align-items: start; } .km-value-large { text-align: left; } .mobile-header { display: flex; } }
        .km-value-large { font-size: 16px; font-weight: 700; color: var(--green-text); text-align: right; padding-bottom: 10px; }
        .checklist-group { list-style: none; padding: 0; margin: 0; }
        .check-item { display: flex; align-items: center; margin-bottom: 12px; font-size: 14px; color: #555; }
        .check-item i { margin-right: 12px; font-size: 18px; width: 20px; text-align: center; }
        .bbm-gauge-container { margin-bottom: 20px; }
        .bbm-gauge-bar { height: 12px; width: 100%; border-radius: 6px; position: relative; margin: 10px 0; background: linear-gradient(90deg, #ff4d4d 0%, #ffd700 50%, #4caf50 100%); }
        .bbm-marker-line { position: absolute; top: -4px; bottom: -4px; width: 4px; background-color: #fff; border: 1px solid #333; border-radius: 2px; }
        .bbm-labels-row { display: flex; justify-content: space-between; font-size: 10px; color: var(--text-muted); }
        .bbm-value-display { text-align: center; font-weight: 700; font-size: 13px; margin-top: 5px; }
        .additional-cost-section { background: var(--orange-box-bg); border: 1px solid var(--orange-box-border); padding: 20px; border-radius: 12px; margin-bottom: 0; }
        .cost-input-group { margin-bottom: 12px; }
        .cost-input-label { font-size: 13px; color: #555; display: block; margin-bottom: 4px; }
        .input-rp-wrapper { display: flex; align-items: center; background: var(--white); border: 1px solid #ffe0b2; border-radius: 6px; padding: 0 10px; }
        .form-input-rp { border: none; outline: none; width: 100%; font-size: 14px; padding: 8px 0; }
        .total-additional-row { display: flex; justify-content: space-between; font-weight: 700; font-size: 14px; border-top: 1px solid #ffe0b2; padding-top: 15px; margin-top: 15px; color: #f57f17; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; }
        .summary-row.grand-total { font-weight: 700; border-top: 1px solid #eee; padding-top: 10px; margin-top: 10px; }
        .summary-row.final-payment { padding-top: 10px; margin-top: 10px; align-items: center; }
        .final-payment-amount { font-weight: 800; font-size: 18px; color: #f57f17; }
        .upload-grid-2x2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        @media (max-width: 576px) { .upload-grid-2x2 { grid-template-columns: 1fr; } .mobile-header { display: flex; } }
        .upload-item-wrapper { display: flex; flex-direction: column; }
        .upload-label-outside { font-size: 13px; color: var(--text-dark); margin-bottom: 8px; font-weight: 500; }
        .upload-box-rectangle { background-color: var(--white); border: 1px dashed #ccc; border-radius: 8px; height: 130px; display: flex; flex-direction: column; justify-content: center; align-items: center; cursor: pointer; transition: all 0.2s ease; }
        .upload-box-rectangle:hover { border-color: var(--primary-yellow); border-style: solid; background-color: #fffdf5; }
        .upload-box-rectangle i { font-size: 24px; color: #adb5bd; margin-bottom: 8px; }
        .upload-box-rectangle span { font-size: 12px; color: var(--text-muted); }
        .upload-large-container { display: grid; gap: 15px; }
        .upload-box-large { border: 1px dashed #ccc; border-radius: 10px; padding: 25px; text-align: center; cursor: pointer; background: var(--white); }
        .upload-box-large:hover { border-color: var(--primary-yellow); border-style: solid; background-color: #fffdf5; }
        .upload-box-large i { font-size: 28px; margin-bottom: 8px; color: #adb5bd; display: block; margin: 0 auto 8px auto; }
        .upload-box-large span { font-size: 13px; color: var(--text-muted); }
        .notes-ul { font-size: 12px; color: var(--text-muted); padding-left: 20px; line-height: 1.6; margin-bottom: 40px; }
        .signature-row { display: flex; justify-content: space-between; margin-top: 40px; padding: 0 20px; flex-wrap: wrap; gap: 30px; }
        .signature-box { text-align: center; width: 200px; margin: 0 auto; }
        .signature-role { font-size: 13px; color: var(--text-dark); font-weight: 600; margin-bottom: 70px; display: block; }
        .signature-line { border-top: 1px solid #ccc; padding-top: 5px; font-size: 13px; color: #555; }
        .signature-company { font-weight: 700; display: block; margin-top: 5px; font-size: 13px; }
        .btn-action { padding: 10px 24px; border-radius: 6px; font-weight: 600; font-size: 13px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; }
        .btn-yellow { background-color: var(--primary-yellow); color: var(--text-dark); }
        .btn-yellow:hover { background-color: var(--primary-yellow-hover); }
        .btn-dark { background-color: var(--btn-dark); color: var(--white); }

        /* === EXTRA STYLE UNTUK FITUR BARU === */
        .is-invalid { border-color: #dc3545 !important; background-color: #fff8f8 !important; }
        .hidden-file-input { display: none; }
        .img-preview { width: 100%; height: 100%; object-fit: cover; border-radius: 12px; display: none; position: absolute; top: 0; left: 0; }

        /* Modal & Signature Styles */
        .custom-modal { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6); align-items: center; justify-content: center; padding: 20px; }
        .custom-modal.show { display: flex; animation: fadeIn 0.2s; }
        .custom-modal-content { background-color: #fff; padding: 20px; border-radius: 16px; width: 100%; max-width: 500px; display: flex; flex-direction: column; }
        .canvas-wrapper { border: 2px dashed #ccc; border-radius: 8px; background: #fdfdfd; height: 250px; width: 100%; position: relative; margin-bottom: 20px; touch-action: none; }
        canvas { width: 100%; height: 100%; display: block; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .modal-title { font-weight: 800; font-size: 18px; color: #333; }
        .close-modal { font-size: 28px; font-weight: bold; color: #aaa; cursor: pointer; line-height: 1; }
        .modal-footer { display: flex; justify-content: flex-end; gap: 10px; }

        .signature-placeholder { width: 100%; height: 100px; border: 2px dashed #A3AED0; border-radius: 8px; background: #FAFCFE; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; color: #A3AED0; transition: all 0.2s; margin-bottom: 10px; }
        .signature-placeholder:hover { border-color: var(--primary-yellow); color: #d4a000; background: #fffdf5; }
        .signature-preview-box { display: none; width: 100%; margin-bottom: 10px; }
        .signature-img { width: 100%; height: auto; max-height: 90px; object-fit: contain; border-bottom: 1px solid #eee; margin-bottom: 5px; }
        .btn-reset-sig { font-size: 11px; color: #dc2626; cursor: pointer; text-decoration: underline; font-weight: 600; }
    </style>
@endsection

@section('content')
    <main class="main-content">

        {{-- Pesan Error Validasi --}}
        @if ($errors->any())
            <div style="background: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
                <div style="font-weight: 700; margin-bottom: 5px;"><i class="fas fa-exclamation-circle"></i> Terdapat Kesalahan Input:</div>
                <ul style="margin: 0; padding-left: 20px; font-size: 14px;">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('serah_terima.store_checkout', $booking->id) }}" method="POST" enctype="multipart/form-data" id="formCheckout">
            @csrf

            <div class="mobile-header">
                <a href="/" style="color:var(--text-dark); font-weight:700; text-decoration:none;">Bonanza Rental</a>
                <div class="hamburger"><i class="fas fa-bars"></i></div>
            </div>

            <div class="section-card header-card">
                <div class="form-header-content">
                    <h3 class="header-title-main">FORM CHECK-OUT KENDARAAN</h3>
                    <span class="booking-code">Kode Booking: {{ $booking->booking_code }}</span>
                </div>
                <div class="header-actions">
                    <button type="submit" class="btn-action btn-yellow"><i class="fas fa-save"></i> Simpan Check-out</button>
                    <a href="{{ route('serah_terima.index') }}" class="btn-action btn-dark"><i class="fas fa-print"></i> Cetak Form</a>
                </div>
            </div>

            <div class="form-grid-layout">

                {{-- KOLOM KIRI --}}
                <div class="form-col-left">

                    <div class="section-card">
                        <h4 class="form-section-title"><i class="far fa-user"></i> Data Penyewa</h4>
                        <div class="input-row-group">
                            <div class="input-row"><span class="input-label-fixed">Nama</span><span class="input-value-fixed">: {{ $booking->user->name }}</span></div>
                            <div class="input-row"><span class="input-label-fixed">Alamat</span><span class="input-value-fixed">: {{ $booking->user->address }}</span></div>
                            <div class="input-row"><span class="input-label-fixed">Telepon</span><span class="input-value-fixed">: {{ $booking->user->phone ?? '-' }}</span></div>
                        </div>
                    </div>

                    <div class="section-card">
                        <h4 class="form-section-title"><i class="fas fa-car"></i> Detail Pengembalian</h4>
                        <div class="comp-grid-2">
                            <div class="form-group"><label class="form-label">Jenis Kendaraan</label><input type="text" class="form-input" value="{{ $booking->car->name }}" readonly></div>
                            <div class="form-group"><label class="form-label">No. Polisi</label><input type="text" class="form-input" value="{{ $booking->car->license_plate }}" readonly></div>
                        </div>

                        <div class="comparison-box-blue">
                            <div class="comp-header">Perbandingan Jadwal</div>
                            <div class="comp-grid-2">
                                <div class="form-group" style="margin-bottom:0;">
                                    <label class="form-label" style="color: var(--blue-text);">Jadwal Kembali</label>
                                    {{-- HIDDEN INPUT UNTUK JS CALCULATION --}}
                                    <input type="hidden" id="planned_end_date" value="{{ \Carbon\Carbon::parse($booking->end_date)->format('Y-m-d\TH:i') }}">
                                    <input type="text" class="form-input" value="{{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y H:i') }}" readonly style="background: #fff;">
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label class="form-label" style="color: var(--blue-text);">Waktu Aktual <span style="color:red">*</span></label>
                                    {{-- ON CHANGE TRIGGER CALC --}}
                                    <input type="datetime-local" name="waktu_pengembalian" id="actual_end_date" class="form-input @error('waktu_pengembalian') is-invalid @enderror" value="{{ old('waktu_pengembalian', now()->format('Y-m-d\TH:i')) }}" required onchange="calcOvertime()">
                                </div>
                            </div>

                            {{-- ALERT KETERLAMBATAN (Akan muncul via JS jika telat) --}}
                            <div id="late_alert_box" style="display: none; background: #fee2e2; border: 1px solid #ef4444; border-radius: 8px; padding: 12px; margin-top: 15px; color: #b91c1c; font-size: 13px; font-weight: 600;">
                                <i class="fas fa-clock"></i> <span id="late_message">Terlambat 0 Jam. Denda diterapkan.</span>
                            </div>
                        </div>

                        <div class="comparison-box-green">
                            <div class="comp-header comp-header-green"><i class="fas fa-chart-line"></i> Perbandingan Kilometer</div>
                            <div class="km-grid">
                                <div>
                                    <label class="form-label" style="color: var(--green-text);">KM Awal</label>
                                    {{-- AMBIL DARI DATABASE --}}
                                    <input type="text" id="start_km" class="form-input" value="{{ $booking->detail->start_km ?? 0 }}" readonly style="background: #fff;">
                                </div>
                                <div>
                                    <label class="form-label" style="color: var(--green-text);">KM Akhir <span style="color:red">*</span></label>
                                    <input type="number" name="km_akhir" id="end_km" class="form-input @error('km_akhir') is-invalid @enderror" value="{{ old('km_akhir') }}" placeholder="Isi KM" required oninput="calcKm()">
                                </div>
                                <div>
                                    <label class="form-label" style="color: var(--green-text);">Total KM</label>
                                    <div class="km-value-large" id="total_distance">0 km</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Catatan Kondisi Pengembalian</label>
                            <textarea name="notes" class="form-input" placeholder="Catatan mengenai kondisi kendaraan saat dikembalikan, kerusakan, dll..."></textarea>
                        </div>
                    </div>

                    <div class="section-card">
                        <h4 class="form-section-title">Foto Kondisi Pulang <span style="color:red">*</span></h4>
                        <div class="upload-grid-2x2">
                            {{-- FOTO WAJIB (Looping biar rapi) --}}
                            @foreach(['front'=>'Depan', 'left'=>'Samping Kiri', 'right'=>'Samping Kanan', 'back'=>'Belakang'] as $key => $label)
                                <div class="upload-item-wrapper">
                                    <label class="upload-label-outside">{{ $label }}</label>
                                    <div class="upload-box-rectangle @error('photo_'.$key) is-invalid @enderror">
                                        <label for="photo_{{ $key }}">
                                            <i class="fas fa-camera"></i><span>Upload Foto</span>
                                            <img id="prev_{{ $key }}" class="img-preview">
                                        </label>
                                        <input type="file" name="photo_{{ $key }}" id="photo_{{ $key }}" class="hidden-file-input" accept="image/*" onchange="previewImage(this, 'prev_{{ $key }}')">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="section-card">
                        <h4 class="form-section-title">Foto & Video Tambahan</h4>
                        <div class="upload-large-container">
                            <div class="upload-box-large">
                                <label for="photo_additional">
                                    <i class="fas fa-upload"></i><span>Klik untuk upload foto tambahan (opsional)</span>
                                    <img id="prev_add" class="img-preview" style="object-fit: contain;">
                                </label>
                                <input type="file" name="photo_additional" id="photo_additional" class="hidden-file-input" accept="image/*" onchange="previewImage(this, 'prev_add')">
                            </div>
                            <div class="upload-box-large">
                                <label for="video_condition">
                                    <i class="fas fa-video"></i><span>Klik untuk upload video (opsional)</span>
                                    <p id="vid_name" style="display:none; color:#2e7d32; margin-top:5px; font-weight:600;"></p>
                                </label>
                                <input type="file" name="video_condition" id="video_condition" class="hidden-file-input" accept="video/*" onchange="showVideoName(this, 'vid_name')">
                            </div>
                        </div>
                    </div>

                </div>

                {{-- KOLOM KANAN --}}
                <div class="form-col-right">

                    <div class="section-card">
                        <h4 class="form-section-title">Perlengkapan Kendaraan <span style="color:red">*</span></h4>
                        <ul class="checklist-group">
                            {{-- LOGIKA CHECKLIST OTOMATIS DARI DB --}}
                            @php
                                // Ambil checklist yang dicentang saat check-in
                                $checkedItems = $booking->detail->checklist_in ?? [];
                                $allItems = ['STNK', 'Tool Kit', 'Ban Serep', 'Dongkrak', 'Kunci Roda', 'Segitiga Pengaman', 'Radio Disc', 'Velg Racing', 'Kotak P3K', 'Kunci Stir', 'Karpet'];
                            @endphp

                            @foreach($allItems as $item)
                                @php $isChecked = in_array($item, $checkedItems); @endphp
                                <li class="check-item">
                                    {{-- Tampilkan icon centang jika ada di DB, lingkaran jika tidak --}}
                                    <i class="{{ $isChecked ? 'fas fa-check-circle' : 'far fa-circle' }}"
                                       style="color: {{ $isChecked ? 'var(--green-text)' : '#ccc' }}"></i>
                                    {{ $item }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="section-card">
                        <h4 class="form-section-title"><i class="fas fa-gas-pump"></i> Panel BBM <span style="color:red">*</span></h4>
                        <div class="bbm-gauge-container" style="background: #f8f9fa; padding: 12px; border-radius: 8px; margin-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between; font-size:12px;">
                                <span>BBM saat Check-in</span>
                                {{-- Ambil data BBM Start --}}
                                <strong style="color:var(--green-text)">{{ $booking->detail->start_fuel_level ?? 100 }}%</strong>
                            </div>
                        </div>

                        <div style="margin-bottom: 5px;">
                            <span style="font-size:12px; color: var(--text-muted);">BBM saat Check-out</span>
                            <input type="range" name="bbm_akhir" class="bbm-gauge-bar" style="-webkit-appearance:none; width:100%; margin:10px 0;" min="0" max="100" value="100" id="bbmSlider">
                            <div class="bbm-labels-row"><span>E</span><span>1/4</span><span>1/2</span><span>3/4</span><span>F</span></div>
                            <div class="bbm-value-display" id="bbmDisplay">Level BBM: 100%</div>
                        </div>
                    </div>

                    <div class="section-card">
                        <h4 class="form-section-title" style="color: #f57f17;"><i class="fas fa-exclamation-circle"></i> Biaya Tambahan</h4>
                        <div class="additional-cost-section">

                            {{-- INPUT OTOMATIS: DENDA OVERTIME --}}
                            <div class="cost-input-group">
                                <label class="cost-input-label">Biaya Overtime (10%/Jam)</label>
                                <div class="input-rp-wrapper" style="background: #eee;">
                                    <span>Rp</span>
                                    <input type="text" name="fine_overtime" id="fine_overtime" class="form-input-rp calc-input" value="0" readonly>
                                </div>
                            </div>

                            {{-- INPUT MANUAL --}}
                            @foreach(['fine_fuel' => 'Biaya Kekurangan BBM', 'fine_damage' => 'Biaya Kerusakan', 'fine_lost' => 'Kehilangan Perlengkapan'] as $name => $label)
                                <div class="cost-input-group">
                                    <label class="cost-input-label">{{ $label }}</label>
                                    <div class="input-rp-wrapper"><span>Rp</span><input type="text" name="{{ $name }}" class="form-input-rp calc-input manual-fine" placeholder="0"></div>
                                </div>
                            @endforeach

                            <div class="total-additional-row">
                                <span>Total Tambahan</span><span id="displayTotalFine">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <div class="section-card">
                        <h4 class="form-section-title">Rincian Biaya Final</h4>
                        <div class="final-cost-summary">
                            <div class="summary-row"><span>Biaya Sewa</span><span>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span></div>
                            <div class="summary-row"><span>Total Denda</span><span id="summaryFine">Rp 0</span></div>
                            <div class="summary-row grand-total"><span>Grand Total</span><span id="summaryGrandTotal">Rp 0</span></div>
                            <div class="summary-row dp-paid"><span>DP (Sudah Dibayar)</span><span>- Rp {{ number_format($booking->down_payment ?? 0, 0, ',', '.') }}</span></div>
                            <div class="summary-row final-payment">
                                <span class="final-payment-label">Sisa Pelunasan</span>
                                <span class="final-payment-amount" id="summaryRemaining">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    {{-- SIGNATURE (MODIFIKASI: DUAL SIGNATURE) --}}
                    <div class="section-card">
                        <h4 class="form-section-title">Tanda Tangan Pengembalian</h4>
                        <div class="signature-row">
                            <div class="signature-box" style="width:100%; margin-bottom:20px;">
                                <span class="signature-role" style="display:block; margin-bottom:10px;">Penyewa</span>
                                <div id="sig-placeholder-customer" class="signature-placeholder" onclick="openSignatureModal('customer')" style="@error('signature_customer_svg') border-color:#dc3545; background-color:#fff8f8; @enderror">
                                    <i class="fas fa-file-signature" style="font-size:24px; margin-bottom:5px;"></i><span style="font-size:12px;">Klik Tanda Tangan</span>
                                </div>
                                <div id="sig-preview-box-customer" class="signature-preview-box"><img id="sig-image-customer" class="signature-img"><div onclick="resetSignature('customer')" class="btn-reset-sig">Hapus</div></div>
                                <input type="hidden" name="signature_customer_svg" id="signature_customer_input">
                                <div class="signature-line" style="margin-top:10px;">{{ $booking->user->name }}</div>
                            </div>
                            <div class="signature-box" style="width:100%;">
                                <span class="signature-role" style="display:block; margin-bottom:10px;">Petugas</span>
                                <div id="sig-placeholder-officer" class="signature-placeholder" onclick="openSignatureModal('officer')" style="@error('signature_officer_svg') border-color:#dc3545; background-color:#fff8f8; @enderror">
                                    <i class="fas fa-file-signature" style="font-size:24px; margin-bottom:5px;"></i><span style="font-size:12px;">Klik Tanda Tangan</span>
                                </div>
                                <div id="sig-preview-box-officer" class="signature-preview-box"><img id="sig-image-officer" class="signature-img"><div onclick="resetSignature('officer')" class="btn-reset-sig">Hapus</div></div>
                                <input type="hidden" name="signature_officer_svg" id="signature_officer_input">
                                <div class="signature-line" style="margin-top:10px;">{{ Auth::user()->name }}</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </main>

    {{-- MODAL SIGNATURE (Hidden) --}}
    <div id="signatureModal" class="custom-modal">
        <div class="custom-modal-content">
            <div class="modal-header">
                <span class="modal-title">Tanda Tangan Digital</span>
                <span class="close-modal" onclick="closeSignatureModal()">&times;</span>
            </div>
            <div class="canvas-wrapper"><canvas id="signature-pad"></canvas></div>
            <div class="modal-footer">
                <button type="button" class="btn-action btn-dark" style="background:#ccc; color:#333; padding:8px 15px;" onclick="clearSignaturePad()">Bersihkan</button>
                <button type="button" class="btn-action btn-yellow" style="padding:8px 20px;" onclick="saveSignature()">Simpan</button>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<script>
    // ============================================================
    // 1. DATA DARI DATABASE (PHP KE JS)
    // ============================================================
    // Menggunakan '?? 0' untuk mencegah error jika data null
    const baseTotalPrice = {{ $booking->total_price ?? 0 }};
    const paidAmount = {{ $booking->down_payment ?? 0 }};

    // Tarif Denda: 10% dari Harga Sewa per Jam
    const hourlyFineRate = baseTotalPrice * 0.10;

    // KM Awal (Untuk hitung jarak)
    const initialKM = {{ $booking->detail->start_km ?? 0 }};

    // ============================================================
    // 2. FUNGSI FORMAT RUPIAH (Agar terlihat profesional)
    // ============================================================
    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    function parseRupiah(str) {
        if (!str) return 0;
        // Hapus semua karakter kecuali angka
        return parseInt(str.toString().replace(/[^0-9]/g, '')) || 0;
    }

    // ============================================================
    // 3. LOGIKA HITUNG DENDA OVERTIME (OTOMATIS)
    // ============================================================
    function calculateOvertime() {
        // Ambil string tanggal dari input
        const plannedStr = document.getElementById('planned_end_date').value; // Format: YYYY-MM-DD HH:mm
        const actualStr = document.getElementById('actual_end_date').value;   // Format: YYYY-MM-DD HH:mm

        if (!plannedStr || !actualStr) return;

        // Ubah ke Object Date
        const plannedDate = new Date(plannedStr);
        const actualDate = new Date(actualStr);

        let fine = 0;
        let lateHours = 0;

        // Cek apakah Actual > Planned
        if (actualDate > plannedDate) {
            const diffMs = actualDate - plannedDate; // Selisih miliseconds
            const diffHrsExact = diffMs / (1000 * 60 * 60); // Selisih jam (desimal)

            // Pembulatan ke atas (contoh: 1 jam 1 menit = 2 jam denda)
            lateHours = Math.ceil(diffHrsExact);

            // Hitung Denda
            fine = lateHours * hourlyFineRate;

            // Tampilkan Info Alert
            document.getElementById('late_alert_box').style.display = 'block';
            document.getElementById('late_message').innerText =
                `Terlambat ${lateHours} Jam. Denda Rp ${formatRupiah(fine)} diterapkan.`;
        } else {
            // Tidak Telat
            document.getElementById('late_alert_box').style.display = 'none';
        }

        // Masukkan nilai ke Input Denda Overtime (Format Rupiah)
        document.getElementById('fine_overtime').value = formatRupiah(fine);

        // Hitung ulang semua total
        recalculateGrandTotal();
    }

    // ============================================================
    // 4. LOGIKA HITUNG GRAND TOTAL (REALTIME)
    // ============================================================
    function recalculateGrandTotal() {
        // 1. Ambil Denda Overtime
        let overtime = parseRupiah(document.getElementById('fine_overtime').value);

        // 2. Ambil Denda Manual (Looping class .manual-fine)
        let manualTotal = 0;
        document.querySelectorAll('.manual-fine').forEach(input => {
            manualTotal += parseRupiah(input.value);
        });

        // 3. Hitung Total Denda
        let totalFine = overtime + manualTotal;

        // 4. Hitung Grand Total Baru
        let newGrandTotal = baseTotalPrice + totalFine;

        // 5. Hitung Sisa Bayar
        let remaining = newGrandTotal - paidAmount;

        // --- UPDATE TAMPILAN ---
        document.getElementById('displayTotalFine').innerText = 'Rp ' + formatRupiah(totalFine);
        document.getElementById('summaryFine').innerText = 'Rp ' + formatRupiah(totalFine);
        document.getElementById('summaryGrandTotal').innerText = 'Rp ' + formatRupiah(newGrandTotal);

        // Update Sisa Bayar (Warna Merah jika masih ada sisa, Hijau jika lunas/kembali)
        const elRemaining = document.getElementById('summaryRemaining');
        elRemaining.innerText = 'Rp ' + formatRupiah(remaining);

        if (remaining > 0) {
            elRemaining.style.color = '#dc2626'; // Merah (Kurang Bayar)
        } else {
            elRemaining.style.color = '#16a34a'; // Hijau (Lunas/Lebih)
        }
    }

    // Event Listener untuk Input Denda Manual (Format Rupiah saat mengetik)
    document.querySelectorAll('.manual-fine').forEach(input => {
        input.addEventListener('input', function(e) {
            // Simpan posisi kursor (opsional, untuk UX lebih baik)
            let val = parseRupiah(this.value);
            this.value = formatRupiah(val); // Format ulang ke 1.000
            recalculateGrandTotal(); // Hitung total lagi
        });
    });

    // ============================================================
    // 5. LOGIKA LAINNYA (KM, SLIDER, GAMBAR)
    // ============================================================

    // Hitung Jarak KM
    function calcKm() {
        const endKM = parseInt(document.getElementById('end_km').value) || 0;
        const diff = endKM - initialKM;

        const text = (diff > 0) ? diff + " km" : "0 km";
        document.getElementById('total_distance').innerText = text;

        // Validasi Visual Sederhana (Opsional)
        if(endKM < initialKM && endKM > 0) {
            alert("KM Akhir tidak boleh lebih kecil dari KM Awal (" + initialKM + ")");
        }
    }

    // Slider BBM
    const bbmSlider = document.getElementById('bbmSlider');
    if(bbmSlider){
        bbmSlider.addEventListener('input', function(){
            document.getElementById('bbmDisplay').innerText = 'Level BBM: ' + this.value + '%';
        });
    }

    // Preview Gambar Upload
    function previewImage(input, id) {
        if(input.files && input.files[0]) {
            const r = new FileReader();
            r.onload = function(e) {
                document.getElementById(id).src = e.target.result;
                document.getElementById(id).style.display = 'block';
            }
            r.readAsDataURL(input.files[0]);
        }
    }

    // Nama File Video
    function showVideoName(input, id) {
        if(input.files && input.files[0]) {
            document.getElementById(id).style.display='block';
            document.getElementById(id).innerText="File: "+input.files[0].name;
        }
    }

    // ============================================================
    // 6. LOGIKA TANDA TANGAN (MODAL)
    // ============================================================
    var signaturePad;
    var currentTarget = '';

    function openSignatureModal(target) {
        currentTarget = target;
        document.getElementById('signatureModal').classList.add('show');

        if(!signaturePad){
            signaturePad = new SignaturePad(document.getElementById('signature-pad'), {
                backgroundColor:'rgba(255,255,255,0)',
                penColor:'rgb(0,0,0)'
            });
        } else {
            signaturePad.clear();
        }
        // Resize canvas agar tidak gepeng
        setTimeout(resizeCanvas, 100);
    }

    function closeSignatureModal() {
        document.getElementById('signatureModal').classList.remove('show');
    }

    function resizeCanvas() {
        var c = document.getElementById('signature-pad');
        var r = Math.max(window.devicePixelRatio || 1, 1);
        c.width = c.offsetWidth * r;
        c.height = c.offsetHeight * r;
        c.getContext("2d").scale(r,r);
        signaturePad.clear();
    }

    function clearSignaturePad() {
        if(signaturePad) signaturePad.clear();
    }

    function saveSignature() {
        if(signaturePad.isEmpty()){
            alert("Harap tanda tangan terlebih dahulu.");
            return;
        }

        var dataUrl = signaturePad.toDataURL('image/png');

        // Simpan ke input hidden & tampilkan preview
        document.getElementById('signature_'+currentTarget+'_input').value = dataUrl;
        document.getElementById('sig-image-'+currentTarget).src = dataUrl;

        // Toggle Tampilan
        document.getElementById('sig-placeholder-'+currentTarget).style.display='none';
        document.getElementById('sig-preview-box-'+currentTarget).style.display='block';

        closeSignatureModal();
    }

    function resetSignature(target) {
        document.getElementById('signature_'+target+'_input').value = '';
        document.getElementById('sig-image-'+target).src = '';
        document.getElementById('sig-preview-box-'+target).style.display = 'none';
        document.getElementById('sig-placeholder-'+target).style.display = 'flex';
    }

    // Close modal jika klik di luar
    window.onclick = function(e) {
        if(e.target == document.getElementById('signatureModal')) closeSignatureModal();
    }

    // ============================================================
    // 7. INISIALISASI SAAT LOAD
    // ============================================================
    // Jalankan hitungan saat halaman pertama dibuka (untuk antisipasi jika langsung telat)
    calcOvertime();

</script>
@endsection
