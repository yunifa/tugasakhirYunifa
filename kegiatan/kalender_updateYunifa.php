<?php
session_start();
include "../kalender_koneksiYunifa.php";

if (!isset($_SESSION['tipe_yunifa'])) {
    header("Location: ../kalender_loginYunifa.php");
    exit;
}

// Hanya guru waka (role 1-6)
if ($_SESSION['tipe_yunifa'] != 'guru' || $_SESSION['role_yunifa'] > 6) {
    header("Location: ../kalender_dashboardYunifa.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: ../kalender_dashboardYunifa.php");
    exit;
}

$id = intval($_GET['id']);

$query = mysqli_query($koneksiYunifa, "
    SELECT * FROM kegiatan_yunifa 
    WHERE id_kegiatan_yunifa = '$id'
");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: ../kalender_dashboardYunifa.php");
    exit;
}

// ── Validasi waktu: cek tanggal DAN jam ──
$sekarang_dt = new DateTime();
$mulai_str   = $data['tanggal_mulai_yunifa'];

if ($data['seharian_yunifa'] == 'ya') {
    // seharian: batas adalah awal hari tanggal mulai (00:00)
    $mulai_dt = new DateTime($mulai_str . ' 00:00:00');
} else {
    $mulai_dt = new DateTime($mulai_str . ' ' . ($data['waktu_mulai_yunifa'] ?? '00:00:00'));
}

if ($sekarang_dt >= $mulai_dt) {
    echo "<script>
        alert('Kegiatan sudah dimulai dan tidak bisa diedit.');
        window.location='../kalender_dashboardYunifa.php';
    </script>";
    exit;
}

// ── Handle update ──
if (isset($_POST['update_yunifa'])) {

    $judul_yunifa     = mysqli_real_escape_string($koneksiYunifa, $_POST['judul_yunifa']);
    $deskripsi_yunifa = mysqli_real_escape_string($koneksiYunifa, $_POST['deskripsi_yunifa']);
    $hak_akses_yunifa = mysqli_real_escape_string($koneksiYunifa, $_POST['hak_akses_yunifa']);
    $warna_yunifa     = mysqli_real_escape_string($koneksiYunifa, $_POST['warna_yunifa']);

    if (isset($_POST['seharian_yunifa'])) {

        $seharian_yunifa        = "ya";
        $tanggal_mulai_yunifa   = $_POST['tanggal_mulai_full_yunifa'];
        $tanggal_selesai_yunifa = !empty($_POST['tanggal_selesai_full_yunifa'])
            ? $_POST['tanggal_selesai_full_yunifa']
            : $tanggal_mulai_yunifa;
        $waktu_mulai_yunifa     = '00:00';
        $waktu_selesai_yunifa   = '23:59';

    } else {

        $seharian_yunifa        = "tidak";
        $tanggal_mulai_yunifa   = $_POST['tanggal_mulai_yunifa'];
        $tanggal_selesai_yunifa = !empty($_POST['tanggal_selesai_yunifa'])
            ? $_POST['tanggal_selesai_yunifa']
            : $tanggal_mulai_yunifa;
        $waktu_mulai_yunifa     = $_POST['waktu_mulai_yunifa'];
        $waktu_selesai_yunifa   = $_POST['waktu_selesai_yunifa'];

        if ($waktu_mulai_yunifa > $waktu_selesai_yunifa) {
            echo "<script>alert('Jam tidak valid.');</script>";
            // lanjut tampil form lagi
        } elseif ($tanggal_mulai_yunifa > $tanggal_selesai_yunifa) {
            echo "<script>alert('Tanggal tidak valid.');</script>";
        } else {
            mysqli_query($koneksiYunifa, "
                UPDATE kegiatan_yunifa SET
                    judul_yunifa            = '$judul_yunifa',
                    deskripsi_yunifa        = '$deskripsi_yunifa',
                    tanggal_mulai_yunifa    = '$tanggal_mulai_yunifa',
                    tanggal_selesai_yunifa  = '$tanggal_selesai_yunifa',
                    waktu_mulai_yunifa      = '$waktu_mulai_yunifa',
                    waktu_selesai_yunifa    = '$waktu_selesai_yunifa',
                    seharian_yunifa         = '$seharian_yunifa',
                    hak_akses_yunifa        = '$hak_akses_yunifa',
                    warna_yunifa            = '$warna_yunifa'
                WHERE id_kegiatan_yunifa = '$id'
            ");
            header("Location: ../kalender_dashboardYunifa.php?updated=1");
            exit;
        }
    }

    // Kalau seharian, langsung update (tidak ada validasi jam)
    if ($seharian_yunifa === 'ya') {
        mysqli_query($koneksiYunifa, "
            UPDATE kegiatan_yunifa SET
                judul_yunifa            = '$judul_yunifa',
                deskripsi_yunifa        = '$deskripsi_yunifa',
                tanggal_mulai_yunifa    = '$tanggal_mulai_yunifa',
                tanggal_selesai_yunifa  = '$tanggal_selesai_yunifa',
                waktu_mulai_yunifa      = '$waktu_mulai_yunifa',
                waktu_selesai_yunifa    = '$waktu_selesai_yunifa',
                seharian_yunifa         = '$seharian_yunifa',
                hak_akses_yunifa        = '$hak_akses_yunifa',
                warna_yunifa            = '$warna_yunifa'
            WHERE id_kegiatan_yunifa = '$id'
        ");
        header("Location: ../kalender_dashboardYunifa.php?updated=1");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kegiatan – Kalender SMKN 2 Cimahi</title>
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
            --gray-mid:     #888;
            --text-main:    #1a1a1a;
            --text-sub:     #555;
            --overlay:      rgba(0,0,0,0.38);
            --nav-h:        68px;
            --radius-lg:    20px;
            --radius-md:    12px;
            --radius-sm:    8px;
            --shadow-card:  0 8px 32px rgba(0,0,0,0.35);
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: url('../background_smk2.jpg') no-repeat center center / cover fixed;
            min-height: 100vh;
        }
        body::before { content:"";position:fixed;inset:0;background:var(--overlay);z-index:0; }
        .navbar_yunifa {
            position:fixed;top:0;left:0;right:0;z-index:100;height:var(--nav-h);
            background:var(--blue-navbar);display:flex;align-items:center;
            justify-content:space-between;padding:0 24px;
            box-shadow:0 2px 12px rgba(0,0,0,0.3);
        }
        .navbar_yunifa img { height:46px;width:46px;border-radius:50%;object-fit:cover;border:2px solid rgba(255,255,255,0.5); }
        .navbar-title_yunifa { color:white;font-size:clamp(15px,2.2vw,22px);font-weight:800;flex:1;text-align:center;padding:0 16px; }
        .navbar-back_yunifa {
            background:white;border:none;border-radius:var(--radius-md);
            height:40px;padding:0 16px;display:flex;align-items:center;gap:8px;
            font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:700;
            color:#333;cursor:pointer;text-decoration:none;transition:background 0.2s;
        }
        .navbar-back_yunifa:hover { background:#f0f0f0; }
        .main_yunifa {
            position:relative;z-index:1;
            padding-top:calc(var(--nav-h) + 32px);padding-bottom:48px;
            display:flex;justify-content:center;
        }
        .form-card_yunifa {
            background:rgba(255,255,255,0.96);border-radius:var(--radius-lg);
            box-shadow:var(--shadow-card);width:min(560px, 96vw);overflow:hidden;
            backdrop-filter:blur(6px);animation:fadeUp 0.4s ease both;
        }
        .form-card-header_yunifa {
            background:linear-gradient(135deg, var(--blue-dark), var(--blue-primary));
            padding:22px 28px;
        }
        .form-card-header_yunifa h2 { color:white;font-size:20px;font-weight:800;display:flex;align-items:center;gap:10px; }
        .form-card-body_yunifa { padding:28px; }
        .form-group_yunifa { margin-bottom:18px; }
        .form-group_yunifa label { display:block;font-size:12px;font-weight:700;color:var(--text-sub);margin-bottom:6px;text-transform:uppercase;letter-spacing:0.4px; }
        .form-group_yunifa input[type="text"],
        .form-group_yunifa input[type="date"],
        .form-group_yunifa input[type="time"],
        .form-group_yunifa select,
        .form-group_yunifa textarea {
            width:100%;padding:11px 14px;border:1.5px solid #dde4f0;border-radius:var(--radius-sm);
            font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:500;
            color:var(--text-main);background:#fafdff;outline:none;
            transition:border-color 0.2s,box-shadow 0.2s;
        }
        .form-group_yunifa textarea { resize:vertical;min-height:90px; }
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
        .toggle-track_yunifa { width:40px;height:22px;background:#ccc;border-radius:20px;position:relative;flex-shrink:0;transition:background 0.25s; }
        .toggle-track_yunifa::after { content:"";position:absolute;top:3px;left:3px;width:16px;height:16px;background:white;border-radius:50%;transition:left 0.25s;box-shadow:0 1px 4px rgba(0,0,0,0.2); }
        .toggle-wrap_yunifa.checked_yunifa .toggle-track_yunifa { background:var(--blue-primary); }
        .toggle-wrap_yunifa.checked_yunifa .toggle-track_yunifa::after { left:21px; }
        .toggle-label_yunifa { font-size:14px;font-weight:700;color:var(--blue-dark); }
        .color-row_yunifa { display:flex;align-items:center;gap:12px; }
        .color-row_yunifa input[type="color"] { width:46px;height:40px;padding:2px;border:1.5px solid #dde4f0;border-radius:var(--radius-sm);cursor:pointer;background:none; }
        .color-swatches_yunifa { display:flex;gap:8px;flex-wrap:wrap; }
        .swatch_yunifa { width:26px;height:26px;border-radius:50%;cursor:pointer;border:2.5px solid transparent;transition:transform 0.15s,border-color 0.15s; }
        .swatch_yunifa:hover { transform:scale(1.18); }
        .swatch_yunifa.active_yunifa { border-color:#333; }
        .btn-row_yunifa { display:flex;justify-content:flex-end;gap:12px;margin-top:8px; }
        .btn_yunifa { padding:11px 28px;border-radius:var(--radius-md);font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:700;cursor:pointer;border:none;transition:background 0.18s,transform 0.1s;display:inline-flex;align-items:center;gap:8px;text-decoration:none; }
        .btn-primary_yunifa { background:var(--blue-primary);color:white;box-shadow:0 3px 10px rgba(26,115,232,0.3); }
        .btn-primary_yunifa:hover { background:var(--blue-dark);transform:translateY(-1px); }
        .btn-cancel_yunifa { background:#f0f4fd;color:var(--blue-dark);border:1.5px solid #d0daf7; }
        .btn-cancel_yunifa:hover { background:#e2eafc; }
        @keyframes fadeUp { from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)} }
        @media(max-width:600px){ .form-row_yunifa{grid-template-columns:1fr;} }
    </style>
</head>
<body>
    <nav class="navbar_yunifa">
        <img src="../logo_smk2.png" alt="Logo"
            onerror="this.style.display='none';">
        <div class="navbar-title_yunifa">Edit Kegiatan</div>
        <a class="navbar-back_yunifa" href="../kalender_dashboardYunifa.php">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </nav>

    <main class="main_yunifa">
        <div class="form-card_yunifa">
            <div class="form-card-header_yunifa">
                <h2><i class="fas fa-pen"></i> Edit Kegiatan</h2>
            </div>
            <div class="form-card-body_yunifa">
                <form method="POST">

                    <!-- Toggle seharian -->
                    <label class="toggle-wrap_yunifa <?= $data['seharian_yunifa']=='ya' ? 'checked_yunifa' : ''; ?>"
                        id="toggleWrap_yunifa" for="seharianCheck_yunifa">
                        <input type="checkbox" id="seharianCheck_yunifa" name="seharian_yunifa"
                            <?= $data['seharian_yunifa']=='ya' ? 'checked' : ''; ?>>
                        <div class="toggle-track_yunifa"></div>
                        <span class="toggle-label_yunifa">Kegiatan Seharian</span>
                    </label>

                    <!-- Judul -->
                    <div class="form-group_yunifa">
                        <label>Judul Kegiatan</label>
                        <input type="text" name="judul_yunifa"
                            value="<?= htmlspecialchars($data['judul_yunifa']); ?>" required>
                    </div>

                    <!-- Deskripsi -->
                    <div class="form-group_yunifa">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi_yunifa"><?= htmlspecialchars($data['deskripsi_yunifa']); ?></textarea>
                    </div>

                    <!-- Mode Normal -->
                    <div id="modeNormal_yunifa">
                        <div class="form-row_yunifa">
                            <div class="form-group_yunifa">
                                <label>Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai_yunifa"
                                    value="<?= $data['tanggal_mulai_yunifa']; ?>">
                            </div>
                            <div class="form-group_yunifa">
                                <label>Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai_yunifa"
                                    value="<?= $data['tanggal_selesai_yunifa']; ?>">
                            </div>
                        </div>
                        <div class="form-row_yunifa">
                            <div class="form-group_yunifa">
                                <label>Jam Mulai</label>
                                <input type="time" name="waktu_mulai_yunifa"
                                    value="<?= $data['waktu_mulai_yunifa']; ?>">
                            </div>
                            <div class="form-group_yunifa">
                                <label>Jam Selesai</label>
                                <input type="time" name="waktu_selesai_yunifa"
                                    value="<?= $data['waktu_selesai_yunifa']; ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Mode Seharian -->
                    <div id="modeFull_yunifa" style="display:none;">
                        <div class="form-row_yunifa">
                            <div class="form-group_yunifa">
                                <label>Dari Tanggal</label>
                                <input type="date" name="tanggal_mulai_full_yunifa"
                                    value="<?= $data['tanggal_mulai_yunifa']; ?>">
                            </div>
                            <div class="form-group_yunifa">
                                <label>Sampai Tanggal</label>
                                <input type="date" name="tanggal_selesai_full_yunifa"
                                    value="<?= $data['tanggal_selesai_yunifa']; ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Hak Akses -->
                    <div class="form-group_yunifa">
                        <label>Hak Akses</label>
                        <select name="hak_akses_yunifa">
                            <option value="guru"     <?= $data['hak_akses_yunifa']=='guru'     ? 'selected':'' ?>>Guru</option>
                            <option value="internal" <?= $data['hak_akses_yunifa']=='internal' ? 'selected':'' ?>>Internal</option>
                            <option value="publik"   <?= $data['hak_akses_yunifa']=='publik'   ? 'selected':'' ?>>Publik</option>
                        </select>
                    </div>

                    <!-- Warna -->
                    <div class="form-group_yunifa">
                        <label>Warna Event</label>
                        <div class="color-row_yunifa">
                            <input type="color" id="warnaInput_yunifa" name="warna_yunifa"
                                value="<?= $data['warna_yunifa']; ?>">
                            <div class="color-swatches_yunifa">
                                <?php
                                $swatches = ['#1A73E8','#E53935','#43A047','#FB8C00','#8E24AA','#00ACC1','#F4511E','#0B8043'];
                                foreach ($swatches as $sw):
                                    $active = (strtolower($data['warna_yunifa']) == strtolower($sw)) ? 'active_yunifa' : '';
                                ?>
                                <div class="swatch_yunifa <?= $active; ?>"
                                    style="background:<?= $sw; ?>;"
                                    data-color="<?= $sw; ?>"></div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="btn-row_yunifa">
                        <a href="../kalender_dashboardYunifa.php" class="btn_yunifa btn-cancel_yunifa">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        <button type="submit" name="update_yunifa" class="btn_yunifa btn-primary_yunifa">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </main>

    <script>
        (function(){
            var toggleWrap  = document.getElementById('toggleWrap_yunifa');
            var toggleCheck = document.getElementById('seharianCheck_yunifa');
            var modeNormal  = document.getElementById('modeNormal_yunifa');
            var modeFull    = document.getElementById('modeFull_yunifa');

            function applyToggle() {
                if (toggleCheck.checked) {
                    toggleWrap.classList.add('checked_yunifa');
                    modeNormal.style.display = 'none';
                    modeFull.style.display   = 'block';
                } else {
                    toggleWrap.classList.remove('checked_yunifa');
                    modeNormal.style.display = 'block';
                    modeFull.style.display   = 'none';
                }
            }
            toggleWrap.addEventListener('click', function(){
                toggleCheck.checked = !toggleCheck.checked;
                applyToggle();
            });
            applyToggle();

            var colorInput = document.getElementById('warnaInput_yunifa');
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
        })();
    </script>
</body>
</html>
