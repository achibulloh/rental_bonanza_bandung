@extends('dashboard.layouts.index')
@section('title', 'Manajemen Hak Akses')

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

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 30px 40px;
            min-height: 100vh;
            background-color: var(--bg-light);
            font-family: 'Poppins', sans-serif;
        }

        /* HEADER & BUTTONS */
        .page-header { margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; }
        .page-title h1 { font-size: 24px; font-weight: 700; margin: 0; color: var(--text-dark); }
        .page-title p { font-size: 13px; color: var(--text-gray); margin-top: 5px; }

        .btn-add {
            background: var(--primary); color: #000; border: none; padding: 10px 20px;
            border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 13px;
            display: flex; align-items: center; gap: 8px; transition: 0.2s; text-decoration: none;
        }
        .btn-add:hover { background: var(--primary-dark); transform: translateY(-2px); }

        .btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 6px; border:none; cursor: pointer; display: inline-block;}
        .btn-edit { background: #E7F5FF; color: #1C7ED6; }
        .btn-delete { background: #FFE3E3; color: #E03131; }

        /* CARDS */
        .card-box {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid #eee;
            margin-bottom: 30px;
            overflow: hidden;
            display: flex; flex-direction: column;
        }

        .card-header-title {
            padding: 15px 25px;
            background: #fff;
            border-bottom: 1px solid #f0f0f0;
            display: flex; justify-content: space-between; align-items: center;
            flex-wrap: wrap; gap: 15px;
        }
        .header-left { font-weight: 600; font-size: 15px; color: var(--text-dark); display: flex; align-items: center; gap: 8px; }
        .header-actions { display: flex; gap: 10px; align-items: center; flex: 1; justify-content: flex-end; }

        /* SEARCH INPUT */
        .search-sm { position: relative; max-width: 200px; width: 100%; }
        .search-sm input {
            width: 100%; padding: 6px 12px 6px 30px;
            border-radius: 6px; border: 1px solid #ddd;
            font-size: 12px; outline: none;
        }
        .search-sm i { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #aaa; font-size: 11px; }

        /* --- TABLE SYSTEM --- */
        .table-responsive {
            width: 100%;
            min-height: 350px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            position: relative;
            display: block;
        }

        .custom-table { width: 100%; border-collapse: collapse; white-space: nowrap; }
        .custom-table th { text-align: left; padding: 15px 25px; background: #fcfcfc; font-size: 12px; color: #888; text-transform: uppercase; font-weight: 600; border-bottom: 1px solid #eee; height: 50px; }
        .custom-table td { padding: 15px 25px; border-bottom: 1px solid #eee; font-size: 13px; color: #333; height: 60px; vertical-align: middle; }
        .custom-table tr:hover { background-color: #fafafa; }

        /* PAGINATION */
        .card-footer-pagination {
            padding: 15px 25px; border-top: 1px solid #f0f0f0; background: #fff;
            display: flex; justify-content: space-between; align-items: center;
            font-size: 12px; color: #666; margin-top: auto;
        }
        .page-nav button {
            border: 1px solid #ddd; background: #fff; padding: 5px 10px;
            border-radius: 4px; cursor: pointer; color: #666; margin-left: 5px;
        }
        .page-nav button:hover:not(:disabled) { background: #f0f0f0; }
        .page-nav button:disabled { opacity: 0.5; cursor: not-allowed; }

        /* BADGES */
        .badge { padding: 5px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .badge-info { background: #E7F5FF; color: #1C7ED6; }
        .badge-method { background: #E9ECEF; color: #495057; border: 1px solid #DEE2E6; padding: 3px 8px; font-size: 10px; border-radius: 4px; font-weight: 700; text-transform: uppercase; }
        .badge-get { color: #0CA678; background: #E6FCF5; border-color: #C3FAE8; }
        .badge-post { color: #1C7ED6; background: #E7F5FF; border-color: #D0EBFF; }

        .icon-box { width: 30px; height: 30px; background: #fff9db; color: #f59f00; display: flex; align-items: center; justify-content: center; border-radius: 6px; font-size: 14px; }

        .access-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 25px; }
        .route-grid { margin-top: 30px; }

        /* TOGGLE SWITCH */
        .switch { position: relative; display: inline-block; width: 36px; height: 18px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #e4e4e4; transition: .4s; border-radius: 34px; }
        .slider:before { position: absolute; content: ""; height: 12px; width: 12px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background-color: var(--primary); }
        input:checked + .slider:before { transform: translateX(18px); }
        input:disabled + .slider { background-color: #dcdcdc; cursor: not-allowed; }

        /* MODAL STYLE */
        .modal-overlay {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;
            opacity: 0; transition: opacity 0.3s ease;
        }
        .modal-overlay.show { display: flex; opacity: 1; }

        .modal-box {
            background: #fff; width: 90%; max-width: 500px; padding: 25px; border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2); transform: translateY(20px); transition: transform 0.3s ease;
            text-align: left;
        }
        .modal-overlay.show .modal-box { transform: translateY(0); }

        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .modal-header h3 { margin: 0; font-size: 18px; font-weight: 700; color: #333; }
        .btn-close-modal { background: none; border: none; font-size: 20px; cursor: pointer; color: #999; }

        .modal-form-group { margin-bottom: 15px; }
        .modal-label {
            display: block; font-size: 13px; font-weight: 500; margin-bottom: 5px; color: #555;
            text-align: left; width: 100%;
        }
        .modal-input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 13px; outline: none; }
        .modal-input:focus { border-color: var(--primary); }

        .modal-footer { display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px; }
        .btn-modal-cancel { background: #f1f3f5; color: #555; padding: 10px 20px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; }
        .btn-modal-save { background: var(--primary); color: #000; padding: 10px 20px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; }

        /* RESPONSIVE */
        @media (max-width: 992px) {
            .main-content { margin-left: 0; padding: 20px; }
            .access-grid { grid-template-columns: 1fr; }
            .mobile-header { display: flex !important; }
            .header-actions { width: 100%; justify-content: space-between; margin-top: 10px; }
            .search-sm { max-width: 100%; flex: 1; margin-right: 10px; }
            .custom-table { min-width: 800px !important; }

            .table-responsive .custom-table th:first-child,
            .table-responsive .custom-table td:first-child {
                position: sticky; left: 0; background-color: #fff; z-index: 5;
                box-shadow: 2px 0 5px rgba(0,0,0,0.05); border-right: 1px solid #eee;
            }
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

    <div class="page-header">
        <div class="page-title">
            <h1>Manajemen Hak Akses</h1>
            <p>Atur role pengguna, menu navigasi, dan izin akses aplikasi.</p>
        </div>
        {{-- Tombol simpan global dihapus karena sudah ada di masing-masing card --}}
    </div>

    <div class="access-grid">

        <div class="card-box" id="roleContainer">
            <div class="card-header-title">
                <div class="header-left"><i class="fas fa-user-shield" style="color:var(--primary);"></i> Data Role</div>
                <div class="header-actions">
                    <div class="search-sm"><i class="fas fa-search"></i><input type="text" id="roleSearch" placeholder="Cari role..."></div>
                    <button class="btn-sm" style="background:#eee; color:#333;" onclick="openModal('modalAddRole')"><i class="fas fa-plus"></i></button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="custom-table" id="roleTable">
                    <thead><tr><th>Role Name</th><th>Users</th><th style="text-align:right;">Aksi</th></tr></thead>
                    <tbody id="roleTableBody">
                        @foreach($roles as $role)
                        <tr>
                            <td>
                                <span class="badge badge-info">{{ $role->name }}</span>
                                <div style="font-size:11px; color:#888; margin-top:2px;">{{ $role->description }}</div>
                            </td>
                            <td>{{ $role->users_count }}</td>
                            <td style="text-align:right;">
                                <button class="btn-sm btn-edit" onclick="editRole('{{ $role->id }}', '{{ $role->name }}', '{{ $role->description }}')"><i class="fas fa-pen"></i></button>
                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus role ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-sm btn-delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer-pagination">
                <span id="roleInfo">Menampilkan data..</span>
                <div class="page-nav"><button id="rolePrev"><</button><button id="roleNext">></button></div>
            </div>
        </div>

        <div class="card-box" id="menuContainer">
            <div class="card-header-title">
                <div class="header-left"><i class="fas fa-list" style="color:var(--primary);"></i> Menu</div>
                <div class="header-actions">
                    <div class="search-sm"><i class="fas fa-search"></i><input type="text" id="menuSearch" placeholder="Cari menu..."></div>
                    <button class="btn-sm" style="background:#eee; color:#333;" onclick="openModal('modalAddMenu')"><i class="fas fa-plus"></i></button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="custom-table" id="menuTable">
                    <thead><tr><th>Icon</th><th>Nama Menu</th><th>Route URL</th><th style="text-align:right;">Aksi</th></tr></thead>
                    <tbody id="menuTableBody">
                        @foreach($menus as $menu)
                        <tr>
                            <td><div class="icon-box"><i class="{{ $menu->icon }}"></i></div></td>
                            <td>{{ $menu->name }}</td>
                            <td style="color:#888;">{{ $menu->url }}</td>
                            <td style="text-align:right;">
                                <button class="btn-sm btn-edit" onclick="editMenu('{{ $menu->id }}', '{{ $menu->name }}', '{{ $menu->url }}', '{{ $menu->route_name }}', '{{ $menu->icon }}')"><i class="fas fa-pen"></i></button>
                                <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus menu ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-sm btn-delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer-pagination">
                <span id="menuInfo">Menampilkan data..</span>
                <div class="page-nav"><button id="menuPrev"><</button><button id="menuNext">></button></div>
            </div>
        </div>
    </div>

    <div class="card-box" id="permContainer">
        <div class="card-header-title">
            <div class="header-left"><i class="fas fa-shield-alt" style="color:var(--primary);"></i> Data Permission</div>
            <div class="header-actions">
                <div class="search-sm">
                    <i class="fas fa-search"></i>
                    <input type="text" id="permSearch" placeholder="Cari permission...">
                </div>
                <button class="btn-sm" style="background:#eee; color:#333;" onclick="openModal('modalAddPermission')"><i class="fas fa-plus"></i> Tambah</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="custom-table" id="permTable">
                <thead><tr><th>Menu Induk</th><th>Kode Permission</th><th>Label Tampilan</th><th style="text-align:right;">Aksi</th></tr></thead>
                <tbody id="permTableBody">
                    {{-- Loop semua permission --}}
                    @foreach($menus as $menu)
                        @if(isset($permissionsGrouped[$menu->id]))
                            @foreach($permissionsGrouped[$menu->id] as $perm)
                            <tr>
                                <td><span class="badge badge-info">{{ $menu->name }}</span></td>
                                <td style="font-family:monospace; color:#d6336c;">{{ $perm->name }}</td>
                                <td>{{ $perm->label }}</td>
                                <td style="text-align:right;">
                                    {{-- Tombol Edit Permission (Membuka Modal) --}}
                                    <button class="btn-sm btn-edit" onclick="editPermission('{{ $perm->id }}', '{{ $perm->menu_id }}', '{{ $perm->name }}', '{{ $perm->label }}')"><i class="fas fa-pen"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer-pagination">
            <span id="permInfo">Menampilkan data..</span>
            <div class="page-nav"><button id="permPrev"><</button><button id="permNext">></button></div>
        </div>
    </div>

    <form action="{{ route('permissions.saveMatrix') }}" method="POST">
        @csrf
        <div class="card-box">
            <div class="card-header-title">
                <div style="display:flex; flex-direction:column;">
                    <span class="header-left"><i class="fas fa-key" style="color:var(--primary);"></i> Matrix Izin Akses</span>
                    <span style="font-weight:400; color:#888; font-size:12px; margin-top:3px;">Centang izin yang sesuai, lalu klik tombol <b>Simpan Matrix</b> di kanan.</span>
                </div>
                <button type="submit" class="btn-add">
                    <i class="fas fa-save"></i> Simpan Matrix
                </button>
            </div>

            <div class="table-responsive">
                <table class="custom-table" style="text-align:center;">
                    <thead>
                        <tr>
                            <th style="text-align:left; width: 30%; padding-left: 25px;">Menu & Permission</th>
                            @foreach($roles as $role)
                                <th style="text-align:center; text-transform: uppercase; font-size: 12px; font-weight: 700; color: var(--text-dark);">
                                    {{ $role->label ?? $role->name }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($menus as $menu)
                            @if(isset($permissionsGrouped[$menu->id]))
                                <tr class="table-header-row">
                                    <td colspan="{{ $roles->count() + 1 }}" style="text-align:left; background:#fffdf5; font-weight:bold; padding-left: 20px;">
                                        <i class="{{ $menu->icon }}" style="margin-right:8px; color:#ffc400;"></i> {{ $menu->name }}
                                    </td>
                                </tr>

                                @foreach($permissionsGrouped[$menu->id] as $perm)
                                    <tr class="permission-row">
                                        <td style="text-align:left; padding-left: 45px;">
                                            <div class="permission-name" style="font-size: 13px; color: #555;">
                                                <i class="fas fa-circle" style="font-size:5px; margin-right: 8px; color: #ccc; vertical-align:middle;"></i>
                                                {{ $perm->label }}
                                            </div>
                                        </td>
                                        @foreach($roles as $role)
                                            <td>
                                                <label class="switch">
                                                    {{-- Checkbox untuk Form Array: matrix[role_id][] = permission_id --}}
                                                    <input type="checkbox"
                                                        name="matrix[{{ $role->id }}][]"
                                                        value="{{ $perm->id }}"
                                                        {{ $role->permissions->contains('id', $perm->id) ? 'checked' : '' }}>
                                                    <span class="slider"></span>
                                                </label>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </form>

    <div class="card-box route-grid" id="routeContainer">
        <div class="card-header-title">
            <div class="header-left"><i class="fas fa-network-wired" style="color:var(--primary);"></i> Manajemen Route</div>
            <div class="header-actions">
                <div class="search-sm"><i class="fas fa-search"></i><input type="text" id="routeSearch" placeholder="Cari route / URI..."></div>
                <button class="btn-sm" style="background:#eee; color:#333;" onclick="openModal('modalAddRoute')"><i class="fas fa-plus"></i></button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="custom-table" id="routeTable">
                <thead><tr><th>URI (URL Path)</th><th>Controller @ Method</th><th>Route Name</th><th>Method</th><th style="text-align:right;">Aksi</th></tr></thead>
                <tbody id="routeTableBody">
                    @foreach($appRoutes as $route)
                    <tr>
                        <td>{{ $route->url }}</td>
                        <td>{{ $route->controller }}</td>
                        <td>{{ $route->route_name }}</td>
                        <td><span class="badge-method {{ $route->method == 'GET' ? 'badge-get' : 'badge-post' }}">{{ $route->method }}</span></td>
                        <td style="text-align:right;">
                            <button class="btn-sm btn-edit" onclick="editRoute('{{ $route->id }}', '{{ $route->url }}', '{{ $route->controller }}', '{{ $route->route_name }}', '{{ $route->method }}')"><i class="fas fa-pen"></i></button>
                            <form action="{{ route('routes.destroy', $route->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus route ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-sm btn-delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer-pagination">
            <span id="routeInfo">Menampilkan data..</span>
            <div class="page-nav"><button id="routePrev"><</button><button id="routeNext">></button></div>
        </div>
    </div>

</main>

<div id="modalAddRole" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header"><h3>Tambah Role Baru</h3><button class="btn-close-modal" onclick="closeModal('modalAddRole')">&times;</button></div>
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="modal-form-group"><label class="modal-label">Nama Role</label><input type="text" name="name" class="modal-input" required placeholder="Contoh: staff"></div>
                <div class="modal-form-group"><label class="modal-label">Deskripsi</label><input type="text" name="description" class="modal-input" placeholder="Keterangan..."></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn-modal-cancel" onclick="closeModal('modalAddRole')">Batal</button><button type="submit" class="btn-modal-save">Simpan</button></div>
        </form>
    </div>
</div>

<div id="modalAddMenu" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header"><h3>Tambah Menu Baru</h3><button class="btn-close-modal" onclick="closeModal('modalAddMenu')">&times;</button></div>
        <form action="{{ route('menus.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="modal-form-group"><label class="modal-label">Nama Menu</label><input type="text" name="name" class="modal-input" required></div>
                <div class="modal-form-group"><label class="modal-label">URL</label><input type="text" name="url" class="modal-input" required></div>
                <div class="modal-form-group"><label class="modal-label">Route Name</label><input type="text" name="route_name" class="modal-input" required></div>
                <div class="modal-form-group"><label class="modal-label">Icon</label><input type="text" name="icon" class="modal-input" placeholder="fas fa-home"></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn-modal-cancel" onclick="closeModal('modalAddMenu')">Batal</button><button type="submit" class="btn-modal-save">Simpan</button></div>
        </form>
    </div>
</div>

<div id="modalAddRoute" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header"><h3>Tambah Route Baru</h3><button class="btn-close-modal" onclick="closeModal('modalAddRoute')">&times;</button></div>
        <form action="{{ route('routes.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="modal-form-group"><label class="modal-label">URL</label><input type="text" name="url" class="modal-input" required></div>
                <div class="modal-form-group"><label class="modal-label">Controller@Method</label><input type="text" name="controller" class="modal-input" required></div>
                <div class="modal-form-group"><label class="modal-label">Route Name</label><input type="text" name="route_name" class="modal-input"></div>
                <div class="modal-form-group"><label class="modal-label">Method</label>
                    <select name="method" class="modal-input"><option value="GET">GET</option><option value="POST">POST</option><option value="PUT">PUT</option><option value="DELETE">DELETE</option></select>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn-modal-cancel" onclick="closeModal('modalAddRoute')">Batal</button><button type="submit" class="btn-modal-save">Simpan</button></div>
        </form>
    </div>
</div>

<div id="modalAddPermission" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header"><h3>Tambah Permission</h3><button class="btn-close-modal" onclick="closeModal('modalAddPermission')">&times;</button></div>
        <form action="{{ route('permissions.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="modal-form-group">
                    <label class="modal-label">Pilih Menu Induk</label>
                    <select name="menu_id" class="modal-input">
                        @foreach($menus as $menu)
                            <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-form-group"><label class="modal-label">Kode Permission</label><input type="text" name="name" class="modal-input" placeholder="contoh: cars.create"></div>
                <div class="modal-form-group"><label class="modal-label">Label Tampilan</label><input type="text" name="label" class="modal-input" placeholder="contoh: Tambah Mobil"></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn-modal-cancel" onclick="closeModal('modalAddPermission')">Batal</button><button type="submit" class="btn-modal-save">Simpan</button></div>
        </form>
    </div>
</div>

<div id="modalEditRole" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header"><h3>Edit Role</h3><button class="btn-close-modal" onclick="closeModal('modalEditRole')">&times;</button></div>
        <form id="formEditRole" method="POST">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="modal-form-group"><label class="modal-label">Nama Role</label><input type="text" name="name" id="edit_role_name" class="modal-input" required></div>
                <div class="modal-form-group"><label class="modal-label">Deskripsi</label><input type="text" name="description" id="edit_role_desc" class="modal-input"></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn-modal-cancel" onclick="closeModal('modalEditRole')">Batal</button><button type="submit" class="btn-modal-save">Update</button></div>
        </form>
    </div>
</div>

<div id="modalEditMenu" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header"><h3>Edit Menu</h3><button class="btn-close-modal" onclick="closeModal('modalEditMenu')">&times;</button></div>
        <form id="formEditMenu" method="POST">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="modal-form-group"><label class="modal-label">Nama Menu</label><input type="text" name="name" id="edit_menu_name" class="modal-input" required></div>
                <div class="modal-form-group"><label class="modal-label">URL</label><input type="text" name="url" id="edit_menu_url" class="modal-input" required></div>
                <div class="modal-form-group"><label class="modal-label">Route Name</label><input type="text" name="route_name" id="edit_menu_route" class="modal-input"></div>
                <div class="modal-form-group"><label class="modal-label">Icon</label><input type="text" name="icon" id="edit_menu_icon" class="modal-input"></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn-modal-cancel" onclick="closeModal('modalEditMenu')">Batal</button><button type="submit" class="btn-modal-save">Update</button></div>
        </form>
    </div>
</div>

<div id="modalEditRoute" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header"><h3>Edit Route</h3><button class="btn-close-modal" onclick="closeModal('modalEditRoute')">&times;</button></div>
        <form id="formEditRoute" method="POST">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="modal-form-group"><label class="modal-label">URL</label><input type="text" name="url" id="edit_route_url" class="modal-input" required></div>
                <div class="modal-form-group"><label class="modal-label">Controller</label><input type="text" name="controller" id="edit_route_controller" class="modal-input" required></div>
                <div class="modal-form-group"><label class="modal-label">Route Name</label><input type="text" name="route_name" id="edit_route_name" class="modal-input"></div>
                <div class="modal-form-group"><label class="modal-label">Method</label>
                    <select name="method" id="edit_route_method" class="modal-input"><option value="GET">GET</option><option value="POST">POST</option><option value="PUT">PUT</option><option value="DELETE">DELETE</option></select>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn-modal-cancel" onclick="closeModal('modalEditRoute')">Batal</button><button type="submit" class="btn-modal-save">Update</button></div>
        </form>
    </div>
</div>

<div id="modalEditPermission" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header"><h3>Edit Permission</h3><button class="btn-close-modal" onclick="closeModal('modalEditPermission')">&times;</button></div>
        <form id="formEditPermission" method="POST">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="modal-form-group">
                    <label class="modal-label">Pilih Menu Induk</label>
                    <select name="menu_id" id="edit_perm_menu" class="modal-input">
                        @foreach($menus as $menu)
                            <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-form-group"><label class="modal-label">Nama Permission (Kode)</label><input type="text" name="name" id="edit_perm_name" class="modal-input" required></div>
                <div class="modal-form-group"><label class="modal-label">Label (Tampilan)</label><input type="text" name="label" id="edit_perm_label" class="modal-input"></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn-modal-cancel" onclick="closeModal('modalEditPermission')">Batal</button><button type="submit" class="btn-modal-save">Update</button></div>
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

    // 2. Logic Modal Basic
    function openModal(id) { document.getElementById(id).classList.add('show'); }
    function closeModal(id) { document.getElementById(id).classList.remove('show'); }
    window.onclick = function(event) {
        if (event.target.classList.contains('modal-overlay')) { event.target.classList.remove('show'); }
    }

    // 3. Logic Edit Modal (Populate Data)
    function editRole(id, name, desc) {
        document.getElementById('formEditRole').action = '/roles/' + id;
        document.getElementById('edit_role_name').value = name;
        document.getElementById('edit_role_desc').value = desc;
        openModal('modalEditRole');
    }

    function editMenu(id, name, url, route, icon) {
        document.getElementById('formEditMenu').action = '/menus/' + id;
        document.getElementById('edit_menu_name').value = name;
        document.getElementById('edit_menu_url').value = url;
        document.getElementById('edit_menu_route').value = route;
        document.getElementById('edit_menu_icon').value = icon;
        openModal('modalEditMenu');
    }

    function editRoute(id, url, controller, name, method) {
        document.getElementById('formEditRoute').action = '/app-routes/' + id;
        document.getElementById('edit_route_url').value = url;
        document.getElementById('edit_route_controller').value = controller;
        document.getElementById('edit_route_name').value = name;
        document.getElementById('edit_route_method').value = method;
        openModal('modalEditRoute');
    }

    function editPermission(id, menuId, name, label) {
        // Pastikan Anda sudah membuat Route PUT: /permissions/{id}
        document.getElementById('formEditPermission').action = '/permissions/' + id;
        document.getElementById('edit_perm_menu').value = menuId;
        document.getElementById('edit_perm_name').value = name;
        document.getElementById('edit_perm_label').value = label;
        openModal('modalEditPermission');
    }

    // 4. Class Table Manager (Search + Pagination)
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
            this.prevBtn.addEventListener('click', () => { if (this.currentPage > 1) { this.currentPage--; this.render(); } });
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
            this.filteredRows.slice(start, end).forEach(row => row.style.display = '');
            this.paginationInfo.innerText = `Menampilkan ${total === 0 ? 0 : start + 1}-${end > total ? total : end} dari ${total} Data`;
            const maxPage = Math.ceil(total / this.rowsPerPage);
            this.prevBtn.disabled = this.currentPage === 1;
            this.nextBtn.disabled = this.currentPage >= maxPage || total === 0;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        new TableManager('roleTableBody', 'roleSearch', 'roleInfo', 'rolePrev', 'roleNext', 5);
        new TableManager('menuTableBody', 'menuSearch', 'menuInfo', 'menuPrev', 'menuNext', 5);
        // Table baru Permission juga menggunakan manager yang sama
        new TableManager('permTableBody', 'permSearch', 'permInfo', 'permPrev', 'permNext', 5);
        new TableManager('routeTableBody', 'routeSearch', 'routeInfo', 'routePrev', 'routeNext', 5);
    });
</script>
@endsection
