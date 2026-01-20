@extends('dashboard.layouts.index')
@section('title', 'Transaksi Offline')

@section('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<style>
    :root { --primary: #FFC400; --text-dark: #333; --bg-light: #f8f9fa; --sidebar-width: 280px; --border-color: #eee; }
    .main-content { flex: 1; margin-left: var(--sidebar-width); padding: 30px 40px; min-height: 100vh; background-color: var(--bg-light); font-family: 'Poppins', sans-serif; color: var(--text-dark); }
    .card-box { background: #fff; border-radius: 12px; padding: 25px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.02); overflow: hidden; }

    /* Header & Table */
    .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .table-title { font-size: 18px; font-weight: 700; color: #333; display: flex; align-items: center; gap: 10px; }
    .table-title i { color: var(--primary); font-size: 20px; }
    .btn-add-custom { background: var(--primary); color: #000; font-weight: 600; padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: 0.3s; }
    .btn-add-custom:hover { background: #e0ac00; }

    /* Alert Style */
    .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; display: flex; align-items: center; gap: 10px; }
    .alert-success { background: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; }
    .alert-danger { background: #f8d7da; color: #842029; border: 1px solid #f5c2c7; }

    /* MODAL */
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); overflow: auto; }
    .modal-content { background-color: #fff; margin: 3% auto; padding: 0; border-radius: 12px; width: 900px; max-width: 95%; position: relative; animation: slideDown 0.3s ease-out; }
    @keyframes slideDown { from {top: -50px; opacity: 0;} to {top: 0; opacity: 1;} }
    .modal-header { padding: 20px 30px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; background: #fffcf5; border-radius: 12px 12px 0 0; }
    .close-btn { font-size: 24px; cursor: pointer; color: #aaa; background: none; border: none; }
    .modal-body { padding: 30px; display: grid; grid-template-columns: 2fr 1fr; gap: 30px; }

    .form-group { margin-bottom: 15px; }
    .form-label { font-weight: 600; font-size: 13px; margin-bottom: 5px; display: block; }
    .form-control { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; }

    .summary-box { background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); height: fit-content; }
    .total-amount { font-size: 28px; font-weight: 800; color: #2ecc71; display: block; margin-top: 5px; }
    .btn-submit { width: 100%; padding: 14px; background: var(--primary); border: none; border-radius: 8px; font-weight: bold; cursor: pointer; margin-top: 20px; }

    /* Select2 Fixes */
    .select2-container { width: 100% !important; z-index: 1005; }
    .select2-container .select2-selection--single { height: 42px; padding: 6px; border: 1px solid #ddd; border-radius: 6px; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { top: 8px; }
    .select2-dropdown { z-index: 1006; }
    #userModal { z-index: 1010; }
    #userModal .modal-content { width: 400px; margin: 15% auto; }
</style>
@endsection

@section('content')
<main class="main-content">
    <div style="margin-bottom: 25px;">
        <h1 style="font-size: 24px; font-weight: 700; margin: 0;">Transaksi Offline</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <div class="card-box">
        <div class="table-header">
            <div class="table-title"><i class="fas fa-users"></i> Daftar Transaksi</div>
            <button onclick="openBookingModal()" class="btn-add-custom"><i class="fas fa-plus"></i> Tambah Transaksi</button>
        </div>

        <div style="padding: 0 10px;">
            <table id="offlineTable" class="display" style="width:100%">
                <thead>
                    <tr><th>Kode</th><th>Customer</th><th>Mobil</th><th>Waktu Sewa</th><th>Total</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @foreach($bookings as $item)
                    <tr>
                        <td style="font-weight:bold; color:var(--primary);">{{ $item->booking_code }}</td>
                        <td>
                            <div style="font-weight:600;">{{ $item->user->name ?? 'Guest' }}</div>
                            <small style="color:#999;">{{ $item->user->email ?? '-' }}</small>
                        </td>
                        <td>{{ $item->car->brand->name ?? '' }} {{ $item->car->name ?? '-' }} <span style="color:#aaa;">({{ $item->car->license_plate ?? '' }})</span></td>
                        <td>{{ \Carbon\Carbon::parse($item->start_date)->format('d/m') }} - {{ \Carbon\Carbon::parse($item->end_date)->format('d/m/Y') }}</td>
                        <td style="font-weight:bold;">Rp {{ number_format($item->grand_total, 0, ',', '.') }}</td>
                        <td><span class="badge bg-success">Paid</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</main>

<div id="bookingModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" style="font-size:18px; font-weight:700;">Input Transaksi Baru</h3>
            <button class="close-btn" onclick="closeBookingModal()">&times;</button>
        </div>

        <form action="{{ route('offline.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div>
                    <div id="notif-error" class="alert alert-danger" style="display:none; margin-bottom:15px;"></div>

                    <div class="form-group">
                        <label class="form-label">Pelanggan</label>
                        <div style="display: flex; gap: 10px;">
                            <select name="user_id" id="customerSelect" class="form-control" required>
                                <option value="">-- Cari Customer --</option>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->email }})</option>
                                @endforeach
                            </select>
                            <button type="button" onclick="openUserModal()" style="background:#333; color:#fff; border:none; border-radius:6px; width:45px; cursor:pointer;"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>

                    <div style="background: #eef7ff; padding: 15px; border-radius: 8px; border: 1px dashed #bcdff1; margin-bottom: 20px;">
                        <label class="form-label" style="color:#0056b3;"><i class="far fa-calendar-alt"></i> Pilih Jadwal Sewa</label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Mulai</label>
                                <input type="date" name="start_date" id="startDate" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Selesai</label>
                                <input type="date" name="end_date" id="endDate" class="form-control" value="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Unit Mobil (Tersedia)</label>
                        <select name="car_id" id="carSelect" class="form-control" style="width: 100%;" required>
                            <option value="" data-packages='[]'>-- Pilih Tanggal Dulu --</option>
                        </select>
                        <div id="loadingCar" style="display:none; color:#FFC400; font-size:12px; margin-top:5px;"><i class="fas fa-spinner fa-spin"></i> Memuat ketersediaan...</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Paket Harga</label>
                        <select name="package_id" id="packageSelect" class="form-control" required disabled>
                            <option value="" data-price="0">-- Pilih Mobil Dulu --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Pembayaran</label>
                        <select name="payment_method" class="form-control">
                            <option value="cash">Tunai (Cash)</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="edc">EDC / Debit</option>
                        </select>
                    </div>
                </div>

                <div>
                    <div class="summary-box">
                        <h4 style="font-size:16px; font-weight:700; margin-bottom:15px;">Estimasi Biaya</h4>
                        <div style="border-top:1px dashed #ddd; margin-bottom:15px;"></div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:8px; color:#666;">
                            <span>Harga Paket</span> <span id="displayPrice" style="font-weight:600; color:#333;">Rp 0</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:8px; color:#666;">
                            <span>Durasi</span> <span id="displayDuration" style="font-weight:600; color:#333;">1 Hari</span>
                        </div>
                        <div style="margin-top:20px;">
                            <span style="font-size:14px; color:#666;">Total Tagihan:</span>
                            <span class="total-amount" id="displayTotal">Rp 0</span>
                        </div>
                        <button type="submit" class="btn-submit">Proses & Bayar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="userModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Tambah Customer</h4>
            <button class="close-btn" onclick="closeUserModal()">&times;</button>
        </div>
        <div style="padding: 20px;">
            <div id="userNotifMsg" class="alert" style="display:none; padding:10px;"></div>

            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" id="newUserName" class="form-control" placeholder="Budi">
            </div>
            <div class="form-group">
                <label class="form-label">No. Telepon</label>
                <input type="number" id="newUserPhone" class="form-control" placeholder="0812...">
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" id="newUserEmail" class="form-control" placeholder="budi@gmail.com">
            </div>
            <button type="button" id="btnSaveUser" class="btn-submit">Simpan Customer</button>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    // Variables
    const carSelect = $('#carSelect');
    const packageSelect = document.getElementById('packageSelect');
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    const formatRupiah = (num) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num);

    $(document).ready(function() {
        $('#offlineTable').DataTable({ "pageLength": 10 });

        // --- 1. AJAX CEK KETERSEDIAAN (TANPA ALERT WINDOW) ---
        function checkAvailability() {
            let start = startDateInput.value;
            let end = endDateInput.value;
            $('#notif-error').hide().text(''); // Reset error message UI

            if(!start || !end) return;

            $('#loadingCar').show();
            carSelect.empty();
            packageSelect.innerHTML = '<option value="" data-price="0">-- Pilih Mobil Dulu --</option>';
            packageSelect.disabled = true;
            document.getElementById('displayTotal').innerText = "Rp 0";

            $.ajax({
                url: "{{ route('offline.check.availability') }}",
                type: "GET",
                data: { start_date: start, end_date: end },
                success: function(response) {
                    $('#loadingCar').hide();

                    if(response.status === 'success' && response.data.length > 0) {
                        carSelect.append('<option value="" data-packages="[]">-- Pilih Mobil --</option>');
                        response.data.forEach(function(car) {
                            // Safe JSON
                            let packagesJson = JSON.stringify(car.packages).replace(/"/g, '&quot;');
                            let brand = car.brand ? car.brand.name : '';
                            let option = `<option value="${car.id}" data-packages="${packagesJson}">${brand} ${car.name} - ${car.license_plate}</option>`;
                            carSelect.append(option);
                        });
                        carSelect.trigger('change');
                    } else {
                        carSelect.append('<option disabled>Tidak ada mobil tersedia</option>');
                    }
                },
                error: function(xhr) {
                    $('#loadingCar').hide();
                    // Tampilkan pesan error di DIV bukan alert window
                    let msg = "Gagal memuat data mobil. Cek koneksi server.";
                    if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;

                    $('#notif-error').text(msg).show();
                }
            });
            calculateTotal();
        }

        // --- 2. LOGIC UPDATE PAKET ---
        carSelect.on('select2:select', function (e) {
            let dataPackages = e.params.data.element.getAttribute('data-packages');
            packageSelect.innerHTML = '<option value="" data-price="0">-- Pilih Paket --</option>';
            packageSelect.disabled = true;

            if(dataPackages) {
                let jsonString = dataPackages.replace(/&quot;/g, '"');
                try {
                    const packages = JSON.parse(jsonString);
                    if(packages.length > 0) {
                        packageSelect.disabled = false;
                        packages.forEach(p => {
                            let opt = new Option(`${p.name} - ${formatRupiah(p.price)}`, p.id);
                            opt.setAttribute('data-price', p.price);
                            packageSelect.add(opt);
                        });
                    } else {
                        packageSelect.add(new Option('Tidak ada paket tersedia', ''));
                    }
                } catch(err) { console.error("JSON Error", err); }
            }
            calculateTotal();
        });

        // --- 3. HITUNG HARGA ---
        function calculateTotal() {
            const pkg = packageSelect.options[packageSelect.selectedIndex];
            const price = parseFloat(pkg?.getAttribute('data-price') || 0);

            const start = new Date(startDateInput.value);
            const end = new Date(endDateInput.value);
            let diff = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
            if(diff <= 0) diff = 1;

            document.getElementById('displayPrice').innerText = formatRupiah(price);
            document.getElementById('displayDuration').innerText = diff + ' Hari';
            document.getElementById('displayTotal').innerText = formatRupiah(price * diff);
        }

        packageSelect.addEventListener('change', calculateTotal);
        startDateInput.addEventListener('change', checkAvailability);
        endDateInput.addEventListener('change', checkAvailability);

        // --- 4. AJAX SAVE USER (TANPA ALERT WINDOW) ---
        $('#btnSaveUser').click(function() {
            let name = $('#newUserName').val();
            let email = $('#newUserEmail').val();
            let phone = $('#newUserPhone').val();
            let msgBox = $('#userNotifMsg');

            msgBox.hide().removeClass('alert-danger alert-success');

            if(!name || !email || !phone) {
                msgBox.addClass('alert-danger').text('Nama, Email, dan Telepon wajib diisi!').show();
                return;
            }

            $.ajax({
                url: "{{ route('offline.user.store') }}",
                type: "POST",
                data: { _token: "{{ csrf_token() }}", name: name, email: email, phone: phone },
                success: function(res) {
                    closeUserModal();
                    let newOpt = new Option(res.data.name + ' (' + res.data.email + ')', res.data.id, true, true);
                    $('#customerSelect').append(newOpt).trigger('change');

                    // Reset Form
                    $('#newUserName').val(''); $('#newUserEmail').val(''); $('#newUserPhone').val('');

                    // Tampilkan Sukses di Modal Utama (optional) atau cukup auto-select
                    // Kita bisa pakai notif error box tapi diubah jadi hijau sementara
                    $('#notif-error').removeClass('alert-danger').addClass('alert-success').text('Customer Berhasil Ditambahkan!').show();
                    setTimeout(() => $('#notif-error').fadeOut(), 3000);
                },
                error: function(xhr) {
                    msgBox.addClass('alert-danger');
                    if(xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let msg = '';
                        $.each(errors, function(k, v) { msg += v[0] + ' '; });
                        msgBox.text(msg).show();
                    } else {
                        msgBox.text('Terjadi kesalahan server.').show();
                    }
                }
            });
        });
    });

    // Modal Functions
    function openBookingModal() {
        document.getElementById('bookingModal').style.display = 'block';
        $('#customerSelect').select2({ placeholder: "-- Cari Customer --", dropdownParent: $('#bookingModal') });
        $('#carSelect').select2({ placeholder: "-- Pilih Mobil --", dropdownParent: $('#bookingModal') });

        // Trigger cek ketersediaan (default hari ini)
        document.getElementById('startDate').dispatchEvent(new Event('change'));
    }
    function closeBookingModal() { document.getElementById('bookingModal').style.display = 'none'; }
    function openUserModal() { document.getElementById('userModal').style.display = 'block'; $('#userNotifMsg').hide(); }
    function closeUserModal() { document.getElementById('userModal').style.display = 'none'; }

    window.onclick = function(e) {
        if (e.target == document.getElementById('bookingModal')) closeBookingModal();
        if (e.target == document.getElementById('userModal')) closeUserModal();
    }
</script>
@endsection
