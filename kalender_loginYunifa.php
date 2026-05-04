<?php
    session_start();
    include 'kalender_koneksiYunifa.php';

    if (isset($_POST['login_yunifa'])) {

        $username_yunifa = $_POST['username_yunifa'];
        $password_yunifa = $_POST['password_yunifa'];

        $queryAdmin_yunifa = mysqli_query($koneksiYunifa, 
        "SELECT * FROM guru_yunifa WHERE (nip_yunifa='$username_yunifa' OR email_guru_yunifa='$username_yunifa') AND password_yunifa='$password_yunifa' AND id_role_yunifa=8");
        if (mysqli_num_rows($queryAdmin_yunifa) > 0) {
            $row_yunifa = mysqli_fetch_assoc($queryAdmin_yunifa);
            $_SESSION['id_user_yunifa'] = $row_yunifa['id_guru_yunifa'];
            $_SESSION['role_yunifa']    = $row_yunifa['id_role_yunifa'];
            $_SESSION['tipe_yunifa']    = 'admin';
            $_SESSION['nama_yunifa']    = $row_yunifa['nama_yunifa'];
            header("Location: kalender_dashboardYunifa.php");
            exit;
        }

        $queryGuruYunifa = mysqli_query($koneksiYunifa,"SELECT * FROM guru_yunifa WHERE nip_yunifa='$username_yunifa' AND password_yunifa='$password_yunifa'");
        if (mysqli_num_rows($queryGuruYunifa) > 0) {
            $row_yunifa = mysqli_fetch_assoc($queryGuruYunifa);
            $_SESSION['id_user_yunifa'] = $row_yunifa['id_guru_yunifa'];
            $_SESSION['role_yunifa']    = $row_yunifa['id_role_yunifa'];
            $_SESSION['tipe_yunifa']    = 'guru';
            $_SESSION['nama_yunifa']    = $row_yunifa['nama_yunifa'];
            header("Location: kalender_dashboardYunifa.php");
            exit;
        }

        $querySiswa_yunifa = mysqli_query($koneksiYunifa,"SELECT * FROM siswa_yunifa WHERE nis_yunifa='$username_yunifa' AND password_siswa_yunifa='$password_yunifa'");
        if (mysqli_num_rows($querySiswa_yunifa) > 0) {
            $row_yunifa = mysqli_fetch_assoc($querySiswa_yunifa);
            $_SESSION['id_user_yunifa'] = $row_yunifa['id_siswa_yunifa'];
            $_SESSION['tipe_yunifa']    = 'siswa';
            $_SESSION['nama_yunifa']    = $row_yunifa['nama_yunifa'];
            header("Location: kalender_dashboardYunifa.php");
            exit;
        }

        $queryOrtu_yunifa = mysqli_query($koneksiYunifa,"SELECT * FROM siswa_yunifa WHERE email_ortu_yunifa='$username_yunifa' AND password_ortu_yunifa='$password_yunifa'");
        if (mysqli_num_rows($queryOrtu_yunifa) > 0) {
            $row_yunifa = mysqli_fetch_assoc($queryOrtu_yunifa);
            $_SESSION['id_user_yunifa'] = $row_yunifa['id_siswa_yunifa'];
            $_SESSION['tipe_yunifa']    = 'ortu';
            $_SESSION['nama_yunifa']    = 'ORANG TUA - '. $row_yunifa['nama_yunifa'];
            header("Location: kalender_dashboardYunifa.php");
            exit;
        }

        $loginError_yunifa = true;
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – Kalender Akademik SMKN 2 Cimahi</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
        }
        .left-panel_yunifa {
            width: 50%;
            min-height: 100vh;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            padding: 36px 48px;
        }
        .brand_yunifa {display: flex;align-items: center;gap: 10px;margin-bottom: auto;}
        .brand_yunifa img {height: 40px;width: 40px;border-radius: 50%;object-fit: cover;}
        .brand-fallback_yunifa {
            height: 40px;
            width: 40px;
            border-radius: 50%;
            background: #1A73E8;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }
        .brand-name_yunifa {font-size: 13px;font-weight: 700;color: #111;line-height: 1.3;}
        .form-area_yunifa {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            max-width: 400px;
            width: 100%;
            padding: 40px 0;
        }
        .form-heading_yunifa {
            font-size: 32px;
            font-weight: 800;
            color: #111;
            line-height: 1.25;
            margin-bottom: 8px;
        }
        .form-heading_yunifa span {font-weight: 400;}
        .form-sub_yunifa {font-size: 14px;color: #888;margin-bottom: 32px;}
        .input-group_yunifa {margin-bottom: 16px;}
        .input-group_yunifa input {
            width: 100%;
            padding: 15px 18px;
            border: none;
            border-radius: 10px;
            background: #EBEBEB;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            color: #333;
            outline: none;
            transition: background 0.2s, box-shadow 0.2s;
        }
        .input-group_yunifa input::placeholder { color: #999; }
        .input-group_yunifa input:focus {background: #e0eaff;box-shadow: 0 0 0 2px #1A73E840;}
        .input-group_yunifa input.error {background: #ffeaea;box-shadow: 0 0 0 2px #e8330040;}
        .error-msg_yunifa {
            display: <?php echo isset($loginError_yunifa) ? 'flex' : 'none'; ?>;
            align-items: center;
            gap: 8px;
            background: #fff0f0;
            border: 1px solid #ffc0c0;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            color: #c0392b;
            margin-bottom: 16px;
        }
        .forgot_yunifa {text-align: center;margin: 6px 0 20px;}
        .forgot_yunifa a {font-size: 13px;font-weight: 600;color: #1A73E8;text-decoration: none;}
        .forgot_yunifa a:hover { text-decoration: underline; }
        .btn-login_yunifa {
            width: 100%;
            padding: 15px;
            background: #1A73E8;
            color: white;
            border: none;
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
        }
        .btn-login_yunifa:hover  { background: #1558B0; }
        .btn-login_yunifa:active { transform: scale(0.98); }
        .back-link_yunifa {text-align: center;margin-top: 20px;font-size: 13px;color: #999;}
        .back-link_yunifa a {color: #1A73E8;font-weight: 600;text-decoration: none;}
        .back-link_yunifa a:hover { text-decoration: underline; }
        .right-panel_yunifa {width: 50%;min-height: 100vh;position: relative;overflow: hidden;}
        .right-panel_yunifa img.bg-photo {width: 100%;height: 100%;object-fit: cover;display: block;}
        .right-panel_yunifa .bg-fallback {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #1A73E8 0%, #0d47a1 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .right-panel_yunifa .logo-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .right-panel_yunifa .logo-overlay img {
            width: 220px;
            height: 220px;
            border-radius: 50%;
            object-fit: cover;
            opacity: 0.92;
            box-shadow: 0 8px 40px rgba(0,0,0,0.4);
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .form-area_yunifa { animation: fadeUp 0.4s ease both; }
        @media (max-width: 768px) {
            body { flex-direction: column; }
            .left-panel_yunifa  { width: 100%; padding: 28px 24px; min-height: auto; }
            .right-panel_yunifa { width: 100%; height: 240px; min-height: unset; }
            .form-heading_yunifa { font-size: 26px; }
        }
    </style>
</head>
<body>
    <div class="left-panel_yunifa">
        <div class="brand_yunifa">
            <img src="logo_smk2.png" alt="Logo"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="brand-fallback_yunifa" style="display:none;"><i class="fas fa-school"></i></div>
            <div class="brand-name_yunifa">SMK NEGERI 2 CIMAHI</div>
        </div>
        <div class="form-area_yunifa">
            <h1 class="form-heading_yunifa">
                <span>Selamat Datang</span> di<br>
                Kalender Akademik<br>
                SMK Negeri 2 Cimahi
            </h1>
            <p class="form-sub_yunifa">login untuk lihat kegiatan akademik terbaru</p>
            <div class="error-msg_yunifa">
                <i class="fas fa-exclamation-circle"></i>
                NIP / NIS / Email atau Password salah
            </div>
            <form method="post" action="">
                <div class="input-group_yunifa">
                    <input
                        type="text"
                        name="username_yunifa"
                        placeholder="NIP / NIS / Email Ortu"
                        value="<?php echo isset($_POST['username_yunifa']) ? htmlspecialchars($_POST['username_yunifa']) : ''; ?>"
                        class="<?php echo isset($loginError_yunifa) ? 'error' : ''; ?>"
                        required>
                </div>
                <div class="input-group_yunifa">
                    <input
                        type="password"
                        name="password_yunifa"
                        placeholder="Password"
                        class="<?php echo isset($loginError_yunifa) ? 'error' : ''; ?>"
                        required>
                </div>
                <button type="submit" name="login_yunifa" class="btn-login_yunifa">Masuk</button>
            </form>
            <div class="back-link_yunifa">
                Lihat kalender tanpa login <a href="kalender_publicYunifa.php">Kembali</a>
            </div>
        </div>
    </div>
    <div class="right-panel_yunifa">
        <img class="bg-photo" src="background_smk2.jpg" alt="SMKN 2 Cimahi"
            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
        <div class="bg-fallback" style="display:none;"></div>

        <div class="logo-overlay">
            <img src="logo_smk2.png" alt="Logo SMKN 2 Cimahi"
                onerror="this.style.display='none';">
        </div>
    </div>
</body>
</html>