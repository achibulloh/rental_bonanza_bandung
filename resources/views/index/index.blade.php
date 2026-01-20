@extends('index.layouts.index')

@section('style')
<style>
    /* Tambahan CSS Slider Testimoni */
    .testimonial-slider-container { overflow: hidden; position: relative; width: 100%; padding: 20px 0; }
    .testimonial-track { display: flex; gap: 20px; transition: transform 0.5s ease-in-out; }
    .testimonial-card { min-width: 300px; max-width: 350px; background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #eee; flex-shrink: 0; }
    .stars { color: #FFC400; margin-bottom: 10px; font-size: 1.2rem; }
    .quote { font-style: italic; color: #555; font-size: 0.95rem; line-height: 1.6; margin-bottom: 20px; min-height: 80px; }
    .user-profile { display: flex; align-items: center; gap: 15px; }
    .user-img { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; }
    .user-info h4 { margin: 0; font-size: 1rem; font-weight: 700; color: #333; }
    .user-info span { font-size: 0.85rem; color: #888; }

    /* Dots Navigation */
    .slider-dots { display: flex; justify-content: center; gap: 8px; margin-top: 20px; }
    .dot { width: 10px; height: 10px; background: #ddd; border-radius: 50%; cursor: pointer; transition: 0.3s; }
    .dot.active { background: #FFC400; width: 25px; border-radius: 5px; }
</style>
@endsection

@section('content')

    <section class="hero">
        <div class="container hero-content">
            <div class="hero-text">
                <span class="promo-badge">Promo Spesial Rental Terbaik</span>
                <h1>Rental Mobil <br><span>Terpercaya</span> & Berkualitas</h1>
                <p>Dapatkan pengalaman berkendara terbaik dengan armada mobil berkualitas dan harga terjangkau.</p>

                <form action="{{ route('index.search') }}" method="POST" class="search-box">
                    @csrf
                    <div class="input-group">
                        <label>Tanggal Mulai Sewa</label>
                        <input type="date" name="start_date" id="start_date" value="{{ request('start_date', date('Y-m-d')) }}" required min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="input-group">
                        <label>Tanggal Selesai Sewa</label>
                        <input type="date" name="end_date" id="end_date" value="{{ request('end_date', date('Y-m-d', strtotime('+1 day'))) }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Cari Mobil Sekarang</button>
                </form>

                <div class="hero-stats">
                    <div class="stat-item"><h3>{{ \App\Models\Car::count() }}+</h3><p>Mobil Tersedia</p></div>
                    <div class="stat-item"><h3>{{ \App\Models\Booking::where('status','completed')->count() }}+</h3><p>Perjalanan Selesai</p></div>
                    <div class="stat-item"><h3>24/7</h3><p>Layanan Support</p></div>
                </div>
            </div>
            <div class="hero-image">
                <img src="https://images.unsplash.com/photo-1494976388531-d1058494cdd8?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Mobil Mewah">
            </div>
        </div>
    </section>

    <section class="section-padding cars-section" id="armada">
        <div class="container">
            <div class="section-header">
                <span class="subtitle">Armada Kami</span>
                <h2>Mobil Populer</h2>
                <p style="color:var(--gray)">Pilih dari berbagai koleksi mobil berkualitas</p>
            </div>

            <div class="cars-grid">
                @forelse($popularCars as $car)
                <div class="car-card">
                    <div class="car-image">
                        <span class="car-tag">{{ $car->brand->name ?? 'Umum' }}</span>

                        {{-- Logika Gambar --}}
                        @php
                            $img = 'img/no-image.jpg';
                            if(!empty($car->images)) {
                                $raw = $car->images;
                                // Cek apakah data JSON atau Array
                                $decoded = is_array($raw) ? $raw : json_decode($raw, true);
                                if(is_array($decoded) && count($decoded) > 0) $img = 'storage/' . $decoded[0];
                            }
                        @endphp
                        <img src="{{ asset($img) }}" alt="{{ $car->name }}">
                    </div>

                    <div class="car-details">
                        <h3 class="car-title">{{ $car->brand->name }} {{ $car->name }}</h3>

                        {{-- Ambil Harga Terendah dari Paket --}}
                        @php $minPrice = $car->packages->min('price'); @endphp
                        <div class="car-price">Rp {{ number_format($minPrice ?? 0, 0, ',', '.') }} <span>/hari</span></div>

                        <div class="car-specs">
                            <span><i class="fas fa-user"></i> {{ $car->seating_capacity }} Kursi</span>
                            <span><i class="fas fa-cogs"></i> {{ $car->transmission }}</span>
                        </div>

                        <div class="car-actions">
                            <a href="{{ route('booking.index', $car->id) }}" class="btn btn-primary" style="width:100%; text-align:center; text-decoration:none;">Sewa</a>
                            {{-- <a href="#" class="btn btn-outline">Detail</a> --}}
                        </div>
                    </div>
                </div>
                @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 40px;">
                    <p>Belum ada armada mobil yang tersedia saat ini.</p>
                </div>
                @endforelse
            </div>

            <div class="view-all-btn">
                <a href="{{ route('index_mobil') }}" class="btn" style="background:#000; color:#fff; text-decoration:none;">Lihat Semua Mobil</a>
            </div>
        </div>
    </section>

    <section class="section-padding" id="layanan">
        <div class="container">
            <div class="section-header">
                <span class="subtitle">Mengapa Kami?</span>
                <h2>Keunggulan Layanan</h2>
                <p style="color:var(--gray)">Kami berkomitmen memberikan pengalaman rental terbaik</p>
            </div>
            <div class="features-grid">
                <div class="feature-card"><div class="feature-icon"><i class="fas fa-shield-alt"></i></div><h3>Aman & Terpercaya</h3><p>Semua mobil diasuransikan dan terawat dengan baik.</p></div>
                <div class="feature-card"><div class="feature-icon"><i class="fas fa-clock"></i></div><h3>Proses Cepat</h3><p>Booking online dalam hitungan menit.</p></div>
                <div class="feature-card"><div class="feature-icon"><i class="fas fa-dollar-sign"></i></div><h3>Harga Terjangkau</h3><p>Harga terbaik dengan banyak pilihan paket.</p></div>
                <div class="feature-card"><div class="feature-icon"><i class="fas fa-headset"></i></div><h3>Support 24/7</h3><p>Tim CS siap membantu kapan saja.</p></div>
                <div class="feature-card"><div class="feature-icon"><i class="fas fa-map-marker-alt"></i></div><h3>Antar Jemput</h3><p>Layanan antar jemput di lokasi tertentu.</p></div>
                <div class="feature-card"><div class="feature-icon"><i class="fas fa-medal"></i></div><h3>Kualitas Premium</h3><p>Armada mobil bersih dan wangi.</p></div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <div class="cta-box">
                <h2>Siap Memulai Perjalanan Anda?</h2>
                <p>Daftar sekarang dan nikmati kemudahan sewa mobil dalam genggaman!</p>
                <div class="cta-buttons">
                    <a href="{{ route('index_mobil') }}" class="btn btn-primary" style="text-decoration:none;">Pilih Mobil</a>
                    <a href="https://wa.me/6281234567890" target="_blank" class="btn btn-outline-light" style="text-decoration:none;">Hubungi Kami</a>
                </div>
            </div>
        </div>
    </section>

    <section class="section-padding cars-section" id="testimoni">
        <div class="container">
            <div class="section-header">
                <span class="subtitle">Testimoni</span>
                <h2>Apa Kata Pelanggan Kami?</h2>
            </div>

            <div class="testimonial-slider-container">
                <div class="testimonial-track" id="sliderTrack">

                    @forelse($testimonials as $testi)
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <div class="stars">
                                @for($i=0; $i<$testi->rating; $i++) ★ @endfor
                            </div>
                            <p class="quote">"{{ Str::limit($testi->comment, 100) }}"</p>
                            <div class="user-profile">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($testi->user->name) }}&background=random" alt="User" class="user-img">
                                <div class="user-info">
                                    <h4>{{ $testi->user->name }}</h4>
                                    <span>Pelanggan</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <div class="stars">★★★★★</div>
                            <p class="quote">"Pelayanan sangat memuaskan! Mobil bersih dan wangi. Recommended banget buat liburan keluarga."</p>
                            <div class="user-profile">
                                <img src="https://ui-avatars.com/api/?name=Budi+Santoso&background=random" class="user-img">
                                <div class="user-info"><h4>Budi Santoso</h4><span>Pengusaha</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <div class="stars">★★★★★</div>
                            <p class="quote">"Proses booking cepat tanpa ribet. Harga juga bersaing dibanding rental lain."</p>
                            <div class="user-profile">
                                <img src="https://ui-avatars.com/api/?name=Siti+Aminah&background=random" class="user-img">
                                <div class="user-info"><h4>Siti Aminah</h4><span>Traveler</span></div>
                            </div>
                        </div>
                    </div>
                    @endforelse

                </div>
                <div class="slider-dots" id="sliderDots"></div>
            </div>
            <div class="trust-stats">
                <div class="trust-item"><h3>{{ $avgRating }}/5</h3><p>Rating Rata-rata</p></div>
                <div class="trust-item"><h3>{{ \App\Models\Booking::count() }}+</h3><p>Total Transaksi</p></div>
                <div class="trust-item"><h3>{{ \App\Models\Car::count() }}+</h3><p>Armada Mobil</p></div>
                <div class="trust-item"><h3>5+</h3><p>Tahun Pengalaman</p></div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
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
<script>
    // Script Sederhana untuk Auto Slide Testimoni
    const track = document.getElementById('sliderTrack');
    const cards = document.querySelectorAll('.testimonial-card');

    if(cards.length > 0) {
        let index = 0;
        const cardWidth = cards[0].offsetWidth + 20; // Width + Gap

        function slideNext() {
            index++;
            if (index >= cards.length) {
                index = 0;
            }
            track.style.transform = `translateX(-${index * cardWidth}px)`;
        }

        // Auto slide setiap 3 detik
        setInterval(slideNext, 3000);
    }
</script>
@endsection
