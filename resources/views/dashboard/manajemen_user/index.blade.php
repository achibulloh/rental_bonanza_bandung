@extends('dashboard.layouts.index')
@section('title', 'Manajemen Users')

@section('style')
    <style>
        :root {
            --primary: #FFC400;
            --primary-dark: #e0ac00;
            --text-dark: #333;
            --text-gray: #666;
            --bg-light: #f8f9fa;
            --sidebar-width: 280px;
            --border-color: #eee;
        }

        /* --- LAYOUT UTAMA (FULL SCREEN) --- */
        html, body { height: 100%; overflow: hidden; }

        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 30px 40px;
            background-color: var(--bg-light);
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
        }

        /* HEADER & BUTTONS */
        .page-header {
            flex-shrink: 0; margin-bottom: 20px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .page-title h1 { font-size: 24px; font-weight: 700; margin: 0; color: var(--text-dark); }
        .page-title p { font-size: 13px; color: var(--text-gray); margin-top: 5px; }

        .btn-add {
            background: var(--primary); color: #000; border: none; padding: 10px 20px;
            border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 13px;
            display: flex; align-items: center; gap: 8px; transition: 0.2s; text-decoration: none;
        }
        .btn-add:hover { background: var(--primary-dark); transform: translateY(-2px); }

        .btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 6px; border:none; cursor: pointer; margin-right: 5px;}
        .btn-edit { background: #E7F5FF; color: #1C7ED6; }
        .btn-delete { background: #FFE3E3; color: #E03131; }
        .btn-view { background: #E6FCF5; color: #0CA678; }

        /* --- CARD BOX --- */
        .card-box {
            background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #eee;
            flex: 1; display: flex; flex-direction: column; overflow: hidden; min-height: 0;
        }

        .card-header-title {
            flex-shrink: 0; padding: 15px 25px; background: #fff; border-bottom: 1px solid #f0f0f0;
            display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;
        }
        .header-left { font-weight: 600; font-size: 15px; color: var(--text-dark); display: flex; align-items: center; gap: 8px; }
        .header-actions { display: flex; gap: 10px; align-items: center; flex: 1; justify-content: flex-end; }

        /* SEARCH INPUT */
        .search-sm { position: relative; max-width: 250px; width: 100%; }
        .search-sm input {
            width: 100%; padding: 8px 12px 8px 35px; border-radius: 8px; border: 1px solid #ddd;
            font-size: 13px; outline: none; background: #f9f9f9;
        }
        .search-sm input:focus { background: #fff; border-color: var(--primary); }
        .search-sm i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #aaa; font-size: 12px; }

        /* --- TABLE SYSTEM --- */
        .table-responsive { flex: 1; overflow: auto; width: 100%; position: relative; }
        .custom-table { width: 100%; border-collapse: collapse; white-space: nowrap; }
        .custom-table thead th {
            position: sticky; top: 0; background: #fcfcfc; z-index: 10;
            text-align: left; padding: 15px 25px; font-size: 12px; color: #888; text-transform: uppercase; font-weight: 600;
            border-bottom: 1px solid #eee; height: 50px; box-shadow: 0 2px 2px -1px rgba(0,0,0,0.1);
        }
        .custom-table td { padding: 15px 25px; border-bottom: 1px solid #eee; font-size: 13px; color: #333; height: 70px; vertical-align: middle; }
        .custom-table tr:hover { background-color: #fafafa; }

        /* USER INFO */
        .user-profile { display: flex; align-items: center; gap: 8px; }
        .profile-pic { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; background: #eee; flex-shrink: 0; }
        .user-info { display: flex; flex-direction: column; line-height: 1.2; }
        .user-name { font-weight: 600; font-size: 13px; color: #333; }
        .user-email { font-size: 11px; color: #888; margin-top: 1px; }

        /* PAGINATION */
        .card-footer-pagination {
            flex-shrink: 0; padding: 15px 25px; border-top: 1px solid #f0f0f0; background: #fff;
            display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #666;
        }
        .page-nav button {
            border: 1px solid #ddd; background: #fff; padding: 6px 12px; border-radius: 6px; cursor: pointer; color: #666; margin-left: 5px; transition: 0.2s;
        }
        .page-nav button:hover:not(:disabled) { background: var(--primary); color: #000; border-color: var(--primary); }
        .page-nav button:disabled { opacity: 0.5; cursor: not-allowed; }

        /* BADGES & STATUS */
        .badge { padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .status-active { color: #0CA678; background: #E6FCF5; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; }
        .status-active::before { content:''; width:6px; height:6px; background:#0CA678; border-radius:50%; }
        .status-inactive { color: #E03131; background: #FFE3E3; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; }
        .status-inactive::before { content:''; width:6px; height:6px; background:#E03131; border-radius:50%; }

        /* --- MODAL STYLE --- */
        .modal-overlay {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;
            opacity: 0; transition: opacity 0.3s ease;
        }
        .modal-overlay.show { display: flex; opacity: 1; }
        .modal-box {
            background: #fff; width: 90%; max-width: 600px; padding: 30px; border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2); transform: translateY(20px); transition: transform 0.3s ease; text-align: left;
        }
        .modal-overlay.show .modal-box { transform: translateY(0); }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
        .modal-header h3 { margin: 0; font-size: 18px; font-weight: 700; color: #333; }
        .btn-close-modal { background: none; border: none; font-size: 24px; cursor: pointer; color: #999; line-height: 1; }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .modal-form-group { margin-bottom: 15px; }
        .modal-label { display: block; font-size: 13px; font-weight: 500; margin-bottom: 8px; color: #444; width: 100%; }
        .modal-input { width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 13px; outline: none; transition: 0.2s; }
        .modal-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(255, 196, 0, 0.1); }
        select.modal-input { background: #fff; cursor: pointer; }
        .modal-footer { display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px; pt: 15px; border-top: 1px solid #eee; }
        .btn-modal-cancel { background: #f1f3f5; color: #555; padding: 10px 25px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; }
        .btn-modal-save { background: var(--primary); color: #000; padding: 10px 25px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; }

        @media (max-width: 992px) {
            .main-content { margin-left: 0; padding: 15px; }
            .mobile-header { display: flex !important; }
            .form-grid { grid-template-columns: 1fr; gap: 0; }
            .custom-table { min-width: 900px !important; }
            .table-responsive .custom-table th:first-child,
            .table-responsive .custom-table td:first-child { position: sticky; left: 0; background-color: #fff; z-index: 15; box-shadow: 2px 0 5px rgba(0,0,0,0.05); }
            .table-responsive .custom-table thead th:first-child { background-color: #fcfcfc; z-index: 20; }
        }
    </style>
@endsection

@section('content')
<main class="main-content">

    <div class="mobile-header" style="display:none; justify-content:space-between; margin-bottom:20px;">
        <a href="/" style="font-weight:700; color:#333; text-decoration:none;">Bonanza</a>
        <div onclick="toggleSidebar()"><i class="fas fa-bars"></i></div>
    </div>

    @if(session('success'))
        <div style="background: #e6fcf5; color: #0ca678; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background: #FFE3E3; color: #E03131; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="page-header">
        <div class="page-title">
            <h1>Manajemen Users</h1>
            <p>Kelola data pengguna, peran (role), dan status akun.</p>
        </div>
    </div>

    <div class="card-box">
        <div class="card-header-title">
            <div class="header-left">
                <i class="fas fa-users" style="color:var(--primary);"></i> Daftar Pengguna
            </div>
            <div class="header-actions">
                <div class="search-sm">
                    <i class="fas fa-search"></i>
                    <input type="text" id="userSearch" placeholder="Cari nama atau email...">
                </div>
                @can('users.create')
                    <button class="btn-add" onclick="openModal('modalAddUser')">
                        <i class="fas fa-plus"></i> Tambah User
                    </button>
                @endcan
            </div>
        </div>

        <div class="table-responsive">
            <table class="custom-table" id="userTable">
                <thead>
                    <tr>
                        <th style="padding-left: 20px;">Pengguna</th>
                        <th>Role / Peran</th>
                        <th>No. Handphone</th>
                        <th>Status Akun</th>
                        <th>Tanggal Gabung</th>
                        <th style="text-align:right; padding-right:25px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    @foreach($users as $user)
                    <tr>
                        <td style="padding-left: 20px;">
                            <div class="user-profile">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" class="profile-pic" alt="Avatar">
                                <div class="user-info">
                                    <span class="user-name">{{ $user->name }}</span>
                                    <span class="user-email">{{ $user->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $roleName = $user->role->name ?? 'Guest';
                                $badgeColor = '#eee';
                                $textColor = '#333';

                                if($roleName == 'admin') { $badgeColor = '#E7F5FF'; $textColor = '#1C7ED6'; }
                                elseif($roleName == 'owner') { $badgeColor = '#F3F0FF'; $textColor = '#7950F2'; }
                                elseif($roleName == 'staff') { $badgeColor = '#E6FCF5'; $textColor = '#0CA678'; }
                                elseif($roleName == 'customer') { $badgeColor = '#FFF4E6'; $textColor = '#FF922B'; }
                            @endphp
                            <span class="badge" style="background: {{ $badgeColor }}; color: {{ $textColor }};">
                                {{ ucfirst($roleName) }}
                            </span>
                        </td>
                        <td>{{ $user->phone ?? '-' }}</td>
                        <td>
                            @if($user->is_active)
                                <span class="status-active">Aktif</span>
                            @else
                                <span class="status-inactive">Nonaktif</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td style="text-align:right; padding-right:25px;">

                            @can('users.edit')
                                <button class="btn-sm btn-edit" onclick='editUser(@json($user))' title="Edit">
                                    <i class="fas fa-pen"></i>
                                </button>
                            @endcan

                            @can('users.delete')
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                    @csrf @method('DELETE')
                                    <button class="btn-sm btn-delete" title="Hapus"><i class="fas fa-trash"></i></button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer-pagination">
            <span id="userInfo">Menampilkan..</span>
            <div class="page-nav">
                <button id="userPrev"><i class="fas fa-chevron-left"></i> Sebelumnya</button>
                <button id="userNext">Selanjutnya <i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </div>

</main>

<div id="modalAddUser" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Tambah User Baru</h3>
            <button class="btn-close-modal" onclick="closeModal('modalAddUser')">&times;</button>
        </div>
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-grid">
                    <div class="modal-form-group">
                        <label class="modal-label">Nama Lengkap</label>
                        <input type="text" name="name" class="modal-input" required placeholder="Contoh: Ahmad Dhani">
                    </div>
                    <div class="modal-form-group">
                        <label class="modal-label">Role / Peran</label>
                        <select name="role_id" class="modal-input" required>
                            <option value="">Pilih Role...</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="modal-form-group">
                        <label class="modal-label">Email</label>
                        <input type="email" name="email" class="modal-input" required placeholder="email@contoh.com">
                    </div>
                    <div class="modal-form-group">
                        <label class="modal-label">No. Handphone (WA)</label>
                        <input type="text" name="phone" class="modal-input" placeholder="0812...">
                    </div>
                </div>

                <div class="form-grid">
                    <div class="modal-form-group">
                        <label class="modal-label">Password</label>
                        <input type="password" name="password" class="modal-input" required placeholder="******">
                    </div>
                    <div class="modal-form-group">
                        <label class="modal-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="modal-input" required placeholder="******">
                    </div>
                </div>

                <div class="modal-form-group">
                    <label class="modal-label">Status Akun</label>
                    <select name="is_active" class="modal-input">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('modalAddUser')">Batal</button>
                <button type="submit" class="btn-modal-save">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<div id="modalEditUser" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Edit User</h3>
            <button class="btn-close-modal" onclick="closeModal('modalEditUser')">&times;</button>
        </div>
        <form id="formEditUser" method="POST">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-grid">
                    <div class="modal-form-group">
                        <label class="modal-label">Nama Lengkap</label>
                        <input type="text" name="name" id="edit_name" class="modal-input" required>
                    </div>
                    <div class="modal-form-group">
                        <label class="modal-label">Role / Peran</label>
                        <select name="role_id" id="edit_role_id" class="modal-input" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="modal-form-group">
                        <label class="modal-label">Email</label>
                        <input type="email" name="email" id="edit_email" class="modal-input" required>
                    </div>
                    <div class="modal-form-group">
                        <label class="modal-label">No. Handphone (WA)</label>
                        <input type="text" name="phone" id="edit_phone" class="modal-input">
                    </div>
                </div>

                <div class="form-grid">
                    <div class="modal-form-group">
                        <label class="modal-label">Password Baru (Opsional)</label>
                        <input type="password" name="password" class="modal-input" placeholder="Isi jika ingin ubah">
                    </div>
                    <div class="modal-form-group">
                        <label class="modal-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="modal-input" placeholder="Ulangi password">
                    </div>
                </div>

                <div class="modal-form-group">
                    <label class="modal-label">Status Akun</label>
                    <select name="is_active" id="edit_is_active" class="modal-input">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('modalEditUser')">Batal</button>
                <button type="submit" class="btn-modal-save">Update Data</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('script')
<script>
    // 1. Sidebar Toggle
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        if(sidebar) sidebar.classList.toggle('active');
        const overlay = document.querySelector('.overlay');
        if(overlay) overlay.classList.toggle('active');
    }

    // 2. Modal Logic
    function openModal(id) { document.getElementById(id).classList.add('show'); }
    function closeModal(id) { document.getElementById(id).classList.remove('show'); }
    window.onclick = function(event) {
        if (event.target.classList.contains('modal-overlay')) event.target.classList.remove('show');
    }

    // 3. Edit User Logic
    function editUser(user) {
        // Set Action Form Update Route
        document.getElementById('formEditUser').action = '/users/' + user.id;

        // Isi Value Input
        document.getElementById('edit_name').value = user.name;
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_phone').value = user.phone;
        document.getElementById('edit_role_id').value = user.role_id;
        document.getElementById('edit_is_active').value = user.is_active;

        openModal('modalEditUser');
    }

    // 4. Table Logic (Search & Pagination)
    class TableManager {
        constructor(tableBodyId, searchInputId, paginationInfoId, prevBtnId, nextBtnId, rowsPerPage = 5) {
            this.tableBody = document.getElementById(tableBodyId);
            this.searchInput = document.getElementById(searchInputId);
            this.paginationInfo = document.getElementById(paginationInfoId);
            this.prevBtn = document.getElementById(prevBtnId);
            this.nextBtn = document.getElementById(nextBtnId);
            this.rowsPerPage = rowsPerPage;
            this.currentPage = 1;
            this.allRows = Array.from(this.tableBody.querySelectorAll('tr'));
            this.filteredRows = this.allRows;
            this.init();
        }

        init() {
            this.searchInput.addEventListener('keyup', (e) => {
                const term = e.target.value.toLowerCase();
                this.filteredRows = this.allRows.filter(row => row.innerText.toLowerCase().includes(term));
                this.currentPage = 1;
                this.render();
            });

            this.prevBtn.addEventListener('click', () => {
                if (this.currentPage > 1) { this.currentPage--; this.render(); }
            });

            this.nextBtn.addEventListener('click', () => {
                const maxPage = Math.ceil(this.filteredRows.length / this.rowsPerPage);
                if (this.currentPage < maxPage) { this.currentPage++; this.render(); }
            });

            this.render();
        }

        render() {
            this.allRows.forEach(row => row.style.display = 'none');
            const total = this.filteredRows.length;
            const start = (this.currentPage - 1) * this.rowsPerPage;
            const end = start + this.rowsPerPage;
            const paginatedRows = this.filteredRows.slice(start, end);

            paginatedRows.forEach(row => row.style.display = '');

            const showingStart = total === 0 ? 0 : start + 1;
            const showingEnd = end > total ? total : end;
            this.paginationInfo.innerText = `Menampilkan ${showingStart}-${showingEnd} dari ${total} User`;

            const maxPage = Math.ceil(total / this.rowsPerPage);
            this.prevBtn.disabled = this.currentPage === 1;
            this.nextBtn.disabled = this.currentPage >= maxPage || total === 0;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        new TableManager('userTableBody', 'userSearch', 'userInfo', 'userPrev', 'userNext', 5);
    });
</script>
@endsection
