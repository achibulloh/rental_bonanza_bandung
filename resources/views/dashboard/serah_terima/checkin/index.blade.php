@extends('dashboard.layouts.index')
@section('title', 'Check In - Serah Terima Mobil')

@section('style')
    <style>
        :root {
            --primary-yellow: #FFD700;
            --primary-yellow-hover: #e6c200;
            --text-dark: #212529;
            --text-muted: #6c757d;
            --bg-light: #f4f6f9;
            --white: #FFFFFF;
            --border-color: #e9ecef;
            --input-bg: #f8f9fa;

            /* Warna Status */
            --badge-yellow-bg: #fff9c4;
            --badge-yellow-text: #f57f17;
            --badge-green-bg: #e8f5e9;
            --badge-green-text: #2e7d32;

            /* Warna Button Disabled */
            --btn-disabled-bg: #e9ecef;
            --btn-disabled-text: #adb5bd;
            --btn-dark-grey: #495057;

            /* Spacing Standard */
            --card-padding: 30px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            background-color: var(--bg-light);
            color: var(--text-dark);
            min-height: 100vh;
        }

        /* =========================================
           1. MAIN LAYOUT & RESPONSIVENESS
        ========================================= */
        .main-content {
            margin-left: 260px; /* Default Desktop */
            flex: 1;
            padding: 30px 40px;
            transition: all 0.3s ease;
        }
        .mobile-header { display: none; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .hamburger { font-size: 1.5rem; cursor: pointer; color: var(--text-dark); }

        /* Responsive Fix for Mobile/Tablet */
        @media (max-width: 992px) {
            .main-content {
                margin-left: 0; /* Remove sidebar margin */
                padding: 20px;  /* Reduce padding */
            }
            :root {
                --card-padding: 20px; /* Smaller card padding */
            }
            .mobile-header { display: flex; }
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .back-link i { margin-right: 8px; }

        /* =========================================
           2. CARD SYSTEM
        ========================================= */
        .section-card {
            background-color: var(--white);
            border-radius: 20px;
            padding: var(--card-padding);
            box-shadow: 0px 5px 20px rgba(112, 144, 176, 0.08);
            border: none;
            margin-bottom: 30px;
            height: fit-content;
        }

        /* Header Card */
        .header-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap; /* Allow wrapping on small screens */
            gap: 20px;
        }

        .header-title-main {
            margin: 5px 0;
            font-size: 22px;
            font-weight: 800;
            color: #1B2559;
            text-transform: uppercase;
        }

        .booking-code {
            color: #FFA800;
            font-weight: 700;
            font-size: 14px;
            background: #FFF8E1;
            padding: 4px 10px;
            border-radius: 6px;
            display: inline-block;
            margin-top: 5px;
        }

        .header-actions {
            display: flex;
            gap: 15px;
        }

        /* =========================================
           3. FORM GRID LAYOUT
        ========================================= */
        .form-grid-layout {
            display: grid;
            grid-template-columns: 1.6fr 1fr; /* 60% - 40% Desktop */
            gap: 30px;
            align-items: start;
        }

        .form-col-left, .form-col-right {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        /* Responsive Grid Fix */
        @media (max-width: 992px) {
            .form-grid-layout {
                grid-template-columns: 1fr; /* Single Column on Mobile */
            }
            .header-card {
                flex-direction: column;
                align-items: flex-start;
            }
            .header-actions {
                width: 100%;
                display: grid;
                grid-template-columns: 1fr 1fr; /* Buttons side-by-side on mobile */
            }
            .mobile-header { display: flex; }

        }

        @media (max-width: 480px) {
             .header-actions {
                grid-template-columns: 1fr; /* Stack buttons on very small screens */
            }
        }

        /* =========================================
           4. FORM COMPONENTS
        ========================================= */
        .form-section-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 25px;
            color: #1B2559;
            padding-bottom: 10px;
            border-bottom: 1px solid #F4F7FE;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .form-section-title i { font-size: 18px; color: #2B3674; }

        /* Read-only Data Rows */
        .input-row-group { margin-bottom: 20px; }
        .input-row {
            display: flex;
            margin-bottom: 12px;
            font-size: 14px;
            flex-wrap: wrap; /* Allow wrapping */
        }
        .input-label-fixed {
            width: 120px;
            color: var(--text-muted);
            font-weight: 500;
            flex-shrink: 0;
        }
        .input-value-fixed {
            font-weight: 600;
            color: #1B2559;
            flex: 1; /* Take remaining space */
            word-break: break-word; /* Prevent overflow */
        }

        /* 2-Column Inputs Row */
        .row-2-cols {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 768px) {
            .row-2-cols {
                grid-template-columns: 1fr; /* Stack on mobile */
                gap: 15px;
            }
            .mobile-header { display: flex; }
        }

        .form-group { margin-bottom: 20px; }
        .form-label { display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #1B2559; }
        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            font-size: 14px;
            background-color: var(--white);
            transition: all 0.2s;
            color: #1B2559;
            font-weight: 500;
        }
        .form-input:focus { outline: none; border-color: var(--primary-yellow); box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.1); }
        .form-input[readonly] { background-color: #F8F9FA; color: #707EAE; border-color: transparent; cursor: default; }
        textarea.form-input { resize: vertical; height: 100px; }

        /* Comparison Box */
        .comparison-box-blue { background-color: #EBF3FF; border-radius: 12px; padding: 20px; margin-bottom: 20px; }
        .comp-header { font-size: 14px; font-weight: 700; color: var(--blue-text); margin-bottom: 15px; }

        /* Checklist */
        .checklist-group { list-style: none; padding: 0; margin: 0; }
        .check-item { display: flex; align-items: center; margin-bottom: 15px; font-size: 14px; color: #1B2559; font-weight: 500; cursor: pointer; }
        .check-item i { margin-right: 12px; font-size: 20px; color: #A3AED0; }
        .check-item.active i { color: #05CD99; font-weight: 900; }

        /* BBM Gauge */
        .bbm-gauge-container { margin-bottom: 25px; }
        .bbm-gauge-bar {
            height: 14px; width: 100%; border-radius: 7px; position: relative; margin: 12px 0;
            background: linear-gradient(90deg, #FF5B5B 0%, #FFB547 50%, #05CD99 100%);
        }
        .bbm-labels-row { display: flex; justify-content: space-between; font-size: 11px; color: var(--text-muted); font-weight: 600; }
        .bbm-value-display { text-align: center; font-weight: 800; font-size: 14px; margin-top: 8px; color: #1B2559; }

        /* Upload Grid */
        .upload-grid-2x2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 576px) {
            .upload-grid-2x2 {
                grid-template-columns: 1fr; /* Stack images on small mobile */
            }
            .mobile-header { display: flex; }

        }

        .upload-item-wrapper { display: flex; flex-direction: column; }
        .upload-label-outside { font-size: 14px; color: #1B2559; margin-bottom: 10px; font-weight: 600; }
        .upload-box-rectangle {
            background-color: #FAFCFE; border: 1px dashed #A3AED0;
            border-radius: 12px; height: 150px;
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            cursor: pointer; transition: all 0.2s ease;
        }
        .upload-box-rectangle:hover {
            border-color: var(--primary-yellow); background-color: #FFF;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
        }
        .upload-box-rectangle i { font-size: 28px; color: #A3AED0; margin-bottom: 10px; }
        .upload-box-rectangle span { font-size: 13px; color: #707EAE; font-weight: 500; }

        .upload-large-container { display: grid; gap: 15px; }
        .upload-box-large {
            border: 1px dashed #A3AED0; border-radius: 12px; padding: 30px;
            text-align: center; cursor: pointer; background: #FAFCFE;
        }
        .upload-box-large:hover { border-color: var(--primary-yellow); background-color: #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .upload-box-large i { font-size: 32px; margin-bottom: 10px; color: #A3AED0; display: block; margin: 0 auto 10px auto; }
        .upload-box-large span { font-size: 13px; color: #707EAE; }

        /* Buttons */
        .btn-action {
            padding: 12px 28px; border-radius: 10px; font-weight: 700; font-size: 14px;
            border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; text-decoration: none;
            transition: transform 0.1s;
        }
        .btn-action:active { transform: scale(0.98); }
        .btn-yellow { background-color: var(--primary-yellow); color: #1B2559; box-shadow: 0 4px 10px rgba(255, 215, 0, 0.3); }
        .btn-yellow:hover { background-color: var(--primary-yellow-hover); }
        .btn-dark { background-color: #2B3674; color: #fff; box-shadow: 0 4px 10px rgba(43, 54, 116, 0.3); }
        .btn-dark:hover { background-color: #212a5e; }

        /* Signature */
        .signature-row {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding: 0 20px;
            flex-wrap: wrap;
            gap: 40px;
        }
        .signature-box { text-align: center; width: 220px; margin: 0 auto; }
        .signature-role { font-size: 13px; color: #707EAE; font-weight: 600; margin-bottom: 80px; display: block; }
        .signature-line { border-top: 1px solid #E0E5F2; padding-top: 8px; font-size: 14px; color: #1B2559; font-weight: 700; }
        .signature-company { font-weight: 700; display: block; margin-top: 8px; font-size: 14px; color: #1B2559; }
        .upload-box-rectangle label {
            cursor: pointer; width: 100%; height: 100%;
            display: flex; flex-direction: column;
            justify-content: center; align-items: center;
        }
        /* Hide default file input */
        .hidden-file-input { display: none; }

        /* Image Preview Style */
        .img-preview {
            width: 100%; height: 100%; object-fit: cover; border-radius: 12px; display: none;
        }

        /* === MODAL STYLE (RESPONSIF) === */
        .custom-modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.6); /* Gelap transparan */
            align-items: center;
            justify-content: center;
            padding: 20px; /* Jarak aman di HP */
        }

        .custom-modal.show {
            display: flex; /* Flex agar tengah */
            animation: fadeIn 0.2s ease-in-out;
        }

        .custom-modal-content {
            background-color: #fff;
            margin: auto;
            padding: 20px;
            border-radius: 16px;
            width: 100%;
            max-width: 500px; /* Maksimal lebar di desktop */
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .modal-header {
            display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;
        }
        .modal-title { font-weight: 800; font-size: 18px; color: #333; }
        .close-modal { font-size: 28px; font-weight: bold; color: #aaa; cursor: pointer; line-height: 1; }
        .close-modal:hover { color: #000; }

        /* Canvas Wrapper */
        .canvas-wrapper {
            border: 2px dashed #ccc;
            border-radius: 8px;
            background: #fdfdfd;
            height: 250px; /* Tinggi canvas */
            width: 100%;
            position: relative;
            margin-bottom: 20px;
            touch-action: none; /* PENTING: Agar tidak scroll saat tanda tangan di HP */
        }

        canvas { width: 100%; height: 100%; display: block; }

        .modal-footer {
            display: flex; justify-content: flex-end; gap: 10px;
        }

        /* === PREVIEW AREA (RESPONSIF) === */
        .signature-box {
            width: 100%;
            max-width: 300px; /* Batas lebar di desktop */
            margin: 0 auto; /* Tengah */
        }

        .signature-placeholder {
            width: 100%;
            height: 120px;
            border: 2px dashed #A3AED0;
            border-radius: 12px;
            background: #FAFCFE;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            cursor: pointer;
            color: #A3AED0;
            transition: all 0.2s;
        }
        .signature-placeholder:hover {
            border-color: var(--primary-yellow); color: #d4a000; background: #fffdf5;
        }

        .signature-preview-box {
            display: none; /* Hidden default */
            text-align: center;
            width: 100%;
        }

        /* Gambar Preview Responsif */
        .signature-img {
            width: 100%;
            height: auto; /* Tinggi otomatis proporsional */
            max-height: 150px; /* Batas tinggi agar tidak terlalu besar */
            object-fit: contain;
            border-bottom: 1px solid #eee;
            margin-bottom: 5px;
        }

        .btn-reset-sig {
            font-size: 12px; color: #dc2626; cursor: pointer; text-decoration: underline; font-weight: 600;
        }
    </style>
@endsection

@section('content')
        {{-- Form Start --}}
        <form action="{{ route('serah_terima.store_checkin', $booking->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Header --}}
            <div class="section-card header-card">
                <div class="form-header-content">
                    <h3 class="header-title-main">FORM CHECK-IN KENDARAAN</h3>
                    <span class="booking-code">Kode Booking: {{ $booking->booking_code }}</span>
                </div>
                <div class="header-actions">
                    <button type="submit" class="btn-action btn-yellow"><i class="fas fa-save"></i> Simpan Check-in</button>
                    <a href="{{ route('serah_terima.index') }}" class="btn-action btn-dark"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
            </div>

            <div class="form-grid-layout">

                {{-- Left Column --}}
                <div class="form-col-left">

                    {{-- Data Penyewa --}}
                    <div class="section-card">
                        <h4 class="form-section-title"><i class="far fa-user"></i> Data Penyewa</h4>
                        <div class="input-row-group">
                            <div class="input-row"><span class="input-label-fixed">Nama</span><span class="input-value-fixed">: {{ $booking->user->name }}</span></div>
                            <div class="input-row"><span class="input-label-fixed">Alamat</span><span class="input-value-fixed">: {{ $booking->user->address }}</span></div>
                            <div class="input-row"><span class="input-label-fixed">Telepon</span><span class="input-value-fixed">: {{ $booking->user->phone ?? '-' }}</span></div>
                            {{-- Add other fields if available in your User/Booking model --}}
                        </div>
                    </div>

                    {{-- Detail Kendaraan --}}
                    <div class="section-card">
                        <h4 class="form-section-title"><i class="fas fa-car"></i> Detail Kendaraan & Jadwal</h4>

                        <div class="row-2-cols">
                            <div class="form-group"><label class="form-label">Jenis Kendaraan</label><input type="text" class="form-input" value="{{ $booking->car->name }}" readonly></div>
                            <div class="form-group"><label class="form-label">No. Polisi</label><input type="text" class="form-input" value="{{ $booking->car->license_plate }}" readonly></div>
                        </div>

                        <div class="row-2-cols">
                            <div class="form-group"><label class="form-label">Tanggal Sewa</label><input type="text" class="form-input" value="{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}" readonly></div>
                            <div class="form-group"><label class="form-label">Jadwal Kembali</label><input type="text" class="form-input" value="{{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}" readonly></div>
                        </div>

                        <div class="comparison-box-blue">
                            <div class="comp-header">Data Kondisi Awal</div>
                            <div class="row-2-cols">
                                <div>
                                    <label class="form-label" style="color: var(--blue-text);">KM Awal (Saat Ini) <span style="color:red">*</span></label>
                                    <input type="number" name="km_awal" class="form-input" placeholder="Masukkan KM" style="background: #fff; border-color: var(--blue-text);" required>
                                </div>
                                <div>
                                    <label class="form-label" style="color: var(--blue-text);">Waktu Serah Terima <span style="color:red">*</span></label>
                                    <input type="datetime-local" name="waktu_serah_terima" class="form-input" style="background: #fff; border-color: var(--blue-text);" required value="{{ now()->format('Y-m-d\TH:i') }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Catatan Kondisi Awal</label>
                            <textarea name="kondisi_awal" class="form-input" placeholder="Catatan mengenai kondisi fisik kendaraan sebelum diserahkan (baret lama, penyok, dll)..."></textarea>
                        </div>
                    </div>

                    {{-- Foto Kendaraan --}}
                    <div class="section-card">
                        <h4 class="form-section-title">Foto Kendaraan (Kondisi Awal)</h4>
                        <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 20px;">Wajib upload foto kendaraan dari 4 sisi sebagai bukti awal.</p>

                        <div class="upload-grid-2x2">
                            {{-- Front Photo --}}
                            <div class="upload-item-wrapper">
                                <label class="upload-label-outside">Depan</label>
                                <div class="upload-box-rectangle">
                                    <label for="photo_front">
                                        <i class="fas fa-camera"></i><span>Upload Foto</span>
                                        <img id="preview_front" class="img-preview">
                                    </label>
                                    <input type="file" name="photo_front" id="photo_front" class="hidden-file-input" accept="image/*" onchange="previewImage(this, 'preview_front')">
                                </div>
                            </div>
                            {{-- Left Photo --}}
                            <div class="upload-item-wrapper">
                                <label class="upload-label-outside">Samping Kiri</label>
                                <div class="upload-box-rectangle">
                                    <label for="photo_left">
                                        <i class="fas fa-camera"></i><span>Upload Foto</span>
                                        <img id="preview_left" class="img-preview">
                                    </label>
                                    <input type="file" name="photo_left" id="photo_left" class="hidden-file-input" accept="image/*" onchange="previewImage(this, 'preview_left')">
                                </div>
                            </div>
                            {{-- Right Photo --}}
                            <div class="upload-item-wrapper">
                                <label class="upload-label-outside">Samping Kanan</label>
                                <div class="upload-box-rectangle">
                                    <label for="photo_right">
                                        <i class="fas fa-camera"></i><span>Upload Foto</span>
                                        <img id="preview_right" class="img-preview">
                                    </label>
                                    <input type="file" name="photo_right" id="photo_right" class="hidden-file-input" accept="image/*" onchange="previewImage(this, 'preview_right')">
                                </div>
                            </div>
                            {{-- Back Photo --}}
                            <div class="upload-item-wrapper">
                                <label class="upload-label-outside">Belakang</label>
                                <div class="upload-box-rectangle">
                                    <label for="photo_back">
                                        <i class="fas fa-camera"></i><span>Upload Foto</span>
                                        <img id="preview_back" class="img-preview">
                                    </label>
                                    <input type="file" name="photo_back" id="photo_back" class="hidden-file-input" accept="image/*" onchange="previewImage(this, 'preview_back')">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="section-card">
                        <h4 class="form-section-title">Dokumentasi Serah Terima</h4>
                        <div class="upload-large-container">

                            {{-- 1. FOTO MOBIL BERSAMA CUSTOMER --}}
                            <div class="upload-box-large">
                                <label for="photo_car_and_customer" style="cursor: pointer; display: block; width: 100%; height: 100%;">
                                    <i class="fas fa-users" style="font-size: 32px; color: #A3AED0; margin-bottom: 10px;"></i>
                                    <span style="display: block; font-size: 13px; color: #707EAE;">Klik untuk upload foto mobil bersama customer</span>

                                    {{-- Preview Image --}}
                                    <img id="prev_customer" class="img-preview" style="object-fit: contain; background: #fff;">
                                </label>
                                {{-- Input File --}}
                                <input type="file" name="photo_car_and_customer" id="photo_car_and_customer" class="hidden-file-input" accept="image/*" onchange="previewImage(this, 'prev_customer')">
                            </div>

                            {{-- 2. VIDEO KONDISI --}}
                            <div class="upload-box-large">
                                <label for="video_condition" style="cursor: pointer; display: block; width: 100%; height: 100%;">
                                    <i class="fas fa-video" style="font-size: 32px; color: #A3AED0; margin-bottom: 10px;"></i>
                                    <span style="display: block; font-size: 13px; color: #707EAE;">Klik untuk upload video kondisi (opsional)</span>

                                    {{-- Teks Feedback Nama File Video --}}
                                    <p id="video_filename" style="display:none; color: #2e7d32; font-weight: 600; margin-top: 10px; font-size: 13px; word-break: break-all;"></p>
                                </label>
                                {{-- Input File --}}
                                <input type="file" name="video_condition" id="video_condition" class="hidden-file-input" accept="video/*" onchange="showVideoName(this, 'video_filename')">
                            </div>

                        </div>
                    </div>

                </div>

                {{-- Right Column --}}
                <div class="form-col-right">

                    {{-- Checklist --}}
                    <div class="section-card">
                        <h4 class="form-section-title">Perlengkapan Kendaraan <span style="color:red">*</span></h4>
                        <ul class="checklist-group">
                            @php
                                $items = ['STNK', 'Tool Kit', 'Ban Serep', 'Dongkrak + Handle', 'Kunci Roda', 'Segitiga Pengaman', 'Radio Disc + Speaker', 'Velg Racing', 'Kotak P3K', 'Kunci Stir', 'Karpet'];
                            @endphp

                            @foreach($items as $item)
                                <li class="check-item" onclick="toggleCheck(this)">
                                    <i class="far fa-circle"></i>
                                    <span>{{ $item }}</span>
                                    {{-- Hidden input to store value --}}
                                    <input type="checkbox" name="checklist[]" value="{{ $item }}" style="display:none;">
                                </li>
                            @endforeach

                            <li class="check-item" id="btn-dll" style="cursor: pointer;">
                                <i class="far fa-circle" id="icon-dll"></i> Lainnya (dll)
                            </li>
                            <br>
                            <div id="input-dll-wrapper" style="display: none; margin-top: -15px; margin-bottom: 20px; padding-left: 32px;">
                                <input type="text" name="checklist[]" class="form-input" placeholder="Sebutkan perlengkapan lainnya..." style="font-size: 13px;">
                            </div>
                        </ul>
                    </div>

                    {{-- BBM Gauge --}}
                    <div class="section-card">
                        <h4 class="form-section-title"><i class="fas fa-gas-pump"></i> Panel BBM Awal <span style="color:red">*</span></h4>
                        <div style="margin-bottom: 5px;">
                            <span style="font-size:12px; color: var(--text-muted); display: block; margin-bottom: 10px;">Geser untuk menentukan posisi BBM awal:</span>
                            <input type="range" name="bbm_awal" class="bbm-gauge-bar" style="-webkit-appearance:none; width:100%; margin:10px 0;" min="0" max="100" value="100" id="bbmSlider">
                            <div class="bbm-labels-row"><span>E</span><span>1/4</span><span>1/2</span><span>3/4</span><span>F</span></div>
                            <div class="bbm-value-display" id="bbmDisplay">Level BBM: 100%</div>
                        </div>
                    </div>

                    {{-- Terms --}}
                    <div class="section-card">
                        <h4 class="form-section-title">Syarat & Ketentuan Sewa</h4>
                        <ul style="font-size: 13px; color: #707EAE; padding-left: 20px; line-height: 1.6; margin: 0;">
                            <li style="margin-bottom:8px;">Penyewa wajib menyerahkan fotocopy KTP/SIM yang masih berlaku beserta aslinya.</li>
                            <li style="margin-bottom:8px;">Penyewa bertanggung jawab penuh atas kerusakan atau kehilangan kendaraan selama masa sewa.</li>
                            <li style="margin-bottom:8px;">Kendaraan harus dikembalikan dalam kondisi bersih dan BBM sesuai posisi awal.</li>
                        </ul>
                    </div>

                </div>
            </div>

            {{-- Signature --}}
            <div class="section-card">
                <h4 class="form-section-title">Tanda Tangan Serah Terima</h4>

                <div class="signature-row">

                    <div class="signature-box">
                        <span class="signature-role" style="margin-bottom: 10px; display:block;">Penyewa</span>

                        <div id="sig-placeholder-customer" class="signature-placeholder" onclick="openSignatureModal('customer')">
                            <i class="fas fa-file-signature" style="font-size: 24px; margin-bottom: 5px;"></i>
                            <span style="font-size: 13px; font-weight: 600;">Klik untuk Tanda Tangan</span>
                        </div>

                        <div style="font-size: 12px; color: #999; margin-top: 4px;">Ditangani Oleh Penyewa</div>
                        <div id="sig-preview-box-customer" class="signature-preview-box">
                            <img id="sig-image-customer" class="signature-img" src="" alt="Tanda Tangan Penyewa">
                            <div onclick="resetSignature('customer')" class="btn-reset-sig">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </div>
                        </div>

                        <input type="hidden" name="signature_customer_svg" id="signature_customer_input">

                        <div class="signature-line" style="margin-top: 15px;">{{ $booking->user->name }}</div>
                    </div>

                    <div class="signature-box">
                        <span class="signature-role" style="margin-bottom: 10px; display:block;">Petugas Rental</span>

                        <div id="sig-placeholder-officer" class="signature-placeholder" onclick="openSignatureModal('officer')">
                            <i class="fas fa-file-signature" style="font-size: 24px; margin-bottom: 5px;"></i>
                            <span style="font-size: 13px; font-weight: 600;">Klik untuk Tanda Tangan</span>
                        </div>

                        <div id="sig-preview-box-officer" class="signature-preview-box">
                            <img id="sig-image-officer" class="signature-img" src="" alt="Tanda Tangan Petugas">
                            <div onclick="resetSignature('officer')" class="btn-reset-sig">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </div>
                        </div>

                        <input type="hidden" name="signature_officer_svg" id="signature_officer_input">
                        <div style="font-size: 12px; color: #999; margin-top: 4px;">Ditangani Oleh Petugas</div>
                        <div class="signature-line" style="margin-top: 15px;">
                            {{ Auth::user()->name ?? 'Admin Petugas' }} | (ID: {{ Auth::id() }})
                        </div>
                    </div>

                </div>
            </div>

        </form>
    <div id="signatureModal" class="custom-modal">
        <div class="custom-modal-content">
            <div class="modal-header">
                <span class="modal-title">Tanda Tangan Digital</span>
                <span class="close-modal" onclick="closeSignatureModal()">&times;</span>
            </div>

            <div class="canvas-wrapper">
                <canvas id="signature-pad"></canvas>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-action btn-dark" style="background:#ccc; color:#333; padding: 8px 15px;" onclick="clearSignaturePad()">Bersihkan</button>
                <button type="button" class="btn-action btn-yellow" style="padding: 8px 20px;" onclick="saveSignature()">Simpan Tanda Tangan</button>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    // 1. Checklist Logic
    function toggleCheck(element) {
        // Toggle UI
        const icon = element.querySelector('i');
        const checkbox = element.querySelector('input[type="checkbox"]');

        // Cek apakah class saat ini 'active'
        const isChecked = element.classList.contains('active');

        if (isChecked) {
            // Uncheck
            element.classList.remove('active');
            icon.classList.remove('fas', 'fa-check-circle');
            icon.classList.add('far', 'fa-circle');
            icon.style.color = '#A3AED0';
            checkbox.checked = false;
        } else {
            // Check
            element.classList.add('active');
            icon.classList.remove('far', 'fa-circle');
            icon.classList.add('fas', 'fa-check-circle');
            icon.style.color = '#05CD99';
            checkbox.checked = true;
        }
    }

    // 2. Extra Item Logic
    const btnDll = document.getElementById('btn-dll');
    const inputWrapper = document.getElementById('input-dll-wrapper');
    const iconDll = document.getElementById('icon-dll');

    if(btnDll){
        btnDll.addEventListener('click', function() {
            if (inputWrapper.style.display === 'none') {
                inputWrapper.style.display = 'block';
                iconDll.classList.remove('fa-circle');
                iconDll.classList.add('fa-check-circle');
                iconDll.style.color = '#05CD99';
                inputWrapper.querySelector('input').focus();
            } else {
                inputWrapper.style.display = 'none';
                iconDll.classList.remove('fa-check-circle');
                iconDll.classList.add('fa-circle');
                iconDll.style.color = '#A3AED0';
                inputWrapper.querySelector('input').value = ''; // clear value
            }
        });
    }

    // 3. BBM Slider Logic
    const bbmSlider = document.getElementById('bbmSlider');
    const bbmDisplay = document.getElementById('bbmDisplay');
    if(bbmSlider && bbmDisplay) {
        bbmSlider.addEventListener('input', function() {
            bbmDisplay.textContent = 'Level BBM: ' + this.value + '%';
        });
    }

    // 4. Image Preview Logic
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                // Hide default icon/text relative to this box if needed
                // For simplicity, we just stack it or could hide parent span
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

</script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

    <script>
        // ... (Kode sebelumnya tetap ada) ...

        // === LOGIKA DUAL SIGNATURE ===
        var wrapper = document.getElementById('signature-pad');
        var signaturePad;
        var currentTarget = ''; // Variabel untuk menyimpan siapa yang sedang tanda tangan ('customer' atau 'officer')

        // 1. Buka Modal (Menerima parameter target)
        function openSignatureModal(target) {
            currentTarget = target; // Set target aktif

            var modal = document.getElementById('signatureModal');
            modal.classList.add('show');

            if (!signaturePad) {
                var canvas = document.getElementById('signature-pad');
                signaturePad = new SignaturePad(canvas, {
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                    penColor: 'rgb(0, 0, 0)'
                });
            } else {
                signaturePad.clear(); // Bersihkan pad setiap kali dibuka baru
            }

            setTimeout(resizeCanvas, 100);
        }

        // 2. Tutup Modal
        function closeSignatureModal() {
            document.getElementById('signatureModal').classList.remove('show');
        }

        // 3. Simpan Tanda Tangan (Dinamis sesuai Target)
        function saveSignature() {
            if (signaturePad.isEmpty()) {
                alert("Silakan tanda tangan terlebih dahulu.");
                return;
            }

            var dataUrl = signaturePad.toDataURL('image/png');

            // Simpan ke input & tampilkan preview sesuai target (customer/officer)
            document.getElementById('signature_' + currentTarget + '_input').value = dataUrl;
            document.getElementById('sig-image-' + currentTarget).src = dataUrl;

            // Toggle Tampilan
            document.getElementById('sig-placeholder-' + currentTarget).style.display = 'none';
            document.getElementById('sig-preview-box-' + currentTarget).style.display = 'block';

            closeSignatureModal();
        }

        // 4. Reset Tanda Tangan
        function resetSignature(target) {
            document.getElementById('signature_' + target + '_input').value = '';
            document.getElementById('sig-image-' + target).src = '';

            document.getElementById('sig-preview-box-' + target).style.display = 'none';
            document.getElementById('sig-placeholder-' + target).style.display = 'flex';
        }

        // ... (Fungsi resizeCanvas, clearSignaturePad, window.onclick tetap sama) ...

        function resizeCanvas() {
            var canvas = document.getElementById('signature-pad');
            var ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear();
        }

        function clearSignaturePad() {
            if(signaturePad) signaturePad.clear();
        }

        window.onclick = function(event) {
            var modal = document.getElementById('signatureModal');
            if (event.target == modal) closeSignatureModal();
        }
    </script>
@endsection
