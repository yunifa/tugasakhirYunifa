<?php
session_start();
include "../kalender_koneksiYunifa.php";

if (!isset($_SESSION['tipe_yunifa'])) {
    header("Location: kalender_loginYunifa.php");
    exit;
}

$tipe_yunifa       = $_SESSION['tipe_yunifa'];
$id_user_yunifa    = $_SESSION['id_user_yunifa'];
$nama_yunifa       = $_SESSION['nama_yunifa'] ?? 'Tamu';

$pesan_yunifa      = '';
$tipe_pesan_yunifa = '';

$is_ortu_yunifa = ($tipe_yunifa === 'ortu');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ganti_pw_yunifa'])) {
    $pw_lama_yunifa  = $_POST['pw_lama']  ?? '';
    $pw_baru_yunifa  = $_POST['pw_baru']  ?? '';
    $pw_ulang_yunifa = $_POST['pw_ulang'] ?? '';

    $field_kosong_yunifa = $is_ortu_yunifa
        ? (empty($pw_baru_yunifa) || empty($pw_ulang_yunifa))
        : (empty($pw_lama_yunifa) || empty($pw_baru_yunifa) || empty($pw_ulang_yunifa));

    if ($field_kosong_yunifa) {
        $pesan_yunifa      = "Semua field wajib diisi.";
        $tipe_pesan_yunifa = 'error';
    } elseif ($pw_baru_yunifa !== $pw_ulang_yunifa) {
        $pesan_yunifa      = "Password baru dan konfirmasi tidak cocok.";
        $tipe_pesan_yunifa = 'error';
    } elseif (strlen($pw_baru_yunifa) < 8) {
        $pesan_yunifa      = "Password baru minimal 8 karakter.";
        $tipe_pesan_yunifa = 'error';
    } elseif (!preg_match('/[0-9]/', $pw_baru_yunifa)) {
        $pesan_yunifa      = "Password baru harus mengandung minimal 1 angka.";
        $tipe_pesan_yunifa = 'error';
    } elseif (!preg_match('/[^a-zA-Z0-9]/', $pw_baru_yunifa)) {
        $pesan_yunifa      = "Password baru harus mengandung minimal 1 simbol (contoh: @, #, !, _).";
        $tipe_pesan_yunifa = 'error';
    } else {
        if ($tipe_yunifa === 'admin' || $tipe_yunifa === 'guru') {
            $q_yunifa       = mysqli_query($koneksiYunifa, "SELECT password_yunifa FROM guru_yunifa WHERE id_guru_yunifa = '$id_user_yunifa'");
            $row_yunifa     = mysqli_fetch_assoc($q_yunifa);
            $hash_db_yunifa = $row_yunifa['password_yunifa'] ?? '';

            $valid_yunifa   = password_verify($pw_lama_yunifa, $hash_db_yunifa) || ($pw_lama_yunifa === $hash_db_yunifa);

            if (!$valid_yunifa) {
                $pesan_yunifa      = "Password lama tidak sesuai.";
                $tipe_pesan_yunifa = 'error';
            } else {
                $hash_baru_yunifa = password_hash($pw_baru_yunifa, PASSWORD_DEFAULT);
                mysqli_query($koneksiYunifa, "UPDATE guru_yunifa SET password_yunifa = '$hash_baru_yunifa' WHERE id_guru_yunifa = '$id_user_yunifa'");
                header("Location: kalender_readProfilYunifa.php");
                exit;
            }

        } elseif ($tipe_yunifa === 'siswa') {
            $q_yunifa       = mysqli_query($koneksiYunifa, "SELECT password_siswa_yunifa FROM siswa_yunifa WHERE id_siswa_yunifa = '$id_user_yunifa'");
            $row_yunifa     = mysqli_fetch_assoc($q_yunifa);
            $hash_db_yunifa = $row_yunifa['password_siswa_yunifa'] ?? '';

            $valid_yunifa   = password_verify($pw_lama_yunifa, $hash_db_yunifa) || ($pw_lama_yunifa === $hash_db_yunifa);

            if (!$valid_yunifa) {
                $pesan_yunifa      = "Password lama tidak sesuai.";
                $tipe_pesan_yunifa = 'error';
            } else {
                $hash_baru_yunifa = password_hash($pw_baru_yunifa, PASSWORD_DEFAULT);
                mysqli_query($koneksiYunifa, "UPDATE siswa_yunifa SET password_siswa_yunifa = '$hash_baru_yunifa' WHERE id_siswa_yunifa = '$id_user_yunifa'");
                header("Location: kalender_readProfilYunifa.php");
                exit;
            }

        } elseif ($tipe_yunifa === 'ortu') {
            $hash_baru_yunifa = password_hash($pw_baru_yunifa, PASSWORD_DEFAULT);
            mysqli_query($koneksiYunifa, "UPDATE siswa_yunifa SET password_ortu_yunifa = '$hash_baru_yunifa' WHERE id_siswa_yunifa = '$id_user_yunifa'");
            header("Location: kalender_readProfilYunifa.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password – Kalender Akademik SMKN 2 Cimahi</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --blue-primary_yunifa: #1A73E8;
            --blue-dark_yunifa:    #1558B0;
            --blue-navbar_yunifa:  #1565D8;
            --white_yunifa:        #ffffff;
            --gray-mid_yunifa:     #888;
            --text-main_yunifa:    #1a1a1a;
            --text-sub_yunifa:     #555;
            --overlay_yunifa:      rgba(0,0,0,0.38);
            --nav-h_yunifa:        68px;
            --radius-lg_yunifa:    20px;
            --radius-md_yunifa:    12px;
            --radius-sm_yunifa:    8px;
            --shadow-card_yunifa:  0 8px 32px rgba(0,0,0,0.35);
            --red_yunifa:          #dc3545;
            --red-dark_yunifa:     #b02a37;
            --green_yunifa:        #22c55e;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: url('../background_smk2.jpg') no-repeat center center / cover fixed;
            min-height: 100vh;
            overflow-x: hidden;
        }
        body::before {
            content: "";
            position: fixed; inset: 0;
            background: var(--overlay_yunifa);
            z-index: 0;
        }
        .navbar_yunifa {
            position: fixed; top: 0; left: 0; right: 0;
            z-index: 1000;
            height: var(--nav-h_yunifa);
            background: var(--blue-navbar_yunifa);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 24px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.3);
        }
        .navbar-logo_yunifa img {
            height: 46px; width: 46px; border-radius: 50%; object-fit: cover;
            border: 2px solid rgba(255,255,255,0.5);
        }
        .navbar-logo_yunifa .logo-fallback_yunifa {
            height: 46px; width: 46px; border-radius: 50%;
            background: white; display: flex; align-items: center; justify-content: center;
            color: var(--blue-primary_yunifa); font-size: 22px;
            border: 2px solid rgba(255,255,255,0.5);
        }
        .navbar-title_yunifa {
            color: var(--white_yunifa); font-size: clamp(15px,2.2vw,22px); font-weight: 800;
            letter-spacing: 0.2px; text-align: center; flex: 1; padding: 0 16px;
        }
        .navbar-back_yunifa {
            background: white; border: none; border-radius: var(--radius-md_yunifa);
            height: 40px; padding: 0 16px;
            display: flex; align-items: center; gap: 8px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px; font-weight: 700; color: #333;
            cursor: pointer; text-decoration: none; white-space: nowrap;
        }
        .navbar-back_yunifa:hover { background: #f0f0f0; }
        .main_yunifa {
            position: relative; z-index: 1;
            padding-top: calc(var(--nav-h_yunifa) + 40px);
            padding-bottom: 60px;
            display: flex; justify-content: center; align-items: flex-start;
            min-height: 100vh;
        }
        .card_yunifa {
            width: min(460px, 94vw);
            background: rgba(255,255,255,0.97);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-radius: var(--radius-lg_yunifa);
            box-shadow: var(--shadow-card_yunifa);
            padding: 36px 32px 32px;
            animation: fadeUp_yunifa 0.4s ease both;
        }
        @keyframes fadeUp_yunifa {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .card-icon_yunifa {
            width: 60px; height: 60px;
            background: #fff0f0;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 26px; color: var(--red_yunifa);
            margin: 0 auto 16px;
            border: 2px solid #ffd6d6;
        }
        .card-title_yunifa {
            text-align: center;
            font-size: 20px; font-weight: 800; color: var(--text-main_yunifa);
            margin-bottom: 4px;
        }
        .card-sub_yunifa {
            text-align: center;
            font-size: 13px; color: var(--gray-mid_yunifa);
            margin-bottom: 28px;
        }
        .card-sub_yunifa span { font-weight: 700; color: var(--blue-primary_yunifa); }
        .alert_yunifa {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 12px 16px;
            border-radius: var(--radius-sm_yunifa);
            font-size: 13px; font-weight: 600;
            margin-bottom: 20px;
            animation: fadeUp_yunifa 0.3s ease both;
        }
        .alert_yunifa.sukses { background: #f0fdf4; color: #166534; border: 1.5px solid #bbf7d0; }
        .alert_yunifa.error  { background: #fff5f5; color: #991b1b; border: 1.5px solid #fecaca; }
        .alert_yunifa i { margin-top: 1px; flex-shrink: 0; }
        .form-group_yunifa { margin-bottom: 18px; }
        .form-group_yunifa label {
            display: block;
            font-size: 11px; font-weight: 700; color: var(--text-sub_yunifa);
            text-transform: uppercase; letter-spacing: 0.5px;
            margin-bottom: 7px;
        }
        .input-wrap_yunifa { position: relative; }
        .input-wrap_yunifa input {
            width: 100%;
            padding: 11px 44px 11px 14px;
            border: 1.5px solid #dde4f0;
            border-radius: var(--radius-sm_yunifa);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px; font-weight: 500; color: var(--text-main_yunifa);
            background: #fafdff;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .input-wrap_yunifa input:focus {
            border-color: var(--blue-primary_yunifa);
            box-shadow: 0 0 0 3px rgba(26,115,232,0.12);
            background: #fff;
        }
        .toggle-pw_yunifa {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: var(--gray-mid_yunifa); font-size: 15px;
            padding: 4px;
            transition: color 0.2s;
        }
        .toggle-pw_yunifa:hover { color: var(--blue-primary_yunifa); }
        .pw-strength_yunifa {
            margin-top: 8px;
            display: flex; gap: 5px; align-items: center;
        }
        .pw-strength_yunifa .bar_yunifa {
            flex: 1; height: 4px; border-radius: 99px;
            background: #e5e7eb;
            transition: background 0.3s;
        }
        .pw-strength_yunifa .label_yunifa {
            font-size: 11px; font-weight: 700; color: var(--gray-mid_yunifa);
            white-space: nowrap; min-width: 60px; text-align: right;
        }
        .divider_yunifa { height: 1px; background: #eef0f5; margin: 6px 0 22px; }
        .btn-submit_yunifa {
            width: 100%;
            padding: 13px;
            border: none; border-radius: var(--radius-md_yunifa);
            background: var(--red_yunifa);
            color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px; font-weight: 800;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(220,53,69,0.35);
            transition: background 0.18s, transform 0.1s, box-shadow 0.18s;
            display: flex; align-items: center; justify-content: center; gap: 10px;
        }
        .btn-submit_yunifa:hover {
            background: var(--red-dark_yunifa);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(220,53,69,0.4);
        }
        .btn-submit_yunifa:active { transform: translateY(0); }
        @media (max-width: 480px) {
            .card_yunifa { padding: 28px 20px 24px; }
        }
    </style>
</head>
<body>
    <nav class="navbar_yunifa">
        <div class="navbar-logo_yunifa">
            <img src="../logo_smk2.png" alt="Logo SMKN 2 Cimahi"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="logo-fallback_yunifa" style="display:none;"><i class="fas fa-school"></i></div>
        </div>
        <div class="navbar-title_yunifa">Kalender Akademik SMKN 2 Cimahi</div>
        <a class="navbar-back_yunifa" href="kalender_readProfilYunifa.php">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </nav>

    <main class="main_yunifa">
        <div class="card_yunifa">

            <div class="card-icon_yunifa"><i class="fas fa-lock"></i></div>
            <div class="card-title_yunifa">Ganti Password</div>
            <div class="card-sub_yunifa">
                Login sebagai <span><?php echo htmlspecialchars($nama_yunifa); ?></span>
            </div>

            <?php if (!empty($pesan_yunifa)): ?>
                <div class="alert_yunifa <?php echo $tipe_pesan_yunifa; ?>">
                    <i class="fas <?php echo $tipe_pesan_yunifa === 'sukses' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                    <?php echo htmlspecialchars($pesan_yunifa); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" id="formGantiPw_yunifa">
                <input type="hidden" name="ganti_pw_yunifa" value="1">

                <?php if (!$is_ortu_yunifa): ?>
                <div class="form-group_yunifa">
                    <label>Password Saat Ini</label>
                    <div class="input-wrap_yunifa">
                        <input type="password" name="pw_lama" id="pw_lama_yunifa"
                               placeholder="Masukkan password saat ini" autocomplete="current-password" required>
                        <button type="button" class="toggle-pw_yunifa" onclick="togglePw_yunifa('pw_lama_yunifa', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="divider_yunifa"></div>
                <?php endif; ?>

                <div class="form-group_yunifa">
                    <label>Password Baru</label>
                    <div class="input-wrap_yunifa">
                        <input type="password" name="pw_baru" id="pw_baru_yunifa"
                               placeholder="Min. 8 karakter, ada angka & simbol" autocomplete="new-password"
                               oninput="cekKekuatan_yunifa(this.value)" required>
                        <button type="button" class="toggle-pw_yunifa" onclick="togglePw_yunifa('pw_baru_yunifa', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="pw-strength_yunifa" id="strengthBar_yunifa" style="display:none;">
                        <div class="bar_yunifa" id="bar1_yunifa"></div>
                        <div class="bar_yunifa" id="bar2_yunifa"></div>
                        <div class="bar_yunifa" id="bar3_yunifa"></div>
                        <div class="label_yunifa" id="strengthLabel_yunifa"></div>
                    </div>
                </div>

                <div class="form-group_yunifa">
                    <label>Konfirmasi Password Baru</label>
                    <div class="input-wrap_yunifa">
                        <input type="password" name="pw_ulang" id="pw_ulang_yunifa"
                               placeholder="Ulangi password baru" autocomplete="new-password"
                               oninput="cekMatch_yunifa()" required>
                        <button type="button" class="toggle-pw_yunifa" onclick="togglePw_yunifa('pw_ulang_yunifa', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-submit_yunifa">
                    <i class="fas fa-key"></i> Simpan Password Baru
                </button>
            </form>
        </div>
    </main>

    <script>
        function togglePw_yunifa(inputId_yunifa, btn_yunifa) {
            var input_yunifa = document.getElementById(inputId_yunifa);
            var icon_yunifa  = btn_yunifa.querySelector('i');
            if (input_yunifa.type === 'password') {
                input_yunifa.type = 'text';
                icon_yunifa.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input_yunifa.type = 'password';
                icon_yunifa.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        function cekKekuatan_yunifa(pw_yunifa) {
            var bar_yunifa   = document.getElementById('strengthBar_yunifa');
            var b1_yunifa    = document.getElementById('bar1_yunifa');
            var b2_yunifa    = document.getElementById('bar2_yunifa');
            var b3_yunifa    = document.getElementById('bar3_yunifa');
            var lbl_yunifa   = document.getElementById('strengthLabel_yunifa');

            if (pw_yunifa.length === 0) { bar_yunifa.style.display = 'none'; return; }
            bar_yunifa.style.display = 'flex';

            var adaAngka_yunifa  = /[0-9]/.test(pw_yunifa);
            var adaSimbol_yunifa = /[^a-zA-Z0-9]/.test(pw_yunifa);
            var skor_yunifa = 0;
            if (pw_yunifa.length >= 8) skor_yunifa++;
            if (pw_yunifa.length >= 8 && (adaAngka_yunifa || adaSimbol_yunifa)) skor_yunifa++;
            if (pw_yunifa.length >= 8 && adaAngka_yunifa && adaSimbol_yunifa) skor_yunifa++;

            var warna_yunifa = ['#ef4444', '#f59e0b', '#22c55e'];
            var label_yunifa = ['Lemah', 'Sedang', 'Kuat'];
            var aktif_yunifa = warna_yunifa[skor_yunifa - 1] || '#ef4444';

            b1_yunifa.style.background = skor_yunifa >= 1 ? aktif_yunifa : '#e5e7eb';
            b2_yunifa.style.background = skor_yunifa >= 2 ? aktif_yunifa : '#e5e7eb';
            b3_yunifa.style.background = skor_yunifa >= 3 ? aktif_yunifa : '#e5e7eb';
            lbl_yunifa.textContent     = label_yunifa[skor_yunifa - 1] || 'Lemah';
            lbl_yunifa.style.color     = aktif_yunifa;
        }

        function cekMatch_yunifa() {
            var baru_yunifa  = document.getElementById('pw_baru_yunifa').value;
            var ulang_yunifa = document.getElementById('pw_ulang_yunifa');
            if (ulang_yunifa.value === '') {
                ulang_yunifa.style.borderColor = '';
                return;
            }
            ulang_yunifa.style.borderColor = (baru_yunifa === ulang_yunifa.value) ? '#22c55e' : '#ef4444';
        }
    </script>
</body>
</html>
