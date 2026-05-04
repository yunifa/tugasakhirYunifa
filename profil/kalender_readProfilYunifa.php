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

    $isAdmin_yunifa     = ($tipe_yunifa == 'admin');
    $isGuruWaka_yunifa  = ($tipe_yunifa == 'guru' && $role_yunifa >= 1 && $role_yunifa <= 6);
    $isGuruBiasa_yunifa = ($tipe_yunifa == 'guru' && $role_yunifa == 7);
    $isSiswaOrtu_yunifa = ($tipe_yunifa == 'siswa' || $tipe_yunifa == 'ortu');

    if (isset($_POST['update_profil_yunifa'])) {
        $nama_baru  = isset($_POST['nama_yunifa'])  ? mysqli_real_escape_string($koneksiYunifa, $_POST['nama_yunifa'])  : null;
        $email_baru = isset($_POST['email_yunifa']) ? mysqli_real_escape_string($koneksiYunifa, $_POST['email_yunifa']) : null;
        $pass_baru  = $_POST['password_yunifa'];

        if ($tipe_yunifa == 'admin') {
            if (!empty($pass_baru)) {
                $hash = password_hash($pass_baru, PASSWORD_DEFAULT);
                mysqli_query($koneksiYunifa, "UPDATE guru_yunifa SET nama_yunifa='$nama_baru', email_guru_yunifa='$email_baru', password_yunifa='$hash' WHERE id_guru_yunifa='$id_user_yunifa'");
            } else {
                mysqli_query($koneksiYunifa, "UPDATE guru_yunifa SET nama_yunifa='$nama_baru', email_guru_yunifa='$email_baru' WHERE id_guru_yunifa='$id_user_yunifa'");
            }
        } elseif ($tipe_yunifa == 'guru') {
            if (!empty($pass_baru)) {
                $hash = password_hash($pass_baru, PASSWORD_DEFAULT);
                mysqli_query($koneksiYunifa, "UPDATE guru_yunifa SET email_guru_yunifa='$email_baru', password_yunifa='$hash' WHERE id_guru_yunifa='$id_user_yunifa'");
            } else {
                mysqli_query($koneksiYunifa, "UPDATE guru_yunifa SET email_guru_yunifa='$email_baru' WHERE id_guru_yunifa='$id_user_yunifa'");
            }
        }

        header("Location: kalender_readProfilYunifa.php?updated=1");
        exit;
    }

    if ($tipe_yunifa == 'guru' || $tipe_yunifa == 'admin') {
        $q = mysqli_query($koneksiYunifa, "
            SELECT g.*, r.role_yunifa
            FROM guru_yunifa g
            JOIN role_yunifa r ON g.id_role_yunifa = r.id_role_yunifa
            WHERE g.id_guru_yunifa = '$id_user_yunifa'
        ");
    } else {
        $q = mysqli_query($koneksiYunifa, "
            SELECT s.*, r.role_yunifa
            FROM siswa_yunifa s
            JOIN role_yunifa r ON s.id_role_yunifa = r.id_role_yunifa
            WHERE s.id_siswa_yunifa = '$id_user_yunifa'
        ");
    }
    $data_yunifa = mysqli_fetch_assoc($q);

    $tipe_label = [
        'admin' => 'Administrator',
        'guru'  => 'Guru',
        'siswa' => 'Siswa',
        'ortu'  => 'Orang Tua',
    ];
    $badge_tipe = $tipe_label[$tipe_yunifa] ?? ucfirst($tipe_yunifa);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil – Kalender Akademik SMKN 2 Cimahi</title>

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
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
            --shadow-soft:  0 2px 12px rgba(0,0,0,0.10);
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
            background: var(--overlay);
            z-index: 0;
        }
        .navbar_yunifa {
            position: fixed; top: 0; left: 0; right: 0;
            z-index: 1000;
            height: var(--nav-h);
            background: var(--blue-navbar);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 24px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.3);
            animation: fadeUp 0.3s ease both;
        }
        .navbar-logo_yunifa img { height:46px;width:46px;border-radius:50%;object-fit:cover;border:2px solid rgba(255,255,255,0.5); }
        .navbar-logo_yunifa .logo-fallback_yunifa {
            height:46px;width:46px;border-radius:50%;background:white;
            display:flex;align-items:center;justify-content:center;
            color:var(--blue-primary);font-size:22px;border:2px solid rgba(255,255,255,0.5);
        }
        .navbar-title_yunifa {
            color:var(--white);font-size:clamp(15px,2.2vw,22px);font-weight:800;
            letter-spacing:0.2px;text-align:center;flex:1;padding:0 16px;
        }
        .main_yunifa {
            position:relative;z-index:1;
            padding-top:calc(var(--nav-h) + 32px);
            padding-bottom:48px;
            display:flex;justify-content:center;align-items:flex-start;min-height:100vh;
        }
        .profile-wrapper_yunifa {
            width: min(760px, 96vw);
            animation: fadeUp 0.45s ease both;
        }
        .profile-banner_yunifa {
            display: none;
        }
        .profile-avatar-wrap_yunifa {
            position: absolute;
            bottom: -44px;
            left: 32px;
        }
        .profile-avatar_yunifa {
            width: 88px; height: 88px;
            border-radius: 50%;
            background: var(--white);
            border: 4px solid var(--white);
            box-shadow: 0 4px 16px rgba(0,0,0,0.18);
            display: flex; align-items: center; justify-content: center;
            font-size: 28px; font-weight: 800;
            color: var(--blue-primary);
            letter-spacing: -1px;
            user-select: none;
        }
        .profile-info-card_yunifa {
            background: rgba(255,255,255,0.96);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            border-radius: var(--radius-lg); /* Memberikan radius di semua sudut (atas & bawah) */
            box-shadow: var(--shadow-card);
            padding: 40px 28px 28px; /* Padding atas ditambah karena tidak ada tombol kembali */
            margin-top: 20px;
        }
        .profile-header-row_yunifa {
            display: flex;
            justify-content: flex-end;
            align-items: flex-start;
            padding-top: 12px;
            min-height: 56px;
        }
        .profile-name-block_yunifa {
            text-align: center;
            margin-bottom: 30px;
        }
        .profile-name_yunifa {
            font-size: 24px;
            font-weight: 800;
            color: var(--text-main);
        }
        .profile-badge_yunifa {
            display: inline-block;
            margin-top: 8px;
            background: var(--blue-light);
            color: var(--blue-dark);
            font-size: 12px;
            font-weight: 700;
            padding: 4px 14px;
            border-radius: 20px;
        }
        .profile-info-rows_yunifa {
            display: flex; flex-direction: column; gap: 12px;
            margin-bottom: 24px;
        }
        .profile-info-row_yunifa {
            display: flex; align-items: center; gap: 14px;
            padding: 13px 16px;
            background: #f7f9fd;
            border-radius: var(--radius-sm);
            border: 1px solid #e8eef7;
        }
        .profile-info-row_yunifa .row-icon_yunifa {
            width: 36px; height: 36px;
            background: var(--blue-light);
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            color: var(--blue-primary); font-size: 14px;
            flex-shrink: 0;
        }
        .profile-info-row_yunifa .row-label_yunifa { font-size: 11px; font-weight: 600; color: var(--gray-mid); text-transform: uppercase; letter-spacing: 0.5px; }
        .profile-info-row_yunifa .row-value_yunifa { font-size: 15px; font-weight: 600; color: var(--text-main); }
        .profile-divider_yunifa { height: 1px; background: #e8eef7; margin: 4px 0 20px; }
        .profile-form-title_yunifa {
            font-size: 14px; font-weight: 800; color: var(--text-main);
            text-transform: uppercase; letter-spacing: 0.6px;
            margin-bottom: 14px;
        }
        .form-group_yunifa { margin-bottom: 16px; }
        .form-group_yunifa label {
            display: block;
            font-size: 12px; font-weight: 700; color: var(--text-sub);
            margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.4px;
        }
        .form-group_yunifa input {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #dde4f0;
            border-radius: var(--radius-sm);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px; font-weight: 500; color: var(--text-main);
            background: #fafdff;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-group_yunifa input:focus {
            border-color: var(--blue-primary);
            box-shadow: 0 0 0 3px rgba(26,115,232,0.12);
            background: #fff;
        }
        .form-group_yunifa input:disabled {
            background: #f0f0f0; color: #999; cursor: not-allowed;
        }
        .pass-hint_yunifa {
            font-size: 11px; color: var(--gray-mid); margin-top: 5px;
        }
        .btn-row_yunifa { display: flex; gap: 12px; justify-content: flex-end; margin-top: 6px; }
        .btn_yunifa {
            padding: 11px 28px;
            border-radius: var(--radius-md);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px; font-weight: 700;
            cursor: pointer; border: none;
            transition: background 0.18s, box-shadow 0.18s, transform 0.1s;
            text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-primary_yunifa {
            background: var(--blue-primary); color: #fff;
            box-shadow: 0 3px 10px rgba(26,115,232,0.30);
        }
        .btn-primary_yunifa:hover { background: var(--blue-dark); transform: translateY(-1px); }
        .btn-secondary_yunifa {
            background: #f0f4fd; color: var(--blue-dark);
            border: 1.5px solid #d0daf7;
        }
        .btn-secondary_yunifa:hover { background: #e2eafc; }
        .toast_yunifa {
            position: fixed; bottom: 28px; left: 50%; transform: translateX(-50%) translateY(20px);
            background: #22c55e; color: white;
            padding: 12px 28px; border-radius: 40px;
            font-size: 14px; font-weight: 700;
            box-shadow: 0 4px 16px rgba(0,0,0,0.2);
            opacity: 0; pointer-events: none;
            transition: opacity 0.35s, transform 0.35s;
            z-index: 9999;
        }
        .toast_yunifa.show_yunifa { opacity: 1; transform: translateX(-50%) translateY(0); }
        .link-ganti-pass_yunifa {
            display: inline-flex; align-items: center; gap: 8px;
            color: var(--blue-primary); font-size: 14px; font-weight: 700;
            text-decoration: none; padding: 10px 0;
            transition: color 0.15s;
        }
        .link-ganti-pass_yunifa:hover { color: var(--blue-dark); }
        .navbar-back_yunifa { background:white;border:none;border-radius:var(--radius-md);height:40px;padding:0 16px;display:flex;align-items:center;gap:8px;font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:700;color:#333;cursor:pointer;text-decoration:none;white-space:nowrap; }
        .navbar-back_yunifa:hover { background:#f0f0f0; }
        @keyframes fadeUp {
            from { opacity:0; transform:translateY(20px); }
            to   { opacity:1; transform:translateY(0); }
        }
        @media (max-width: 600px) {
            .profile-banner_yunifa { height: 100px; }
            .profile-info-card_yunifa { padding: 0 16px 20px; }
            .profile-name_yunifa { font-size: 18px; }
            .btn-row_yunifa { flex-direction: column; }
            .btn_yunifa { width: 100%; justify-content: center; }
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
        <a class="navbar-back_yunifa" href="../kalender_dashboardYunifa.php">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </nav>

    <main class="main_yunifa">
        <div class="profile-wrapper_yunifa">

            <div class="profile-info-card_yunifa">
                
                <div class="profile-name-block_yunifa">
                    <div class="profile-name_yunifa"><?php echo htmlspecialchars($data_yunifa['nama_yunifa']); ?></div>
                    
                    <?php if (!empty($data_yunifa['role_yunifa']) && ($tipe_yunifa == 'guru' || $tipe_yunifa == 'admin')): ?>
                        <br>
                        <span class="profile-badge_yunifa" style="background: #f0f4fd; color: #5c7fbc; margin-top: 5px;">
                            <?php echo htmlspecialchars($data_yunifa['role_yunifa']); ?>
                        </span>
                    <?php endif; ?>
                </div>

                <div class="profile-info-rows_yunifa">
                    
                    <div class="profile-info-row_yunifa">
                        <div class="row-icon_yunifa"><i class="fas fa-id-card"></i></div>
                        <div>
                            <div class="row-label_yunifa">
                                <?php 
                                    if($tipe_yunifa == 'admin') echo "ID Admin (NIP)";
                                    elseif($tipe_yunifa == 'guru') echo "NIP";
                                    else echo "NIS";
                                ?>
                            </div>
                            <div class="row-value_yunifa">
                                <?php 
                                    // Admin dan Guru menggunakan nip_yunifa, Siswa menggunakan nis_yunifa
                                    if($tipe_yunifa == 'admin' || $tipe_yunifa == 'guru') {
                                        echo htmlspecialchars($data_yunifa['nip_yunifa']);
                                    } else {
                                        echo htmlspecialchars($data_yunifa['nis_yunifa']);
                                    }
                                ?>
                            </div>
                        </div>
                    </div>

                    <?php if($tipe_yunifa == 'admin' || $tipe_yunifa == 'guru'): ?>
                    <?php $email_val = $data_yunifa['email_guru_yunifa']; ?>
                    <div class="profile-info-row_yunifa" style="justify-content: space-between; align-items: center;">
                        <div style="display: flex; gap: 14px; align-items: center;">
                            <div class="row-icon_yunifa"><i class="fas fa-envelope"></i></div>
                            <div>
                                <div class="row-label_yunifa">Email</div>
                                <div class="row-value_yunifa">
                                    <?php echo !empty($email_val) ? htmlspecialchars($email_val) : '<span style="color:#999; font-style:italic;">tambahkan email</span>'; ?>
                                </div>
                            </div>
                        </div>
                        <a href="edit_email_yunifa.php" class="btn_yunifa <?php echo !empty($email_val) ? 'btn-secondary_yunifa' : 'btn-primary_yunifa'; ?>" style="padding: 8px 16px; font-size: 12px;">
                            <i class="fas <?php echo !empty($email_val) ? 'fa-edit' : 'fa-plus'; ?>"></i>
                            <?php echo !empty($email_val) ? 'Edit Email' : 'Tambah Email'; ?>
                        </a>
                    </div>
                    <?php endif; ?>

                    <?php if($tipe_yunifa == 'siswa' || $tipe_yunifa == 'ortu'): ?>
                        <?php $email_s = $data_yunifa['email_siswa_yunifa']; ?>
                        <div class="profile-info-row_yunifa" style="justify-content: space-between; align-items: center;">
                            <div style="display: flex; gap: 14px; align-items: center;">
                                <div class="row-icon_yunifa"><i class="fas fa-user-graduate"></i></div>
                                <div>
                                    <div class="row-label_yunifa">Email Siswa</div>
                                    <div class="row-value_yunifa">
                                        <?php echo !empty($email_s) ? htmlspecialchars($email_s) : '<span style="color:#999; font-style:italic;">tambahkan email</span>'; ?>
                                    </div>
                                </div>
                            </div>
                            <a href="edit_email_siswa.php" class="btn_yunifa <?php echo !empty($email_s) ? 'btn-secondary_yunifa' : 'btn-primary_yunifa'; ?>" style="padding: 8px 16px; font-size: 12px;">
                                <i class="fas <?php echo !empty($email_s) ? 'fa-edit' : 'fa-plus'; ?>"></i>
                            </a>
                        </div>

                        <?php $email_o = $data_yunifa['email_ortu_yunifa']; ?>
                        <div class="profile-info-row_yunifa" style="justify-content: space-between; align-items: center;">
                            <div style="display: flex; gap: 14px; align-items: center;">
                                <div class="row-icon_yunifa"><i class="fas fa-users"></i></div>
                                <div>
                                    <div class="row-label_yunifa">Email Orang Tua</div>
                                    <div class="row-value_yunifa">
                                        <?php echo !empty($email_o) ? htmlspecialchars($email_o) : '<span style="color:#999; font-style:italic;">tambahkan email</span>'; ?>
                                    </div>
                                </div>
                            </div>
                            <a href="edit_email_ortu.php" class="btn_yunifa <?php echo !empty($email_o) ? 'btn-secondary_yunifa' : 'btn-primary_yunifa'; ?>" style="padding: 8px 16px; font-size: 12px;">
                                <i class="fas <?php echo !empty($email_o) ? 'fa-edit' : 'fa-plus'; ?>"></i>
                            </a>
                        </div>
                    <?php endif; ?>

                </div>

                <div class="profile-divider_yunifa"></div>

                <div class="btn-row_yunifa">
                    <a href="kalender_gantipwYunifa.php" class="btn_yunifa btn-primary_yunifa" style="width: 100%; justify-content: center; background: #dc3545; border-radius: var(--radius-md);">
                        <i class="fas fa-lock"></i> Edit Password
                    </a>
                </div>

            </div>
        </div>
    </main>

    <div class="toast_yunifa" id="toast_yunifa">
        <i class="fas fa-check-circle" style="margin-right:8px;"></i> Profil berhasil diperbarui!
    </div>

    <script>
        (function(){
            var sidebar = document.getElementById('sidebar_yunifa');
            var overlay = document.getElementById('sidebarOverlay_yunifa');
            var btn     = document.getElementById('menuToggle_yunifa');
            function open()  { sidebar.classList.add('open_yunifa'); overlay.classList.add('open_yunifa'); }
            function close() { sidebar.classList.remove('open_yunifa'); overlay.classList.remove('open_yunifa'); }
            btn.addEventListener('click', function(){ sidebar.classList.contains('open_yunifa') ? close() : open(); });
            overlay.addEventListener('click', close);
        })();

        <?php if (isset($_GET['updated'])): ?>
        (function(){
            var t = document.getElementById('toast_yunifa');
            t.classList.add('show_yunifa');
            setTimeout(function(){ t.classList.remove('show_yunifa'); }, 3000);
        })();
        <?php endif; ?>
    </script>
</body>
</html>