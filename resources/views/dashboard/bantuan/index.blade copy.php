@extends('dashboard.layouts.index')
@section('title', 'Bantuan & Kontak')

@section('style')
    <style>
        /* --- Layout Wrapper --- */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 40px;
            min-height: 100vh;
            background-color: #F8F9FA; /* Background abu-abu muda lembut */
            transition: 0.3s;
        }

        /* --- Typography Headers --- */
        h1.page-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 30px;
            color: #111;
        }

        h2.section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            margin-top: 10px;
            color: #111;
        }

        /* --- Top Cards (3 Columns) --- */
        .top-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .contact-card {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            border: 1px solid #f0f0f0;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        /* Icon Styles (Persis Foto) */
        .icon-wrapper {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .icon-blue { background-color: #EFF6FF; color: #3B82F6; }   /* Biru Telepon */
        .icon-green { background-color: #F0FDF4; color: #22C55E; }  /* Hijau WA */
        .icon-purple { background-color: #FAF5FF; color: #A855F7; } /* Ungu Email */

        .card-label { font-size: 14px; color: #666; margin-bottom: 5px; }
        .card-value { font-size: 16px; font-weight: 700; color: #000; margin-bottom: 5px; }
        .card-subtext { font-size: 12px; color: #888; margin-bottom: 20px; }

        /* Tombol Kuning Full Width */
        .btn-yellow {
            width: 100%;
            background-color: #FFC107; /* Kuning standar */
            color: #000;
            font-weight: 600;
            border: none;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.2s;
            font-size: 14px;
        }
        .btn-yellow:hover { background-color: #e0a800; }

        /* --- Bottom Content Split --- */
        .content-split {
            display: grid;
            grid-template-columns: 1.3fr 1fr; /* Kiri sedikit lebih lebar dari kanan */
            gap: 30px;
        }

        /* --- FAQ Section --- */
        .faq-item {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
            border: 1px solid #f0f0f0;
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .faq-icon {
            color: #FFC107; /* Kuning */
            font-size: 18px;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .faq-text h4 {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #111;
        }

        .faq-text p {
            font-size: 13px;
            color: #666;
            line-height: 1.5;
            margin: 0;
        }

        /* --- Form Section & Office --- */
        .white-card {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            border: 1px solid #f0f0f0;
            margin-bottom: 30px;
        }

        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            font-size: 13px;
            color: #555;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            font-size: 14px;
            background-color: #fff;
            color: #333;
        }
        .form-input::placeholder { color: #9CA3AF; }
        .form-input:focus { outline: none; border-color: #FFC107; }

        /* Office Info Styles */
        .office-info-item {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        .office-icon {
            color: #FFC107;
            font-size: 16px;
            margin-top: 3px;
        }
        .office-text h5 { font-size: 14px; font-weight: 700; margin-bottom: 4px; }
        .office-text p { font-size: 13px; color: #666; margin: 0; line-height: 1.4; }

        /* Map Placeholder - Kotak Abu Besar */
        .map-box {
            width: 100%;
            height: 180px;
            background-color: #E5E7EB; /* Abu-abu sesuai gambar */
            border-radius: 8px;
            margin-top: 10px;
        }

        /* Mobile Responsive */
        .mobile-header { display: none; margin-bottom: 20px; justify-content: space-between; align-items: center; }
        .hamburger-btn { background: none; border: none; font-size: 24px; cursor: pointer; color: #333; }

        @media (max-width: 992px) {
            .main-content { margin-left: 0; padding: 20px; }
            .content-split { grid-template-columns: 1fr; } /* Stack ke bawah di HP */
            .mobile-header { display: flex; }
            h1.page-title { display: none; } /* Sembunyikan judul besar di HP agar hemat tempat */
        }
    </style>
@endsection

@section('content')
    <main class="main-content">

        <div class="mobile-header">
            <h2 style="margin:0; font-size:18px;">Bantuan & Kontak</h2>
            <button class="hamburger-btn" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <h1 class="page-title">Bantuan & Kontak</h1>

        <div class="top-cards-grid">

            <div class="contact-card">
                <div class="icon-wrapper icon-blue">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <span class="card-label">Telepon</span>
                <span class="card-value">+62 812-3456-7890</span>
                <span class="card-subtext">Senin - Minggu, 08:00 - 20:00</span>
                <button class="btn-yellow">Hubungi</button>
            </div>

            <div class="contact-card">
                <div class="icon-wrapper icon-green">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <span class="card-label">WhatsApp</span>
                <span class="card-value">+62 812-3456-7890</span>
                <span class="card-subtext">Respon cepat 24/7</span>
                <button class="btn-yellow">Hubungi</button>
            </div>

            <div class="contact-card">
                <div class="icon-wrapper icon-purple">
                    <i class="far fa-envelope"></i>
                </div>
                <span class="card-label">Email</span>
                <span class="card-value">support@hendrarental.com</span>
                <span class="card-subtext">Respon dalam 1x24 jam</span>
                <button class="btn-yellow">Hubungi</button>
            </div>
        </div>


        <div class="content-split">

            <section class="faq-section">
                <h2 class="section-title">Pertanyaan Umum (FAQ)</h2>

                <div class="faq-item">
                    <i class="far fa-question-circle faq-icon"></i>
                    <div class="faq-text">
                        <h4>Bagaimana cara melakukan booking mobil?</h4>
                        <p>Pilih menu "Cari Mobil", pilih mobil yang diinginkan, pilih paket sewa, lalu klik "Ajukan Booking". Isi detail booking dan tunggu konfirmasi dari admin.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <i class="far fa-question-circle faq-icon"></i>
                    <div class="faq-text">
                        <h4>Dokumen apa saja yang diperlukan untuk sewa mobil?</h4>
                        <p>Anda memerlukan KTP asli, SIM A yang masih berlaku, dan selfie dengan KTP. Dokumen akan diverifikasi saat pengambilan mobil.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <i class="far fa-question-circle faq-icon"></i>
                    <div class="faq-text">
                        <h4>Bagaimana cara pembayaran?</h4>
                        <p>Pembayaran dapat dilakukan melalui transfer bank atau QRIS. Setelah booking disetujui, upload bukti pembayaran pada sistem kami.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <i class="far fa-question-circle faq-icon"></i>
                    <div class="faq-text">
                        <h4>Apakah bisa membatalkan booking?</h4>
                        <p>Ya, pembatalan gratis hingga 24 jam sebelum waktu pengambilan. Pembatalan kurang dari 24 jam dikenakan biaya 50% dari total sewa.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <i class="far fa-question-circle faq-icon"></i>
                    <div class="faq-text">
                        <h4>Apakah tersedia layanan antar jemput?</h4>
                        <p>Ya, kami menyediakan layanan antar jemput gratis untuk area Jakarta Pusat dan Jakarta Selatan. Untuk area lain mungkin dikenakan biaya tambahan.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <i class="far fa-question-circle faq-icon"></i>
                    <div class="faq-text">
                        <h4>Bagaimana jika terjadi kerusakan mobil?</h4>
                        <p>Semua mobil kami diasuransikan. Laporkan segera kepada admin jika terjadi kerusakan. Kerusakan akibat kelalaian penyewa akan dikenakan biaya sesuai ketentuan.</p>
                    </div>
                </div>
            </section>

            <section class="right-section">

                <div class="white-card">
                    <h2 class="section-title" style="margin-top:0;">Kirim Pesan</h2>
                    <form action="#">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" class="form-input" placeholder="Masukkan nama Anda">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-input" placeholder="Masukkan email Anda">
                        </div>
                        <div class="form-group">
                            <label>Subjek</label>
                            <input type="text" class="form-input">
                        </div>
                        <div class="form-group">
                            <label>Pesan</label>
                            <textarea class="form-input" rows="4" placeholder="Tulis pesan Anda..."></textarea>
                        </div>
                        <button type="button" class="btn-yellow">Kirim Pesan</button>
                    </form>
                </div>

                <div class="white-card">
                    <h2 class="section-title" style="margin-top:0;">Kantor Kami</h2>

                    <div class="office-info-item">
                        <i class="fas fa-map-marker-alt office-icon"></i>
                        <div class="office-text">
                            <h5>Alamat</h5>
                            <p>Jl. Sudirman No. 123, Jakarta Pusat, DKI Jakarta 10220</p>
                        </div>
                    </div>

                    <div class="office-info-item">
                        <i class="far fa-clock office-icon"></i>
                        <div class="office-text">
                            <h5>Jam Operasional</h5>
                            <p>Senin - Minggu: 08:00 - 20:00 WIB</p>
                            <p>Support 24/7 via WhatsApp</p>
                        </div>
                    </div>

                    <div class="map-box"></div>
                </div>

            </section>

        </div>
    </main>
@endsection
@section('script')
@endsection
