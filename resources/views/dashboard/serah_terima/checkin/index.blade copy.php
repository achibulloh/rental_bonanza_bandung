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

    </style>
@endsection

@section('content')
    <main class="main-content">
        <div class="mobile-header">
            <a href="/" style="color:var(--text-dark); font-weight:700; text-decoration:none;">Bonanza Rental</a>
            <div class="hamburger" onclick="toggleSidebar()"><i class="fas fa-bars"></i></div>
        </div>
        <div class="section-card header-card">
            <div class="form-header-content">
                <h3 class="header-title-main">FORM CHECK-IN KENDARAAN</h3>
                <span class="booking-code">Kode Booking: BK-2024-008</span>
            </div>
            <div class="header-actions">
                <button class="btn-action btn-yellow"><i class="fas fa-save"></i> Simpan Check-in</button>
                <button class="btn-action btn-dark" onclick="history.back()"><i class="fas fa-arrow-left"></i> Kembali</button>
            </div>
        </div>

        <div class="form-grid-layout">

            <div class="form-col-left">

                <div class="section-card">
                    <h4 class="form-section-title"><i class="far fa-user"></i> Data Penyewa</h4>
                    <div class="input-row-group">
                        <div class="input-row"><span class="input-label-fixed">Nama</span><span class="input-value-fixed">: Budi Santoso</span></div>
                        <div class="input-row"><span class="input-label-fixed">Alamat</span><span class="input-value-fixed">: Jl. Sudirman No. 123, Jakarta Selatan</span></div>
                        <div class="input-row"><span class="input-label-fixed">Telepon</span><span class="input-value-fixed">: +62 812-3456-7890</span></div>
                        <div class="input-row"><span class="input-label-fixed">KTP / SIM</span><span class="input-value-fixed">: 3174012345670001</span></div>
                    </div>
                </div>

                <div class="section-card">
                    <h4 class="form-section-title"><i class="fas fa-car"></i> Detail Kendaraan & Jadwal</h4>

                    <div class="row-2-cols">
                        <div class="form-group"><label class="form-label">Jenis Kendaraan</label><input type="text" class="form-input" value="Toyota Avanza 1.5 G" readonly></div>
                        <div class="form-group"><label class="form-label">No. Polisi</label><input type="text" class="form-input" value="B 1234 XYZ" readonly></div>
                    </div>

                    <div class="row-2-cols">
                        <div class="form-group"><label class="form-label">Tanggal Sewa</label><input type="text" class="form-input" value="2026-01-10" readonly></div>
                        <div class="form-group"><label class="form-label">Jadwal Kembali</label><input type="text" class="form-input" value="2026-01-13" readonly></div>
                    </div>

                    <div class="comparison-box-blue">
                        <div class="comp-header">Data Kondisi Awal</div>
                        <div class="row-2-cols">
                            <div>
                                <label class="form-label" style="color: var(--blue-text);">KM Awal (Saat Ini) <span style="color:red">*</span></label>
                                <input type="number" class="form-input" placeholder="Masukkan KM" style="background: #fff; border-color: var(--blue-text);">
                            </div>
                            <div>
                                <label class="form-label" style="color: var(--blue-text);">Waktu Serah Terima <span style="color:red">*</span></label>
                                <input type="datetime-local" class="form-input" style="background: #fff; border-color: var(--blue-text);">
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Catatan Kondisi Awal</label>
                        <textarea class="form-input" placeholder="Catatan mengenai kondisi fisik kendaraan sebelum diserahkan (baret lama, penyok, dll)..."></textarea>
                    </div>
                </div>

                <div class="section-card">
                    <h4 class="form-section-title">Foto Kendaraan (Kondisi Awal) <span style="color:red">*</span></h4>
                    <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 20px;">Wajib upload foto kendaraan dari 4 sisi sebagai bukti awal.</p>

                    <div class="upload-grid-2x2">
                        <div class="upload-item-wrapper">
                            <label class="upload-label-outside">Depan</label>
                            <div class="upload-box-rectangle"><i class="fas fa-camera"></i><span>Upload Foto</span></div>
                        </div>
                        <div class="upload-item-wrapper">
                            <label class="upload-label-outside">Samping Kiri</label>
                            <div class="upload-box-rectangle"><i class="fas fa-camera"></i><span>Upload Foto</span></div>
                        </div>
                        <div class="upload-item-wrapper">
                            <label class="upload-label-outside">Samping Kanan</label>
                            <div class="upload-box-rectangle"><i class="fas fa-camera"></i><span>Upload Foto</span></div>
                        </div>
                        <div class="upload-item-wrapper">
                            <label class="upload-label-outside">Belakang</label>
                            <div class="upload-box-rectangle"><i class="fas fa-camera"></i><span>Upload Foto</span></div>
                        </div>
                    </div>
                </div>

                <div class="section-card">
                    <h4 class="form-section-title">Foto Detail Tambahan</h4>
                    <div class="upload-large-container">
                        <div class="upload-box-large">
                            <i class="fas fa-upload"></i>
                            <span>Klik untuk upload foto detail (baret lama/interior)</span>
                        </div>
                        <div class="upload-box-large">
                            <i class="fas fa-video"></i>
                            <span>Klik untuk upload video kondisi (opsional)</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="form-col-right">

                <div class="section-card">
                    <h4 class="form-section-title">Perlengkapan Kendaraan <span style="color:red">*</span></h4>
                    <ul class="checklist-group">
                        <li class="check-item"><i class="far fa-circle"></i> STNK</li>
                        <li class="check-item"><i class="far fa-circle"></i> Tool Kit</li>
                        <li class="check-item"><i class="far fa-circle"></i> Ban Serep</li>
                        <li class="check-item"><i class="far fa-circle"></i> Dongkrak + Handle</li>
                        <li class="check-item"><i class="far fa-circle"></i> Kunci Roda</li>
                        <li class="check-item"><i class="far fa-circle"></i> Segitiga Pengaman</li>
                        <li class="check-item"><i class="far fa-circle"></i> Radio Disc + Speaker</li>
                        <li class="check-item"><i class="far fa-circle"></i> Velg Racing</li>
                        <li class="check-item"><i class="far fa-circle"></i> Kotak P3K</li>
                        <li class="check-item"><i class="far fa-circle"></i> Kunci Stir</li>
                        <li class="check-item"><i class="far fa-circle"></i> Karpet</li>

                        <li class="check-item" id="btn-dll" style="cursor: pointer;">
                            <i class="far fa-circle" id="icon-dll"></i> Lainnya (dll)
                        </li>
                        <br>
                        <div id="input-dll-wrapper" style="display: none; margin-top: -15px; margin-bottom: 20px; padding-left: 32px;">
                            <input type="text" class="form-input" placeholder="Sebutkan perlengkapan lainnya..." style="font-size: 13px;">
                        </div>
                    </ul>
                </div>

                <div class="section-card">
                    <h4 class="form-section-title"><i class="fas fa-gas-pump"></i> Panel BBM Awal <span style="color:red">*</span></h4>
                    <div style="margin-bottom: 5px;">
                        <span style="font-size:12px; color: var(--text-muted); display: block; margin-bottom: 10px;">Geser untuk menentukan posisi BBM awal:</span>
                        <input type="range" class="bbm-gauge-bar" style="-webkit-appearance:none; width:100%; margin:10px 0;" min="0" max="100" value="100">
                        <div class="bbm-labels-row"><span>E</span><span>1/4</span><span>1/2</span><span>3/4</span><span>F</span></div>
                        <div class="bbm-value-display">Level BBM: 100%</div>
                    </div>
                </div>

                <div class="section-card">
                    <h4 class="form-section-title">Syarat & Ketentuan Sewa</h4>
                    <ul style="font-size: 13px; color: #707EAE; padding-left: 20px; line-height: 1.6; margin: 0;">
                        <li style="margin-bottom:8px;">Penyewa wajib menyerahkan fotocopy KTP/SIM yang masih berlaku beserta aslinya.</li>
                        <li style="margin-bottom:8px;">Penyewa bertanggung jawab penuh atas kerusakan atau kehilangan kendaraan selama masa sewa.</li>
                        <li style="margin-bottom:8px;">Kendaraan harus dikembalikan dalam kondisi bersih dan BBM sesuai posisi awal.</li>
                        <li style="margin-bottom:8px;">Dilarang keras menggunakan kendaraan untuk tindak kejahatan atau melanggar hukum.</li>
                    </ul>
                </div>

            </div>
        </div>

        <div class="section-card">
            <h4 class="form-section-title">Tanda Tangan Serah Terima</h4>
            <div class="signature-row">
                <div class="signature-box">
                    <span class="signature-role">Penyewa,</span>
                    <div class="signature-line">( .................................... )</div>
                </div>
                <div class="signature-box">
                    <span class="signature-role">Hormat kami,</span>
                    <span class="signature-company">Bonanza Rental</span>
                </div>
            </div>
        </div>

    </main>
@endsection

@section('script')
<script>
    // Script Sederhana untuk Toggle Input "Lainnya"
    const btnDll = document.getElementById('btn-dll');
    const inputWrapper = document.getElementById('input-dll-wrapper');
    const iconDll = document.getElementById('icon-dll');

    btnDll.addEventListener('click', function() {
        if (inputWrapper.style.display === 'none') {
            inputWrapper.style.display = 'block';
            iconDll.classList.remove('fa-circle');
            iconDll.classList.add('fa-check-circle');

            // Adjust color based on context (default green here)
            iconDll.style.color = '#05CD99';

            inputWrapper.querySelector('input').focus();
        } else {
            inputWrapper.style.display = 'none';
            iconDll.classList.remove('fa-check-circle');
            iconDll.classList.add('fa-circle');
            iconDll.style.color = '#A3AED0';
        }
    });

    // Simple interaction for checklists (for demo purposes)
    const checkItems = document.querySelectorAll('.check-item:not(#btn-dll)');
    checkItems.forEach(item => {
        item.addEventListener('click', () => {
            const icon = item.querySelector('i');
            item.classList.toggle('active');
            if(item.classList.contains('active')) {
                icon.classList.remove('far', 'fa-circle');
                icon.classList.add('fas', 'fa-check-circle');
            } else {
                icon.classList.remove('fas', 'fa-check-circle');
                icon.classList.add('far', 'fa-circle');
            }
        });
    });

    // Simple Interaction for BBM Slider
    const bbmSlider = document.querySelector('.bbm-gauge-bar');
    const bbmDisplay = document.querySelector('.bbm-value-display');
    if(bbmSlider && bbmDisplay) {
        bbmSlider.addEventListener('input', function() {
            bbmDisplay.textContent = 'Level BBM: ' + this.value + '%';
        });
    }
</script>
@endsection
