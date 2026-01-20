@extends('dashboard.layouts.index')
@section('title', 'Manajemen Mobil & Kategori')

@section('style')
    <style>
        :root { --primary: #FFC400; --bg-light: #f8f9fa; --text-dark: #333; }
        .main-content { flex: 1;  margin-left: var(--sidebar-width); padding: 30px 40px; background: var(--bg-light); font-family: 'Poppins', sans-serif; }
        .mobile-header { display: none; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .hamburger { font-size: 1.5rem; cursor: pointer; color: var(--text-dark); }

        /* --- TABS SYSTEM (FIXED SCROLL) --- */
        .tab-nav {
            display: flex;
            gap: 20px;
            border-bottom: 2px solid #ddd;
            margin-bottom: 25px;
            overflow-x: auto;
            scrollbar-width: none; /* Firefox: Sembunyikan scrollbar */
            -ms-overflow-style: none;  /* IE: Sembunyikan scrollbar */
        }
        .tab-nav::-webkit-scrollbar { display: none; /* Chrome: Sembunyikan scrollbar */ }

        .tab-btn { padding: 10px 20px; background: none; border: none; font-size: 14px; font-weight: 600; color: #888; cursor: pointer; position: relative; white-space: nowrap; }
        .tab-btn.active { color: var(--text-dark); }
        .tab-btn.active::after { content: ''; position: absolute; bottom: -2px; left: 0; width: 100%; height: 3px; background: var(--primary); }

        .tab-pane { display: none; animation: fadeIn 0.3s; }
        .tab-pane.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

        /* CARDS & TABLES */
        .card-box { background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #eee; overflow: hidden; margin-bottom:30px; }

        /* --- HEADER LAYOUT (SEARCH & BUTTON FIX) --- */
        .card-header {
            padding: 20px 25px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between; /* Judul Kiri, Aksi Kanan */
            align-items: center;
            background: #fff;
            flex-wrap: wrap;
            gap: 15px;
        }

        .header-left .card-title { font-weight: 700; font-size: 16px; color: var(--text-dark); display: flex; align-items: center; gap: 10px; }

        .header-actions { display: flex; align-items: center; gap: 10px; }

        /* Search Box Modern */
        .search-box { position: relative; width: 250px; }
        .search-box input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 13px;
            outline: none;
            background: #f9f9f9;
            transition: 0.3s;
        }
        .search-box input:focus { border-color: var(--primary); background: #fff; box-shadow: 0 0 0 3px rgba(255, 196, 0, 0.1); }
        .search-box i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #aaa; font-size: 14px; }

        /* Tombol Tambah */
        .btn-add {
            background: var(--primary);
            color: #000;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            gap: 8px;
            align-items: center;
            transition: 0.2s;
            white-space: nowrap;
        }
        .btn-add:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(255, 196, 0, 0.2); }

        /* TABLE STYLES */
        .custom-table { width: 100%; border-collapse: collapse; white-space: nowrap; }
        .custom-table th { text-align: left; padding: 15px 20px; background: #f8f9fa; font-size: 12px; color: #666; font-weight: 700; border-bottom: 2px solid #eee; text-transform: uppercase; letter-spacing: 0.5px; }
        .custom-table td { padding: 15px 20px; border-bottom: 1px solid #eee; font-size: 13px; color: #333; vertical-align: middle; }
        .custom-table tr:hover td { background: #fafafa; }

        .btn-action { border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 12px; margin-right: 5px; transition: 0.2s; }
        .btn-edit { background: #E7F5FF; color: #1C7ED6; }
        .btn-edit:hover { background: #d0ebff; }
        .btn-delete { background: #FFE3E3; color: #E03131; }
        .btn-delete:hover { background: #ffc9c9; }

        /* PAGINATION */
        .pagination-wrapper { padding: 15px 25px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #eee; font-size: 12px; color: #666; }
        .pagination-controls button { background: #fff; border: 1px solid #ddd; padding: 6px 14px; border-radius: 6px; cursor: pointer; margin-left: 5px; transition: 0.2s; }
        .pagination-controls button:hover:not(:disabled) { background: #f0f0f0; border-color: #ccc; }
        .pagination-controls button:disabled { opacity: 0.5; cursor: not-allowed; }

        /* --- MODAL SYSTEM (FIXED HEADER/FOOTER + SCROLL BODY) --- */
        .modal-overlay {
            display: none;
            position: fixed; top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 9999;
            justify-content: center; align-items: center;
            padding: 20px;
        }
        .modal-overlay.show { display: flex; }

        .modal-box {
            background: #fff;
            width: 100%; max-width: 500px;
            border-radius: 12px;
            position: relative;
            max-height: 85vh;
            display: flex; flex-direction: column;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .modal-lg { max-width: 900px; }

        /* Header Diam */
        .modal-header { padding: 20px 25px; border-bottom: 1px solid #eee; background: #fff; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0; }
        .modal-header h3 { margin: 0; font-size: 18px; font-weight: 700; color: #333; }

        /* Body Scroll */
        .modal-body { padding: 25px; overflow-y: auto; flex-grow: 1; }

        /* Footer Diam */
        .modal-footer { padding: 15px 25px; border-top: 1px solid #eee; background: #f8f9fa; text-align: right; flex-shrink: 0; }

        /* Form Elements */
        .modal-form-flex { display: flex; flex-direction: column; height: 100%; max-height: 100%; overflow: hidden; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-input, .form-select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 13px; outline: none; margin-top: 5px; margin-bottom: 15px; transition:0.2s; }
        .form-input:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(255, 196, 0, 0.1); }

        /* RESPONSIF */
        @media (max-width: 992px) {
            .main-content { margin-left: 0; padding: 20px; }
            .mobile-header { display: flex; }

        }
        @media (max-width: 768px) {
            .card-header { flex-direction: column; align-items: flex-start; }
            .header-actions { width: 100%; display: flex; flex-direction: column; gap:10px; }
            .search-box, .btn-add { width: 100%; }
            .form-grid { grid-template-columns: 1fr; gap: 10px; }
            .modal-header, .modal-body, .modal-footer { padding: 15px; }
        }
    </style>
@endsection

@section('content')
<main class="main-content">
    <div class="mobile-header">
        <a href="#" style="color:var(--text-dark); font-weight:700; text-decoration:none;">Bonanza Rental</a>
        <div class="hamburger" onclick="toggleSidebar()"><i class="fas fa-bars"></i></div>
    </div>

    <div style="margin-bottom: 20px;">
        <h1 style="font-size:24px; font-weight:700;">Manajemen Armada</h1>
        <p style="color:#666; font-size:13px; margin-top:5px;">Kelola mobil, kategori, dan tipe kendaraan.</p>
    </div>

    @if(session('success'))
        <div style="background: #e6fcf5; color: #0ca678; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #ffe3e3; color: #e03131; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background: #ffe3e3; color: #e03131; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $activeTab = '';
        if (auth()->user()->can('cars.index')) { $activeTab = 'tabMobil'; }
        elseif (auth()->user()->can('brands.index')) { $activeTab = 'tabBrand'; }
        elseif (auth()->user()->can('categories.index')) { $activeTab = 'tabKategori'; }
        elseif (auth()->user()->can('types.index')) { $activeTab = 'tabType'; }
    @endphp

    <div class="tab-nav">
        @can('cars.index') <button class="tab-btn {{ $activeTab == 'tabMobil' ? 'active' : '' }}" onclick="openTab('tabMobil')">Data Mobil</button> @endcan
        @can('brands.index') <button class="tab-btn {{ $activeTab == 'tabBrand' ? 'active' : '' }}" onclick="openTab('tabBrand')">Data Brand</button> @endcan
        @can('categories.index') <button class="tab-btn {{ $activeTab == 'tabKategori' ? 'active' : '' }}" onclick="openTab('tabKategori')">Data Kategori</button> @endcan
        @can('types.index') <button class="tab-btn {{ $activeTab == 'tabType' ? 'active' : '' }}" onclick="openTab('tabType')">Data Type</button> @endcan
    </div>

    {{-- @can('cars.index')
        <div id="tabMobil" class="tab-pane {{ $activeTab == 'tabMobil' ? 'active' : '' }}">
            <div class="card-box">
                <div class="card-header">
                    <div class="header-left">
                        <div class="card-title"><i class="fas fa-car"></i> Daftar Mobil</div>
                    </div>
                    <div class="header-actions">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" id="searchMobil" placeholder="Cari merk, nama, atau plat...">
                        </div>
                        @can('cars.store')
                            <button class="btn-add" onclick="openModal('modalAddCar')"><i class="fas fa-plus"></i> Tambah Mobil</button>
                        @endcan
                    </div>
                </div>

                <div style="overflow-x:auto;">
                    <table class="custom-table" id="tableMobil">
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Mobil</th>
                                <th>Kategori / Tipe</th>
                                <th>Harga / Hari</th>
                                <th>Status</th>
                                <th style="text-align:right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyMobil">
                            @foreach($cars as $car)
                            <tr>
                                <td>
                                    @php
                                        $imagePath = is_array($car->images) ? ($car->images[0] ?? null) : $car->images;
                                        $finalPath = $imagePath ? 'storage/' . $imagePath : 'img/no-image.jpg';
                                    @endphp
                                    <img src="{{ asset($finalPath) }}" alt="Foto" style="width:60px; height:40px; object-fit:cover; border-radius:6px; border:1px solid #eee;">
                                </td>
                                <td>
                                    <b>{{ $car->brand }} {{ $car->name }}</b>
                                    <div style="font-size:12px; color:#555;">{{ $car->model }}</div>
                                    <div style="font-size:11px; color:#888;">{{ $car->license_plate }}</div>
                                </td>
                                <td>
                                    <div>{{ $car->type->name ?? '-' }}</div>
                                    <small style="color:#888;">{{ $car->type->category->name ?? '-' }}</small>
                                </td>
                                <td>Rp {{ number_format($car->price_per_day, 0, ',', '.') }}</td>
                                <td>
                                    <span style="padding:3px 8px; border-radius:10px; font-size:10px; background:{{ $car->status == 'available' ? '#e6fcf5' : '#fff5f5' }}; color:{{ $car->status == 'available' ? '#0ca678' : '#fa5252' }}">
                                        {{ ucfirst($car->status) }}
                                    </span>
                                </td>
                                <td style="text-align:right;">
                                    @can('cars.update')
                                        <button class="btn-action btn-edit" onclick='editCar(@json($car))'><i class="fas fa-pen"></i></button>
                                    @endcan
                                    @can('cars.destroy')
                                        <button class="btn-action btn-delete" onclick="openDeleteModal('{{ route('cars.destroy', $car->id) }}')"><i class="fas fa-trash"></i></button>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination-wrapper">
                    <span id="infoMobil">Menampilkan...</span>
                    <div class="pagination-controls">
                        <button id="prevMobil"><</button>
                        <button id="nextMobil">></button>
                    </div>
                </div>
            </div>
        </div>
    @endcan --}}
    @can('cars.index')
        <div id="tabMobil" class="tab-pane {{ $activeTab == 'tabMobil' ? 'active' : '' }}">
            <div class="card-box">
                <div class="card-header">
                    <div class="header-left"><div class="card-title"><i class="fas fa-car"></i> Daftar Mobil</div></div>
                    <div class="header-actions">
                        <div class="search-box"><i class="fas fa-search"></i><input type="text" id="searchMobil" placeholder="Cari mobil..."></div>
                        @can('cars.store') <button class="btn-add" onclick="openModal('modalAddCar')"><i class="fas fa-plus"></i> Tambah Mobil</button> @endcan
                    </div>
                </div>
                <div style="overflow-x:auto;">
                    <table class="custom-table" id="tableMobil">
                        <thead><tr><th>Foto</th><th>Mobil</th><th>Kategori / Tipe</th><th>Harga / Hari</th><th>Status</th><th style="text-align:right;">Aksi</th></tr></thead>
                        <tbody id="tbodyMobil">
                            @foreach($cars as $car)
                            <tr>
                                <td>
                                    @php $image = is_array($car->images) ? ($car->images[0] ?? null) : $car->images; $path = $image ? 'storage/'.$image : 'img/no-image.jpg'; @endphp
                                    <img src="{{ asset($path) }}" style="width:60px; height:40px; object-fit:cover; border-radius:6px; border:1px solid #eee;">
                                </td>
                                <td>
                                    <b>{{ $car->brand->name ?? 'Tanpa Brand' }} - {{ $car->name }}</b>
                                    <div style="font-size:12px; color:#555;">{{ $car->model }}</div>
                                    <div style="font-size:11px; color:#888;">{{ $car->license_plate }}</div>
                                </td>
                                <td><div>{{ $car->type->name ?? '-' }}</div><small style="color:#888;">{{ $car->type->category->name ?? '-' }}</small></td>
                                <td>Rp {{ number_format($car->price_per_day, 0, ',', '.') }}</td>
                                <td><span style="padding:3px 8px; border-radius:10px; font-size:10px; background:{{ $car->status == 'available' ? '#e6fcf5' : '#fff5f5' }}; color:{{ $car->status == 'available' ? '#0ca678' : '#fa5252' }}">{{ ucfirst($car->status) }}</span></td>
                                <td style="text-align:right;">
                                    @can('cars.update') <button class="btn-action btn-edit" onclick='editCar(@json($car))'><i class="fas fa-pen"></i></button> @endcan
                                    @can('cars.destroy') <button class="btn-action btn-delete" onclick="openDeleteModal('{{ route('cars.destroy', $car->id) }}')"><i class="fas fa-trash"></i></button> @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination-wrapper"><span id="infoMobil">Menampilkan...</span><div class="pagination-controls"><button id="prevMobil"><</button><button id="nextMobil">></button></div></div>
            </div>
        </div>
    @endcan


    @can('brands.index')
        <div id="tabBrand" class="tab-pane {{ $activeTab == 'tabBrand' ? 'active' : '' }}">
            <div class="card-box">
                <div class="card-header">
                    <div class="header-left">
                        <div class="card-title"><i class="fas fa-copyright"></i> Data Brand (Merk)</div>
                    </div>
                    <div class="header-actions">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" id="searchBrand" placeholder="Cari brand...">
                        </div>

                        @can('brands.store')
                            <button class="btn-add" onclick="openModal('modalAddBrand')">
                                <i class="fas fa-plus"></i> Tambah Brand
                            </button>
                        @endcan
                    </div>
                </div>
                <div style="overflow-x:auto;">
                    <table class="custom-table" id="tableBrand">
                        <thead>
                            <tr>
                                <th>Nama Brand</th>
                                <th>Slug</th>
                                <th style="text-align:right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyBrand">
                            @foreach($brands as $brand)
                            <tr>
                                <td>{{ $brand->name }}</td>
                                <td>{{ $brand->slug }}</td>
                                <td style="text-align:right;">
                                    @can('brands.update')
                                        <button class="btn-action btn-edit" onclick="editBrand('{{ $brand->id }}', '{{ $brand->name }}')"><i class="fas fa-pen"></i></button>
                                    @endcan
                                    @can('brands.destroy')
                                        <button class="btn-action btn-delete" onclick="openDeleteModal('{{ route('brands.destroy', $brand->id) }}')"><i class="fas fa-trash"></i></button>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination-wrapper">
                    <span id="infoBrand">Menampilkan...</span>
                    <div class="pagination-controls">
                        <button id="prevBrand"><</button>
                        <button id="nextBrand">></button>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    @can('categories.index')
        <div id="tabKategori" class="tab-pane {{ $activeTab == 'tabKategori' ? 'active' : '' }}">
            <div class="card-box">
                <div class="card-header">
                    <div class="header-left">
                        <div class="card-title"><i class="fas fa-tags"></i> Kategori Mobil</div>
                    </div>
                    <div class="header-actions">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" id="searchKategori" placeholder="Cari kategori...">
                        </div>
                        @can('categories.store')
                            <button class="btn-add" onclick="openModal('modalAddCat')"><i class="fas fa-plus"></i> Baru</button>
                        @endcan
                    </div>
                </div>
                <div style="overflow-x:auto;">
                    <table class="custom-table" id="tableKategori">
                        <thead><tr><th>Nama Kategori</th><th>Jml Tipe</th><th style="text-align:right;">Aksi</th></tr></thead>
                        <tbody id="tbodyKategori">
                            @foreach($categories as $cat)
                            <tr>
                                <td>{{ $cat->name }}</td>
                                <td>{{ $cat->types_count }}</td>
                                <td style="text-align:right;">
                                    @can('categories.update')
                                        <button class="btn-action btn-edit" onclick="editCat('{{ $cat->id }}', '{{ $cat->name }}')"><i class="fas fa-pen"></i></button>
                                    @endcan
                                    @can('categories.destroy')
                                        <button class="btn-action btn-delete" onclick="openDeleteModal('{{ route('categories.destroy', $cat->id) }}')"><i class="fas fa-trash"></i></button>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination-wrapper">
                    <span id="infoKategori">Menampilkan...</span>
                    <div class="pagination-controls">
                        <button id="prevKategori"><</button>
                        <button id="nextKategori">></button>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    @can('types.index')
        <div id="tabType" class="tab-pane {{ $activeTab == 'tabType' ? 'active' : '' }}">
            <div class="card-box">
                <div class="card-header">
                    <div class="header-left">
                        <div class="card-title"><i class="fas fa-layer-group"></i> Tipe Mobil</div>
                    </div>
                    <div class="header-actions">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" id="searchType" placeholder="Cari tipe...">
                        </div>
                        @can('types.store')
                            <button class="btn-add" onclick="openModal('modalAddType')"><i class="fas fa-plus"></i> Baru</button>
                        @endcan
                    </div>
                </div>
                <div style="overflow-x:auto;">
                    <table class="custom-table" id="tableType">
                        <thead><tr><th>Nama Tipe</th><th>Induk Kategori</th><th style="text-align:right;">Aksi</th></tr></thead>
                        <tbody id="tbodyType">
                            @foreach($types as $type)
                            <tr>
                                <td>{{ $type->name }}</td>
                                <td><span style="background:#eee; padding:2px 6px; border-radius:4px; font-size:10px;">{{ $type->category->name ?? '-' }}</span></td>
                                <td style="text-align:right;">
                                    @can('types.update')
                                        <button class="btn-action btn-edit" onclick="editType('{{ $type->id }}', '{{ $type->name }}', '{{ $type->car_category_id }}')"><i class="fas fa-pen"></i></button>
                                    @endcan
                                    @can('types.destroy')
                                        <button class="btn-action btn-delete" onclick="openDeleteModal('{{ route('types.destroy', $type->id) }}')"><i class="fas fa-trash"></i></button>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination-wrapper">
                    <span id="infoType">Menampilkan...</span>
                    <div class="pagination-controls">
                        <button id="prevType"><</button>
                        <button id="nextType">></button>
                    </div>
                </div>
            </div>
        </div>
    @endcan

</main>

<div id="modalAddCar" class="modal-overlay">
    <div class="modal-box modal-lg">
        <form action="{{ route('cars.store') }}" method="POST" class="modal-form-flex" enctype="multipart/form-data">
            @csrf

            <div class="modal-header">
                <h3>Tambah Mobil Baru</h3>
                <button type="button" onclick="closeModal('modalAddCar')" style="background:none; border:none; font-size:24px; cursor:pointer;">&times;</button>
            </div>

            <div class="modal-body">
                <div class="form-grid">
                    <div>
                        <h4 style="margin-bottom:10px; border-bottom:1px solid #eee; padding-bottom:5px; color:var(--primary);">Data Utama</h4>

                        <label>Brand</label>
                        <select name="car_brand_id" class="form-select" required>
                            <option value="">Pilih Brand</option>
                            @foreach($brands as $b) <option value="{{ $b->id }}">{{ $b->name }}</option> @endforeach
                        </select>
                        <label>Nama Mobil</label><input type="text" name="name" class="form-input" required placeholder="Avanza">
                        <label>Model / Varian</label><input type="text" name="model" class="form-input" required placeholder="Veloz 1.5 Q CVT">

                        <label>Tipe Kategori</label>
                        <select name="car_type_id" class="form-select" required>
                            @foreach($typesGrouped as $catName => $listTypes)
                                <optgroup label="{{ $catName }}">
                                    @foreach($listTypes as $t) <option value="{{ $t->id }}">{{ $t->name }}</option> @endforeach
                                </optgroup>
                            @endforeach
                        </select>

                        <label>Plat Nomor</label><input type="text" name="license_plate" class="form-input" required placeholder="B 1234 XX">
                        <label>Tahun</label><input type="number" name="year" class="form-input" required value="2023">
                        <label>Harga Sewa (Rp)</label><input type="number" name="price_per_day" class="form-input" required>
                    </div>

                    <div>
                        <h4 style="margin-bottom:10px; border-bottom:1px solid #eee; padding-bottom:5px; color:var(--primary);">Spesifikasi</h4>

                        <label>Transmisi</label>
                        <select name="transmission" class="form-select" required>
                            <option value="Automatic">Automatic</option>
                            <option value="Manual">Manual</option>
                            <option value="CVT">Automatic (CVT)</option>
                        </select>

                        <label>Bahan Bakar</label>
                        <select name="fuel_type" class="form-select" required>
                            <option value="Bensin">Bensin</option>
                            <option value="Diesel">Diesel</option>
                            <option value="Listrik">Listrik</option>
                        </select>

                        <div class="form-grid" style="gap:10px;">
                            <div><label>Mesin</label><input type="text" name="engine_capacity" class="form-input" required placeholder="1500cc"></div>
                            <div><label>Tenaga</label><input type="text" name="horsepower" class="form-input" required placeholder="104 PS"></div>
                        </div>

                        <div class="form-grid" style="gap:10px;">
                            <div><label>Kursi</label><input type="number" name="seating_capacity" class="form-input" required value="5"></div>
                            <div><label>Bagasi</label><input type="number" name="luggage_capacity" class="form-input" required value="2"></div>
                        </div>

                        <label>Warna</label><input type="text" name="color" class="form-input" required placeholder="Putih">
                        <label>Konsumsi BBM</label><input type="text" name="fuel_consumption" class="form-input" required placeholder="10-12 km/l">
                    </div>
                </div>

                <div style="margin-top:20px;">
                    <label style="font-weight:600;">Foto Mobil</label>
                    <div style="background:#f9f9f9; padding:15px; border-radius:8px; border:1px dashed #ddd; text-align:center;">
                        <input type="file" name="image" class="form-input" required style="border:none; padding:0;">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="closeModal('modalAddCar')" style="padding:8px 20px; background:#eee; border:none; border-radius:6px; cursor:pointer; margin-right:10px;">Batal</button>
                <button type="submit" style="padding:8px 20px; background:var(--primary); border:none; border-radius:6px; font-weight:600; cursor:pointer;">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<div id="modalEditCar" class="modal-overlay">
    <div class="modal-box modal-lg">
        <form id="formEditCar" method="POST" enctype="multipart/form-data" class="modal-form-flex">
            @csrf @method('PUT')

            <div class="modal-header">
                <h3>Edit Data Mobil</h3>
                <button type="button" onclick="closeModal('modalEditCar')" style="background:none; border:none; font-size:24px; cursor:pointer;">&times;</button>
            </div>

            <div class="modal-body">
                <div class="form-grid">
                    <div>
                        <h4 style="margin-bottom:10px; border-bottom:1px solid #eee; padding-bottom:5px; color:var(--primary);">Data Utama</h4>

                        <label>Brand</label>
                        <select name="car_brand_id" id="e_brand" class="form-select" required>
                            @foreach($brands as $b) <option value="{{ $b->id }}">{{ $b->name }}</option> @endforeach
                        </select>
                        <label>Nama Mobil</label><input type="text" name="name" id="e_name" class="form-input" required>
                        <label>Model / Varian</label><input type="text" name="model" id="e_model" class="form-input" required>

                        <label>Tipe Kategori</label>
                        <select name="car_type_id" id="e_type" class="form-select" required>
                            @foreach($typesGrouped as $catName => $listTypes)
                                <optgroup label="{{ $catName }}">
                                    @foreach($listTypes as $t) <option value="{{ $t->id }}">{{ $t->name }}</option> @endforeach
                                </optgroup>
                            @endforeach
                        </select>

                        <label>Plat Nomor</label><input type="text" name="license_plate" id="e_plat" class="form-input" required>
                        <label>Tahun</label><input type="number" name="year" id="e_year" class="form-input" required>
                        <label>Harga Sewa (Rp)</label><input type="number" name="price_per_day" id="e_price" class="form-input" required>

                        <label>Status</label>
                        <select name="status" id="e_status" class="form-select">
                            <option value="available">Available</option>
                            <option value="rented">Rented</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>

                    <div>
                        <h4 style="margin-bottom:10px; border-bottom:1px solid #eee; padding-bottom:5px; color:var(--primary);">Spesifikasi</h4>

                        <label>Transmisi</label>
                        <select name="transmission" id="e_trans" class="form-select" required>
                            <option value="Automatic">Automatic</option>
                            <option value="Manual">Manual</option>
                            <option value="CVT">Automatic (CVT)</option>
                        </select>

                        <label>Bahan Bakar</label>
                        <select name="fuel_type" id="e_fuel" class="form-select" required>
                            <option value="Bensin">Bensin</option>
                            <option value="Diesel">Diesel</option>
                            <option value="Listrik">Listrik</option>
                        </select>

                        <div class="form-grid" style="gap:10px;">
                            <div><label>Mesin</label><input type="text" name="engine_capacity" id="e_engine" class="form-input" required></div>
                            <div><label>Tenaga</label><input type="text" name="horsepower" id="e_hp" class="form-input" required></div>
                        </div>

                        <div class="form-grid" style="gap:10px;">
                            <div><label>Kursi</label><input type="number" name="seating_capacity" id="e_seat" class="form-input" required></div>
                            <div><label>Bagasi</label><input type="number" name="luggage_capacity" id="e_luggage" class="form-input" required></div>
                        </div>

                        <label>Warna</label><input type="text" name="color" id="e_color" class="form-input" required>
                        <label>Konsumsi BBM</label><input type="text" name="fuel_consumption" id="e_consumption" class="form-input" required>
                    </div>
                </div>

                <div style="margin-top:20px;">
                    <label style="font-weight:600;">Ganti Foto (Opsional)</label>
                    <div style="background:#f9f9f9; padding:15px; border-radius:8px; border:1px dashed #ddd; text-align:center;">
                        <input type="file" name="image" class="form-input" style="border:none; padding:0;">
                        <small style="color:#888;">Biarkan kosong jika tidak ingin mengubah foto.</small>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="closeModal('modalEditCar')" style="padding:8px 20px; background:#eee; border:none; border-radius:6px; cursor:pointer; margin-right:10px;">Batal</button>
                <button type="submit" style="padding:8px 20px; background:var(--primary); border:none; border-radius:6px; font-weight:600; cursor:pointer;">Update Data</button>
            </div>
        </form>
    </div>
</div>

<div id="modalAddBrand" class="modal-overlay">
    <div class="modal-box">
        <form action="{{ route('brands.store') }}" method="POST" class="modal-form-flex">
            @csrf

            <div class="modal-header">
                <h3>Tambah Brand Baru</h3>
                <button type="button" onclick="closeModal('modalAddBrand')" style="background:none; border:none; font-size:24px; cursor:pointer;">&times;</button>
            </div>

            <div class="modal-body">
                <label>Nama Brand</label>
                <input type="text" name="name" class="form-input" required placeholder="Contoh: Toyota, Honda">
            </div>

            <div class="modal-footer">
                <button type="button" onclick="closeModal('modalAddBrand')" style="padding:8px 20px; background:#eee; border:none; border-radius:6px; cursor:pointer; margin-right:10px;">Batal</button>
                <button type="submit" style="padding:8px 20px; background:var(--primary); border:none; border-radius:6px; font-weight:600; cursor:pointer;">Simpan</button>
            </div>

        </form>
    </div>
</div>

<div id="modalEditBrand" class="modal-overlay"><div class="modal-box"><form id="formEditBrand" method="POST" class="modal-form-flex">@csrf @method('PUT')<div class="modal-header"><h3>Edit Brand</h3><button type="button" onclick="closeModal('modalEditBrand')" style="background:none;border:none;font-size:24px;">&times;</button></div><div class="modal-body"><label>Nama Brand</label><input type="text" name="name" id="edit_brand_name" class="form-input" required></div><div class="modal-footer"><button type="submit" style="padding:8px 20px; background:var(--primary); border:none; border-radius:6px;">Update</button></div></form></div></div>

<div id="modalAddCat" class="modal-overlay">
    <div class="modal-box">
        <form action="{{ route('categories.store') }}" method="POST" class="modal-form-flex">
            @csrf
            <div class="modal-header">
                <h3>Tambah Kategori</h3>
                <button type="button" onclick="closeModal('modalAddCat')" style="background:none; border:none; font-size:24px; cursor:pointer;">&times;</button>
            </div>
            <div class="modal-body">
                <label>Nama Kategori</label><input type="text" name="name" class="form-input" required placeholder="Contoh: Family Car">
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modalAddCat')" style="padding:8px 20px; background:#eee; border:none; border-radius:6px; cursor:pointer; margin-right:10px;">Batal</button>
                <button type="submit" style="padding:8px 20px; background:var(--primary); border:none; border-radius:6px; font-weight:600; cursor:pointer;">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="modalEditCat" class="modal-overlay">
    <div class="modal-box">
        <form id="formEditCat" method="POST" class="modal-form-flex">
            @csrf @method('PUT')
            <div class="modal-header">
                <h3>Edit Kategori</h3>
                <button type="button" onclick="closeModal('modalEditCat')" style="background:none; border:none; font-size:24px; cursor:pointer;">&times;</button>
            </div>
            <div class="modal-body">
                <label>Nama Kategori</label><input type="text" name="name" id="edit_cat_name" class="form-input" required>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modalEditCat')" style="padding:8px 20px; background:#eee; border:none; border-radius:6px; cursor:pointer; margin-right:10px;">Batal</button>
                <button type="submit" style="padding:8px 20px; background:var(--primary); border:none; border-radius:6px; font-weight:600; cursor:pointer;">Update</button>
            </div>
        </form>
    </div>
</div>

<div id="modalAddType" class="modal-overlay">
    <div class="modal-box">
        <form action="{{ route('types.store') }}" method="POST" class="modal-form-flex">
            @csrf
            <div class="modal-header">
                <h3>Tambah Tipe Mobil</h3>
                <button type="button" onclick="closeModal('modalAddType')" style="background:none; border:none; font-size:24px; cursor:pointer;">&times;</button>
            </div>
            <div class="modal-body">
                <label>Induk Kategori</label>
                <select name="car_category_id" class="form-select">
                    @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endforeach
                </select>
                <label>Nama Tipe</label><input type="text" name="name" class="form-input" required placeholder="Contoh: Low MPV">
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modalAddType')" style="padding:8px 20px; background:#eee; border:none; border-radius:6px; cursor:pointer; margin-right:10px;">Batal</button>
                <button type="submit" style="padding:8px 20px; background:var(--primary); border:none; border-radius:6px; font-weight:600; cursor:pointer;">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="modalEditType" class="modal-overlay">
    <div class="modal-box">
        <form id="formEditType" method="POST" class="modal-form-flex">
            @csrf @method('PUT')
            <div class="modal-header">
                <h3>Edit Tipe Mobil</h3>
                <button type="button" onclick="closeModal('modalEditType')" style="background:none; border:none; font-size:24px; cursor:pointer;">&times;</button>
            </div>
            <div class="modal-body">
                <label>Induk Kategori</label>
                <select name="car_category_id" id="edit_type_cat" class="form-select">
                    @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endforeach
                </select>
                <label>Nama Tipe</label><input type="text" name="name" id="edit_type_name" class="form-input" required>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modalEditType')" style="padding:8px 20px; background:#eee; border:none; border-radius:6px; cursor:pointer; margin-right:10px;">Batal</button>
                <button type="submit" style="padding:8px 20px; background:var(--primary); border:none; border-radius:6px; font-weight:600; cursor:pointer;">Update</button>
            </div>
        </form>
    </div>
</div>

<div id="modalDelete" class="modal-overlay">
    <div class="modal-box" style="max-width: 400px;">
        <form id="formDelete" method="POST" class="modal-form-flex" style="height: auto;">
            @csrf @method('DELETE')
            <div class="modal-header" style="background:#FFE3E3;">
                <h3 style="color:#E03131;"><i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus</h3>
                <button type="button" onclick="closeModal('modalDelete')" style="background:none; border:none; font-size:24px; cursor:pointer;">&times;</button>
            </div>
            <div class="modal-body" style="text-align:center; padding: 30px 25px;">
                <p style="font-size:15px; color:#555;">Apakah Anda yakin ingin menghapus data ini?<br><strong style="color:#E03131;">Tindakan ini tidak dapat dibatalkan.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modalDelete')" style="padding:8px 20px; background:#eee; border:none; border-radius:6px; cursor:pointer; margin-right:10px;">Batal</button>
                <button type="submit" style="padding:8px 20px; background:#E03131; color:white; border:none; border-radius:6px; font-weight:600; cursor:pointer;">Ya, Hapus</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('script')
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('active');
        document.querySelector('.overlay').classList.toggle('active');
    }

    // Tab Switching
    function openTab(tabId) {
        document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
        document.getElementById(tabId).classList.add('active');
        event.currentTarget.classList.add('active');
    }

    // Modal Control
    function openModal(id) { document.getElementById(id).classList.add('show'); }
    function closeModal(id) { document.getElementById(id).classList.remove('show'); }

    // DELETE MODAL Logic
    function openDeleteModal(url) {
        document.getElementById('formDelete').action = url;
        openModal('modalDelete');
    }

    window.onclick = function(e) { if(e.target.classList.contains('modal-overlay')) e.target.classList.remove('show'); }

    // Edit Helpers - MENGISI OTOMATIS FORM EDIT
    function editCar(car) {
        document.getElementById('formEditCar').action = '/cars/' + car.id;

        // Data Utama
        document.getElementById('e_brand').value = car.car_brand_id;
        document.getElementById('e_name').value = car.name;
        document.getElementById('e_model').value = car.model; // <--- FIX MODEL
        document.getElementById('e_type').value = car.car_type_id;
        document.getElementById('e_plat').value = car.license_plate;
        document.getElementById('e_year').value = car.year;
        document.getElementById('e_price').value = car.price_per_day;
        document.getElementById('e_status').value = car.status;

        // Data Spesifikasi
        document.getElementById('e_trans').value = car.transmission;
        document.getElementById('e_fuel').value = car.fuel_type;
        document.getElementById('e_engine').value = car.engine_capacity;
        document.getElementById('e_hp').value = car.horsepower;
        document.getElementById('e_seat').value = car.seating_capacity;
        document.getElementById('e_luggage').value = car.luggage_capacity;
        document.getElementById('e_color').value = car.color;
        document.getElementById('e_consumption').value = car.fuel_consumption;

        openModal('modalEditCar');
    }

    function editBrand(id, name) {
        var form = document.getElementById('formEditBrand');
        if (form) {
            form.action = '/brands/' + id;
        } else {
            console.error("Form 'formEditBrand' tidak ditemukan!");
        }

        var inputName = document.getElementById('edit_brand_name');
        if (inputName) {
            inputName.value = name;
        }

        openModal('modalEditBrand');
    }

    function editCat(id, name) {
        document.getElementById('formEditCat').action = '/categories/' + id;
        document.getElementById('edit_cat_name').value = name;
        openModal('modalEditCat');
    }

    function editType(id, name, catId) {
        document.getElementById('formEditType').action = '/types/' + id;
        document.getElementById('edit_type_name').value = name;
        document.getElementById('edit_type_cat').value = catId;
        openModal('modalEditType');
    }

    // ==============================================================
    // CLASS: TABLE MANAGER (Search + Pagination via JS)
    // ==============================================================
    class TableManager {
        constructor(tbodyId, searchId, infoId, prevId, nextId, rowsPerPage = 10) {
            this.tbody = document.getElementById(tbodyId);
            this.searchInput = document.getElementById(searchId);
            this.infoSpan = document.getElementById(infoId);
            this.prevBtn = document.getElementById(prevId);
            this.nextBtn = document.getElementById(nextId);

            this.rowsPerPage = rowsPerPage;
            this.currentPage = 1;
            this.allRows = Array.from(this.tbody.querySelectorAll('tr'));
            this.filteredRows = this.allRows;

            this.init();
        }

        init() {
            if(this.searchInput){
                this.searchInput.addEventListener('keyup', (e) => {
                    const term = e.target.value.toLowerCase();
                    this.filteredRows = this.allRows.filter(row => row.innerText.toLowerCase().includes(term));
                    this.currentPage = 1;
                    this.render();
                });
            }
            if(this.prevBtn) {
                this.prevBtn.addEventListener('click', () => { if (this.currentPage > 1) { this.currentPage--; this.render(); } });
            }
            if(this.nextBtn) {
                this.nextBtn.addEventListener('click', () => {
                    const maxPage = Math.ceil(this.filteredRows.length / this.rowsPerPage);
                    if (this.currentPage < maxPage) { this.currentPage++; this.render(); }
                });
            }
            this.render();
        }

        render() {
            this.allRows.forEach(row => row.style.display = 'none');
            const total = this.filteredRows.length;
            const start = (this.currentPage - 1) * this.rowsPerPage;
            const end = start + this.rowsPerPage;
            this.filteredRows.slice(start, end).forEach(row => row.style.display = '');

            if(this.infoSpan) {
                if(total === 0) { this.infoSpan.innerText = "Tidak ada data."; }
                else {
                    const showEnd = end > total ? total : end;
                    this.infoSpan.innerText = `Menampilkan ${start + 1}-${showEnd} dari ${total} data`;
                }
            }
            const maxPage = Math.ceil(total / this.rowsPerPage);
            if(this.prevBtn) this.prevBtn.disabled = this.currentPage === 1;
            if(this.nextBtn) this.nextBtn.disabled = this.currentPage >= maxPage || total === 0;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        new TableManager('tbodyMobil', 'searchMobil', 'infoMobil', 'prevMobil', 'nextMobil', 10);
        new TableManager('tbodyKategori', 'searchKategori', 'infoKategori', 'prevKategori', 'nextKategori', 10);
        new TableManager('tbodyType', 'searchType', 'infoType', 'prevType', 'nextType', 10);
        new TableManager('tbodyBrand', 'searchBrand', 'infoBrand', 'prevBrand', 'nextBrand', 10);
    });

</script>
@endsection
