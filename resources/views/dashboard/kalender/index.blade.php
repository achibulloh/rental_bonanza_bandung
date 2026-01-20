@extends('dashboard.layouts.index')
@section('title', 'Kalender Ketersediaan Armada')

@section('style')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <style>
        :root { --primary: #FFC400; --bg-light: #f8f9fa; --text-dark: #333; }
        .main-content { flex: 1; margin-left: var(--sidebar-width); padding: 30px 40px; background: var(--bg-light); font-family: 'Poppins', sans-serif; }

        .card-box { background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #eee; padding: 25px; }

        /* Calendar Styling */
        #calendar { max-width: 100%; margin: 0 auto; min-height: 750px; }
        .fc-event { cursor: pointer; border:none; padding: 4px 6px; font-size: 12px; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: transform 0.2s; }
        .fc-event:hover { transform: scale(1.02); z-index: 5; }
        .fc-toolbar-title { font-size: 1.5rem !important; font-weight: 700; }
        .fc-button { background-color: var(--primary) !important; border: none !important; color: #000 !important; font-weight: 600 !important; text-transform: capitalize; padding: 8px 16px !important; }
        .fc-day-today { background-color: #fffdf0 !important; }
        .fc-col-header-cell { padding: 10px 0; background: #f8f9fa; }

        .legend-item { display: flex; align-items: center; gap: 8px; font-size: 13px; margin-right: 15px; }
        .dot { width: 10px; height: 10px; border-radius: 50%; }

        /* Modal Detail */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(2px); }
        .modal-overlay.show { display: flex; }
        .modal-box { background: #fff; width: 90%; max-width: 450px; padding: 0; border-radius: 12px; overflow: hidden; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.2); animation: popIn 0.3s ease; }
        @keyframes popIn { from{transform:scale(0.9); opacity:0;} to{transform:scale(1); opacity:1;} }

        .modal-header-custom { padding: 20px; background: #f8f9fa; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
        .modal-body-custom { padding: 25px; }
        .detail-row { display: flex; justify-content: space-between; border-bottom: 1px solid #f0f0f0; padding: 12px 0; font-size: 13px; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { color: #666; font-weight: 500; }
        .detail-val { font-weight: 600; color: #333; text-align: right; max-width: 60%; }
        .status-badge { padding: 4px 10px; border-radius: 12px; font-size: 11px; color: white; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }

        @media (max-width: 992px) { .main-content { margin-left: 0; padding: 20px; } }
    </style>
@endsection

@section('content')
<main class="main-content">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 25px; flex-wrap:wrap; gap:15px;">
        <div>
            <h1 style="font-size:24px; font-weight:700; margin:0;">Kalender Armada</h1>
            <p style="color:#666; font-size:14px; margin:5px 0 0;">Monitoring jadwal pemakaian unit mobil secara real-time.</p>
        </div>

        <div style="display:flex; background:#fff; padding:12px 20px; border-radius:10px; border:1px solid #eee; flex-wrap:wrap; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
            <div class="legend-item"><div class="dot" style="background:#ffc107;"></div> Pending</div>
            <div class="legend-item"><div class="dot" style="background:#dc3545;"></div> Booked</div>
            <div class="legend-item"><div class="dot" style="background:#28a745;"></div> Ongoing</div>
            <div class="legend-item"><div class="dot" style="background:#6c757d;"></div> Completed</div>
        </div>
    </div>

    <div class="card-box">
        <div id='calendar'></div>
    </div>
</main>

<div id="modalDetail" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header-custom">
            <div style="display:flex; align-items:center; gap:10px;">
                <div style="width:35px; height:35px; background:#fff3cd; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#856404;">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3 style="margin:0; font-size:16px; font-weight:700;">Detail Booking</h3>
            </div>
            <button onclick="closeModal()" style="background:none; border:none; font-size:24px; cursor:pointer; color:#888; line-height:1;">&times;</button>
        </div>
        <div class="modal-body-custom" id="modalContent">
            </div>
        <div style="padding:15px 25px; background:#f8f9fa; text-align:right; border-top:1px solid #eee;">
            <button onclick="closeModal()" style="padding:10px 25px; background:#e9ecef; border:none; border-radius:8px; cursor:pointer; font-weight:600; color:#495057; transition:0.3s;">Tutup</button>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    function closeModal() {
        document.getElementById('modalDetail').classList.remove('show');
    }

    // Tutup modal jika klik di luar box
    window.onclick = function(event) {
        let modal = document.getElementById('modalDetail');
        if (event.target == modal) {
            closeModal();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listMonth'
            },
            locale: 'id',
            firstDay: 1, // Senin
            buttonText: {
                today: 'Hari Ini',
                month: 'Bulan',
                week: 'Minggu',
                list: 'List Agenda'
            },
            navLinks: true,
            events: '{{ route("calendar.events") }}', // Load dari controller

            eventClick: function(info) {
                // Ambil data extendedProps yang dikirim dari controller
                var p = info.event.extendedProps;

                // Format Tanggal (Menggunakan date string dari controller agar akurat)
                // Kita gunakan helper function sederhana untuk format tanggal ID
                const formatDateID = (dateStr) => {
                    const d = new Date(dateStr);
                    return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                };

                var html = `
                    <div style="text-align:center; margin-bottom:20px;">
                        <h4 style="margin:0; font-size:18px;">${p.unit}</h4>
                        <div style="margin-top:5px;">
                            <span class="status-badge" style="background:${p.bg_color}">${p.status_order}</span>
                        </div>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label"><i class="far fa-user"></i> Penyewa</span>
                        <span class="detail-val">${p.penyewa}<br><small style="color:#888; font-weight:400;">${p.email}</small></span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label"><i class="far fa-calendar"></i> Tanggal Mulai</span>
                        <span class="detail-val">${formatDateID(info.event.start)}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label"><i class="far fa-calendar-check"></i> Tanggal Selesai</span>
                        <span class="detail-val">${formatDateID(p.real_end_date)}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label"><i class="far fa-clock"></i> Durasi</span>
                        <span class="detail-val">${p.durasi}</span>
                    </div>

                    <div class="detail-row" style="margin-top:15px; border-top:2px dashed #eee; padding-top:15px;">
                         <span class="detail-label">Status Bayar</span>
                         <span class="detail-val" style="color:${p.payment_info === 'LUNAS' ? '#28a745' : '#dc3545'}">${p.payment_info}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Total Tagihan</span>
                        <span class="detail-val" style="font-size:16px; color:#28a745;">${p.total}</span>
                    </div>
                `;

                document.getElementById('modalContent').innerHTML = html;
                document.getElementById('modalDetail').classList.add('show');
            },

            // Ubah cursor saat hover event
            eventMouseEnter: function(mouseEnterInfo) {
                mouseEnterInfo.el.style.cursor = 'pointer';
            }
        });

        calendar.render();
    });
</script>
@endsection
