@extends('dashboard.layouts.index')
@section('title', 'Pesan Masuk')

@section('style')
<style>
    /* === 1. STYLE UTAMA === */
    :root {
        --primary: #FFC400;
        --primary-dark: #e0ac00;
        --text-dark: #333;
        --text-gray: #666;
        --bg-light: #f8f9fa;
        --sidebar-width: 280px;
        --border-color: #eee;
    }

    .main-content {
        flex: 1;
        margin-left: var(--sidebar-width);
        padding: 30px 40px;
        min-height: 100vh;
        background-color: var(--bg-light);
        font-family: 'Poppins', sans-serif;
    }

    /* === 2. STYLE TABLE & CARD === */
    .card-box {
        background: #fff;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        padding: 25px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }

    .table-custom { width: 100%; border-collapse: separate; border-spacing: 0; }
    .table-custom th {
        background: #fff;
        color: var(--text-gray);
        font-weight: 600;
        padding: 15px;
        border-bottom: 2px solid var(--border-color);
        text-align: left;
        font-size: 0.9rem;
    }
    .table-custom td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-dark);
        font-size: 0.95rem;
    }

    /* Badges */
    .badge-status { padding: 6px 12px; border-radius: 30px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .status-pending { background: #fff8e1; color: #b7791f; border: 1px solid #f6e05e; }
    .status-replied { background: #def7ec; color: #03543f; border: 1px solid #84e1bc; }

    /* Buttons */
    .btn-action {
        padding: 8px 16px; border-radius: 8px; font-weight: 600; font-size: 0.85rem;
        border: none; cursor: pointer; transition: 0.2s; display: inline-flex; align-items: center; gap: 8px; text-decoration: none;
    }
    .btn-reply { background: var(--primary); color: #000; box-shadow: 0 2px 5px rgba(255, 196, 0, 0.3); }
    .btn-reply:hover { background: var(--primary-dark); transform: translateY(-2px); }
    .btn-view { background: #e2e8f0; color: #4a5568; }
    .btn-view:hover { background: #cbd5e0; }

    /* === 3. STYLE MODAL === */
    .custom-modal-overlay {
        display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.5); z-index: 9999;
        align-items: center; justify-content: center; overflow-y: auto; padding: 20px;
        backdrop-filter: blur(4px); opacity: 0; transition: opacity 0.3s ease;
    }
    .custom-modal-overlay.show { display: flex; opacity: 1; }

    .custom-modal-box {
        background: #fff; width: 100%; max-width: 750px; border-radius: 16px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.25); transform: scale(0.95); transition: transform 0.3s ease;
        display: flex; flex-direction: column; max-height: 90vh;
    }
    .custom-modal-overlay.show .custom-modal-box { transform: scale(1); }

    .modal-header-custom { padding: 20px 25px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: #fafafa; border-radius: 16px 16px 0 0; }
    .modal-body-custom { padding: 30px; overflow-y: auto; }

    .msg-bubble { background: #f1f5f9; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 25px; color: #334155; line-height: 1.6; }
    .admin-reply-bubble { background: #fffbeb; padding: 20px; border-radius: 12px; border: 1px solid #fcd34d; color: #92400e; margin-top: 20px; }
    .img-attachment { max-width: 100%; height: auto; border-radius: 8px; margin-top: 15px; border: 1px solid #ddd; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    .custom-textarea { width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 15px; font-family: inherit; resize: vertical; min-height: 150px; transition: 0.2s; }
    .custom-textarea:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(255, 196, 0, 0.15); }
    .avatar-circle { width: 45px; height: 45px; background: #e2e8f0; color: #64748b; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.1rem; }

    /* === 4. SEARCH, FILTER & PAGINATION STYLE === */
    .filter-btn { padding: 8px 16px; border: 1px solid transparent; background: transparent; color: var(--text-gray); border-radius: 20px; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: 0.2s; }
    .filter-btn:hover { background: #eee; }
    .filter-btn.active { background: var(--primary); color: #000; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }

    .search-input-group { position: relative; width: 250px; }
    .search-input-group input { width: 100%; padding: 10px 15px 10px 40px; border-radius: 20px; border: 1px solid #ddd; outline: none; transition: 0.2s; }
    .search-input-group input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(255, 196, 0, 0.1); }
    .search-input-group i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #999; }

    /* STYLE PAGINATION JS */
    .pagination-container { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee; }
    .btn-page { padding: 8px 15px; background: #fff; border: 1px solid #ddd; border-radius: 8px; color: #555; cursor: pointer; font-weight: 600; transition: 0.2s; }
    .btn-page:hover:not(:disabled) { background: #f0f0f0; border-color: #ccc; }
    .btn-page:disabled { opacity: 0.5; cursor: not-allowed; background: #f9f9f9; }
    .page-info { font-size: 0.9rem; color: var(--text-gray); font-weight: 500; }
</style>
@endsection

@section('content')

<main class="main-content">

    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--text-dark); margin-bottom: 5px;">Kotak Masuk Bantuan</h1>
            <p style="color: var(--text-gray); margin: 0;">Kelola pesan, pertanyaan, dan keluhan dari pelanggan.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 10px; background-color: #d1fae5; color: #065f46;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card-box">

        {{-- FILTER & SEARCH --}}
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div class="d-flex gap-2">
                <button class="filter-btn active" onclick="filterTable('pending', this)">Menunggu</button>
                <button class="filter-btn" onclick="filterTable('replied', this)">Selesai</button>
                <button class="filter-btn" onclick="filterTable('all', this)">Semua</button>
            </div>

            <div class="search-input-group">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Cari nama atau subjek...">
            </div>
        </div>

        <table class="table table-custom">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="25%">Pengirim</th>
                    <th width="30%">Subjek & Pesan</th>
                    <th width="15%">Waktu</th>
                    <th width="10%">Status</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody id="messageTableBody">
                {{-- Loop Data dari Controller (get) --}}
                @forelse($messages as $index => $msg)
                {{-- Tambahkan class 'search-item' agar bisa dihitung JS --}}
                <tr class="search-item" data-status="{{ $msg->status }}" style="{{ $msg->status == 'pending' ? 'background-color: #fffef2;' : '' }}">
                    {{-- No kita generate via JS nanti agar urut --}}
                    <td class="row-number">{{ $index + 1 }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-circle">
                                {{ substr($msg->user->name ?? '?', 0, 1) }}
                            </div>
                            <div>
                                <div class="text-name" style="font-weight: 600; color: var(--text-dark);">{{ $msg->user->name ?? 'Guest' }}</div>
                                <div style="font-size: 0.8rem; color: var(--text-gray);">{{ $msg->user->email ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="text-subject" style="font-weight: 600; color: var(--text-dark); margin-bottom: 3px;">{{ Str::limit($msg->subject, 35) }}</div>
                        <div style="font-size: 0.85rem; color: var(--text-gray);">{{ Str::limit($msg->message, 50) }}</div>
                    </td>
                    <td style="font-size: 0.9rem; color: var(--text-gray);">
                        {{ $msg->created_at->translatedFormat('d M Y') }}<br>
                        <small>{{ $msg->created_at->format('H:i') }}</small>
                    </td>
                    <td>
                        @if($msg->status == 'pending')
                            <span class="badge-status status-pending">Menunggu</span>
                        @else
                            <span class="badge-status status-replied">Selesai</span>
                        @endif
                    </td>
                    <td>
                        @if($msg->status == 'pending')
                            @can('reply_message.reply')
                            <button type="button" class="btn-action btn-reply" onclick="openModal('modalReply{{ $msg->id }}')">
                                <i class="fas fa-reply"></i> Balas
                            </button>
                            @endcan
                        @else
                            @can('reply_message.index')
                            <button type="button" class="btn-action btn-view" onclick="openModal('modalReply{{ $msg->id }}')">
                                <i class="fas fa-eye"></i> Lihat
                            </button>
                            @endcan
                        @endif
                    </td>
                </tr>

                {{-- MODAL --}}
                <div id="modalReply{{ $msg->id }}" class="custom-modal-overlay">
                    <div class="custom-modal-box">
                        <div class="modal-header-custom">
                            <h5 style="margin:0; font-weight:700; color: var(--text-dark);">
                                @if($msg->status == 'pending') 💬 Balas Pesan @else 📜 Detail Pesan @endif
                            </h5>
                            <button type="button" onclick="closeModal('modalReply{{ $msg->id }}')"
                                style="border:none; background:transparent; font-size:1.5rem; cursor:pointer; color: #999;">&times;</button>
                        </div>
                        <div class="modal-body-custom">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="avatar-circle" style="width: 50px; height: 50px; background: var(--primary); color: #000;">
                                    {{ substr($msg->user->name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <div style="font-weight:bold; font-size: 1.1rem;">{{ $msg->user->name ?? 'Guest' }}</div>
                                    <div style="font-size:0.9rem; color: var(--text-gray);">Topik: <strong>{{ $msg->subject }}</strong></div>
                                </div>
                            </div>
                            <div class="msg-bubble">
                                <p style="margin:0;">{!! nl2br(e($msg->message)) !!}</p>
                                @if($msg->image_path)
                                    <div style="margin-top:15px; font-size:0.85rem; font-weight:bold; color: var(--text-gray);">📎 Lampiran Gambar:</div>
                                    <a href="{{ asset('storage/'.$msg->image_path) }}" target="_blank">
                                        <img src="{{ asset('storage/'.$msg->image_path) }}" class="img-attachment" alt="Bukti Lampiran">
                                    </a>
                                @endif
                            </div>
                            <hr style="border:0; border-top:1px dashed #ddd; margin: 30px 0;">
                            @if($msg->status == 'replied')
                                <div class="admin-reply-bubble">
                                    <div style="font-size:0.9rem; font-weight:bold; color:#b45309; margin-bottom:8px;">
                                        <i class="fas fa-check-circle me-1"></i> Balasan Admin:
                                    </div>
                                    <p style="margin:0;">{!! nl2br(e($msg->reply_message)) !!}</p>
                                    <div style="text-align:right; font-size:0.8rem; color:#b45309; margin-top:10px; opacity: 0.8;">
                                        Dibalas pada: {{ \Carbon\Carbon::parse($msg->replied_at)->format('d F Y, H:i') }}
                                    </div>
                                </div>
                            @else
                                @can('reply_message.reply')
                                <form action="{{ route('reply_message.sendReply', $msg->id) }}" method="POST">
                                    @csrf
                                    <label style="font-weight:600; margin-bottom:10px; display:block; color: var(--text-dark);">Tulis Balasan:</label>
                                    <textarea name="reply_message" class="custom-textarea" placeholder="Halo {{ $msg->user->name }}, terima kasih telah menghubungi kami..." required></textarea>
                                    <div class="d-flex justify-content-end gap-3 mt-4">
                                        <button type="button" class="btn-action" style="background:#f1f5f9; color:#475569;" onclick="closeModal('modalReply{{ $msg->id }}')">Batal</button>
                                        <button type="submit" class="btn-action btn-reply" style="padding: 10px 25px;"><i class="fas fa-paper-plane"></i> Kirim Balasan</button>
                                    </div>
                                </form>
                                @else
                                <div class="alert alert-warning text-center"><small>Anda tidak memiliki izin.</small></div>
                                @endcan
                            @endif
                        </div>
                    </div>
                </div>
                {{-- END MODAL --}}

                @empty
                <tr id="noDataRow">
                    <td colspan="6" class="text-center py-5">
                        <div style="color: var(--text-gray); opacity: 0.6;">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>Belum ada pesan masuk.</p>
                        </div>
                    </td>
                </tr>
                @endforelse

                {{-- Baris "Data Tidak Ditemukan" untuk JS Search --}}
                <tr id="jsNoData" style="display: none;">
                    <td colspan="6" class="text-center py-5 text-muted">
                        Data tidak ditemukan untuk pencarian/filter ini.
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- PAGINATION JS CONTROLS --}}
        <div class="pagination-container" id="paginationControls">
            <div class="page-info">
                Menampilkan <span id="showingStart">0</span> - <span id="showingEnd">0</span> dari <span id="totalItems">0</span> data
            </div>
            <div class="d-flex gap-2">
                <button class="btn-page" id="btnPrev" onclick="changePage(-1)">
                    <i class="fas fa-chevron-left"></i> Sebelumnya
                </button>
                <button class="btn-page" id="btnNext" onclick="changePage(1)">
                    Selanjutnya <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

    </div>
</main>

{{-- SCRIPT: SEARCH, FILTER & PAGINATION --}}
<script>
    // === KONFIGURASI ===
    let currentPage = 1;
    const itemsPerPage = 10;
    let currentFilter = 'pending';
    let filteredRows = []; // Menyimpan baris yang lolos filter/search

    // === 1. FUNGSI FILTER & SEARCH ===
    function filterTable(status, btnElement) {
        currentFilter = status;

        // Update Class Active Tombol
        if(btnElement) {
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            btnElement.classList.add('active');
        }

        applyLogic();
    }

    function searchTable() {
        applyLogic();
    }

    // Fungsi Utama: Menggabungkan Search, Filter, dan Pagination
    function applyLogic() {
        let input = document.getElementById("searchInput").value.toLowerCase();
        let allRows = document.querySelectorAll(".search-item"); // Ambil semua baris asli

        filteredRows = []; // Reset penampung

        // 1. Loop semua baris untuk cek apakah lolos filter & search
        allRows.forEach(row => {
            let name = row.querySelector(".text-name").innerText.toLowerCase();
            let subject = row.querySelector(".text-subject").innerText.toLowerCase();
            let status = row.getAttribute("data-status");

            let statusMatch = (currentFilter === 'all') || (status === currentFilter);
            let textMatch = name.includes(input) || subject.includes(input);

            // Jika lolos, simpan ke array filteredRows, tapi sembunyikan dulu dari HTML
            if (statusMatch && textMatch) {
                filteredRows.push(row);
            }
            row.style.display = "none"; // Sembunyikan semua dulu
        });

        // 2. Reset ke Halaman 1 setiap kali filter/search berubah
        currentPage = 1;

        // 3. Render Halaman
        renderTable();
    }

    // === 2. FUNGSI PAGINATION RENDER ===
    function renderTable() {
        let noDataRow = document.getElementById("jsNoData");

        // Cek jika kosong
        if (filteredRows.length === 0) {
            noDataRow.style.display = ""; // Tampilkan pesan kosong
            document.getElementById("paginationControls").style.display = "none";
            return;
        } else {
            noDataRow.style.display = "none";
            document.getElementById("paginationControls").style.display = "flex";
        }

        // Hitung Index Start & End
        let start = (currentPage - 1) * itemsPerPage;
        let end = start + itemsPerPage;

        // Ambil data yang harus muncul di halaman ini
        let pageItems = filteredRows.slice(start, end);

        // Tampilkan data tersebut
        pageItems.forEach((row, index) => {
            row.style.display = ""; // Munculkan
            // Update Nomor Urut biar rapi (1, 2, 3...) sesuai tampilan
            row.querySelector(".row-number").innerText = start + index + 1;
        });

        // Update Info Text & Button State
        updatePaginationInfo();
    }

    function updatePaginationInfo() {
        let total = filteredRows.length;
        let start = (currentPage - 1) * itemsPerPage + 1;
        let end = Math.min(start + itemsPerPage - 1, total);
        let totalPages = Math.ceil(total / itemsPerPage);

        document.getElementById("showingStart").innerText = start;
        document.getElementById("showingEnd").innerText = end;
        document.getElementById("totalItems").innerText = total;

        // Disable tombol jika mentok
        document.getElementById("btnPrev").disabled = (currentPage === 1);
        document.getElementById("btnNext").disabled = (currentPage === totalPages);
    }

    function changePage(direction) {
        currentPage += direction;

        // Sembunyikan yang lama
        filteredRows.forEach(row => row.style.display = "none");

        // Render halaman baru
        renderTable();
    }

    // === 3. INISIALISASI ===
    document.addEventListener("DOMContentLoaded", function() {
        applyLogic(); // Jalankan saat load (otomatis filter pending)
    });

    // === 4. MODAL SCRIPT ===
    function openModal(id) {
        document.getElementById(id).classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
        document.body.style.overflow = 'auto';
    }
    window.onclick = function(e) {
        if(e.target.classList.contains('custom-modal-overlay')) {
            e.target.classList.remove('show');
            document.body.style.overflow = 'auto';
        }
    }
</script>
@endsection
