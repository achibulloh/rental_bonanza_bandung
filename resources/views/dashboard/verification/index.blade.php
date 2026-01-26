@extends('dashboard.layouts.index')
@section('title', 'Verifikasi Dokumen')

@section('style')
<style>
    :root {
        --primary: #FFC400;
        --primary-dark: #e0ac00;
        --text-dark: #212529;
        --bg-light: #f4f6f9;
        --card-radius: 16px;
    }

    body { background: var(--bg-light); font-family: 'Poppins', sans-serif; }

    /* Layout */
    .page-wrapper { width: 100%; padding: 30px; min-height: 100vh; }

    /* Header */
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 20px; }
    .page-title h1 { font-size: 1.8rem; font-weight: 700; color: var(--text-dark); margin: 0; }
    .page-subtitle { color: #666; margin: 5px 0 0; font-size: 0.9rem; }

    /* Header Actions */
    .header-actions { display: flex; align-items: center; gap: 15px; flex-wrap: wrap; }

    /* Filter Buttons */
    .filter-group { background: #fff; padding: 5px; border-radius: 50px; display: flex; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
    .btn-filter {
        border: none; background: transparent; padding: 10px 25px; border-radius: 40px;
        font-weight: 600; color: #888; cursor: pointer; transition: 0.2s; font-size: 0.9rem;
    }
    .btn-filter.active { background: var(--primary); color: #000; box-shadow: 0 4px 10px rgba(0,0,0,0.15); }
    .btn-filter:hover:not(.active) { color: #333; }

    /* Search Bar */
    .search-box { position: relative; width: 250px; }
    .search-input { width: 100%; padding: 10px 40px 10px 20px; border: 1px solid #ddd; border-radius: 50px; outline: none; background: #fff; transition: 0.3s; height: 42px;}
    .search-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(255,196,0,0.1); }
    .search-icon { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #aaa; }

    /* --- CARD STYLE --- */
    .doc-grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 25px;
    }

    .doc-card {
        background: #fff; border-radius: var(--card-radius);
        box-shadow: 0 5px 20px rgba(0,0,0,0.03); border: 1px solid #eee;
        transition: transform 0.2s, box-shadow 0.2s; overflow: hidden;
        display: flex; flex-direction: column;
    }
    .doc-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.08); border-color: var(--primary); }

    /* Status Bar Kiri */
    .doc-card[data-status="pending"] { border-top: 4px solid #ffc107; }
    .doc-card[data-status="rejected"] { border-top: 4px solid #dc3545; }

    .card-body { padding: 20px; flex: 1; }

    /* User Info */
    .user-row { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; }
    .avatar { width: 50px; height: 50px; background: #f0f2f5; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #555; font-size: 1.2rem; flex-shrink: 0; }
    .user-details h4 { margin: 0; font-size: 1.05rem; font-weight: 700; color: var(--text-dark); }
    .user-details p { margin: 0; font-size: 0.85rem; color: #888; }

    /* Doc Preview Mini */
    .doc-preview-mini {
        background: #fafafa; border: 1px dashed #ddd; border-radius: 10px;
        padding: 10px; display: flex; align-items: center; gap: 15px;
        cursor: pointer; transition: 0.2s;
    }
    .doc-preview-mini:hover { background: #fff; border-color: var(--primary); }
    .doc-thumb { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; background: #ddd; }
    .doc-meta label { font-size: 0.75rem; color: #999; display: block; margin-bottom: 2px; text-transform: uppercase; letter-spacing: 0.5px; }
    .doc-meta span { font-weight: 600; color: #333; font-size: 0.95rem; }

    .card-footer {
        padding: 15px 20px; border-top: 1px solid #f8f9fa; background: #fff;
        display: flex; justify-content: space-between; align-items: center;
    }
    .timestamp { font-size: 0.75rem; color: #aaa; }
    .btn-review {
        background: var(--primary); color: #fff; border: none; padding: 8px 20px;
        border-radius: 50px; font-size: 0.85rem; font-weight: 600; cursor: pointer;
        display: inline-flex; align-items: center; gap: 8px; transition: 0.2s;
    }
    .btn-review:hover { background: var(--primary-dark); color: #000; }

    /* --- MODAL SPLIT VIEW --- */
    .modal-overlay {
        display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.85); z-index: 9999; align-items: center; justify-content: center;
        backdrop-filter: blur(5px);
    }
    .modal-overlay.show { display: flex; animation: fadeIn 0.3s; }

    .modal-box {
        background: #fff; width: 95%; max-width: 1100px; height: 85vh;
        border-radius: 16px; overflow: hidden; display: flex;
        box-shadow: 0 25px 50px rgba(0,0,0,0.5);
    }

    /* Kiri: Gambar (Gelap) */
    .modal-left {
        flex: 2; background: #1a1a1a; display: flex; align-items: center; justify-content: center;
        position: relative; overflow: hidden; padding: 20px;
    }
    .modal-left img { max-width: 100%; max-height: 100%; object-fit: contain; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }

    /* Kanan: Info & Aksi (Putih) */
    .modal-right {
        flex: 1; background: #fff; padding: 30px; display: flex; flex-direction: column;
        border-left: 1px solid #eee; overflow-y: auto; position: relative;
    }

    .modal-close {
        position: absolute; top: 15px; right: 20px; background: #f1f3f5; border: none;
        width: 35px; height: 35px; border-radius: 50%; font-size: 1.2rem; cursor: pointer;
        display: flex; align-items: center; justify-content: center; z-index: 10;
    }
    .modal-close:hover { background: #e9ecef; }

    .detail-group { margin-bottom: 20px; }
    .detail-group label { display: block; font-size: 0.8rem; color: #888; text-transform: uppercase; margin-bottom: 5px; }
    .detail-group p { font-size: 1rem; font-weight: 600; color: #333; margin: 0; }

    .action-buttons { margin-top: auto; display: grid; gap: 10px; }
    .btn-approve { width: 100%; padding: 12px; border: none; border-radius: 8px; background: var(--primary); color: #fff; font-weight: 600; cursor: pointer; font-size: 1rem; transition: 0.2s; }
    .btn-approve:hover { background: var(--primary-dark); transform: translateY(-2px); }
    .btn-reject { width: 100%; padding: 12px; border: 1px solid #dc3545; border-radius: 8px; background: #fff; color: #dc3545; font-weight: 600; cursor: pointer; font-size: 1rem; transition: 0.2s; }
    .btn-reject:hover { background: #fff5f5; }

    /* Empty State */
    .empty-state { text-align: center; padding: 60px; grid-column: 1 / -1; }
    .empty-state i { font-size: 4rem; color: #eee; margin-bottom: 15px; }

    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

    /* Responsive */
    @media (max-width: 992px) {
        .modal-box { flex-direction: column; height: 95vh; }
        .modal-left { flex: 1; min-height: 40%; }
        .modal-right { flex: 1; }
        .page-header { flex-direction: column; align-items: flex-start; }
        .header-actions { width: 100%; justify-content: space-between; }
        .filter-group { width: 100%; justify-content: space-between; }
        .btn-filter { flex: 1; text-align: center; padding: 10px; }
    }
</style>
@endsection

@section('content')
<div class="page-wrapper">

    <div class="page-header">
        <div class="header-title-wrapper">
            <div class="page-title">
                <h1>Verifikasi Dokumen</h1>
                <p class="page-subtitle">Tinjau dokumen identitas (KTP/SIM) pengguna.</p>
            </div>
        </div>

        <div class="header-actions">
            <div class="filter-group">
                <button class="btn-filter active" onclick="filterDocs('pending', this)">
                    Menunggu <span class="badge bg-warning text-dark rounded-pill ms-1" style="font-size:0.7rem;">{{ $documents->where('status', 'pending')->count() }}</span>
                </button>
                <button class="btn-filter" onclick="filterDocs('rejected', this)">Ditolak</button>
            </div>

            <div class="search-box">
                <input type="text" id="searchInput" class="search-input" onkeyup="searchDocs()" placeholder="Cari Nama User...">
                <i class="fas fa-search search-icon"></i>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4" style="padding: 15px; background: #d1fae5; color: #065f46; border-radius: 12px;">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
    @endif

    <div class="doc-grid-container" id="docList">
        @foreach($documents as $doc)
        <div class="doc-card item-doc" data-status="{{ $doc->status }}">

            <div class="card-body">
                <div class="user-row">
                    <div class="avatar">{{ substr($doc->user->name ?? 'U', 0, 1) }}</div>
                    <div class="user-details">
                        <h4>{{ $doc->user->name ?? 'User Tidak Dikenal' }}</h4>
                        <p>{{ $doc->user->email ?? '-' }}</p>
                    </div>
                </div>

                <div class="doc-preview-mini" onclick="openReviewModal('modal{{ $doc->id }}')">
                    @if($doc->file_path)
                        <img src="{{ asset('storage/' . $doc->file_path) }}" class="doc-thumb" alt="Thumb">
                    @else
                        <div class="doc-thumb" style="display:flex;align-items:center;justify-content:center;"><i class="fas fa-image text-muted"></i></div>
                    @endif

                    <div class="doc-meta">
                        <label>Tipe Dokumen</label>
                        <span>{{ str_replace('_', ' ', $doc->document_type) }}</span>
                    </div>

                    <div style="margin-left: auto;">
                        @if($doc->status == 'pending')
                            <span class="badge bg-warning text-dark" style="padding: 5px 10px; border-radius: 20px; font-size: 0.7rem;">Menunggu</span>
                        @else
                            <span class="badge bg-danger" style="padding: 5px 10px; border-radius: 20px; font-size: 0.7rem;">Ditolak</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <span class="timestamp"><i class="far fa-clock"></i> {{ $doc->updated_at->diffForHumans() }}</span>
                <button class="btn-review" onclick="openReviewModal('modal{{ $doc->id }}')">
                    Review <i class="fas fa-arrow-right"></i>
                </button>
            </div>

        </div>

        {{-- MODAL REVIEW (SPLIT VIEW) --}}
        <div id="modal{{ $doc->id }}" class="modal-overlay">
            <div class="modal-box">

                <div class="modal-left">
                    @if($doc->file_path)
                        <img src="{{ asset('storage/' . $doc->file_path) }}" alt="Preview Dokumen">
                    @else
                        <p style="color:white;">File tidak ditemukan.</p>
                    @endif
                </div>

                <div class="modal-right">
                    <button class="modal-close" onclick="closeReviewModal('modal{{ $doc->id }}')">&times;</button>

                    <h3 style="margin-top:0; margin-bottom:25px; font-weight:800;">Review Dokumen</h3>

                    <div class="detail-group">
                        <label>Nama Pengguna</label>
                        <p>{{ $doc->user->name ?? '-' }}</p>
                    </div>
                    <div class="detail-group">
                        <label>Jenis Dokumen</label>
                        <p>{{ str_replace('_', ' ', $doc->document_type) }}</p>
                    </div>
                    <div class="detail-group">
                        <label>Status Saat Ini</label>
                        @if($doc->status == 'pending')
                            <span style="color:#b45309; font-weight:700;">MENUNGGU VERIFIKASI</span>
                        @else
                            <span style="color:#dc3545; font-weight:700;">DITOLAK</span>
                            <p style="font-size:0.85rem; color:#dc3545; margin-top:5px;">Reason: {{ $doc->rejection_reason }}</p>
                        @endif
                    </div>

                    <div class="action-buttons">
                        @if($doc->status == 'pending')
                            <form action="{{ route('verification.approve', $doc->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-approve">
                                    <i class="fas fa-check-circle"></i> Setujui Dokumen
                                </button>
                            </form>

                            <button class="btn-reject" onclick="document.getElementById('rejectArea{{ $doc->id }}').style.display='block'; this.style.display='none'">
                                <i class="fas fa-times-circle"></i> Tolak Dokumen
                            </button>

                            <div id="rejectArea{{ $doc->id }}" style="display:none; margin-top:10px;">
                                <form action="{{ route('verification.reject', $doc->id) }}" method="POST">
                                    @csrf
                                    <label style="font-size:0.85rem; font-weight:600; display:block; margin-bottom:5px;">Alasan Penolakan:</label>
                                    <textarea name="rejection_reason" style="width:100%; border:1px solid #ddd; padding:10px; border-radius:8px; outline:none;" rows="3" placeholder="Contoh: Foto buram / Data tidak sesuai..." required></textarea>
                                    <button type="submit" class="btn-reject" style="background:#dc3545; color:#fff; margin-top:10px;">Kirim Penolakan</button>
                                </form>
                            </div>
                        @else
                            <div style="padding:15px; background:#f8d7da; color:#721c24; border-radius:8px; text-align:center; font-size:0.9rem;">
                                Dokumen ini sudah ditolak. User harus mengupload ulang.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- EMPTY STATE --}}
    <div id="emptyState" class="empty-state" style="{{ $documents->isEmpty() ? '' : 'display:none;' }}">
        <i class="fas fa-clipboard-check"></i>
        <h3>Semua Beres!</h3>
        <p>Tidak ada dokumen pending atau rejected saat ini.</p>
    </div>

</div>
@endsection

@section('script')
<script>
    // Search Function
    function searchDocs() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const activeBtn = document.querySelector('.btn-filter.active');
        const currentFilter = activeBtn.innerText.includes('Menunggu') ? 'pending' : 'rejected';

        const nodes = document.getElementsByClassName('item-doc');
        let visibleCount = 0;

        for (i = 0; i < nodes.length; i++) {
            let status = nodes[i].getAttribute('data-status');
            let text = nodes[i].innerText.toLowerCase();

            if (status === currentFilter && text.includes(filter)) {
                nodes[i].style.display = "flex";
                visibleCount++;
            } else {
                nodes[i].style.display = "none";
            }
        }

        const emptyState = document.getElementById('emptyState');
        if(visibleCount === 0) {
            emptyState.style.display = 'block';
            emptyState.innerHTML = '<i class="fas fa-search" style="font-size:4rem;color:#eee;margin-bottom:15px;"></i><h3 style="color:#555;">Tidak Ditemukan</h3>';
        } else {
            emptyState.style.display = 'none';
        }
    }

    // Filter Tabs Function
    function filterDocs(status, btn) {
        document.querySelectorAll('.btn-filter').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const nodes = document.getElementsByClassName('item-doc');
        let visibleCount = 0;

        for (i = 0; i < nodes.length; i++) {
            if (nodes[i].getAttribute('data-status') === status) {
                nodes[i].style.display = "flex";
                visibleCount++;
            } else {
                nodes[i].style.display = "none";
            }
        }

        const emptyState = document.getElementById('emptyState');
        if(visibleCount === 0) {
            emptyState.style.display = 'block';
            emptyState.innerHTML = '<i class="fas fa-clipboard-check" style="font-size:4rem;color:#eee;margin-bottom:15px;"></i><h3 style="color:#555;">Tidak Ada Data</h3><p style="color:#888;">List '+status+' kosong.</p>';
        } else {
            emptyState.style.display = 'none';
        }

        document.getElementById('searchInput').value = '';
    }

    // Modal Functions
    function openReviewModal(id) {
        document.getElementById(id).classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeReviewModal(id) {
        document.getElementById(id).classList.remove('show');
        document.body.style.overflow = 'auto';

        // Reset reject form visibility
        setTimeout(() => {
            const rejectArea = document.querySelector(`#${id} [id^='rejectArea']`);
            const rejectBtn = document.querySelector(`#${id} .btn-reject`);
            if(rejectArea) rejectArea.style.display = 'none';
            if(rejectBtn) rejectBtn.style.display = 'block';
        }, 300);
    }

    // Close on click overlay
    window.onclick = function(e) {
        if(e.target.classList.contains('modal-overlay')) {
            e.target.classList.remove('show');
            document.body.style.overflow = 'auto';
        }
    }

    // Init First Filter
    document.addEventListener('DOMContentLoaded', function() {
        const firstTab = document.querySelector('.btn-filter');
        if(firstTab) {
            filterDocs('pending', firstTab);
        }
    });
</script>
@endsection
