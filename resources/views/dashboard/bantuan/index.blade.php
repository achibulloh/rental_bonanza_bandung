@extends('dashboard.layouts.index')
@section('title', 'Bantuan & Kontak')

@section('style')
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        :root {
            --primary-color: #FFC107; /* Kuning Bonanza */
            --primary-hover: #e0a800;
            --bg-color: #F4F7FE;
            --text-dark: #333;
            --text-grey: #666;
            --white: #ffffff;
            --border-radius: 12px;
        }

        /* Reset & Layout Utama */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 30px;
            min-height: 100vh;
            background-color: var(--bg-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .mobile-header { display: none; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .hamburger { font-size: 1.5rem; cursor: pointer; color: var(--text-dark); }

        h1.page-title {
            margin-bottom: 30px;
            font-size: 24px;
            color: var(--text-dark);
            font-weight: 700;
        }

        /* --- CONTACT CARDS (ATAS) --- */
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: var(--white);
            padding: 25px;
            border-radius: var(--border-radius);
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            border: 1px solid #eee;
            transition: transform 0.2s;
        }

        /* Ikon Warna-warni */
        .contact-icon {
            width: 45px; height: 45px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; margin-bottom: 15px;
        }
        .icon-phone { background: #e3f2fd; color: #1976d2; }
        .icon-wa { background: #e8f5e9; color: #2e7d32; }
        .icon-mail { background: #f3e5f5; color: #7b1fa2; }

        .card h3 { font-size: 14px; margin-bottom: 5px; color: #888; font-weight: 600; }
        .card h2 { font-size: 18px; margin-bottom: 5px; color: #333; font-weight: 700; word-break: break-all; }
        .card p { font-size: 12px; color: var(--text-grey); margin-bottom: 15px; }

        .btn-action {
            display: block; width: 100%; padding: 10px;
            background: var(--primary-color); border: none; border-radius: 6px;
            cursor: pointer; font-weight: 600; color: #000;
            transition: 0.2s; text-decoration: none; text-align: center; font-size: 14px;
        }
        .btn-action:hover { background: var(--primary-hover); }

        /* --- CONTENT SPLIT (FAQ & FORM) --- */
        .content-split {
            display: grid;
            grid-template-columns: 1.5fr 1fr; /* FAQ lebih lebar sedikit */
            gap: 30px;
        }

        /* --- FAQ SECTION (ACCORDION) --- */
        .faq-section h2 { margin-bottom: 20px; font-size: 20px; color: #333; }

        .faq-item {
            background: var(--white);
            border-radius: var(--border-radius);
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
            border: 1px solid #eee;
            overflow: hidden;
        }

        .faq-question {
            padding: 20px;
            cursor: pointer;
            display: flex;
            align-items: flex-start;
            gap: 15px;
            font-weight: 600;
            font-size: 14px;
            color: #333;
            transition: background 0.2s;
        }
        .faq-question:hover { background: #f9f9f9; }

        .faq-question i {
            color: var(--primary-color);
            font-size: 18px;
            margin-top: 2px;
        }

        /* Default Hidden */
        .faq-answer {
            padding: 0 20px 20px 53px;
            color: var(--text-grey);
            font-size: 13px;
            line-height: 1.6;
            display: none;
            border-top: 1px solid #f0f0f0;
            padding-top: 15px;
        }

        /* Class Active untuk menampilkan jawaban */
        .faq-item.active .faq-answer { display: block; }
        .faq-item.active .faq-question { background: #fffcf0; }

        /* --- FORM & OFFICE SECTION --- */
        .right-column { display: flex; flex-direction: column; gap: 30px; }

        .form-card, .office-card {
            background: var(--white);
            padding: 25px;
            border-radius: var(--border-radius);
            border: 1px solid #eee;
        }

        .form-card h2, .office-card h2 { margin-bottom: 20px; font-size: 18px; color: #333; }

        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 13px; color: var(--text-grey); margin-bottom: 5px; font-weight: 500; }

        .form-control {
            width: 100%; padding: 12px;
            border: 1px solid #ddd; border-radius: 8px;
            font-size: 14px; outline: none;
        }
        .form-control:focus { border-color: var(--primary-color); }
        textarea.form-control { resize: vertical; min-height: 100px; }

        /* Custom File Input */
        input[type="file"]::file-selector-button {
            margin-right: 15px; border: none; background: #eee;
            padding: 8px 12px; border-radius: 6px; cursor: pointer; color: #555;
        }

        /* Office Details */
        .office-details { margin-bottom: 20px; }
        .office-item { display: flex; gap: 15px; margin-bottom: 15px; }
        .office-item i { color: var(--primary-color); font-size: 20px; margin-top: 2px; }
        .office-item div h4 { font-size: 14px; margin-bottom: 3px; font-weight: 700; color: #333; }
        .office-item div p { font-size: 13px; color: var(--text-grey); margin: 0; line-height: 1.4; }

        .map-placeholder {
            width: 100%; height: 180px;
            background: #e0e0e0; border-radius: 8px;
            overflow: hidden;
            display: flex; align-items: center; justify-content: center; color: #888;
        }

        /* Alert Styling */
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #e6fcf5; color: #087f5b; border: 1px solid #b2f2bb; }
        .alert-error { background: #fff5f5; color: #c92a2a; border: 1px solid #ffc9c9; }

        /* Responsif */
        @media (max-width: 900px) {
            .content-split { grid-template-columns: 1fr; }
            .mobile-header { display: flex; }
            .main-content { margin-left: 0; padding: 20px; }
        }
    </style>
@endsection

@section('content')

        <h1 class="page-title">Bantuan & Kontak</h1>

        @if(session('success'))
            <div class="alert alert-success">
                <i class='bx bxs-check-circle'></i> {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <div class="contact-grid">
            <div class="card">
                <div class="contact-icon icon-phone"><i class='bx bx-phone'></i></div>
                <h3>Telepon</h3>
                <h2>{{ $settings['phone'] ?? '-' }}</h2>
                <p>{{ $settings['office_hours'] ?? '08:00 - 20:00' }}</p>
                <a href="tel:{{ $settings['phone'] ?? '' }}" class="btn-action">Hubungi</a>
            </div>

            <div class="card">
                <div class="contact-icon icon-wa"><i class='bx bxl-whatsapp'></i></div>
                <h3>WhatsApp</h3>
                <h2>{{ $settings['whatsapp'] ?? '-' }}</h2>
                <p>Respon cepat 24/7</p>
                <a href="https://wa.me/{{ $settings['whatsapp'] ?? '' }}" target="_blank" class="btn-action">Chat Sekarang</a>
            </div>

            <div class="card">
                <div class="contact-icon icon-mail"><i class='bx bx-envelope'></i></div>
                <h3>Email</h3>
                <h2 style="font-size:16px;">{{ $settings['email'] ?? '-' }}</h2>
                <p>Respon dalam 1x24 jam</p>
                <a href="mailto:{{ $settings['email'] ?? '' }}" class="btn-action">Kirim Email</a>
            </div>
        </div>

        <div class="content-split">

            <div class="faq-section">
                <h2>Pertanyaan Umum (FAQ)</h2>

                <div class="faq-item active">
                    <div class="faq-question">
                        <i class='bx bx-question-mark'></i>
                        Bagaimana cara melakukan booking mobil?
                    </div>
                    <div class="faq-answer">
                        Pilih menu "Cari Mobil", pilih mobil yang diinginkan, pilih paket sewa, lalu klik "Ajukan Booking". Isi detail booking dan tunggu konfirmasi dari admin via WhatsApp.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <i class='bx bx-question-mark'></i>
                        Dokumen apa saja yang diperlukan untuk sewa mobil?
                    </div>
                    <div class="faq-answer">
                        Anda memerlukan KTP asli, SIM A yang masih berlaku, dan akun media sosial aktif. Dokumen akan diverifikasi saat pengambilan mobil (Syarat Lepas Kunci).
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <i class='bx bx-question-mark'></i>
                        Bagaimana cara pembayaran?
                    </div>
                    <div class="faq-answer">
                        Pembayaran dapat dilakukan melalui transfer bank ({{ $settings['namabank'] ?? 'BCA' }}). Setelah booking disetujui, silakan upload bukti pembayaran pada sistem kami.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <i class='bx bx-question-mark'></i>
                        Apakah bisa membatalkan booking?
                    </div>
                    <div class="faq-answer">
                        Ya, pembatalan gratis hingga 24 jam sebelum waktu pengambilan. Pembatalan kurang dari 24 jam dikenakan biaya 50% dari total sewa.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <i class='bx bx-question-mark'></i>
                        Bagaimana jika terjadi kerusakan mobil?
                    </div>
                    <div class="faq-answer">
                        Semua mobil kami diasuransikan. Laporkan segera kepada admin jika terjadi kerusakan. Kerusakan akibat kelalaian penyewa akan dikenakan biaya klaim asuransi (own risk) sesuai ketentuan.
                    </div>
                </div>
            </div>

            <div class="right-column">

                <div class="form-card" id="ticketArea">

                    {{-- LOGIKA 1: Jika ada pesan terakhir DAN statusnya Masih PENDING --}}
                    @if($lastMessage && $lastMessage->status == 'pending')

                        <div style="text-align: center; padding: 30px 10px;">
                            <div style="width: 60px; height: 60px; background: #fff3cd; color: #856404; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 30px; margin: 0 auto 20px;">
                                <i class='bx bx-time-five'></i>
                            </div>
                            <h3 style="margin-bottom: 10px; font-size: 18px;">Tiket Sedang Diproses</h3>
                            <p style="color: #666; font-size: 14px; margin-bottom: 20px;">
                                Halo, pesan Anda dengan subjek <strong>"{{ $lastMessage->subject }}"</strong> sedang ditinjau oleh tim kami. Mohon tunggu balasan admin sebelum mengirim pesan baru.
                            </p>
                            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: left; font-size: 13px; color: #555; border: 1px solid #eee;">
                                <strong>Pesan Anda:</strong><br>
                                {{ $lastMessage->message }}
                                <br><br>
                                <small style="color: #999;">Dikirim: {{ $lastMessage->created_at->format('d M Y, H:i') }}</small>
                            </div>
                        </div>

                    {{-- LOGIKA 2: Jika pesan terakhir SUDAH DIBALAS (Replied) --}}
                    @elseif($lastMessage && $lastMessage->status == 'replied')

                        <div id="replyView">
                            <div style="text-align: center; padding: 20px 0;">
                                <div style="width: 60px; height: 60px; background: #d1e7dd; color: #0f5132; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 30px; margin: 0 auto 20px;">
                                    <i class='bx bx-check'></i>
                                </div>
                                <h3 style="margin-bottom: 10px;">Tiket Telah Dibalas</h3>
                                <p style="color: #666; font-size: 14px;">Admin telah merespon pesan Anda.</p>
                            </div>

                            <div style="border: 1px solid #ddd; border-radius: 12px; overflow: hidden; margin-bottom: 25px;">
                                <div style="background: #f8f9fa; padding: 15px; border-bottom: 1px solid #eee;">
                                    <div style="font-size: 12px; color: #888; margin-bottom: 5px;">Anda menulis:</div>
                                    <p style="font-size: 14px; color: #333; margin: 0;">{{ $lastMessage->message }}</p>
                                </div>
                                <div style="background: #e6fcf5; padding: 20px;">
                                    <div style="display: flex; gap: 10px; margin-bottom: 10px; align-items: center;">
                                        <i class='bx bxs-user-voice' style="font-size: 20px; color: #0ca678;"></i>
                                        <span style="font-weight: 700; font-size: 14px; color: #0ca678;">Balasan Admin ({{ $lastMessage->replier->name ?? 'Staff' }})</span>
                                    </div>
                                    <p style="font-size: 14px; color: #333; line-height: 1.6;">{{ $lastMessage->reply_message }}</p>
                                    <div style="margin-top: 10px; font-size: 11px; color: #0ca678;">
                                        Dibalas pada: {{ \Carbon\Carbon::parse($lastMessage->replied_at)->format('d M Y, H:i') }}
                                    </div>
                                </div>
                            </div>

                            <button onclick="showNewTicketForm()" class="btn-action" style="background: var(--text-dark); color: #fff;">
                                <i class='bx bx-plus'></i> Buat Tiket Baru
                            </button>
                        </div>

                        <div id="newTicketForm" style="display: none;">
                            <h2 style="display: flex; justify-content: space-between; align-items: center;">
                                Kirim Pesan Baru
                                <button onclick="cancelNewTicket()" style="background: none; border: none; font-size: 14px; color: #666; cursor: pointer;">Batal</button>
                            </h2>
                            <form action="{{ route('bantuan.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label>Subjek</label>
                                    <input type="text" name="subject" class="form-control" placeholder="Contoh: Pertanyaan Baru" required>
                                </div>
                                <div class="form-group">
                                    <label>Pesan</label>
                                    <textarea name="message" class="form-control" placeholder="Tulis pesan Anda..." required></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Lampirkan Foto (Opsional)</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                </div>
                                <button type="submit" class="btn-action">Kirim Pesan</button>
                            </form>
                        </div>

                    {{-- LOGIKA 3: Tidak ada pesan (User Baru) --}}
                    @else
                        <h2>Kirim Pesan / Tiket</h2>
                        <form action="{{ route('bantuan.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label>Subjek</label>
                                <input type="text" name="subject" class="form-control" placeholder="Contoh: Konfirmasi Pembayaran" required>
                            </div>
                            <div class="form-group">
                                <label>Pesan</label>
                                <textarea name="message" class="form-control" placeholder="Tulis pesan Anda..." required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Lampirkan Foto (Opsional)</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                            <button type="submit" class="btn-action">Kirim Pesan</button>
                        </form>
                    @endif

                </div>

                <div class="office-card">
                    <h2>Kantor Kami</h2>
                    <div class="office-details">
                        <div class="office-item">
                            <i class='bx bx-map'></i>
                            <div>
                                <h4>Alamat</h4>
                                <p>{{ $settings['address'] ?? 'Alamat belum diatur' }}</p>
                            </div>
                        </div>
                        <div class="office-item">
                            <i class='bx bx-time-five'></i>
                            <div>
                                <h4>Jam Operasional</h4>
                                <p>{{ $settings['office_hours'] ?? '-' }}<br>Support 24/7 via WhatsApp</p>
                            </div>
                        </div>
                    </div>

                    <div class="map-placeholder">
                        @if(!empty($settings['maps_link']))
                            <iframe src="{{ $settings['maps_link'] }}" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        @else
                            <span>Peta belum tersedia</span>
                        @endif
                    </div>

                    <div style="margin-top:15px; text-align:center;">
                        @if(!empty($settings['social_ig']))
                            <a href="{{ $settings['social_ig'] }}" target="_blank" style="margin:0 5px; font-size:24px; color:#E1306C;"><i class='bx bxl-instagram'></i></a>
                        @endif
                        @if(!empty($settings['social_fb']))
                            <a href="{{ $settings['social_fb'] }}" target="_blank" style="margin:0 5px; font-size:24px; color:#1877F2;"><i class='bx bxl-facebook-circle'></i></a>
                        @endif
                         @if(!empty($settings['social_tiktok']))
                            <a href="{{ $settings['social_tiktok'] }}" target="_blank" style="margin:0 5px; font-size:24px; color:#000;"><i class='bx bxl-tiktok'></i></a>
                        @endif
                    </div>

                </div>
            </div>
        </div>
@endsection
@section('script')
<script>
    // Script Sidebar (yang sudah ada)
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
    }

    // Script Accordion (yang sudah ada)
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        question.addEventListener('click', () => {
            item.classList.toggle('active');
        });
    });

    // --- SCRIPT BARU UNTUK TIKET --- //

    // Munculkan Form
    function showNewTicketForm() {
        document.getElementById('replyView').style.display = 'none'; // Sembunyikan balasan
        document.getElementById('newTicketForm').style.display = 'block'; // Munculkan form
        // Opsional: Animasi fade in bisa ditambahkan lewat CSS
    }

    // Batal (Kembali lihat balasan)
    function cancelNewTicket() {
        document.getElementById('newTicketForm').style.display = 'none';
        document.getElementById('replyView').style.display = 'block';
    }
</script>
@endsection
