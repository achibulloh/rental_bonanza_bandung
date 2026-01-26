@extends('dashboard.layouts.index')
@section('title', $mobil->brand->name . ' ' . $mobil->model)

@section('style')
<style>
    :root { --primary: #FFC400; --text-dark: #111; --text-gray: #666; --border-color: #eee; --radius: 12px; }

    .back-link { display: inline-flex; align-items: center; gap: 8px; color: var(--text-gray); text-decoration: none; font-size: 0.95rem; margin-bottom: 25px; transition: color 0.2s; }
    .back-link:hover { color: var(--primary); }

    /* Layout */
    .top-section { display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 30px; margin-bottom: 40px; }

    /* Galeri */
    .gallery-container { display: flex; flex-direction: column; gap: 15px; }
    .main-image-frame { width: 100%; height: 350px; background-color: #f8f9fa; border-radius: var(--radius); overflow: hidden; border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; }
    .main-image-frame img { width: 100%; height: 100%; object-fit: cover; }
    .thumbnails-row { display: flex; gap: 15px; overflow-x: auto; padding-bottom: 5px; }
    .thumb-box { width: 100px; height: 70px; border-radius: 8px; border: 2px solid transparent; cursor: pointer; overflow: hidden; transition: all 0.2s; background: #fff; flex-shrink: 0; }
    .thumb-box img { width: 100%; height: 100%; object-fit: cover; }
    .thumb-box.active { border-color: var(--primary); }
    .thumb-box:hover { opacity: 0.8; }

    /* Info Card */
    .car-info-card { background: #fff; border: 1px solid var(--border-color); border-radius: var(--radius); padding: 30px; }
    .car-title { font-size: 2rem; font-weight: 700; color: var(--text-dark); margin: 0; }
    .car-subtitle { color: var(--text-gray); font-size: 1rem; margin-top: 5px; }
    .rating-badge { display: inline-flex; align-items: center; gap: 5px; background: #fff8e1; padding: 5px 12px; border-radius: 20px; font-weight: 600; font-size: 0.9rem; color: var(--text-dark); margin: 15px 0 25px 0; }
    .rating-badge i { color: #f59e0b; }

    .specs-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; padding-bottom: 30px; border-bottom: 1px solid #eee; }
    .spec-item { display: flex; align-items: flex-start; gap: 12px; }
    .spec-icon { width: 40px; height: 40px; background: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--text-dark); font-size: 1.1rem; }
    .spec-text span { display: block; font-size: 0.75rem; color: var(--text-gray); }
    .spec-text strong { font-size: 0.95rem; color: var(--text-dark); }
    .car-desc { font-size: 0.95rem; color: #555; line-height: 1.6; }

    /* Packages */
    .section-title { font-size: 1.5rem; font-weight: 700; color: var(--text-dark); margin-bottom: 20px; }
    .packages-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin-bottom: 30px; }
    .package-card { background: #fff; border: 1px solid var(--border-color); border-radius: var(--radius); padding: 20px; cursor: pointer; transition: all 0.2s; position: relative; }
    .package-card:hover { border-color: #ddd; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
    .package-card.active { border-color: var(--primary); background-color: #fffbf0; box-shadow: 0 0 0 1px var(--primary); }
    .pkg-name { font-weight: 700; font-size: 1rem; color: var(--text-dark); margin-bottom: 8px; }
    .pkg-price { font-size: 1.1rem; color: #d97706; font-weight: 700; margin-bottom: 0; }
    .pkg-unit { font-size: 0.8rem; color: var(--text-gray); font-weight: 400; }

    /* Selected Detail */
    .selected-package-box { background: #fff; border: 1px solid var(--border-color); border-radius: var(--radius); padding: 30px; }
    .detail-pkg-title { font-size: 1.25rem; font-weight: 700; margin-bottom: 10px; }
    .detail-pkg-desc { color: var(--text-gray); margin-bottom: 25px; }
    .features-list { margin-bottom: 30px; }
    .feature-item { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; font-size: 0.95rem; color: var(--text-dark); }
    .feature-item i { color: #f59e0b; font-size: 0.9rem; }

    .btn-booking { width: 100%; background: var(--primary); color: #000; border: none; padding: 18px; border-radius: 10px; font-weight: 700; font-size: 1.1rem; cursor: pointer; transition: 0.2s; }
    .btn-booking:hover { background: #e0ac00; }

    @media (max-width: 992px) {
        .top-section { grid-template-columns: 1fr; }
        .main-image-frame { height: 250px; }
    }
</style>
@endsection

@section('content')

    {{-- TOMBOL KEMBALI --}}
    <a href="{{ url('/cari-mobil') }}" class="back-link">
        <i class="fas fa-chevron-left"></i> Kembali ke Pencarian
    </a>

    {{-- DETAIL UTAMA --}}
    <div class="top-section">

        {{-- GALERI --}}
        <div class="gallery-container">
            <div class="main-image-frame">
                @php
                    $images = $mobil->images ?? [];
                    $firstImage = !empty($images) ? asset('storage/'.$images[0]) : 'https://via.placeholder.com/600x400?text=No+Image';
                @endphp
                <img id="mainImage" src="{{ $firstImage }}" alt="{{ $mobil->name }}">
            </div>
            <div class="thumbnails-row">
                @if(!empty($images))
                    @foreach($images as $index => $img)
                        <div class="thumb-box {{ $index == 0 ? 'active' : '' }}"
                             onclick="changeImage(this, '{{ asset('storage/'.$img) }}')">
                            <img src="{{ asset('storage/'.$img) }}" alt="Thumb">
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- INFO MOBIL --}}
        <div class="car-info-card">
            <h1 class="car-title">{{ $mobil->brand->name }} {{ $mobil->model }}</h1>
            <p class="car-subtitle">{{ $mobil->year }} • {{ $mobil->type->name }}</p>

            <div class="rating-badge">
                <i class="fas fa-star"></i> {{ $mobil->rating }} Rating
            </div>

            <div class="specs-grid">
                <div class="spec-item">
                    <div class="spec-icon"><i class="fas fa-user-group"></i></div>
                    <div class="spec-text"><span>Kapasitas</span><strong>{{ $mobil->seating_capacity }} Orang</strong></div>
                </div>
                <div class="spec-item">
                    <div class="spec-icon"><i class="fas fa-gears"></i></div>
                    <div class="spec-text"><span>Transmisi</span><strong>{{ $mobil->transmission }}</strong></div>
                </div>
                <div class="spec-item">
                    <div class="spec-icon"><i class="fas fa-gas-pump"></i></div>
                    <div class="spec-text"><span>Bahan Bakar</span><strong>{{ $mobil->fuel_type }}</strong></div>
                </div>
                <div class="spec-item">
                    <div class="spec-icon"><i class="fas fa-tachometer-alt"></i></div>
                    <div class="spec-text"><span>Konsumsi</span><strong>{{ $mobil->fuel_consumption }}</strong></div>
                </div>
            </div>

            <div class="car-desc">
                {{ $mobil->description ?? 'Tidak ada deskripsi tersedia.' }}
            </div>
        </div>
    </div>

    {{-- PAKET SEWA (DARI TABEL car_packages) --}}
    <h3 class="section-title">Pilih Paket Sewa</h3>

    @if($mobil->packages->count() > 0)
        <div class="packages-grid">
            @foreach($mobil->packages as $index => $pkg)
                {{--
                   TRIK: Karena tabel car_packages cuma punya 'description' (text),
                   Kita pecah text description berdasarkan tanda koma (,) agar bisa jadi list fitur di JS
                --}}
                @php
                    $featuresArray = explode(',', $pkg->description);
                @endphp

                <div class="package-card {{ $index == 0 ? 'active' : '' }}" onclick="selectPackage(this)"
                     data-id="{{ $pkg->id }}"
                     data-title="{{ $pkg->name }}"
                     data-desc="{{ $pkg->description }}"
                     data-price="{{ $pkg->price }}"
                     {{-- Kirim array hasil explode ke JS --}}
                     data-features="{{ json_encode($featuresArray) }}">

                    <div class="pkg-name">{{ $pkg->name }}</div>
                    <div class="pkg-price">
                        Rp {{ number_format($pkg->price, 0, ',', '.') }}
                        <span class="pkg-unit">/hari</span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- DETAIL PAKET TERPILIH --}}
        @php
            $firstPkg = $mobil->packages->first();
            $firstFeatures = explode(',', $firstPkg->description);
        @endphp

        <div class="selected-package-box">
            <h4 class="detail-pkg-title" id="displayTitle">{{ $firstPkg->name }}</h4>

            <p style="margin-bottom: 10px; color: var(--text-gray);">Keterangan Paket:</p>
            <div class="features-list" id="displayFeatures">
                @foreach($firstFeatures as $feature)
                    <div class="feature-item"><i class="fas fa-check"></i> {{ trim($feature) }}</div>
                @endforeach
            </div>

            {{-- FORM BOOKING --}}
            <form action="{{ route('booking.index', $mobil->id) }}" method="GET">
                <input type="hidden" name="car_id" value="{{ $mobil->id }}">
                <input type="hidden" name="package_id" id="inputPackageId" value="{{ $firstPkg->id }}">
                <input type="hidden" name="price" id="inputPrice" value="{{ $firstPkg->price }}">

                <button type="submit" class="btn-booking" id="btnBooking">
                    Ajukan Booking - Rp {{ number_format($firstPkg->price, 0, ',', '.') }}
                </button>
            </form>
        </div>
    @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i> Belum ada paket sewa tersedia.
        </div>
    @endif

@endsection

@section('script')
<script>
    // 1. GANTI GAMBAR
    function changeImage(element, src) {
        document.getElementById('mainImage').src = src;
        document.querySelectorAll('.thumb-box').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
    }

    // 2. PILIH PAKET
    function selectPackage(element) {
        // Highlight
        document.querySelectorAll('.package-card').forEach(el => el.classList.remove('active'));
        element.classList.add('active');

        // Ambil Data
        const id = element.getAttribute('data-id');
        const title = element.getAttribute('data-title');
        const price = parseInt(element.getAttribute('data-price'));

        // Ambil features (hasil explode PHP tadi)
        let features = [];
        try {
            features = JSON.parse(element.getAttribute('data-features'));
        } catch(e) { console.log('Error parse features'); }

        // Update UI
        document.getElementById('displayTitle').innerText = title;

        // Update Fitur List
        const featuresContainer = document.getElementById('displayFeatures');
        featuresContainer.innerHTML = '';
        if(Array.isArray(features)) {
            features.forEach(feature => {
                // Trim spasi berlebih
                feature = feature.trim();
                if(feature) {
                    featuresContainer.innerHTML += `<div class="feature-item"><i class="fas fa-check"></i> ${feature}</div>`;
                }
            });
        }

        // Update Input Form
        document.getElementById('inputPackageId').value = id;
        document.getElementById('inputPrice').value = price;

        // Update Tombol
        const formattedPrice = new Intl.NumberFormat('id-ID').format(price);
        document.getElementById('btnBooking').innerText = `Ajukan Booking - Rp ${formattedPrice}`;
    }
</script>
@endsection
