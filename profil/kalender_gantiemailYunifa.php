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

if ($tipe_yunifa === 'admin' || $tipe_yunifa === 'guru') {
    $q_cur_yunifa          = mysqli_query($koneksiYunifa, "SELECT email_guru_yunifa FROM guru_yunifa WHERE id_guru_yunifa = '$id_user_yunifa'");
    $row_cur_yunifa        = mysqli_fetch_assoc($q_cur_yunifa);
    $email_sekarang_yunifa = $row_cur_yunifa['email_guru_yunifa'] ?? '';
} elseif ($tipe_yunifa === 'siswa') {
    $q_cur_yunifa          = mysqli_query($koneksiYunifa, "SELECT email_siswa_yunifa FROM siswa_yunifa WHERE id_siswa_yunifa = '$id_user_yunifa'");
    $row_cur_yunifa        = mysqli_fetch_assoc($q_cur_yunifa);
    $email_sekarang_yunifa = $row_cur_yunifa['email_siswa_yunifa'] ?? '';
} elseif ($tipe_yunifa === 'ortu') {
    $q_cur_yunifa          = mysqli_query($koneksiYunifa, "SELECT email_ortu_yunifa FROM siswa_yunifa WHERE id_siswa_yunifa = '$id_user_yunifa'");
    $row_cur_yunifa        = mysqli_fetch_assoc($q_cur_yunifa);
    $email_sekarang_yunifa = $row_cur_yunifa['email_ortu_yunifa'] ?? '';
} else {
    $email_sekarang_yunifa = '';
}

$label_email_yunifa  = match($tipe_yunifa) {
    'siswa' => 'Email Siswa',
    'ortu'  => 'Email Orang Tua',
    default => 'Email',
};

$email_kosong_yunifa = empty($email_sekarang_yunifa);
$label_tombol_yunifa = $email_kosong_yunifa ? 'Tambah Email' : 'Simpan Email Baru';
$label_field_yunifa  = $email_kosong_yunifa ? 'Email' : 'Email Baru';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ganti_email_yunifa'])) {
    $email_baru_yunifa  = trim($_POST['email_baru_yunifa']  ?? '');
    $email_ulang_yunifa = trim($_POST['email_ulang_yunifa'] ?? '');
    $konfirm_pw_yunifa  = $_POST['konfirm_pw_yunifa']       ?? '';

    if (empty($email_baru_yunifa) || empty($email_ulang_yunifa) || empty($konfirm_pw_yunifa)) {
        $pesan_yunifa      = "Semua field wajib diisi.";
        $tipe_pesan_yunifa = 'error';
    } elseif (!filter_var($email_baru_yunifa, FILTER_VALIDATE_EMAIL)) {
        $pesan_yunifa      = "Format email tidak valid.";
        $tipe_pesan_yunifa = 'error';
    } elseif ($email_baru_yunifa !== $email_ulang_yunifa) {
        $pesan_yunifa      = "Email baru dan konfirmasi email tidak cocok.";
        $tipe_pesan_yunifa = 'error';
    } elseif (!$email_kosong_yunifa && $email_baru_yunifa === $email_sekarang_yunifa) {
        $pesan_yunifa      = "Email baru tidak boleh sama dengan email saat ini.";
        $tipe_pesan_yunifa = 'error';
    } else {
        if ($tipe_yunifa === 'admin' || $tipe_yunifa === 'guru') {
            $q_pw_yunifa    = mysqli_query($koneksiYunifa, "SELECT password_yunifa FROM guru_yunifa WHERE id_guru_yunifa = '$id_user_yunifa'");
            $row_pw_yunifa  = mysqli_fetch_assoc($q_pw_yunifa);
            $hash_pw_yunifa = $row_pw_yunifa['password_yunifa'] ?? '';
        } elseif ($tipe_yunifa === 'siswa') {
            $q_pw_yunifa    = mysqli_query($koneksiYunifa, "SELECT password_siswa_yunifa FROM siswa_yunifa WHERE id_siswa_yunifa = '$id_user_yunifa'");
            $row_pw_yunifa  = mysqli_fetch_assoc($q_pw_yunifa);
            $hash_pw_yunifa = $row_pw_yunifa['password_siswa_yunifa'] ?? '';
        } elseif ($tipe_yunifa === 'ortu') {
            $q_pw_yunifa    = mysqli_query($koneksiYunifa, "SELECT password_ortu_yunifa FROM siswa_yunifa WHERE id_siswa_yunifa = '$id_user_yunifa'");
            $row_pw_yunifa  = mysqli_fetch_assoc($q_pw_yunifa);
            $hash_pw_yunifa = $row_pw_yunifa['password_ortu_yunifa'] ?? '';
        }

        $pw_valid_yunifa = password_verify($konfirm_pw_yunifa, $hash_pw_yunifa) || ($konfirm_pw_yunifa === $hash_pw_yunifa);

        if (!$pw_valid_yunifa) {
            $pesan_yunifa      = "Password tidak sesuai.";
            $tipe_pesan_yunifa = 'error';
        } else {
            $email_esc_yunifa = mysqli_real_escape_string($koneksiYunifa, $email_baru_yunifa);
            $duplikat_yunifa  = false;

            if ($tipe_yunifa === 'admin' || $tipe_yunifa === 'guru') {
                $q_dup_yunifa = mysqli_query($koneksiYunifa, "SELECT id_guru_yunifa FROM guru_yunifa WHERE email_guru_yunifa = '$email_esc_yunifa' AND id_guru_yunifa != '$id_user_yunifa'");
                if (mysqli_num_rows($q_dup_yunifa) > 0) $duplikat_yunifa = true;
            } elseif ($tipe_yunifa === 'siswa') {
                $q_dup_yunifa = mysqli_query($koneksiYunifa, "SELECT id_siswa_yunifa FROM siswa_yunifa WHERE email_siswa_yunifa = '$email_esc_yunifa' AND id_siswa_yunifa != '$id_user_yunifa'");
                if (mysqli_num_rows($q_dup_yunifa) > 0) $duplikat_yunifa = true;
            } elseif ($tipe_yunifa === 'ortu') {
                $q_dup_yunifa = mysqli_query($koneksiYunifa, "SELECT id_siswa_yunifa FROM siswa_yunifa WHERE email_ortu_yunifa = '$email_esc_yunifa' AND id_siswa_yunifa != '$id_user_yunifa'");
                if (mysqli_num_rows($q_dup_yunifa) > 0) $duplikat_yunifa = true;
            }

            if ($duplikat_yunifa) {
                $pesan_yunifa      = "Email tersebut sudah digunakan oleh akun lain.";
                $tipe_pesan_yunifa = 'error';
            } else {
                if ($tipe_yunifa === 'admin' || $tipe_yunifa === 'guru') {
                    mysqli_query($koneksiYunifa, "UPDATE guru_yunifa SET email_guru_yunifa = '$email_esc_yunifa' WHERE id_guru_yunifa = '$id_user_yunifa'");
                } elseif ($tipe_yunifa === 'siswa') {
                    mysqli_query($koneksiYunifa, "UPDATE siswa_yunifa SET email_siswa_yunifa = '$email_esc_yunifa' WHERE id_siswa_yunifa = '$id_user_yunifa'");
                } elseif ($tipe_yunifa === 'ortu') {
                    mysqli_query($koneksiYunifa, "UPDATE siswa_yunifa SET email_ortu_yunifa = '$email_esc_yunifa' WHERE id_siswa_yunifa = '$id_user_yunifa'");
                }

                header("Location: kalender_readProfilYunifa.php");
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Email – Kalender Akademik SMKN 2 Cimahi</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --blue-primary_yunifa: #1A73E8;
            --blue-dark_yunifa:    #1558B0;
            --blue-navbar_yunifa:  #1565D8;
            --blue-light_yunifa:   #EBF3FD;
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
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: url('../background_smk2.jpg') no-repeat center center / cover fixed;
            min-height: 100vh;
            overflow-x: hidden;
        }
        body::before {
            content: ""; position: fixed; inset: 0;
            background: var(--overlay_yunifa); z-index: 0;
        }
        .navbar_yunifa {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
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
            text-align: center; flex: 1; padding: 0 16px;
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
            width: min(480px, 94vw);
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
            background: var(--blue-light_yunifa);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 26px; color: var(--blue-primary_yunifa);
            margin: 0 auto 16px;
            border: 2px solid #c5dcf8;
        }
        .card-title_yunifa {
            text-align: center; font-size: 20px; font-weight: 800;
            color: var(--text-main_yunifa); margin-bottom: 4px;
        }
        .card-sub_yunifa {
            text-align: center; font-size: 13px; color: var(--gray-mid_yunifa); margin-bottom: 24px;
        }
        .card-sub_yunifa span { font-weight: 700; color: var(--blue-primary_yunifa); }
        .email-sekarang_yunifa {
            display: flex; align-items: center; gap: 12px;
            border-radius: var(--radius-sm_yunifa);
            padding: 12px 14px;
            margin-bottom: 22px;
        }
        .email-sekarang_yunifa.ada_yunifa {
            background: #f7f9fd;
            border: 1.5px solid #e0e8f5;
        }
        .email-sekarang_yunifa.kosong_yunifa {
            background: #fffbeb;
            border: 1.5px dashed #fbbf24;
        }
        .email-sekarang_yunifa .es-ikon_yunifa {
            width: 36px; height: 36px; flex-shrink: 0;
            border-radius: var(--radius-sm_yunifa);
            display: flex; align-items: center; justify-content: center;
            font-size: 14px;
        }
        .ada_yunifa .es-ikon_yunifa {
            background: var(--blue-light_yunifa);
            color: var(--blue-primary_yunifa);
        }
        .kosong_yunifa .es-ikon_yunifa {
            background: #fef3c7;
            color: #d97706;
        }
        .email-sekarang_yunifa .es-lbl_yunifa {
            font-size: 10px; font-weight: 700; color: var(--gray-mid_yunifa);
            text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px;
        }
        .email-sekarang_yunifa .es-val_yunifa {
            font-size: 14px; font-weight: 600; color: var(--text-main_yunifa); word-break: break-all;
        }
        .email-sekarang_yunifa .es-kosong_yunifa {
            font-size: 13px; font-weight: 600; color: #d97706; font-style: italic;
        }
        .alert_yunifa {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 12px 16px; border-radius: var(--radius-sm_yunifa);
            font-size: 13px; font-weight: 600; margin-bottom: 20px;
            animation: fadeUp_yunifa 0.3s ease both;
        }
        .alert_yunifa.sukses { background: #f0fdf4; color: #166534; border: 1.5px solid #bbf7d0; }
        .alert_yunifa.error  { background: #fff5f5; color: #991b1b; border: 1.5px solid #fecaca; }
        .alert_yunifa i { margin-top: 1px; flex-shrink: 0; }
        .section-label_yunifa {
            font-size: 11px; font-weight: 800; color: var(--text-sub_yunifa);
            text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 14px;
        }
        .divider_yunifa { height: 1px; background: #eef0f5; margin: 4px 0 20px; }
        .form-group_yunifa { margin-bottom: 16px; }
        .form-group_yunifa label {
            display: block; font-size: 11px; font-weight: 700; color: var(--text-sub_yunifa);
            text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 7px;
        }
        .input-wrap_yunifa { position: relative; }
        .input-wrap_yunifa input {
            width: 100%; padding: 11px 42px 11px 14px;
            border: 1.5px solid #dde4f0; border-radius: var(--radius-sm_yunifa);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px; font-weight: 500; color: var(--text-main_yunifa);
            background: #fafdff; outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .input-wrap_yunifa input:focus {
            border-color: var(--blue-primary_yunifa);
            box-shadow: 0 0 0 3px rgba(26,115,232,0.12);
            background: #fff;
        }
        .input-icon_yunifa {
            position: absolute; right: 13px; top: 50%; transform: translateY(-50%);
            color: #b0bec5; font-size: 14px; pointer-events: none;
        }
        .toggle-pw_yunifa {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: var(--gray-mid_yunifa); font-size: 15px; padding: 4px;
            transition: color 0.2s;
        }
        .toggle-pw_yunifa:hover { color: var(--blue-primary_yunifa); }
        .match-hint_yunifa {
            font-size: 11px; font-weight: 600; margin-top: 5px; display: none;
        }
        .btn-submit_yunifa {
            width: 100%; padding: 13px; border: none;
            border-radius: var(--radius-md_yunifa);
            background: var(--blue-primary_yunifa); color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px; font-weight: 800; cursor: pointer;
            box-shadow: 0 4px 14px rgba(26,115,232,0.30);
            transition: background 0.18s, transform 0.1s, box-shadow 0.18s;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            margin-top: 4px;
        }
        .btn-submit_yunifa:hover {
            background: var(--blue-dark_yunifa); transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(26,115,232,0.35);
        }
        .btn-submit_yunifa:active { transform: translateY(0); }
        @media (max-width: 480px) {
            .card_yunifa { padding: 28px 18px 24px; }
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

            <div class="card-icon_yunifa"><i class="fas fa-envelope"></i></div>
            <div class="card-title_yunifa"><?php echo $email_kosong_yunifa ? 'Tambah' : 'Ganti'; ?> <?php echo $label_email_yunifa; ?></div>
            <div class="card-sub_yunifa">
                Login sebagai <span><?php echo htmlspecialchars($nama_yunifa); ?></span>
            </div>

            <div class="email-sekarang_yunifa <?php echo $email_kosong_yunifa ? 'kosong_yunifa' : 'ada_yunifa'; ?>">
                <div class="es-ikon_yunifa">
                    <i class="fas <?php echo $email_kosong_yunifa ? 'fa-exclamation-triangle' : 'fa-envelope'; ?>"></i>
                </div>
                <div>
                    <div class="es-lbl_yunifa"><?php echo $label_email_yunifa; ?> Saat Ini</div>
                    <?php if (!$email_kosong_yunifa): ?>
                        <div class="es-val_yunifa"><?php echo htmlspecialchars($email_sekarang_yunifa); ?></div>
                    <?php else: ?>
                        <div class="es-kosong_yunifa">Belum ada email — silakan tambahkan</div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($pesan_yunifa)): ?>
                <div class="alert_yunifa <?php echo $tipe_pesan_yunifa; ?>">
                    <i class="fas <?php echo $tipe_pesan_yunifa === 'sukses' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                    <?php echo htmlspecialchars($pesan_yunifa); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" id="formGantiEmail_yunifa">
                <input type="hidden" name="ganti_email_yunifa" value="1">

                <div class="section-label_yunifa"><?php echo $email_kosong_yunifa ? 'Email Baru' : 'Email Baru'; ?></div>

                <div class="form-group_yunifa">
                    <label><?php echo $label_field_yunifa; ?></label>
                    <div class="input-wrap_yunifa">
                        <input type="email" name="email_baru_yunifa" id="email_baru_yunifa"
                               placeholder="contoh@email.com"
                               value="<?php echo htmlspecialchars($_POST['email_baru_yunifa'] ?? ''); ?>"
                               oninput="cekMatchEmail_yunifa()"
                               autocomplete="email" required>
                        <span class="input-icon_yunifa"><i class="fas fa-at"></i></span>
                    </div>
                </div>

                <div class="form-group_yunifa">
                    <label>Konfirmasi <?php echo $label_field_yunifa; ?></label>
                    <div class="input-wrap_yunifa">
                        <input type="email" name="email_ulang_yunifa" id="email_ulang_yunifa"
                               placeholder="Ulangi email"
                               oninput="cekMatchEmail_yunifa()"
                               autocomplete="email" required>
                        <span class="input-icon_yunifa"><i class="fas fa-at"></i></span>
                    </div>
                    <div class="match-hint_yunifa" id="matchHint_yunifa"></div>
                </div>

                <div class="divider_yunifa"></div>

                <div class="section-label_yunifa">Konfirmasi Password</div>

                <div class="form-group_yunifa">
                    <label>Password Anda</label>
                    <div class="input-wrap_yunifa">
                        <input type="password" name="konfirm_pw_yunifa" id="konfirm_pw_yunifa"
                               placeholder="Masukkan password Anda"
                               autocomplete="current-password" required>
                        <button type="button" class="toggle-pw_yunifa"
                                onclick="togglePw_yunifa('konfirm_pw_yunifa', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-submit_yunifa">
                    <i class="fas fa-save"></i> <?php echo $label_tombol_yunifa; ?>
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

        function cekMatchEmail_yunifa() {
            var baru_yunifa  = document.getElementById('email_baru_yunifa');
            var ulang_yunifa = document.getElementById('email_ulang_yunifa');
            var hint_yunifa  = document.getElementById('matchHint_yunifa');

            if (ulang_yunifa.value === '') {
                hint_yunifa.style.display      = 'none';
                ulang_yunifa.style.borderColor = '';
                return;
            }

            if (baru_yunifa.value === ulang_yunifa.value) {
                ulang_yunifa.style.borderColor = '#22c55e';
                hint_yunifa.style.display      = 'block';
                hint_yunifa.style.color        = '#166534';
                hint_yunifa.textContent        = '✓ Email cocok';
            } else {
                ulang_yunifa.style.borderColor = '#ef4444';
                hint_yunifa.style.display      = 'block';
                hint_yunifa.style.color        = '#991b1b';
                hint_yunifa.textContent        = '✗ Email tidak cocok';
            }
        }
    </script>
</body>
</html>
