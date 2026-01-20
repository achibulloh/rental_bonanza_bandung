@extends('index.layouts.index')
@section('title', 'Semua Mobil - Rental Mobil')
@section('style')
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--bg-page); color: var(--text-dark); }

        .search-filter-section { background: var(--white); padding: 20px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); margin-top: 30px; border: 1px solid #eee; }

        .search-row { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 15px; margin-bottom: 20px; }
        .search-input { position: relative; }
        .search-input i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #aaa; }
        .search-input input { width: 100%; padding: 12px 15px 12px 45px; border: 1px solid #ddd; border-radius: 8px; outline: none; height: 45px; }
        .search-input input[type="date"] { padding-left: 15px; color: #555; }
        .input-label { font-size: 0.75rem; color: #888; margin-bottom: 3px; display: block; margin-left: 5px; }

        .category-tags { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 10px; }
        .tag { padding: 8px 20px; border-radius: 20px; font-size: 0.85rem; border: 1px solid #ddd; background: #fff; cursor: pointer; transition: 0.3s; text-decoration: none; color: #555; }
        .tag:hover { border-color: var(--primary); color: #000; }
        .tag.active { background: var(--primary); border-color: var(--primary); color: #000; font-weight: 600; }

        .result-count { font-size: 0.9rem; color: #666; margin: 20px 0; }

        /* GRID MOBIL */
        .car-grid-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px; margin-bottom: 60px; }
        .car-card { background: #fff; border-radius: 12px; overflow: hidden; border: 1px solid #eee; transition: 0.3s; position: relative; }
        .car-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.05); }

        .card-badge { position: absolute; top: 15px; right: 15px; padding: 4px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 700; z-index: 2; }
        .badge-yellow { background: var(--primary); color: #000; }
        .badge-black { background: #000; color: #fff; }

        .car-img-box { height: 180px; width: 100%; position: relative; background: #f4f4f4; }
        .car-img-box img { width: 100%; height: 100%; object-fit: cover; }
        .not-available-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); display: flex; align-items: center; justify-content: center; z-index: 3; }
        .na-tag { background: #ff3b3b; color: white; padding: 5px 15px; border-radius: 4px; font-weight: 600; font-size: 0.9rem; }

        .car-info { padding: 20px; }
        .car-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 5px; }
        .car-title { font-size: 1.1rem; font-weight: 700; color: #333; }
        .rating { color: orange; font-size: 0.85rem; font-weight: 600; }
        .car-type { color: #888; font-size: 0.8rem; margin-bottom: 15px; display: block; }

        .car-specs { display: flex; justify-content: space-between; font-size: 0.75rem; color: #666; margin-bottom: 20px; border-top: 1px solid #f0f0f0; border-bottom: 1px solid #f0f0f0; padding: 10px 0; }
        .spec-item i { color: #aaa; margin-right: 4px; }

        .price-tag { font-size: 1.1rem; font-weight: 700; color: #333; margin-bottom: 15px; }
        .price-tag span { font-size: 0.8rem; color: #888; font-weight: 400; }

        .card-btns { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .btn-card { padding: 10px; border-radius: 6px; font-weight: 600; font-size: 0.9rem; border: none; cursor: pointer; display: flex; justify-content: center; align-items: center; gap: 5px; transition: 0.3s; text-decoration: none; }
        .btn-detail { background: transparent; border: 1px solid var(--primary); color: #333; }
        .btn-detail:hover { background: #fffcf0; }
        .btn-sewa { background: var(--primary); color: #000; border: 1px solid var(--primary); }
        .btn-sewa:hover { background: #e6b000; }
        .card-disabled { opacity: 0.8; }
        .card-disabled .btn-sewa { background: #ccc; border-color: #ccc; cursor: not-allowed; pointer-events: none; }

        @media (max-width: 768px) { .search-row { grid-template-columns: 1fr; } }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="page-header">
            <h1>Daftar Mobil Rental</h1>
            <p>Temukan mobil terbaik untuk perjalanan Anda dengan harga kompetitif.</p>

            <form action="{{ route('index_mobil') }}" method="GET" class="search-filter-section">
                <div class="search-row">
                    <div>
                        <span class="input-label" style="display: none;">Cari Mobil</span>
                        <div class="search-input">
                            <i class="fas fa-search"></i>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama mobil atau merk...">
                        </div>
                    </div>
                    <div>
                        <span class="input-label">Mulai Sewa</span>
                        <div class="search-input">
                            <input type="date" name="start_date" value="{{ $startDate }}">
                        </div>
                    </div>
                    <div>
                        <span class="input-label">Selesai Sewa</span>
                        <div class="search-input">
                            <input type="date" name="end_date" value="{{ $endDate }}">
                        </div>
                    </div>
                </div>
                <button type="submit" style="display:none;">Cari</button>

                <div class="category-tags">
                    @php
                        function getFilterUrl($cat) {
                            $params = request()->all();
                            $params['category'] = $cat;
                            return route('index_mobil', $params);
                        }
                        $currentCat = request('category', 'Semua');
                    @endphp

                    {{-- Tombol Semua --}}
                    <a href="{{ getFilterUrl('Semua') }}" class="tag {{ $currentCat == 'Semua' ? 'active' : '' }}">Semua</a>

                    {{-- Tombol Dinamis dari Tabel car_types --}}
                    @foreach($types as $type)
                        <a href="{{ getFilterUrl($type->name) }}"
                           class="tag {{ $currentCat == $type->name ? 'active' : '' }}">
                           {{ $type->name }}
                        </a>
                    @endforeach
                </div>
            </form>
        </div>

        <p class="result-count">Menampilkan <strong>{{ $cars->count() }}</strong> mobil</p>

        <div class="car-grid-container">
            @forelse($cars as $car)
                <div class="car-card {{ !$car->is_available ? 'card-disabled' : '' }}">
                    @if($car->is_available)
                        <span class="card-badge badge-yellow">Tersedia</span>
                    @else
                        <span class="card-badge badge-black">Booked</span>
                    @endif

                    <div class="car-img-box">
                        @if(!$car->is_available)
                            <div class="not-available-overlay"><span class="na-tag">Tidak Tersedia</span></div>
                        @endif

                        @php
                            $img = 'img/no-image.jpg';
                            if(!empty($car->images)) {
                                $raw = $car->images;
                                $decoded = is_array($raw) ? $raw : json_decode($raw, true);
                                if(is_array($decoded) && count($decoded) > 0) $img = 'storage/' . $decoded[0];
                            }
                        @endphp
                        <img src="{{ asset($img) }}" alt="{{ $car->name }}">
                    </div>

                    <div class="car-info">
                        <div class="car-header">
                            <h3 class="car-title">{{ $car->brand->name ?? '' }} {{ $car->name }}</h3>
                            <div class="rating"><i class="fas fa-star"></i> 5.0</div>
                        </div>

                        {{-- PERBAIKAN: Mengambil nama dari relasi type --}}
                        <span class="car-type">
                            {{ $car->type->name ?? 'General' }}
                        </span>

                        <div class="car-specs">
                            <div class="spec-item"><i class="fas fa-user-friends"></i> {{ $car->seating_capacity }}</div>
                            <div class="spec-item"><i class="fas fa-cogs"></i> {{ $car->transmission }}</div>
                            <div class="spec-item"><i class="fas fa-gas-pump"></i> {{ $car->fuel_type ?? 'Bensin' }}</div>
                        </div>

                        @php $minPrice = $car->packages->min('price'); @endphp
                        <div class="price-tag">Rp {{ number_format($minPrice ?? 0, 0, ',', '.') }} <span>/hari</span></div>

                        <div class="card-btns">
                            <a href="{{ route('booking.index', $car->id) }}" class="btn-card btn-detail">
                                <i class="far fa-eye"></i> Detail
                            </a>
                            @if($car->is_available)
                                <a href="{{ route('booking.index', $car->id) }}" class="btn-card btn-sewa">
                                    <i class="fas fa-shopping-cart"></i> Sewa
                                </a>
                            @else
                                <button class="btn-card btn-sewa" disabled><i class="fas fa-times"></i> Full</button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 50px;">
                    <h3>Tidak ada mobil ditemukan</h3>
                    <p>Coba ubah filter atau tanggal pencarian Anda.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
