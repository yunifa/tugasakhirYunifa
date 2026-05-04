<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalender Akademik SMKN 2 Cimahi</title>

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
        --blue-primary: #1A73E8;
        --blue-dark:    #1558B0;
        --blue-navbar:  #1565D8;
        --white:        #ffffff;
        --gray-light:   #c0c0c0;
        --gray-mid:     #888;
        --text-main:    #1a1a1a;
        --overlay:      rgba(0,0,0,0.38);
        --sidebar-w:    220px;
        --nav-h:        68px;
        --radius-lg:    20px;
        --radius-md:    12px;
        --radius-sm:    8px;
        --shadow-card:  0 8px 32px rgba(0,0,0,0.35);
    }
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: url('background_smk2.jpg') no-repeat center center / cover fixed;
        min-height: 100vh; overflow-x: hidden;
    }
    body::before { content:"";position:fixed;inset:0;background:var(--overlay);z-index:0; }
    .navbar_yunifa {
        position:fixed;top:0;left:0;right:0;z-index:1000;height:var(--nav-h);
        background:var(--blue-navbar);display:flex;align-items:center;
        justify-content:space-between;padding:0 24px;
        box-shadow:0 2px 12px rgba(0,0,0,0.3);animation:fadeUp 0.3s ease both;
    }
    .navbar-logo_yunifa img { height:46px;width:46px;border-radius:50%;object-fit:cover;border:2px solid rgba(255,255,255,0.5); }
    .navbar-logo_yunifa .logo-fallback_yunifa {
        height:46px;width:46px;border-radius:50%;background:white;
        display:flex;align-items:center;justify-content:center;
        color:var(--blue-primary);font-size:22px;border:2px solid rgba(255,255,255,0.5);
    }
    .navbar-title_yunifa { color:var(--white);font-size:clamp(15px,2.2vw,22px);font-weight:800;text-align:center;flex:1;padding:0 16px; }
    .navbar-hamburger_yunifa {
        background:white;border:none;border-radius:var(--radius-md);
        width:44px;height:44px;display:flex;flex-direction:column;
        align-items:center;justify-content:center;gap:5px;cursor:pointer;transition:background 0.2s;flex-shrink:0;
    }
    .navbar-hamburger_yunifa:hover { background:#f0f0f0; }
    .navbar-hamburger_yunifa span { display:block;width:22px;height:2.5px;background:#222;border-radius:2px; }
    .sidebar-overlay_yunifa { display:none;position:fixed;inset:0;background:rgba(0,0,0,0.25);z-index:1100; }
    .sidebar-overlay_yunifa.open_yunifa { display:block; }
    .sidebar_yunifa {
        position:fixed;top:0;right:0;width:var(--sidebar-w);height:100%;
        background:white;z-index:1200;transform:translateX(100%);
        transition:transform 0.3s cubic-bezier(.4,0,.2,1);
        display:flex;flex-direction:column;padding-top:var(--nav-h);
        box-shadow:-4px 0 24px rgba(0,0,0,0.18);
    }
    .sidebar_yunifa.open_yunifa { transform:translateX(0); }
    .sidebar-menu_yunifa { flex:1;padding:12px 0; }
    .sidebar-item_yunifa {
        display:flex;align-items:center;gap:12px;padding:14px 24px;
        font-size:16px;font-weight:700;color:#1a1a1a;cursor:pointer;
        transition:background 0.15s;border:none;background:none;
        width:100%;text-align:left;text-decoration:none;
    }
    .sidebar-item_yunifa:hover { background:#f4f8ff;color:var(--blue-primary); }
    .sidebar-item_yunifa i { width:20px;text-align:center;color:var(--blue-primary);font-size:15px; }
    .sidebar-divider_yunifa { height:1px;background:#e5e5e5;margin:4px 20px; }
    .sidebar-user_yunifa { padding:16px 24px;display:flex;align-items:center;gap:10px;border-top:1px solid #e5e5e5;font-size:15px;font-weight:700;color:#1a1a1a; }
    .sidebar-user_yunifa i { font-size:20px;color:#555; }
    .main_yunifa {
        position:relative;z-index:1;padding-top:calc(var(--nav-h) + 28px);
        padding-bottom:40px;display:flex;justify-content:center;align-items:flex-start;min-height:100vh;
    }
    .calendar-card_yunifa {
        background:rgba(255,255,255,0.93);border-radius:var(--radius-lg);
        box-shadow:var(--shadow-card);padding:20px 20px 24px;
        width:min(940px, 96vw);backdrop-filter:blur(6px);
        -webkit-backdrop-filter:blur(6px);animation:fadeUp 0.45s ease both;
    }
    .fc .fc-toolbar { display:flex !important;justify-content:space-between !important;align-items:center !important;padding:8px 4px 12px !important;margin-bottom:0 !important; }
    .fc .fc-toolbar-chunk { display:flex;align-items:center; }
    .fc-toolbar-title { font-family:'Plus Jakarta Sans',sans-serif;font-size:clamp(18px,3vw,26px) !important;font-weight:700 !important;color:#1a1a1a;text-align:center; }
    .fc .fc-prev-button,.fc .fc-next-button {
        position:static !important;background:white !important;border:1px solid #ddd !important;
        color:#333 !important;border-radius:var(--radius-md) !important;
        width:40px !important;height:40px !important;
        display:flex !important;align-items:center !important;justify-content:center !important;
        box-shadow:0 2px 6px rgba(0,0,0,0.1);padding:0 !important;
    }
    .fc .fc-prev-button:hover,.fc .fc-next-button:hover { background:#f0f0f0 !important; }
    .fc .fc-prev-button .fc-icon,.fc .fc-next-button .fc-icon { font-size:16px !important;color:#333; }
    .fc .fc-today-button { display:none; }
    .fc .fc-scrollgrid { border-radius:8px;overflow:hidden;border-color:#d0d0d0 !important; }
    .fc .fc-col-header-cell { background:white;padding:10px 0 !important; }
    .fc .fc-col-header-cell-cushion { font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:700;color:#333;text-decoration:none; }
    .fc .fc-daygrid-day { min-height:72px; }
    .fc .fc-daygrid-day-number { font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:600;color:#222;padding:6px 10px !important;text-decoration:none; }
    .fc .fc-day-other .fc-daygrid-day-number { color:var(--gray-light) !important; }
    .fc .fc-day-today { background:rgba(26,115,232,0.07) !important; }
    .fc .fc-day-today .fc-daygrid-day-number {
        background:var(--blue-primary);color:white !important;border-radius:50%;
        width:28px;height:28px;display:flex;align-items:center;justify-content:center;padding:0 !important;margin:4px;
    }
    .fc-event { border-radius:5px !important;font-size:11px !important;font-weight:600 !important;padding:1px 5px !important;border:none !important;cursor:pointer !important; }
    .fc .fc-scrollgrid-sync-table td,.fc .fc-scrollgrid-sync-table th { border-color:#d8d8d8 !important; }
    .modal-overlay_yunifa {
        display:none;position:fixed;inset:0;
        background:rgba(0,0,0,0.5);z-index:2000;
        align-items:center;justify-content:center;padding:20px;
    }
    .modal-overlay_yunifa.open_yunifa { display:flex; }
    .modal-box_yunifa {
        background:white;border-radius:var(--radius-lg);
        width:min(480px, 100%);
        box-shadow:0 24px 64px rgba(0,0,0,0.4);
        animation:modalIn 0.3s cubic-bezier(.34,1.56,.64,1) both;
        overflow:hidden;
    }
    @keyframes modalIn {
        from { opacity:0;transform:scale(0.92) translateY(24px); }
        to   { opacity:1;transform:scale(1) translateY(0); }
    }
    .modal-header_yunifa {
        padding:22px 24px 18px;position:relative;
    }
    .modal-header_yunifa h2 {
        color:white;font-size:19px;font-weight:800;
        line-height:1.3;padding-right:40px;
    }
    .modal-close_yunifa {
        position:absolute;top:16px;right:16px;
        background:rgba(255,255,255,0.2);border:none;color:white;
        width:32px;height:32px;border-radius:50%;cursor:pointer;
        display:flex;align-items:center;justify-content:center;
        font-size:15px;transition:background 0.2s;
    }
    .modal-close_yunifa:hover { background:rgba(255,255,255,0.35); }
    .modal-body_yunifa { padding:18px 24px 24px; }
    .detail-row_yunifa {
        display:flex;align-items:flex-start;gap:12px;
        padding:12px 0;border-bottom:1px solid #f0f0f0;
    }
    .detail-row_yunifa:last-child { border-bottom:none; }
    .detail-icon_yunifa {
        width:34px;height:34px;border-radius:var(--radius-sm);
        display:flex;align-items:center;justify-content:center;
        font-size:13px;flex-shrink:0;margin-top:1px;
    }
    .detail-label_yunifa { font-size:11px;font-weight:700;color:var(--gray-mid);text-transform:uppercase;letter-spacing:0.4px;margin-bottom:3px; }
    .detail-value_yunifa { font-size:14px;font-weight:600;color:var(--text-main);line-height:1.6; }
    @keyframes fadeUp { from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)} }
    @media(max-width:600px){
        .calendar-card_yunifa{padding:14px 8px 18px;}
        .fc .fc-daygrid-day{min-height:52px;}
        .sidebar_yunifa{width:200px;}
        .modal-header_yunifa h2{font-size:16px;}
    }
    </style>
</head>
<body>
    <nav class="navbar_yunifa">
        <div class="navbar-logo_yunifa">
            <img src="logo_smk2.png" alt="Logo SMKN 2 Cimahi"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="logo-fallback_yunifa" style="display:none;"><i class="fas fa-school"></i></div>
        </div>
        <div class="navbar-title_yunifa">Kalender Akademik SMKN 2 Cimahi</div>
        <button class="navbar-hamburger_yunifa" id="menuToggle_yunifa" aria-label="Buka menu">
            <span></span><span></span><span></span>
        </button>
    </nav>

    <div class="sidebar-overlay_yunifa" id="sidebarOverlay_yunifa"></div>

    <aside class="sidebar_yunifa" id="sidebar_yunifa">
        <div class="sidebar-menu_yunifa">
            <button class="sidebar-item_yunifa" onclick="changeView('multiMonthYear')">
                <i class="fas fa-calendar-alt"></i> Tahun
            </button>
            <button class="sidebar-item_yunifa" onclick="changeView('dayGridMonth')">
                <i class="fas fa-calendar"></i> Bulan
            </button>
            <button class="sidebar-item_yunifa" onclick="changeView('timeGridWeek')">
                <i class="fas fa-calendar-week"></i> Minggu
            </button>
            <button class="sidebar-item_yunifa" onclick="changeView('timeGridDay')">
                <i class="fas fa-calendar-day"></i> Hari
            </button>
            <button class="sidebar-item_yunifa" onclick="changeView('listMonth')">
                <i class="fas fa-list"></i> Acara
            </button>
            <div class="sidebar-divider_yunifa"></div>
            <a class="sidebar-item_yunifa" href="kalender_loginYunifa.php">
                <i class="fas fa-sign-in-alt"></i> Masuk
            </a>
        </div>
        <div class="sidebar-user_yunifa">
            <i class="fas fa-user-circle"></i> Tamu
        </div>
    </aside>

    <main class="main_yunifa">
        <div class="calendar-card_yunifa">
            <div id="calendar_yunifa"></div>
        </div>
    </main>

    <div class="modal-overlay_yunifa" id="modalOverlay_yunifa">
        <div class="modal-box_yunifa" id="modalBox_yunifa"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            var BULAN = ['Januari','Februari','Maret','April','Mei','Juni',
                        'Juli','Agustus','September','Oktober','November','Desember'];

            function formatTgl(str) {
                if (!str) return '-';
                var p = str.split('-');
                return parseInt(p[2]) + ' ' + BULAN[parseInt(p[1])-1] + ' ' + p[0];
            }
            function escHtml(str) {
                if (!str) return '';
                return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
            }

            var mOverlay = document.getElementById('modalOverlay_yunifa');
            var mBox     = document.getElementById('modalBox_yunifa');

            function closeModal() {
                mOverlay.classList.remove('open_yunifa');
                document.body.style.overflow = '';
            }
            mOverlay.addEventListener('click', function(e){
                if (e.target === mOverlay) closeModal();
            });

            function openModal(event) {
                var p     = event.extendedProps;
                var warna = event.backgroundColor || '#1A73E8';
                var iconBg = warna + '22';

                var waktuHtml = '';
                if (p.seharian === 'ya') {
                    if (p.tanggal_mulai === p.tanggal_selesai) {
                        waktuHtml = formatTgl(p.tanggal_mulai) + ' &bull; Seharian';
                    } else {
                        waktuHtml = formatTgl(p.tanggal_mulai) + ' &ndash; ' + formatTgl(p.tanggal_selesai) + ' &bull; Seharian';
                    }
                } else {
                    var jam = (p.waktu_mulai ? p.waktu_mulai.slice(0,5) : '') +
                            ' &ndash; ' +
                            (p.waktu_selesai ? p.waktu_selesai.slice(0,5) : '');
                    if (p.tanggal_mulai === p.tanggal_selesai) {
                        waktuHtml = formatTgl(p.tanggal_mulai) + '<br>' + jam;
                    } else {
                        waktuHtml = formatTgl(p.tanggal_mulai) + ' ' + (p.waktu_mulai ? p.waktu_mulai.slice(0,5) : '') +
                                    '<br>&ndash; ' + formatTgl(p.tanggal_selesai) + ' ' + (p.waktu_selesai ? p.waktu_selesai.slice(0,5) : '');
                    }
                }

                var deskripsi = p.deskripsi
                    ? escHtml(p.deskripsi).replace(/\n/g,'<br>')
                    : '<span style="color:#aaa;font-style:italic;">Tidak ada deskripsi</span>';

                mBox.innerHTML =
                    '<div class="modal-header_yunifa" style="background:' + warna + ';">' +
                        '<h2>' + escHtml(event.title) + '</h2>' +
                        '<button class="modal-close_yunifa" id="btnCloseModal_yunifa"><i class="fas fa-times"></i></button>' +
                    '</div>' +
                    '<div class="modal-body_yunifa">' +
                        '<div class="detail-row_yunifa">' +
                            '<div class="detail-icon_yunifa" style="background:' + iconBg + ';color:' + warna + ';"><i class="fas fa-clock"></i></div>' +
                            '<div><div class="detail-label_yunifa">Waktu</div><div class="detail-value_yunifa">' + waktuHtml + '</div></div>' +
                        '</div>' +
                        '<div class="detail-row_yunifa">' +
                            '<div class="detail-icon_yunifa" style="background:' + iconBg + ';color:' + warna + ';"><i class="fas fa-align-left"></i></div>' +
                            '<div><div class="detail-label_yunifa">Deskripsi</div><div class="detail-value_yunifa">' + deskripsi + '</div></div>' +
                        '</div>' +
                        '<div class="detail-row_yunifa">' +
                            '<div class="detail-icon_yunifa" style="background:' + iconBg + ';color:' + warna + ';"><i class="fas fa-user"></i></div>' +
                            '<div><div class="detail-label_yunifa">Dibuat oleh</div><div class="detail-value_yunifa">' + escHtml(p.nama_guru) + '</div></div>' +
                        '</div>' +
                    '</div>';

                document.getElementById('btnCloseModal_yunifa').addEventListener('click', closeModal);
                mOverlay.classList.add('open_yunifa');
                document.body.style.overflow = 'hidden';
            }

            window.calendar_yunifa = new FullCalendar.Calendar(
                document.getElementById('calendar_yunifa'), {
                initialView: 'dayGridMonth',
                locale: 'id',
                headerToolbar: { left:'prev', center:'title', right:'next' },
                views: { multiMonthYear: { type:'multiMonth', duration:{ years:1 } } },
                buttonText: { today:'Hari Ini' },
                events: 'kalender_APIYunifa.php',
                eventClick: function(info) {
                    info.jsEvent.preventDefault(); // cegah url redirect
                    openModal(info.event);
                }
            });
            calendar_yunifa.render();

            var sidebar  = document.getElementById('sidebar_yunifa');
            var sOverlay = document.getElementById('sidebarOverlay_yunifa');
            document.getElementById('menuToggle_yunifa').addEventListener('click', function(){
                sidebar.classList.toggle('open_yunifa');
                sOverlay.classList.toggle('open_yunifa');
            });
            sOverlay.addEventListener('click', function(){
                sidebar.classList.remove('open_yunifa');
                sOverlay.classList.remove('open_yunifa');
            });
        });

        function changeView(view) {
            window.calendar_yunifa.changeView(view);
            document.getElementById('sidebar_yunifa').classList.remove('open_yunifa');
            document.getElementById('sidebarOverlay_yunifa').classList.remove('open_yunifa');
        }
    </script>
</body>
</html>
