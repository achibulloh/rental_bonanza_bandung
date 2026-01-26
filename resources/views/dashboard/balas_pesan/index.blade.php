@extends('dashboard.layouts.index')
@section('title', 'Pesan Masuk')

@section('style')
<style>
    /* === 1. STYLE UTAMA === */
    :root {
        --primary: #FFC400;
        --primary-dark: #e0ac00;
        --text-dark: #111;
        --text-gray: #666;
        --bg-light: #f8f9fa;
        --border-color: #eee;
        --radius: 12px;
    }

    /* ⚠️ BAGIAN MARGIN NEGATIF DIHAPUS
       (Layout utama sekarang sudah menangani padding & margin otomatis)
    */

    /* === 2. HEADER SECTION === */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        width: 100%;
        flex-wrap: wrap; /* Agar aman di layar kecil */
        gap: 15px;
    }

    .header-title-wrapper {
        flex-shrink: 0;
    }

    .page-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
        line-height: 1.2;
    }

    .page-subtitle {
        color: var(--text-gray);
        margin: 5px 0 0 0;
        font-size: 0.9rem;
    }

    /* Wrapper Kanan (Search + Filter) */
    .header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Search Bar Style */
    .search-wrap {
        position: relative;
        width: 250px;
    }
    .search-input {
        width: 100%;
        padding: 10px 40px 10px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        outline: none;
        font-size: 0.9rem;
        transition: 0.3s;
        background: #fff;
        height: 42px;
    }
    .search-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(255, 196, 0, 0.1); }
    .search-icon { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #999; pointer-events: none; }

    /* Filter Buttons Style */
    .filter-buttons {
        display: flex;
        gap: 5px;
        background: #f8f9fa;
        padding: 4px;
        border-radius: 8px;
        border: 1px solid #eee;
        height: 42px;
        align-items: center;
    }
    .btn-filter {
        border: none;
        background: transparent;
        padding: 0 16px;
        height: 100%;
        border-radius: 6px;
        font-weight: 600;
        color: #888;
        cursor: pointer;
        transition: 0.3s;
        white-space: nowrap;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
    }
    .btn-filter.active { background: var(--primary); color: #000; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .btn-filter:hover:not(.active) { color: #333; background: #e9ecef; }

    /* === 3. TABLE & CARD === */
    .card-box {
        background: #fff;
        border-radius: var(--radius);
        border: 1px solid var(--border-color);
        padding: 0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        overflow: hidden;
    }

    .table-custom { width: 100%; border-collapse: separate; border-spacing: 0; }
    .table-custom th {
        background: #fafafa;
        color: var(--text-gray);
        font-weight: 700;
        padding: 18px 25px;
        border-bottom: 1px solid var(--border-color);
        text-align: left;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }
    .table-custom td {
        padding: 20px 25px;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-dark);
        font-size: 0.95rem;
    }
    .table-custom tr:last-child td { border-bottom: none; }
    .table-custom tr:hover td { background-color: #fcfcfc; }

    /* === 4. STYLE EMPTY STATE (TENGAH) === */
    .empty-state-row td {
        text-align: center;
        padding: 80px 20px !important;
    }
    .empty-state-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
    }
    .empty-state-content i {
        font-size: 3.5rem;
        margin-bottom: 15px;
        color: #e5e7eb;
    }
    .empty-state-content p {
        font-size: 1.1rem;
        font-weight: 600;
        color: #6b7280;
    }

    /* Badges & Avatars */
    .badge-status { padding: 6px 12px; border-radius: 30px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block; white-space: nowrap; }
    .status-pending { background: #fff8e1; color: #b7791f; border: 1px solid #f6e05e; }
    .status-replied { background: #def7ec; color: #03543f; border: 1px solid #84e1bc; }

    .avatar-circle { width: 40px; height: 40px; background: #e2e8f0; color: #64748b; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1rem; flex-shrink: 0; }

    /* Buttons Action */
    .btn-action { padding: 8px 14px; border-radius: 8px; font-weight: 600; font-size: 0.85rem; border: none; cursor: pointer; transition: 0.2s; display: inline-flex; align-items: center; gap: 6px; white-space: nowrap; }
    .btn-reply { background: var(--primary); color: #000; }
    .btn-reply:hover { background: var(--primary-dark); transform: translateY(-1px); }
    .btn-view { background: #fff; border: 1px solid #ddd; color: #555; }
    .btn-view:hover { background: #f8f9fa; border-color: #bbb; }

    /* === 5. PAGINATION === */
    .pagination-container { display: flex; justify-content: space-between; align-items: center; padding: 20px 25px; border-top: 1px solid var(--border-color); background: #fff; flex-wrap: wrap; gap: 10px; }
    .btn-page { padding: 6px 12px; background: #fff; border: 1px solid #ddd; border-radius: 6px; color: #555; cursor: pointer; font-weight: 600; font-size: 0.85rem; transition: 0.2s; }
    .btn-page:hover:not(:disabled) { background: #f0f0f0; }
    .btn-page:disabled { opacity: 0.5; cursor: default; }
    .page-info { font-size: 0.85rem; color: var(--text-gray); }

    /* === 6. MODAL STYLE === */
    .custom-modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px); opacity: 0; transition: opacity 0.3s ease; }
    .custom-modal-overlay.show { display: flex; opacity: 1; }
    .custom-modal-box { background: #fff; width: 90%; max-width: 700px; border-radius: 16px; box-shadow: 0 25px 50px rgba(0,0,0,0.25); transform: scale(0.95); transition: transform 0.3s ease; display: flex; flex-direction: column; max-height: 90vh; }
    .custom-modal-overlay.show .custom-modal-box { transform: scale(1); }
    .modal-header-custom { padding: 20px 25px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: #fafafa; border-radius: 16px 16px 0 0; }
    .modal-body-custom { padding: 30px; overflow-y: auto; }

    .msg-bubble { background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 20px; color: #334155; line-height: 1.6; }
    .admin-reply-bubble { background: #fffbeb; padding: 20px; border-radius: 12px; border: 1px solid #fcd34d; color: #92400e; margin-top: 20px; }
    .custom-textarea { width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 15px; font-family: inherit; resize: vertical; min-height: 120px; transition: 0.2s; outline: none; }
    .custom-textarea:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(255, 196, 0, 0.15); }
    .img-attachment { max-width: 200px; border-radius: 8px; margin-top: 10px; border: 1px solid #ddd; cursor: pointer; transition: 0.2s; }
    .img-attachment:hover { transform: scale(1.02); }

    /* MOBILE RESPONSIVE */
    @media (max-width: 992px) {
        /* Hapus override .main-content disini karena sudah dihandle layout */
        .page-header { flex-direction: column; align-items: flex-start; gap: 15px; }
        .header-actions { width: 100%; justify-content: space-between; flex-wrap: wrap; }
        .search-wrap { width: 100%; flex: 1; min-width: 200px; }
        .filter-buttons { overflow-x: auto; max-width: 100%; }

        /* Table responsive adjustments */
        .table-custom th, .table-custom td { padding: 15px; }
    }
</style>
@endsection

@section('content')
        {{-- HEADER SECTION --}}
        <div class="page-header">

            <div class="header-title-wrapper">
                <h1 class="page-title">Pesan Masuk</h1>
                <p class="page-subtitle">Kelola pertanyaan dan keluhan pelanggan.</p>
            </div>

            <div class="header-actions">
                <div class="search-wrap">
                    <input type="text" id="searchInput" class="search-input" onkeyup="searchTable()" placeholder="Cari nama, subjek...">
                    <i class="fas fa-search search-icon"></i>
                </div>

                <div class="filter-buttons">
                    <button class="btn-filter active" onclick="filterTable('pending', this)">Menunggu</button>
                    <button class="btn-filter" onclick="filterTable('replied', this)">Selesai</button>
                    <button class="btn-filter" onclick="filterTable('all', this)">Semua</button>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 10px; background-color: #d1fae5; color: #065f46; padding: 15px;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        {{-- TABLE CARD --}}
        <div class="card-box">
            <div style="overflow-x: auto;">
                <table class="table-custom">
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
                        @forelse($messages as $index => $msg)
                        <tr class="search-item" data-status="{{ $msg->status }}" style="{{ $msg->status == 'pending' ? 'background-color: #fffff0;' : '' }}">
                            <td class="row-number">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-circle">
                                        {{ substr($msg->user->name ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-name" style="font-weight: 700; color: var(--text-dark);">{{ $msg->user->name ?? 'Guest' }}</div>
                                        <div style="font-size: 0.8rem; color: var(--text-gray);">{{ $msg->user->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-subject" style="font-weight: 600; color: var(--text-dark); margin-bottom: 3px;">{{ Str::limit($msg->subject, 30) }}</div>
                                <div style="font-size: 0.85rem; color: #888;">{{ Str::limit($msg->message, 45) }}</div>
                            </td>
                            <td style="font-size: 0.85rem; color: var(--text-gray);">
                                <div>{{ $msg->created_at->translatedFormat('d M Y') }}</div>
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
                                        <i class="fas fa-eye"></i> Detail
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
                                            <div style="margin-top:15px; font-size:0.85rem; font-weight:bold; color: var(--text-gray);">📎 Lampiran:</div>
                                            <a href="{{ asset('storage/'.$msg->image_path) }}" target="_blank">
                                                <img src="{{ asset('storage/'.$msg->image_path) }}" class="img-attachment" alt="Lampiran">
                                            </a>
                                        @endif
                                    </div>

                                    @if($msg->status == 'replied')
                                        <div class="admin-reply-bubble">
                                            <div style="font-size:0.9rem; font-weight:bold; color:#b45309; margin-bottom:8px;">
                                                <i class="fas fa-check-circle me-1"></i> Balasan Admin:
                                            </div>
                                            <p style="margin:0;">{!! nl2br(e($msg->reply_message)) !!}</p>
                                            <div style="text-align:right; font-size:0.8rem; color:#b45309; margin-top:10px; opacity: 0.8;">
                                                {{ \Carbon\Carbon::parse($msg->replied_at)->format('d F Y, H:i') }}
                                            </div>
                                        </div>
                                    @else
                                        @can('reply_message.reply')
                                        <form action="{{ route('reply_message.sendReply', $msg->id) }}" method="POST">
                                            @csrf
                                            <label style="font-weight:600; margin-bottom:10px; display:block; color: var(--text-dark);">Tulis Balasan:</label>
                                            <textarea name="reply_message" class="custom-textarea" placeholder="Tulis balasan Anda di sini..." required></textarea>
                                            <div class="d-flex justify-content-end gap-3 mt-4">
                                                <button type="button" class="btn-action" style="background:#f1f5f9; color:#475569;" onclick="closeModal('modalReply{{ $msg->id }}')">Batal</button>
                                                <button type="submit" class="btn-action btn-reply" style="padding: 10px 25px;"><i class="fas fa-paper-plane"></i> Kirim</button>
                                            </div>
                                        </form>
                                        @endcan
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        {{-- EMPTY STATE PHP (Database Kosong) --}}
                        <tr id="noDataRow" class="empty-state-row">
                            <td colspan="6">
                                <div class="empty-state-content">
                                    <i class="fas fa-inbox"></i>
                                    <p>Tidak ada pesan masuk.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse

                        {{-- Row Not Found JS (Pencarian/Filter Kosong) --}}
                        <tr id="jsNoData" class="empty-state-row" style="display: none;">
                            <td colspan="6">
                                <div class="empty-state-content">
                                    <i class="fas fa-search"></i>
                                    <p>Data tidak ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="pagination-container" id="paginationControls">
                <div class="page-info">
                    Menampilkan <span id="showingStart">0</span> - <span id="showingEnd">0</span> dari <span id="totalItems">0</span>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn-page" id="btnPrev" onclick="changePage(-1)"><i class="fas fa-chevron-left"></i> Prev</button>
                    <button class="btn-page" id="btnNext" onclick="changePage(1)">Next <i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>

{{-- SCRIPT JAVASCRIPT --}}
<script>
    let currentPage = 1;
    const itemsPerPage = 10;
    let currentFilter = 'pending';
    let filteredRows = [];

    function filterTable(status, btnElement) {
        currentFilter = status;
        if(btnElement) {
            document.querySelectorAll('.btn-filter').forEach(btn => btn.classList.remove('active'));
            btnElement.classList.add('active');
        }
        applyLogic();
    }

    function searchTable() {
        applyLogic();
    }

    function applyLogic() {
        let input = document.getElementById("searchInput").value.toLowerCase();
        let allRows = document.querySelectorAll(".search-item");

        let noDataRow = document.getElementById("noDataRow");
        if(noDataRow && allRows.length === 0) {
            document.getElementById("jsNoData").style.display = 'none';
            document.getElementById("paginationControls").style.display = 'none';
            return;
        }

        filteredRows = [];

        allRows.forEach(row => {
            let name = row.querySelector(".text-name").innerText.toLowerCase();
            let subject = row.querySelector(".text-subject").innerText.toLowerCase();
            let status = row.getAttribute("data-status");

            let statusMatch = (currentFilter === 'all') || (status === currentFilter);
            let textMatch = name.includes(input) || subject.includes(input);

            if (statusMatch && textMatch) {
                filteredRows.push(row);
            }
            row.style.display = "none";
        });

        currentPage = 1;
        renderTable();
    }

    function renderTable() {
        let jsNoData = document.getElementById("jsNoData");
        let noDataRow = document.getElementById("noDataRow");

        if (filteredRows.length === 0) {
            if(!noDataRow) {
                jsNoData.style.display = "";
            }
            document.getElementById("paginationControls").style.display = 'none';
            return;
        } else {
            jsNoData.style.display = "none";
            document.getElementById("paginationControls").style.display = "flex";
        }

        let start = (currentPage - 1) * itemsPerPage;
        let end = start + itemsPerPage;
        let pageItems = filteredRows.slice(start, end);

        pageItems.forEach((row, index) => {
            row.style.display = "";
            row.querySelector(".row-number").innerText = start + index + 1;
        });

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

        document.getElementById("btnPrev").disabled = (currentPage === 1);
        document.getElementById("btnNext").disabled = (currentPage === totalPages);
    }

    function changePage(direction) {
        currentPage += direction;
        filteredRows.forEach(row => row.style.display = "none");
        renderTable();
    }

    // Modal Helpers
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

    document.addEventListener("DOMContentLoaded", function() {
        applyLogic();
    });
</script>
@endsection
