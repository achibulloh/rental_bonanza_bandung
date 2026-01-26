@extends('dashboard.layouts.index')
@section('title', 'Kelola Driver')

@section('style')
<style>
    /* GLOBAL */
    :root { --primary: #FFC400; --primary-hover: #e0ac00; --text-dark: #1f2937; --text-gray: #6b7280; --card-bg: #ffffff; --border-color: #f3f4f6; }

    /* GRID & CARD */
    .driver-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 25px; margin-top: 25px; }
    .driver-card { background: var(--card-bg); border-radius: 16px; padding: 25px; border: 1px solid var(--border-color); display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); transition: transform 0.2s; position: relative; }
    .driver-card:hover { transform: translateY(-5px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }

    /* TOMBOL EDIT & CANCEL (POSISI POJOK) */
    .btn-icon-edit { position: absolute; top: 15px; right: 15px; width: 30px; height: 30px; border-radius: 50%; background: #f3f4f6; color: #6b7280; border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s; z-index: 5; }
    .btn-icon-edit:hover { background: var(--primary); color: #000; }

    .btn-icon-cancel { position: absolute; top: 10px; right: 10px; width: 25px; height: 25px; border-radius: 6px; background: #fff; color: #ef4444; border: 1px solid #fecaca; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s; font-size: 0.8rem; }
    .btn-icon-cancel:hover { background: #ef4444; color: #fff; }

    /* HEADER & INFO */
    .driver-header { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; padding-right: 35px; }
    .driver-avatar { width: 56px; height: 56px; background: #fffbeb; border: 2px solid #fcd34d; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #d97706; font-size: 1.4rem; flex-shrink: 0; overflow: hidden; }
    .driver-info h3 { margin: 0; font-weight: 700; font-size: 1.1rem; color: var(--text-dark); }
    .driver-info span { font-size: 0.8rem; color: var(--text-gray); }

    /* BADGES */
    .badge { padding: 5px 12px; border-radius: 50px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; display: inline-block; margin-top: 5px; }
    .badge-success { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
    .badge-primary { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }

    /* CONTACT DETAILS */
    .driver-details { margin-bottom: 20px; background: #f9fafb; padding: 15px; border-radius: 10px; border: 1px solid #f3f4f6; }
    .detail-item { display: flex; align-items: center; gap: 10px; color: var(--text-dark); font-size: 0.9rem; margin-bottom: 8px; }
    .detail-item i { width: 20px; text-align: center; color: #9ca3af; }

    /* ACTION AREAS */
    .active-task-box { background: #eff6ff; border-radius: 10px; padding: 15px; border: 1px solid #bfdbfe; position: relative; }
    .task-label { font-size: 0.7rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; display: block; margin-bottom: 5px; }
    .task-code { font-size: 0.95rem; font-weight: 800; color: #1e3a8a; display: block; margin-bottom: 5px; }
    .task-car { font-size: 0.8rem; color: #4b5563; display: flex; align-items: center; gap: 5px; background: #fff; padding: 5px 8px; border-radius: 6px; width: fit-content; border: 1px solid #dbeafe; }

    .btn-assign { width: 100%; padding: 12px; background: var(--primary); color: #000; font-weight: 700; border: none; border-radius: 10px; cursor: pointer; text-align: center; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s; font-size: 0.95rem; }
    .btn-assign:hover { background: var(--primary-hover); transform: translateY(-2px); }

    /* === STATISTIK STYLE (WAJIB ADA) === */
    .stats-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .stat-card { background: #fff; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb; display: flex; align-items: center; gap: 20px; }
    .stat-icon { width: 45px; height: 45px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }
    .stat-info h4 { margin: 0; font-size: 0.85rem; color: var(--text-gray); font-weight: 500; }
    .stat-info .stat-value { font-size: 1.6rem; font-weight: 800; margin-top: 5px; line-height: 1; }

    /* Warna Statistik */
    .stat-card.orange .stat-icon { background: #fff7ed; color: #ea580c; } .stat-card.orange .stat-value { color: #ea580c; }
    .stat-card.green .stat-icon { background: #f0fdf4; color: #16a34a; } .stat-card.green .stat-value { color: #16a34a; }
    .stat-card.blue .stat-icon { background: #eff6ff; color: #2563eb; } .stat-card.blue .stat-value { color: #2563eb; }

    /* MODAL STYLE */
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 999; display: none; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease; }
    .modal-overlay.show { display: flex; opacity: 1; }
    .custom-modal { background: #fff; width: 100%; max-width: 500px; border-radius: 16px; padding: 30px; transform: translateY(20px); transition: transform 0.3s ease; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
    .modal-overlay.show .custom-modal { transform: translateY(0); }
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .modal-header h3 { margin: 0; font-size: 1.25rem; font-weight: 800; color: var(--text-dark); }
    .close-modal { background: none; border: none; font-size: 1.5rem; color: #9ca3af; cursor: pointer; transition: 0.2s; }
    .close-modal:hover { color: #ef4444; }
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 0.9rem; }
    .form-input { width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem; outline: none; background: #fff; }
    .modal-footer { display: flex; gap: 10px; justify-content: flex-end; margin-top: 30px; }
    .btn-cancel { padding: 10px 20px; background: #f3f4f6; color: #374151; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }
    .btn-save { padding: 10px 20px; background: var(--primary); color: #000; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }
    .btn-danger { padding: 10px 20px; background: #ef4444; color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }
    .btn-danger:hover { background: #dc2626; }
</style>
@endsection

@section('content')
<div class="container-fluid" style="padding: 20px;">

    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--text-dark); margin-bottom: 5px;">Manajemen Driver</h1>
        <p style="color: var(--text-gray); font-size: 0.95rem;">Pantau ketersediaan, penugasan, dan data driver.</p>
    </div>

    <div class="stats-container">
        <div class="stat-card orange">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-info">
                <h4>Total Driver</h4>
                <div class="stat-value">{{ $stats['total'] }}</div>
            </div>
        </div>

        <div class="stat-card green">
            <div class="stat-icon"><i class="fas fa-user-check"></i></div>
            <div class="stat-info">
                <h4>Driver Tersedia</h4>
                <div class="stat-value">{{ $stats['tersedia'] }}</div>
            </div>
        </div>

        <div class="stat-card blue">
            <div class="stat-icon"><i class="fas fa-car-side"></i></div>
            <div class="stat-info">
                <h4>Driver Bertugas</h4>
                <div class="stat-value">{{ $stats['bertugas'] }}</div>
            </div>
        </div>
    </div>
    @if(session('success'))
        <div style="background: #ecfdf5; color: #047857; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #a7f3d0;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background: #fef2f2; color: #b91c1c; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #fecaca;">
            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <div class="driver-grid">
        @forelse($drivers as $driver)
            <div class="driver-card">

                {{-- LOGIKA 1: Tombol Edit HANYA jika Tersedia (Tidak Bertugas) --}}
                @if(!$driver->activeTransaction)
                    @can('drivers.update')
                        <button class="btn-icon-edit" onclick="openEditModal('{{ $driver->id }}', '{{ $driver->name }}', '{{ $driver->phone }}', '{{ $driver->email }}')" title="Edit Data Driver">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                    @endcan
                @endif

                <div class="driver-header">
                    <div class="driver-avatar">
                        @if($driver->avatar)
                            <img src="{{ asset('storage/' . $driver->avatar) }}" alt="Avatar" style="width:100%; height:100%; object-fit:cover;">
                        @else <i class="fas fa-user"></i> @endif
                    </div>
                    <div class="driver-info">
                        <h3>{{ $driver->name }}</h3>
                        <span>ID: {{ str_pad($driver->id, 3, '0', STR_PAD_LEFT) }}</span>

                        @if($driver->activeTransaction)
                            <div class="badge badge-primary">Bertugas</div>
                        @else
                            <div class="badge badge-success">Tersedia</div>
                        @endif
                    </div>
                </div>

                <div class="driver-details">
                    <div class="detail-item"><i class="fas fa-phone-alt"></i> {{ $driver->phone ?? '-' }}</div>
                    <div class="detail-item"><i class="fas fa-envelope"></i> {{ Str::limit($driver->email, 22) }}</div>
                </div>

                @if($driver->activeTransaction)
                    {{-- JIKA BERTUGAS: Tampil Info + Tombol Cancel --}}
                    <div class="active-task-box">

                        {{-- LOGIKA 2: Tombol Cancel HANYA jika Bertugas --}}
                        @can('drivers.cancel')
                            <button class="btn-icon-cancel" onclick="openCancelModal('{{ $driver->activeTransaction->id }}', '{{ $driver->name }}', '{{ $driver->activeTransaction->booking_code }}')" title="Batalkan Pekerjaan">
                                <i class="fas fa-times"></i>
                            </button>
                        @endcan

                        <span class="task-label">Sedang Menjalankan</span>
                        <span class="task-code">{{ $driver->activeTransaction->booking_code }}</span>
                        <div class="task-car"><i class="fas fa-car"></i> {{ $driver->activeTransaction->car->name ?? 'Mobil' }}</div>
                    </div>
                @else
                    {{-- JIKA TERSEDIA: Tombol Assign --}}
                    @can('drivers.assign')
                        <button type="button" class="btn-assign" onclick="openAssignModal('{{ $driver->id }}', '{{ $driver->name }}')">
                            <i class="fas fa-plus-circle"></i> Assign ke Booking
                        </button>
                    @endcan
                @endif
            </div>
        @empty
            <div style="grid-column: 1 / -1; padding: 60px; text-align: center; background: #fff; border-radius: 16px; border: 2px dashed #e5e7eb;">
                <p style="color: #6b7280;">Belum ada data driver.</p>
            </div>
        @endforelse
    </div>
</div>

<div class="modal-overlay" id="assignModal">
    <div class="custom-modal">
        <div class="modal-header">
            <h3>Assign Driver</h3>
            <button type="button" class="close-modal" onclick="closeModal('assignModal')">&times;</button>
        </div>
        <form action="{{ route('drivers.assign') }}" method="POST">
            @csrf
            <input type="hidden" name="driver_id" id="assignDriverId">
            <div class="form-group">
                <label class="form-label">Driver: <span id="assignDriverName" style="color:var(--primary-hover)"></span></label>
                <label class="form-label" style="margin-top:10px;">Pilih Booking</label>
                <select name="booking_id" class="form-input" required>
                    <option value="" disabled selected>-- Pilih Booking --</option>
                    @foreach($availableBookings as $booking)
                        <option value="{{ $booking->id }}">{{ $booking->booking_code }} - {{ $booking->car->name ?? 'Mobil' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('assignModal')">Batal</button>
                <button type="submit" class="btn-save">Simpan Penugasan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="editModal">
    <div class="custom-modal">
        <div class="modal-header">
            <h3>Edit Data Driver</h3>
            <button type="button" class="close-modal" onclick="closeModal('editModal')">&times;</button>
        </div>
        <form id="editForm" method="POST">
            @csrf @method('PUT')
            <div class="form-group"><label class="form-label">Nama</label><input type="text" name="name" id="editName" class="form-input" required></div>
            <div class="form-group"><label class="form-label">WhatsApp/HP</label><input type="text" name="phone" id="editPhone" class="form-input" required></div>
            <div class="form-group"><label class="form-label">Email</label><input type="email" name="email" id="editEmail" class="form-input" required></div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('editModal')">Batal</button>
                <button type="submit" class="btn-save">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="cancelModal">
    <div class="custom-modal">
        <div class="modal-header">
            <h3 style="color:#ef4444;">Batalkan Pekerjaan?</h3>
            <button type="button" class="close-modal" onclick="closeModal('cancelModal')">&times;</button>
        </div>
        <form action="{{ route('drivers.cancel_job') }}" method="POST">
            @csrf
            <input type="hidden" name="booking_id" id="cancelBookingId">
            <p style="color:#374151; line-height:1.6; margin-bottom:20px;">
                Anda yakin ingin membatalkan tugas driver <strong id="cancelDriverName"></strong>
                untuk kode booking <strong id="cancelBookingCode"></strong>?
                <br><span style="font-size:0.85rem; color:#6b7280;">Driver akan menjadi 'Tersedia'.</span>
            </p>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('cancelModal')">Tutup</button>
                <button type="submit" class="btn-danger">Ya, Batalkan</button>
            </div>
        </form>
    </div>
</div>

<script>
    // ASSIGN
    function openAssignModal(id, name) {
        document.getElementById('assignDriverId').value = id;
        document.getElementById('assignDriverName').innerText = name;
        document.getElementById('assignModal').classList.add('show');
    }
    // EDIT
    function openEditModal(id, name, phone, email) {
        document.getElementById('editName').value = name;
        document.getElementById('editPhone').value = phone;
        document.getElementById('editEmail').value = email;
        let url = "{{ route('drivers.update', ':id') }}";
        document.getElementById('editForm').action = url.replace(':id', id);
        document.getElementById('editModal').classList.add('show');
    }
    // CANCEL
    function openCancelModal(bookingId, driverName, bookingCode) {
        document.getElementById('cancelBookingId').value = bookingId;
        document.getElementById('cancelDriverName').innerText = driverName;
        document.getElementById('cancelBookingCode').innerText = bookingCode;
        document.getElementById('cancelModal').classList.add('show');
    }
    // CLOSE GLOBAL
    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('show');
    }
    window.onclick = function(e) {
        if (e.target.classList.contains('modal-overlay')) e.target.classList.remove('show');
    }
</script>
@endsection
