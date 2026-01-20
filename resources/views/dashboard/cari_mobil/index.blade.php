@extends('dashboard.layouts.index')
@section('title', 'Cari Mobil')
@section('style')
    <style>
        :root { --primary: #FFC400; --primary-hover: #e0ac00; --text-dark: #333; --bg-light: #f4f6f9; --white: #fff; --sidebar-width: 280px; }
        .main-content { flex: 1; margin-left: var(--sidebar-width); padding: 30px 40px; font-family: 'Poppins', sans-serif; }


        .mobile-header { display: none; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .hamburger { font-size: 1.5rem; cursor: pointer; color: var(--text-dark); }

        /* Filter */
        .filter-card { background: var(--white); padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); margin-bottom: 30px; border: 1px solid #f0f0f0; }
        .filter-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
        .form-group label { display: block; font-size: 0.8rem; color: #888; margin-bottom: 8px; }
        .form-control { width: 100%; padding: 10px 15px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 0.9rem; outline: none; }
        .btn-search { background: var(--primary); color: #000; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; height: 100%; width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; transition: 0.3s; }
        .btn-search:hover { background: var(--primary-hover); }

        /* Grid Mobil */
        .cars-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px; }
        .car-card { background: var(--white); border-radius: 24px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.08); overflow: hidden; display: flex; flex-direction: column; transition: 0.3s; position: relative; }
        .car-card:hover { transform: translateY(-5px); }

        .car-img-area { width: 100%; height: 200px; overflow: hidden; background: #eee; }
        .car-img-area img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
        .car-card:hover .car-img-area img { transform: scale(1.05); }

        .car-info { padding: 20px; flex: 1; display: flex; flex-direction: column; }
        .car-name { font-size: 1.2rem; font-weight: 700; margin-bottom: 5px; color: #000; }
        .car-desc { font-size: 0.9rem; color: #888; margin-bottom: 15px; }

        .car-specs { display: flex; gap: 15px; margin-bottom: 20px; font-size: 0.85rem; color: #555; }
        .spec-item i { color: #888; margin-right: 5px; }

        .price-value { font-size: 1.3rem; font-weight: 700; color: var(--primary); }
        .price-unit { font-size: 0.8rem; color: #aaa; font-weight: 400; }

        .card-actions { display: flex; gap: 10px; margin-top: auto; }
        .btn-card { flex: 1; padding: 10px 0; border-radius: 8px; font-weight: 600; cursor: pointer; text-align: center; text-decoration: none; font-size: 0.9rem; display: inline-block; }
        .btn-outline { background: #fff; border: 2px solid var(--primary); color: #333; }
        .btn-fill { background: var(--primary); border: 2px solid var(--primary); color: #000; }
        .btn-fill:hover { background: var(--primary-hover); }

        .badge-cat { position: absolute; top: 15px; right: 15px; background: rgba(0,0,0,0.6); color: #fff; padding: 4px 12px; border-radius: 20px; font-size: 0.7rem; }

        @media (max-width: 992px) { .main-content { margin-left: 0; padding: 20px; } .mobile-header { display: flex; } .filter-grid { grid-template-columns: 1fr; } }
    </style>
@endsection

@section('content')
    <main class="main-content">
        <div class="mobile-header">
            <a href="/" style="color:var(--text-dark); font-weight:700; text-decoration:none;">Bonanza Rental</a>
            <div class="hamburger" onclick="toggleSidebar()"><i class="fas fa-bars"></i></div>
        </div>
        <h1 class="page-title" style="margin-bottom:20px; font-size:24px; font-weight:700;">Cari Mobil</h1>

        <div class="filter-card">
            <form action="{{ route('cari_mobil.index') }}" method="GET">
                <div class="filter-grid">
                    <div class="form-group">
                        <label>Kategori Mobil</label>
                        <select name="category_id" class="form-control">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- PERBAIKAN: Default Value Hari Ini --}}
                    <div class="form-group">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" class="form-control"
                               value="{{ request('start_date', date('Y-m-d')) }}"
                               min="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Selesai</label>
                        {{-- PERBAIKAN: Default Value Hari Ini + 1 Hari (strtotime '+1 day') --}}
                        <input type="date" name="end_date" id="end_date" class="form-control"
                               value="{{ request('end_date', date('Y-m-d', strtotime('+1 day'))) }}"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                    </div>
                    {{-- END PERBAIKAN --}}

                    <div class="form-group">
                        <label style="visibility: hidden;">Action</label>
                        <button type="submit" class="btn-search" style="height: 45px;"><i class="fas fa-search"></i> Cari Ketersediaan</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="results-header" style="margin-bottom:20px;">
            <h3>Hasil Pencarian</h3>
            <p style="color:#666; font-size:14px;">Menampilkan {{ $cars->count() }} mobil tersedia</p>
        </div>

        <div class="cars-grid">
            @forelse($cars as $car)
                <div class="car-card">
                    <span class="badge-cat">{{ $car->category->name ?? 'Umum' }}</span>
                    <div class="car-img-area">
                        @php
                            $img = is_array($car->images) ? ($car->images[0] ?? null) : $car->images;
                            $imgUrl = $img ? asset('storage/'.$img) : asset('img/no-image.jpg');
                        @endphp
                        <img src="{{ $imgUrl }}" alt="{{ $car->name }}">
                    </div>
                    <div class="car-info">
                        <h3 class="car-name">{{ $car->brand->name ?? '' }} {{ $car->name }}</h3>
                        <p class="car-desc">{{ $car->year }} • {{ $car->transmission }}</p>

                        <div class="car-specs">
                            <div class="spec-item"><i class="fas fa-user-friends"></i> {{ $car->seating_capacity }}</div>
                            <div class="spec-item"><i class="fas fa-gas-pump"></i> {{ $car->fuel_type }}</div>
                            <div class="spec-item"><i class="fas fa-star" style="color:orange;"></i> {{ $car->rating }}</div>
                        </div>

                        <div style="margin-bottom:15px;">
                            <span style="font-size:0.8rem; color:#888;">Harga Sewa:</span><br>
                            <span class="price-value">Rp {{ number_format($car->price_per_day, 0, ',', '.') }} <span class="price-unit">/hari</span></span>
                        </div>

                        <hr style="border:0; border-top:1px solid #eee; margin:10px 0 20px 0;">

                        <div class="card-actions">
                            <a href="#" class="btn-card btn-outline">Detail</a>
                            {{-- PERBAIKAN: Link Booking juga otomatis bawa tanggal Start & End (+1 hari) --}}
                            <a href="{{ route('booking.index', $car->id) }}?start={{ request('start_date', date('Y-m-d')) }}&end={{ request('end_date', date('Y-m-d', strtotime('+1 day'))) }}" class="btn-card btn-fill">Booking</a>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1/-1; text-align:center; padding:50px;">
                    <i class="fas fa-car-crash" style="font-size:40px; color:#ccc; margin-bottom:10px;"></i>
                    <p style="color:#666;">Tidak ada mobil yang tersedia pada tanggal tersebut.</p>
                </div>
            @endforelse
        </div>
    </main>

    {{-- Script Tambahan: Update otomatis End Date jika Start Date berubah --}}
    <script>
        document.getElementById('start_date').addEventListener('change', function() {
            var startDate = new Date(this.value);
            if (!isNaN(startDate.getTime())) {
                // Tambah 1 hari
                startDate.setDate(startDate.getDate() + 1);

                // Format ke YYYY-MM-DD
                var day = ("0" + startDate.getDate()).slice(-2);
                var month = ("0" + (startDate.getMonth() + 1)).slice(-2);
                var nextDay = startDate.getFullYear() + "-" + month + "-" + day;

                // Set value dan min attribute untuk end_date
                var endDateInput = document.getElementById('end_date');
                endDateInput.value = nextDay;
                endDateInput.min = nextDay;
            }
        });
    </script>
@endsection
