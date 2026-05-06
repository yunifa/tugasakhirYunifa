<?php
session_start();
include "../kalender_koneksiYunifa.php";

if (!isset($_SESSION['tipe_yunifa'])) {
    header("Location: kalender_loginYunifa.php");
    exit;
}

$tipe_yunifa    = $_SESSION['tipe_yunifa'];
$id_user_yunifa = $_SESSION['id_user_yunifa'];
$nama_yunifa    = $_SESSION['nama_yunifa'] ?? 'Tamu';
$role_yunifa    = $_SESSION['role_yunifa'] ?? 0;

// =====================================================================
// AMBIL DATA SESUAI TIPE
// =====================================================================
if ($tipe_yunifa === 'admin' || $tipe_yunifa === 'guru') {
    $q_yunifa = mysqli_query($koneksiYunifa, "
        SELECT g.*, r.role_yunifa AS nama_role_yunifa
        FROM guru_yunifa g
        JOIN role_yunifa r ON g.id_role_yunifa = r.id_role_yunifa
        WHERE g.id_guru_yunifa = '$id_user_yunifa'
    ");
    $data_yunifa = mysqli_fetch_assoc($q_yunifa);

} elseif ($tipe_yunifa === 'siswa') {
    $q_yunifa = mysqli_query($koneksiYunifa, "
        SELECT s.*, r.role_yunifa AS nama_role_yunifa
        FROM siswa_yunifa s
        JOIN role_yunifa r ON s.id_role_yunifa = r.id_role_yunifa
        WHERE s.id_siswa_yunifa = '$id_user_yunifa'
    ");
    $data_yunifa = mysqli_fetch_assoc($q_yunifa);

} elseif ($tipe_yunifa === 'ortu') {
    // Ortu memakai id_siswa, ambil data anak
    $q_yunifa = mysqli_query($koneksiYunifa, "
        SELECT s.nama_yunifa, s.nis_yunifa, s.kelas_yunifa,
               s.email_ortu_yunifa, s.password_ortu_yunifa
        FROM siswa_yunifa s
        WHERE s.id_siswa_yunifa = '$id_user_yunifa'
    ");
    $data_yunifa = mysqli_fetch_assoc($q_yunifa);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil – Kalender Akademik SMKN 2 Cimahi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --blue-primary_yunifa: #1A73E8;
            --blue-dark_yunifa:    #1558B0;
            --blue-navbar_yunifa:  #1565D8;
            --blue-light_yunifa:   #EBF3FD;
            --orange_yunifa:       #f59e0b;
            --orange-light_yunifa: #fef3c7;
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: url('../background_smk2.jpg') no-repeat center center / cover fixed; min-height: 100vh; overflow-x: hidden; }
        body::before { content: ""; position: fixed; inset: 0; background: var(--overlay_yunifa); z-index: 0; }

        /* ---- NAVBAR ---- */
        .navbar_yunifa { position: fixed; top: 0; left: 0; right: 0; z-index: 1000; height: var(--nav-h_yunifa); background: var(--blue-navbar_yunifa); display: flex; align-items: center; justify-content: space-between; padding: 0 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.3); }
        .navbar-logo_yunifa img { height: 46px; width: 46px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(255,255,255,0.5); }
        .navbar-logo_yunifa .logo-fallback_yunifa { height: 46px; width: 46px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; color: var(--blue-primary_yunifa); font-size: 22px; border: 2px solid rgba(255,255,255,0.5); }
        .navbar-title_yunifa { color: #fff; font-size: clamp(15px,2.2vw,22px); font-weight: 800; text-align: center; flex: 1; padding: 0 16px; }
        .navbar-back_yunifa { background: white; border: none; border-radius: var(--radius-md_yunifa); height: 40px; padding: 0 16px; display: flex; align-items: center; gap: 8px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; font-weight: 700; color: #333; cursor: pointer; text-decoration: none; white-space: nowrap; }
        .navbar-back_yunifa:hover { background: #f0f0f0; }

        /* ---- LAYOUT ---- */
        .main_yunifa { position: relative; z-index: 1; padding-top: calc(var(--nav-h_yunifa) + 36px); padding-bottom: 60px; display: flex; justify-content: center; min-height: 100vh; }
        .card_yunifa { width: min(580px, 95vw); background: rgba(255,255,255,0.97); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); border-radius: var(--radius-lg_yunifa); box-shadow: var(--shadow-card_yunifa); padding: 36px 30px 30px; animation: fadeUp_yunifa 0.4s ease both; }
        @keyframes fadeUp_yunifa { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        /* ---- HEADER PROFIL ---- */
        .profil-header_yunifa { text-align: center; margin-bottom: 28px; }
        .profil-avatar_yunifa { width: 72px; height: 72px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 28px; margin: 0 auto 14px; }
        .profil-avatar-biru_yunifa  { background: var(--blue-light_yunifa); border: 3px solid var(--blue-primary_yunifa); color: var(--blue-primary_yunifa); }
        .profil-avatar-hijau_yunifa { background: #dcfce7; border: 3px solid #22c55e; color: #16a34a; }
        .profil-avatar-ortu_yunifa  { background: var(--orange-light_yunifa); border: 3px solid var(--orange_yunifa); color: var(--orange_yunifa); }

        .profil-nama_yunifa { font-size: 22px; font-weight: 800; color: var(--text-main_yunifa); }
        .profil-sub_yunifa  { font-size: 12px; color: var(--gray-mid_yunifa); margin-top: 3px; }
        .profil-badge_yunifa { display: inline-block; margin-top: 8px; font-size: 12px; font-weight: 700; padding: 4px 14px; border-radius: 20px; }
        .badge-biru_yunifa  { background: var(--blue-light_yunifa); color: var(--blue-dark_yunifa); }
        .badge-hijau_yunifa { background: #dcfce7; color: #15803d; }
        .badge-ortu_yunifa  { background: var(--orange-light_yunifa); color: #92400e; }

        /* ---- INFO ROWS ---- */
        .info-rows_yunifa { display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px; }
        .info-row_yunifa { display: flex; align-items: center; gap: 14px; padding: 13px 16px; background: #f7f9fd; border-radius: var(--radius-sm_yunifa); border: 1px solid #e8eef7; }
        .info-row-aksi_yunifa { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 13px 16px; background: #f7f9fd; border-radius: var(--radius-sm_yunifa); border: 1px solid #e8eef7; }
        .info-row-aksi_yunifa .kiri_yunifa { display: flex; align-items: center; gap: 14px; flex: 1; min-width: 0; }
        /* Row khusus data anak (biru muda) */
        .info-row-anak_yunifa { display: flex; align-items: center; gap: 14px; padding: 13px 16px; background: #f0f9ff; border-radius: var(--radius-sm_yunifa); border: 1px solid #bae6fd; }

        .row-ikon_yunifa { width: 36px; height: 36px; background: var(--blue-light_yunifa); border-radius: var(--radius-sm_yunifa); display: flex; align-items: center; justify-content: center; color: var(--blue-primary_yunifa); font-size: 14px; flex-shrink: 0; }
        .row-ikon-ortu_yunifa { background: var(--orange-light_yunifa); color: var(--orange_yunifa); }
        .row-ikon-anak_yunifa { background: #e0f2fe; color: #0284c7; width: 36px; height: 36px; border-radius: var(--radius-sm_yunifa); display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }

        .row-lbl_yunifa { font-size: 10px; font-weight: 700; color: var(--gray-mid_yunifa); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px; }
        .row-val_yunifa { font-size: 14px; font-weight: 600; color: var(--text-main_yunifa); word-break: break-all; }
        .row-val-kosong_yunifa { font-size: 13px; color: #bbb; font-style: italic; }

        /* ---- STATUS ORTU ---- */
        .ortu-status_yunifa { display: flex; align-items: center; gap: 8px; padding: 10px 14px; border-radius: var(--radius-sm_yunifa); margin-bottom: 10px; font-size: 13px; font-weight: 600; }
        .ortu-status_yunifa.lengkap_yunifa { background: #f0fdf4; color: #166534; border: 1.5px solid #bbf7d0; }
        .ortu-status_yunifa.belum_yunifa    { background: #fffbeb; color: #92400e; border: 1.5px dashed #fbbf24; }

        /* ---- SECTION TITLE ---- */
        .section-title_yunifa { font-size: 11px; font-weight: 800; color: var(--text-sub_yunifa); text-transform: uppercase; letter-spacing: 0.6px; margin: 20px 0 10px; }

        /* ---- TOMBOL ---- */
        .btn_yunifa { padding: 8px 14px; border-radius: var(--radius-sm_yunifa); font-family: 'Plus Jakarta Sans', sans-serif; font-size: 12px; font-weight: 700; cursor: pointer; border: none; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; white-space: nowrap; transition: background 0.15s, transform 0.1s; flex-shrink: 0; }
        .btn-primary_yunifa   { background: var(--blue-primary_yunifa); color: #fff; box-shadow: 0 2px 8px rgba(26,115,232,0.25); }
        .btn-primary_yunifa:hover { background: var(--blue-dark_yunifa); transform: translateY(-1px); }
        .btn-secondary_yunifa { background: #f0f4fd; color: var(--blue-dark_yunifa); border: 1.5px solid #d0daf7; }
        .btn-secondary_yunifa:hover { background: #e2eafc; }
        .btn-red_yunifa       { background: #dc3545; color: #fff; box-shadow: 0 2px 8px rgba(220,53,69,0.25); }
        .btn-red_yunifa:hover { background: #b02a37; transform: translateY(-1px); }
        .btn-full_yunifa      { width: 100%; justify-content: center; padding: 12px; font-size: 14px; border-radius: var(--radius-md_yunifa); margin-top: 4px; }

        .divider_yunifa { height: 1px; background: #e8eef7; margin: 20px 0; }

        /* ---- TOAST ---- */
        .toast_yunifa { position: fixed; bottom: 28px; left: 50%; transform: translateX(-50%) translateY(20px); background: #22c55e; color: white; padding: 12px 28px; border-radius: 40px; font-size: 14px; font-weight: 700; box-shadow: 0 4px 16px rgba(0,0,0,0.2); opacity: 0; pointer-events: none; transition: opacity 0.35s, transform 0.35s; z-index: 9999; }
        .toast_yunifa.show_yunifa { opacity: 1; transform: translateX(-50%) translateY(0); }

        @media (max-width: 480px) { .card_yunifa { padding: 28px 16px 24px; } }
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
        <a class="navbar-back_yunifa" href="../kalender_dashboardYunifa.php">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </nav>

    <main class="main_yunifa">
        <div class="card_yunifa">

        <?php if ($tipe_yunifa === 'admin' || $tipe_yunifa === 'guru'): ?>
        <!-- ============================================================ -->
        <!-- PROFIL: ADMIN / GURU                                         -->
        <!-- ============================================================ -->

            <div class="profil-header_yunifa">
                <div class="profil-avatar_yunifa profil-avatar-biru_yunifa">
                    <i class="fas <?php echo $tipe_yunifa === 'admin' ? 'fa-user-shield' : 'fa-chalkboard-teacher'; ?>"></i>
                </div>
                <div class="profil-nama_yunifa"><?php echo htmlspecialchars($data_yunifa['nama_yunifa']); ?></div>
                <?php if (!empty($data_yunifa['nama_role_yunifa'])): ?>
                    <span class="profil-badge_yunifa badge-biru_yunifa"><?php echo htmlspecialchars($data_yunifa['nama_role_yunifa']); ?></span>
                <?php endif; ?>
            </div>

            <div class="info-rows_yunifa">

                <div class="info-row_yunifa">
                    <div class="row-ikon_yunifa"><i class="fas fa-id-card"></i></div>
                    <div>
                        <div class="row-lbl_yunifa"><?php echo $tipe_yunifa === 'admin' ? 'ID Admin (NIP)' : 'NIP'; ?></div>
                        <div class="row-val_yunifa"><?php echo htmlspecialchars($data_yunifa['nip_yunifa']); ?></div>
                    </div>
                </div>

                <?php $email_val_yunifa = $data_yunifa['email_guru_yunifa'] ?? ''; ?>
                <div class="info-row-aksi_yunifa">
                    <div class="kiri_yunifa">
                        <div class="row-ikon_yunifa"><i class="fas fa-envelope"></i></div>
                        <div style="min-width:0;">
                            <div class="row-lbl_yunifa">Email</div>
                            <?php if (!empty($email_val_yunifa)): ?>
                                <div class="row-val_yunifa"><?php echo htmlspecialchars($email_val_yunifa); ?></div>
                            <?php else: ?>
                                <div class="row-val-kosong_yunifa">Belum ada email</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <a href="kalender_gantiemailYunifa.php" class="btn_yunifa <?php echo !empty($email_val_yunifa) ? 'btn-secondary_yunifa' : 'btn-primary_yunifa'; ?>">
                        <i class="fas <?php echo !empty($email_val_yunifa) ? 'fa-edit' : 'fa-plus'; ?>"></i>
                        <?php echo !empty($email_val_yunifa) ? 'Edit Email' : 'Tambah Email'; ?>
                    </a>
                </div>

            </div>

            <div class="divider_yunifa"></div>

            <a href="kalender_gantipwYunifa.php" class="btn_yunifa btn-red_yunifa btn-full_yunifa">
                <i class="fas fa-lock"></i> Ganti Password
            </a>


        <?php elseif ($tipe_yunifa === 'siswa'): ?>
        <!-- ============================================================ -->
        <!-- PROFIL: SISWA                                                 -->
        <!-- ============================================================ -->

            <?php
                $email_s_yunifa      = $data_yunifa['email_siswa_yunifa'] ?? '';
                $email_o_yunifa      = $data_yunifa['email_ortu_yunifa']  ?? '';
                $pw_o_yunifa         = $data_yunifa['password_ortu_yunifa'] ?? '';
                $ortu_lengkap_yunifa = !empty($email_o_yunifa) && !empty($pw_o_yunifa);
            ?>

            <div class="profil-header_yunifa">
                <div class="profil-avatar_yunifa profil-avatar-hijau_yunifa">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="profil-nama_yunifa"><?php echo htmlspecialchars($data_yunifa['nama_yunifa']); ?></div>
                <span class="profil-badge_yunifa badge-hijau_yunifa">Siswa</span>
            </div>

            <div class="info-rows_yunifa">

                <div class="info-row_yunifa">
                    <div class="row-ikon_yunifa"><i class="fas fa-id-card"></i></div>
                    <div>
                        <div class="row-lbl_yunifa">NIS</div>
                        <div class="row-val_yunifa"><?php echo htmlspecialchars($data_yunifa['nis_yunifa']); ?></div>
                    </div>
                </div>

                <div class="info-row_yunifa">
                    <div class="row-ikon_yunifa"><i class="fas fa-chalkboard"></i></div>
                    <div>
                        <div class="row-lbl_yunifa">Kelas</div>
                        <div class="row-val_yunifa"><?php echo htmlspecialchars($data_yunifa['kelas_yunifa']); ?></div>
                    </div>
                </div>

                <!-- Email Siswa -->
                <div class="info-row-aksi_yunifa">
                    <div class="kiri_yunifa">
                        <div class="row-ikon_yunifa"><i class="fas fa-envelope"></i></div>
                        <div style="min-width:0;">
                            <div class="row-lbl_yunifa">Email Siswa</div>
                            <?php if (!empty($email_s_yunifa)): ?>
                                <div class="row-val_yunifa"><?php echo htmlspecialchars($email_s_yunifa); ?></div>
                            <?php else: ?>
                                <div class="row-val-kosong_yunifa">Belum ada email</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <a href="kalender_gantiemailYunifa.php" class="btn_yunifa <?php echo !empty($email_s_yunifa) ? 'btn-secondary_yunifa' : 'btn-primary_yunifa'; ?>">
                        <i class="fas <?php echo !empty($email_s_yunifa) ? 'fa-edit' : 'fa-plus'; ?>"></i>
                        <?php echo !empty($email_s_yunifa) ? 'Edit' : 'Tambah'; ?>
                    </a>
                </div>

            </div>

            <div class="divider_yunifa"></div>

            <!-- Seksi Akun Orang Tua -->
            <div class="section-title_yunifa">
                <i class="fas fa-users" style="margin-right:6px;color:var(--orange_yunifa);"></i>Akun Orang Tua
            </div>

            <?php if ($ortu_lengkap_yunifa): ?>
                <div class="ortu-status_yunifa lengkap_yunifa">
                    <i class="fas fa-check-circle"></i> Akun orang tua sudah aktif
                </div>
            <?php else: ?>
                <div class="ortu-status_yunifa belum_yunifa">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php
                        if (empty($email_o_yunifa) && empty($pw_o_yunifa)) echo "Email dan password orang tua belum diatur";
                        elseif (empty($email_o_yunifa))                    echo "Email orang tua belum diatur";
                        else                                                echo "Password orang tua belum diatur";
                    ?>
                </div>
            <?php endif; ?>

            <div class="info-rows_yunifa">
                <div class="info-row-aksi_yunifa">
                    <div class="kiri_yunifa">
                        <div class="row-ikon_yunifa row-ikon-ortu_yunifa"><i class="fas fa-users"></i></div>
                        <div style="min-width:0;">
                            <div class="row-lbl_yunifa">Email Orang Tua</div>
                            <?php if (!empty($email_o_yunifa)): ?>
                                <div class="row-val_yunifa"><?php echo htmlspecialchars($email_o_yunifa); ?></div>
                                <?php if (empty($pw_o_yunifa)): ?>
                                    <div style="font-size:11px;color:#d97706;font-weight:600;margin-top:2px;">
                                        <i class="fas fa-exclamation-circle"></i> Password belum diatur
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="row-val-kosong_yunifa">Belum ada email</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <a href="kalender_gantiemailOrtuYunifa.php" class="btn_yunifa <?php echo !empty($email_o_yunifa) ? 'btn-secondary_yunifa' : 'btn-primary_yunifa'; ?>">
                        <i class="fas <?php echo !empty($email_o_yunifa) ? 'fa-edit' : 'fa-plus'; ?>"></i>
                        <?php echo !empty($email_o_yunifa) ? 'Edit' : 'Atur'; ?>
                    </a>
                </div>
            </div>

            <div class="divider_yunifa"></div>

            <a href="kalender_gantipwYunifa.php" class="btn_yunifa btn-red_yunifa btn-full_yunifa">
                <i class="fas fa-lock"></i> Ganti Password Siswa
            </a>


        <?php elseif ($tipe_yunifa === 'ortu'): ?>
        <!-- ============================================================ -->
        <!-- PROFIL: ORANG TUA                                            -->
        <!-- ============================================================ -->

            <?php
                $nama_anak_yunifa  = $data_yunifa['nama_yunifa']      ?? '-';
                $kelas_anak_yunifa = $data_yunifa['kelas_yunifa']     ?? '-';
                $email_o_yunifa    = $data_yunifa['email_ortu_yunifa'] ?? '';
            ?>

            <div class="profil-header_yunifa">
                <div class="profil-avatar_yunifa profil-avatar-ortu_yunifa">
                    <i class="fas fa-user-friends"></i>
                </div>
                <div class="profil-sub_yunifa">ORANG TUA</div>
                <div class="profil-nama_yunifa"><?php echo htmlspecialchars($nama_anak_yunifa); ?></div>
                <span class="profil-badge_yunifa badge-ortu_yunifa">
                    <i class="fas fa-chalkboard" style="margin-right:4px;"></i>
                    <?php echo htmlspecialchars($kelas_anak_yunifa); ?>
                </span>
            </div>

            <!-- Data Anak (readonly) -->
            <div class="section-title_yunifa">
                <i class="fas fa-user-graduate" style="margin-right:6px;color:#0284c7;"></i>Data Anak
            </div>
            <div class="info-rows_yunifa">
                <div class="info-row-anak_yunifa">
                    <div class="row-ikon-anak_yunifa"><i class="fas fa-user"></i></div>
                    <div>
                        <div class="row-lbl_yunifa">Nama Anak</div>
                        <div class="row-val_yunifa"><?php echo htmlspecialchars($nama_anak_yunifa); ?></div>
                    </div>
                </div>
                <div class="info-row-anak_yunifa">
                    <div class="row-ikon-anak_yunifa"><i class="fas fa-chalkboard"></i></div>
                    <div>
                        <div class="row-lbl_yunifa">Kelas</div>
                        <div class="row-val_yunifa"><?php echo htmlspecialchars($kelas_anak_yunifa); ?></div>
                    </div>
                </div>
            </div>

            <div class="divider_yunifa"></div>

            <!-- Akun Saya (Ortu) -->
            <div class="section-title_yunifa">
                <i class="fas fa-user-lock" style="margin-right:6px;"></i>Akun Saya
            </div>
            <div class="info-rows_yunifa">
                <div class="info-row-aksi_yunifa">
                    <div class="kiri_yunifa">
                        <div class="row-ikon_yunifa"><i class="fas fa-envelope"></i></div>
                        <div style="min-width:0;">
                            <div class="row-lbl_yunifa">Email Orang Tua</div>
                            <?php if (!empty($email_o_yunifa)): ?>
                                <div class="row-val_yunifa"><?php echo htmlspecialchars($email_o_yunifa); ?></div>
                            <?php else: ?>
                                <div class="row-val-kosong_yunifa">Belum ada email</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <a href="kalender_gantiemailYunifa.php" class="btn_yunifa <?php echo !empty($email_o_yunifa) ? 'btn-secondary_yunifa' : 'btn-primary_yunifa'; ?>">
                        <i class="fas <?php echo !empty($email_o_yunifa) ? 'fa-edit' : 'fa-plus'; ?>"></i>
                        <?php echo !empty($email_o_yunifa) ? 'Edit' : 'Tambah'; ?>
                    </a>
                </div>
            </div>

            <div class="divider_yunifa"></div>

            <a href="kalender_gantipwYunifa.php" class="btn_yunifa btn-red_yunifa btn-full_yunifa">
                <i class="fas fa-lock"></i> Ganti Password Orang Tua
            </a>

        <?php endif; ?>

        </div><!-- /.card_yunifa -->
    </main>

    <div class="toast_yunifa" id="toast_yunifa">
        <i class="fas fa-check-circle" style="margin-right:8px;"></i> Berhasil diperbarui!
    </div>

    <script>
        <?php if (isset($_GET['updated'])): ?>
        (function(){
            var t_yunifa = document.getElementById('toast_yunifa');
            t_yunifa.classList.add('show_yunifa');
            setTimeout(function(){ t_yunifa.classList.remove('show_yunifa'); }, 3000);
        })();
        <?php endif; ?>
    </script>
</body>
</html>
