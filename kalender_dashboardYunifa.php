<?php
    session_start();
    if (!isset($_SESSION['tipe_yunifa'])) {
        header("Location: kalender_loginYunifa.php");
        exit;
    }
    $id_user_yunifa = $_SESSION['id_user_yunifa'];
    $tipe_yunifa    = $_SESSION['tipe_yunifa'] ?? null;
    $role_yunifa    = $_SESSION['role_yunifa'] ?? 0;
    $nama_yunifa    = $_SESSION['nama_yunifa'] ?? 'Tamu';

    $isAdmin_yunifa      = ($tipe_yunifa == 'admin');
    $isGuruWaka_yunifa   = ($tipe_yunifa == 'guru' && $role_yunifa >= 1 && $role_yunifa <= 6);
    $isGuruBiasa_yunifa  = ($tipe_yunifa == 'guru' && $role_yunifa == 7);
    $isSiswaOrtu_yunifa  = ($tipe_yunifa == 'siswa' || $tipe_yunifa == 'ortu');
?>
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
        --blue-light:   #EBF3FD;
        --white:        #ffffff;
        --gray-light:   #c0c0c0;
        --gray-mid:     #888;
        --text-main:    #1a1a1a;
        --text-sub:     #555;
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
    .navbar-title_yunifa { color:var(--white);font-size:clamp(15px,2.2vw,22px);font-weight:800;letter-spacing:0.2px;text-align:center;flex:1;padding:0 16px; }
    .navbar-hamburger_yunifa {
        background:white;border:none;border-radius:var(--radius-md);
        width:44px;height:44px;display:flex;flex-direction:column;
        align-items:center;justify-content:center;gap:5px;cursor:pointer;transition:background 0.2s;flex-shrink:0;
    }
    .navbar-hamburger_yunifa:hover { background:#f0f0f0; }
    .navbar-hamburger_yunifa span { display:block;width:22px;height:2.5px;background:#222;border-radius:2px;transition:all 0.3s; }
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
    .sidebar-user_yunifa {
        padding:16px 24px;display:flex;align-items:center;gap:10px;
        border-top:1px solid #e5e5e5;font-size:15px;font-weight:700;color:#1a1a1a;
    }
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
        box-shadow:0 2px 6px rgba(0,0,0,0.1);transition:background 0.15s,box-shadow 0.15s;padding:0 !important;
    }
    .fc .fc-prev-button:hover,.fc .fc-next-button:hover { background:#f0f0f0 !important;box-shadow:0 3px 10px rgba(0,0,0,0.15); }
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
        background:rgba(0,0,0,0.45);z-index:2000;
        align-items:center;justify-content:center;
        padding:20px;overflow-y:auto;
    }
    .modal-overlay_yunifa.open_yunifa { display:flex; }
    .modal-box_yunifa {
        background:white;border-radius:var(--radius-lg);
        box-shadow:0 24px 64px rgba(0,0,0,0.35);
        animation:modalIn 0.3s cubic-bezier(.34,1.56,.64,1) both;
        position:relative;overflow:hidden;
    }
    @keyframes modalIn {
        from { opacity:0;transform:scale(0.92) translateY(24px); }
        to   { opacity:1;transform:scale(1) translateY(0); }
    }
    #modalTambah_yunifa .modal-box_yunifa {
        width:min(540px, 100%);max-height:90vh;overflow-y:auto;
    }
    .modal-header-tambah_yunifa {
        background:linear-gradient(135deg, var(--blue-dark), var(--blue-primary));
        padding:20px 24px;display:flex;align-items:center;justify-content:space-between;
    }
    .modal-header-tambah_yunifa h2 { color:white;font-size:18px;font-weight:800;display:flex;align-items:center;gap:10px; }
    #modalDetail_yunifa .modal-box_yunifa { width:min(480px, 100%); }
    .modal-header-detail_yunifa { padding:22px 24px 18px;position:relative; }
    .modal-header-detail_yunifa h2 { color:white;font-size:19px;font-weight:800;line-height:1.3;padding-right:40px; }
    .modal-close_yunifa {
        background:rgba(255,255,255,0.2);border:none;color:white;
        width:34px;height:34px;border-radius:50%;cursor:pointer;
        display:flex;align-items:center;justify-content:center;
        font-size:15px;transition:background 0.2s;flex-shrink:0;
    }
    .modal-close_yunifa:hover { background:rgba(255,255,255,0.35); }
    .modal-header-detail_yunifa .modal-close_yunifa { position:absolute;top:16px;right:16px; }
    .modal-body_yunifa { padding:24px; }
    .form-group_yunifa { margin-bottom:18px; }
    .form-group_yunifa label { display:block;font-size:12px;font-weight:700;color:var(--text-sub);margin-bottom:6px;text-transform:uppercase;letter-spacing:0.4px; }
    .form-group_yunifa input[type="text"],
    .form-group_yunifa input[type="date"],
    .form-group_yunifa input[type="time"],
    .form-group_yunifa select,
    .form-group_yunifa textarea {
        width:100%;padding:11px 14px;border:1.5px solid #dde4f0;border-radius:var(--radius-sm);
        font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:500;color:var(--text-main);
        background:#fafdff;outline:none;transition:border-color 0.2s,box-shadow 0.2s;
    }
    .form-group_yunifa textarea { resize:vertical;min-height:80px; }
    .form-group_yunifa input:focus,.form-group_yunifa select:focus,.form-group_yunifa textarea:focus {
        border-color:var(--blue-primary);box-shadow:0 0 0 3px rgba(26,115,232,0.12);background:white;
    }
    .form-row_yunifa { display:grid;grid-template-columns:1fr 1fr;gap:14px; }
    .toggle-wrap_yunifa {
        display:flex;align-items:center;gap:12px;padding:12px 14px;
        background:var(--blue-light);border-radius:var(--radius-sm);
        margin-bottom:18px;cursor:pointer;user-select:none;
    }
    .toggle-wrap_yunifa input[type="checkbox"] { display:none; }
    .toggle-track_yunifa {
        width:40px;height:22px;background:#ccc;border-radius:20px;
        position:relative;flex-shrink:0;transition:background 0.25s;
    }
    .toggle-track_yunifa::after {
        content:"";position:absolute;top:3px;left:3px;
        width:16px;height:16px;background:white;border-radius:50%;
        transition:left 0.25s;box-shadow:0 1px 4px rgba(0,0,0,0.2);
    }
    .toggle-wrap_yunifa.checked_yunifa .toggle-track_yunifa { background:var(--blue-primary); }
    .toggle-wrap_yunifa.checked_yunifa .toggle-track_yunifa::after { left:21px; }
    .toggle-label_yunifa { font-size:14px;font-weight:700;color:var(--blue-dark); }
    .color-row_yunifa { display:flex;align-items:center;gap:12px; }
    .color-row_yunifa input[type="color"] { width:46px;height:40px;padding:2px;border:1.5px solid #dde4f0;border-radius:var(--radius-sm);cursor:pointer;background:none; }
    .color-swatches_yunifa { display:flex;gap:8px;flex-wrap:wrap; }
    .swatch_yunifa { width:26px;height:26px;border-radius:50%;cursor:pointer;border:2.5px solid transparent;transition:transform 0.15s,border-color 0.15s; }
    .swatch_yunifa:hover { transform:scale(1.18); }
    .swatch_yunifa.active_yunifa { border-color:#333; }
    .modal-footer_yunifa { display:flex;justify-content:flex-end;gap:12px;padding:0 24px 24px; }
    .btn_yunifa { padding:11px 26px;border-radius:var(--radius-md);font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:700;cursor:pointer;border:none;transition:background 0.18s,transform 0.1s;display:inline-flex;align-items:center;gap:8px; }
    .btn-primary_yunifa { background:var(--blue-primary);color:white;box-shadow:0 3px 10px rgba(26,115,232,0.30); }
    .btn-primary_yunifa:hover { background:var(--blue-dark);transform:translateY(-1px); }
    .btn-cancel_yunifa { background:#f0f4fd;color:var(--blue-dark);border:1.5px solid #d0daf7; }
    .btn-cancel_yunifa:hover { background:#e2eafc; }
    .form-error_yunifa { background:#fef2f2;color:#dc2626;border:1px solid #fca5a5;border-radius:var(--radius-sm);padding:10px 14px;font-size:13px;font-weight:600;margin-bottom:16px;display:none; }
    .detail-row_yunifa { display:flex;align-items:flex-start;gap:12px;padding:12px 0;border-bottom:1px solid #f0f0f0; }
    .detail-row_yunifa:last-child { border-bottom:none; }
    .detail-icon_yunifa { width:34px;height:34px;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0;margin-top:1px; }
    .detail-label_yunifa { font-size:11px;font-weight:700;color:var(--gray-mid);text-transform:uppercase;letter-spacing:0.4px;margin-bottom:3px; }
    .detail-value_yunifa { font-size:14px;font-weight:600;color:var(--text-main);line-height:1.6; }
    .badge-akses_yunifa {
        display:inline-block;font-size:11px;font-weight:700;
        padding:3px 10px;border-radius:20px;
    }
    .toast_yunifa {
        position:fixed;bottom:28px;left:50%;transform:translateX(-50%) translateY(20px);
        background:#22c55e;color:white;padding:12px 28px;border-radius:40px;
        font-size:14px;font-weight:700;box-shadow:0 4px 16px rgba(0,0,0,0.2);
        opacity:0;pointer-events:none;transition:opacity 0.35s,transform 0.35s;z-index:9999;
    }
    .toast_yunifa.show_yunifa { opacity:1;transform:translateX(-50%) translateY(0); }

    @keyframes fadeUp { from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)} }
    @media(max-width:600px){
        .form-row_yunifa { grid-template-columns:1fr; }
        .modal-box_yunifa { max-height:95vh; }
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
            <button class="sidebar-item_yunifa" onclick="changeView('multiMonthYear')"><i class="fas fa-calendar-alt"></i> Tahun</button>
            <button class="sidebar-item_yunifa" onclick="changeView('dayGridMonth')"><i class="fas fa-calendar"></i> Bulan</button>
            <button class="sidebar-item_yunifa" onclick="changeView('timeGridWeek')"><i class="fas fa-calendar-week"></i> Minggu</button>
            <button class="sidebar-item_yunifa" onclick="changeView('timeGridDay')"><i class="fas fa-calendar-day"></i> Hari</button>
            <button class="sidebar-item_yunifa" onclick="changeView('listMonth')"><i class="fas fa-list"></i> Acara</button>
            <?php if ($isAdmin_yunifa): ?>
                <a class="sidebar-item_yunifa" href="manage/kalender_manageUserYunifa.php"><i class="fas fa-user-cog"></i> Kelola User</a>
            <?php endif; ?>
            <?php if ($isGuruWaka_yunifa): ?>
                <button class="sidebar-item_yunifa" onclick="openModalTambah(); closeSidebar();">
                    <i class="fas fa-plus"></i> Tambah Kegiatan
                </button>
                <a class="sidebar-item_yunifa" href="kalender_downloadYunifa.php"><i class="fas fa-file-pdf"></i> Download Kegiatan </a>
            <?php endif; ?>
            <?php if ($isGuruBiasa_yunifa): ?>
                <div class="sidebar-item_yunifa"><i class="fas fa-lock"></i> Kegiatan Guru</div>
                <a class="sidebar-item_yunifa" href="kalender_downloadYunifa.php"><i class="fas fa-file-pdf"></i> Download Kegiatan </a>
            <?php endif; ?>
            <?php if ($isSiswaOrtu_yunifa): ?>
            <?php endif; ?>
            <div class="sidebar-divider_yunifa"></div>
            <a class="sidebar-item_yunifa" href="profil/kalender_readProfilYunifa.php"><i class="fas fa-user"></i> Lihat Profil</a>
            <a class="sidebar-item_yunifa" href="profil/kalender_logoutYunifa.php" onclick="return confirm('Yakin mau logout?')">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
        <div class="sidebar-user_yunifa">
            <i class="fas fa-user-circle"></i>
            <?php echo htmlspecialchars($nama_yunifa); ?>
        </div>
    </aside>

    <main class="main_yunifa">
        <div class="calendar-card_yunifa">
            <div id="calendar_yunifa"></div>
        </div>
    </main>

    <div class="modal-overlay_yunifa" id="modalDetail_yunifa">
        <div class="modal-box_yunifa" id="modalDetailBox_yunifa"></div>
    </div>

    <div class="modal-overlay_yunifa" id="modalTambah_yunifa">
        <div class="modal-box_yunifa">
            <div class="modal-header-tambah_yunifa">
                <h2><i class="fas fa-calendar-plus"></i> Tambah Kegiatan</h2>
                <button class="modal-close_yunifa" id="modalCloseTambah_yunifa"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body_yunifa">
                <div class="form-error_yunifa" id="formError_yunifa"></div>
                <label class="toggle-wrap_yunifa" id="toggleWrap_yunifa" for="seharianCheckYunifa">
                    <input type="checkbox" id="seharianCheckYunifa">
                    <div class="toggle-track_yunifa"></div>
                    <span class="toggle-label_yunifa">Kegiatan Seharian</span>
                </label>
                <div class="form-group_yunifa">
                    <label>Judul Kegiatan</label>
                    <input type="text" id="judulYunifa" placeholder="Contoh: Ujian Tengah Semester" required>
                </div>
                <div class="form-group_yunifa">
                    <label>Deskripsi</label>
                    <textarea id="deskripsiYunifa" placeholder="Keterangan tambahan (opsional)"></textarea>
                </div>
                <div id="modeNormalYunifa">
                    <div class="form-row_yunifa">
                        <div class="form-group_yunifa"><label>Tanggal Mulai</label><input type="date" id="tanggalMulaiYunifa"></div>
                        <div class="form-group_yunifa"><label>Tanggal Selesai</label><input type="date" id="tanggalSelesaiYunifa"></div>
                    </div>
                    <div class="form-row_yunifa">
                        <div class="form-group_yunifa"><label>Jam Mulai</label><input type="time" id="waktuMulaiYunifa"></div>
                        <div class="form-group_yunifa"><label>Jam Selesai</label><input type="time" id="waktuSelesaiYunifa"></div>
                    </div>
                </div>
                <div id="modeFullYunifa" style="display:none;">
                    <div class="form-row_yunifa">
                        <div class="form-group_yunifa"><label>Dari Tanggal</label><input type="date" id="tanggalMulaiFullYunifa"></div>
                        <div class="form-group_yunifa"><label>Sampai Tanggal</label><input type="date" id="tanggalSelesaiFullYunifa"></div>
                    </div>
                </div>
                <div class="form-group_yunifa">
                    <label>Hak Akses</label>
                    <select id="hakAksesYunifa">
                        <option value="guru">Guru</option>
                        <option value="internal">Internal</option>
                        <option value="publik">Publik</option>
                    </select>
                </div>
                <div class="form-group_yunifa">
                    <label>Warna Event</label>
                    <div class="color-row_yunifa">
                        <input type="color" id="warnaYunifa" value="#1A73E8">
                        <div class="color-swatches_yunifa" id="swatches_yunifa">
                            <div class="swatch_yunifa active_yunifa" style="background:#1A73E8;" data-color="#1A73E8"></div>
                            <div class="swatch_yunifa" style="background:#E53935;" data-color="#E53935"></div>
                            <div class="swatch_yunifa" style="background:#43A047;" data-color="#43A047"></div>
                            <div class="swatch_yunifa" style="background:#FB8C00;" data-color="#FB8C00"></div>
                            <div class="swatch_yunifa" style="background:#8E24AA;" data-color="#8E24AA"></div>
                            <div class="swatch_yunifa" style="background:#00ACC1;" data-color="#00ACC1"></div>
                            <div class="swatch_yunifa" style="background:#F4511E;" data-color="#F4511E"></div>
                            <div class="swatch_yunifa" style="background:#0B8043;" data-color="#0B8043"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer_yunifa">
                <button class="btn_yunifa btn-cancel_yunifa" id="btnBatal_yunifa"><i class="fas fa-times"></i> Batal</button>
                <button class="btn_yunifa btn-primary_yunifa" id="btnSimpan_yunifa"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </div>
    </div>

    <div class="toast_yunifa" id="toast_yunifa"></div>

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

            window.calendar_yunifa = new FullCalendar.Calendar(
                document.getElementById('calendar_yunifa'), {
                initialView: 'dayGridMonth',
                locale: 'id',
                headerToolbar: { left:'prev', center:'title', right:'next' },
                views: { multiMonthYear: { type:'multiMonth', duration:{ years:1 } } },
                buttonText: { today:'Hari Ini' },
                events: 'kalender_APIYunifa.php',
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    openModalDetail(info.event);
                },
                dateClick: function(info) {
                    <?php if ($isGuruWaka_yunifa): ?>
                    openModalTambah(info.dateStr);
                    <?php endif; ?>
                }
            });
            calendar_yunifa.render();

            var sidebar  = document.getElementById('sidebar_yunifa');
            var sOverlay = document.getElementById('sidebarOverlay_yunifa');
            window.closeSidebar = function() {
                sidebar.classList.remove('open_yunifa');
                sOverlay.classList.remove('open_yunifa');
            };
            document.getElementById('menuToggle_yunifa').addEventListener('click', function(){
                sidebar.classList.contains('open_yunifa') ? closeSidebar() : (function(){
                    sidebar.classList.add('open_yunifa'); sOverlay.classList.add('open_yunifa');
                })();
            });
            sOverlay.addEventListener('click', closeSidebar);

            var mDetail    = document.getElementById('modalDetail_yunifa');
            var mDetailBox = document.getElementById('modalDetailBox_yunifa');

            function closeModalDetail() {
                mDetail.classList.remove('open_yunifa');
                document.body.style.overflow = '';
            }
            mDetail.addEventListener('click', function(e){ if(e.target===mDetail) closeModalDetail(); });

            function openModalDetail(event) {
                var p      = event.extendedProps;
                var warna  = event.backgroundColor || '#1A73E8';
                var iconBg = warna + '22';
                var id     = event.id;

                var waktuHtml = '';
                if (p.seharian === 'ya') {
                    waktuHtml = (p.tanggal_mulai === p.tanggal_selesai)
                        ? formatTgl(p.tanggal_mulai) + ' &bull; Seharian'
                        : formatTgl(p.tanggal_mulai) + ' &ndash; ' + formatTgl(p.tanggal_selesai) + ' &bull; Seharian';
                } else {
                    var jam = (p.waktu_mulai ? p.waktu_mulai.slice(0,5) : '') + ' &ndash; ' + (p.waktu_selesai ? p.waktu_selesai.slice(0,5) : '');
                    waktuHtml = (p.tanggal_mulai === p.tanggal_selesai)
                        ? formatTgl(p.tanggal_mulai) + '<br>' + jam
                        : formatTgl(p.tanggal_mulai) + ' ' + (p.waktu_mulai||'').slice(0,5) + '<br>&ndash; ' + formatTgl(p.tanggal_selesai) + ' ' + (p.waktu_selesai||'').slice(0,5);
                }

                var deskripsi = p.deskripsi
                    ? escHtml(p.deskripsi).replace(/\n/g,'<br>')
                    : '<span style="color:#aaa;font-style:italic;">Tidak ada deskripsi</span>';

                var badgeColor = { guru:'#8E24AA', internal:'#FB8C00', publik:'#43A047' };
                var badgeBg    = { guru:'#F3E5F5', internal:'#FFF3E0', publik:'#E8F5E9' };
                var akses      = p.hak_akses || 'publik';
                var badgeHtml  = '<span class="badge-akses_yunifa" style="background:' + (badgeBg[akses]||'#eee') + ';color:' + (badgeColor[akses]||'#333') + ';">' + akses.charAt(0).toUpperCase() + akses.slice(1) + '</span>';

                var now = new Date();
                var mulaiStr = p.tanggal_mulai + 'T' + (p.seharian === 'ya' ? '00:00:00' : (p.waktu_mulai || '00:00:00'));
                var mulaiDt  = new Date(mulaiStr);
                var sudahMulai = (now >= mulaiDt);

                <?php if ($isGuruWaka_yunifa): ?>
                var aksiBtns = '';
                if (!sudahMulai) {
                    aksiBtns =
                        '<div style="display:flex;gap:10px;padding:16px 24px 20px;border-top:1px solid #f0f0f0;">' +
                            '<a href="kegiatan/kalender_updateYunifa.php?id=' + id + '" ' +
                            'style="flex:1;padding:10px;border-radius:10px;background:#EBF3FD;color:#1558B0;font-size:13px;font-weight:700;text-align:center;text-decoration:none;display:flex;align-items:center;justify-content:center;gap:6px;">' +
                                '<i class="fas fa-pen"></i> Edit' +
                            '</a>' +
                            '<button onclick="hapusKegiatan(' + id + ')" ' +
                            'style="flex:1;padding:10px;border-radius:10px;background:#fef2f2;color:#dc2626;font-size:13px;font-weight:700;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;">' +
                                '<i class="fas fa-trash"></i> Hapus' +
                            '</button>' +
                        '</div>';
                } else {
                    aksiBtns =
                        '<div style="padding:12px 24px 18px;">' +
                            '<div style="background:#f8f8f8;border-radius:8px;padding:10px 14px;font-size:12px;font-weight:600;color:#888;display:flex;align-items:center;gap:8px;">' +
                                '<i class="fas fa-lock"></i> Kegiatan sudah dimulai, tidak dapat diedit atau dihapus.' +
                            '</div>' +
                        '</div>';
                }
                <?php else: ?>
                var aksiBtns = '';
                <?php endif; ?>

                mDetailBox.innerHTML =
                    '<div class="modal-header-detail_yunifa" style="background:' + warna + ';">' +
                        '<h2>' + escHtml(event.title) + '</h2>' +
                        '<button class="modal-close_yunifa" id="btnCloseDetail_yunifa"><i class="fas fa-times"></i></button>' +
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
                        '<div class="detail-row_yunifa">' +
                            '<div class="detail-icon_yunifa" style="background:' + iconBg + ';color:' + warna + ';"><i class="fas fa-lock-open"></i></div>' +
                            '<div><div class="detail-label_yunifa">Hak Akses</div><div class="detail-value_yunifa">' + badgeHtml + '</div></div>' +
                        '</div>' +
                    '</div>' +
                    aksiBtns;

                document.getElementById('btnCloseDetail_yunifa').addEventListener('click', closeModalDetail);
                mDetail.classList.add('open_yunifa');
                document.body.style.overflow = 'hidden';
            }

            window.hapusKegiatan = function(id) {
                if (!confirm('Yakin ingin menghapus kegiatan ini?')) return;
                fetch('kegiatan/kalender_deleteYunifa.php?id=' + id, { method: 'POST' })
                .then(function(){
                    closeModalDetail();
                    calendar_yunifa.refetchEvents();
                    showToast('🗑 Kegiatan berhasil dihapus!');
                })
                .catch(function(){
                    alert('Gagal menghapus. Coba lagi.');
                });
            };

            var mTambah   = document.getElementById('modalTambah_yunifa');
            var btnSimpan = document.getElementById('btnSimpan_yunifa');

            function closeModalTambah() {
                mTambah.classList.remove('open_yunifa');
                document.body.style.overflow = '';
            }
            window.openModalTambah = function(dateStr) {
                document.getElementById('judulYunifa').value     = '';
                document.getElementById('deskripsiYunifa').value = '';
                document.getElementById('formError_yunifa').style.display = 'none';
                if (dateStr) {
                    document.getElementById('tanggalMulaiYunifa').value     = dateStr;
                    document.getElementById('tanggalMulaiFullYunifa').value = dateStr;
                }
                mTambah.classList.add('open_yunifa');
                document.body.style.overflow = 'hidden';
            };
            document.getElementById('modalCloseTambah_yunifa').addEventListener('click', closeModalTambah);
            document.getElementById('btnBatal_yunifa').addEventListener('click', closeModalTambah);
            mTambah.addEventListener('click', function(e){ if(e.target===mTambah) closeModalTambah(); });

            var toggleWrap  = document.getElementById('toggleWrap_yunifa');
            var toggleCheck = document.getElementById('seharianCheckYunifa');
            var modeNormal  = document.getElementById('modeNormalYunifa');
            var modeFull    = document.getElementById('modeFullYunifa');
            toggleWrap.addEventListener('click', function(){
                toggleCheck.checked = !toggleCheck.checked;
                if (toggleCheck.checked) {
                    toggleWrap.classList.add('checked_yunifa');
                    modeNormal.style.display='none'; modeFull.style.display='block';
                } else {
                    toggleWrap.classList.remove('checked_yunifa');
                    modeNormal.style.display='block'; modeFull.style.display='none';
                }
            });

            var colorInput = document.getElementById('warnaYunifa');
            document.querySelectorAll('.swatch_yunifa').forEach(function(sw){
                sw.addEventListener('click', function(){
                    document.querySelectorAll('.swatch_yunifa').forEach(s => s.classList.remove('active_yunifa'));
                    sw.classList.add('active_yunifa');
                    colorInput.value = sw.dataset.color;
                });
            });
            colorInput.addEventListener('input', function(){
                document.querySelectorAll('.swatch_yunifa').forEach(s => s.classList.remove('active_yunifa'));
            });

            btnSimpan.addEventListener('click', function(){
                var errBox = document.getElementById('formError_yunifa');
                errBox.style.display = 'none';
                var judul    = document.getElementById('judulYunifa').value.trim();
                var deskripsi= document.getElementById('deskripsiYunifa').value.trim();
                var hakAkses = document.getElementById('hakAksesYunifa').value;
                var warna    = document.getElementById('warnaYunifa').value;
                var seharian = toggleCheck.checked;

                if (!judul) { errBox.textContent='Judul kegiatan wajib diisi.'; errBox.style.display='block'; return; }

                var fd = new FormData();
                fd.append('simpan_yunifa','1');
                fd.append('judul_yunifa', judul);
                fd.append('deskripsi_yunifa', deskripsi);
                fd.append('hak_akses_yunifa', hakAkses);
                fd.append('warna_yunifa', warna);

                if (seharian) {
                    var tM = document.getElementById('tanggalMulaiFullYunifa').value;
                    var tS = document.getElementById('tanggalSelesaiFullYunifa').value;
                    if (!tM) { errBox.textContent='Tanggal mulai wajib diisi.'; errBox.style.display='block'; return; }
                    fd.append('seharian_yunifa','1');
                    fd.append('tanggal_mulai_full_yunifa', tM);
                    fd.append('tanggal_selesai_full_yunifa', tS);
                } else {
                    var tM = document.getElementById('tanggalMulaiYunifa').value;
                    var tS = document.getElementById('tanggalSelesaiYunifa').value;
                    var wM = document.getElementById('waktuMulaiYunifa').value;
                    var wS = document.getElementById('waktuSelesaiYunifa').value;
                    if (!tM)  { errBox.textContent='Tanggal mulai wajib diisi.'; errBox.style.display='block'; return; }
                    if (!wM)  { errBox.textContent='Jam mulai wajib diisi.'; errBox.style.display='block'; return; }
                    if (!wS)  { errBox.textContent='Jam selesai wajib diisi.'; errBox.style.display='block'; return; }
                    if (tM > tS && tS) { errBox.textContent='Tanggal tidak valid.'; errBox.style.display='block'; return; }
                    if (wM > wS) { errBox.textContent='Jam mulai tidak boleh melebihi jam selesai.'; errBox.style.display='block'; return; }
                    fd.append('tanggal_mulai_yunifa', tM);
                    fd.append('tanggal_selesai_yunifa', tS);
                    fd.append('waktu_mulai_yunifa', wM);
                    fd.append('waktu_selesai_yunifa', wS);
                }

                btnSimpan.disabled = true;
                btnSimpan.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

                fetch('kegiatan/kalender_createYunifa.php', { method:'POST', body:fd })
                .then(function(r){ return r.text(); })
                .then(function(text){
                    btnSimpan.disabled = false;
                    btnSimpan.innerHTML = '<i class="fas fa-save"></i> Simpan';
                    if (text.includes('Error')) {
                        errBox.textContent='Gagal menyimpan. Cek koneksi database.'; errBox.style.display='block'; return;
                    }
                    closeModalTambah();
                    calendar_yunifa.refetchEvents();
                    showToast('✓ Kegiatan berhasil ditambahkan!');
                })
                .catch(function(){
                    btnSimpan.disabled = false;
                    btnSimpan.innerHTML = '<i class="fas fa-save"></i> Simpan';
                    errBox.textContent='Terjadi kesalahan jaringan.'; errBox.style.display='block';
                });
            });

            window.showToast = function(msg) {
                var t = document.getElementById('toast_yunifa');
                t.textContent = msg;
                t.classList.add('show_yunifa');
                setTimeout(function(){ t.classList.remove('show_yunifa'); }, 3000);
            };
        });

        function changeView(view) {
            window.calendar_yunifa.changeView(view);
            document.getElementById('sidebar_yunifa').classList.remove('open_yunifa');
            document.getElementById('sidebarOverlay_yunifa').classList.remove('open_yunifa');
        }
    </script>
</body>
</html>
