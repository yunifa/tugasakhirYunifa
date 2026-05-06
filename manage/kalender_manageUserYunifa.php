<?php
session_start();
if (!isset($_SESSION['role_yunifa']) || $_SESSION['role_yunifa'] != 8) {
    header('Location: kalender_loginYunifa.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalender Akademik SMKN 2 Cimahi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --blue-primary: #1A73E8;--blue-dark: #1558B0;--blue-navbar: #1565D8;--white: #ffffff;
            --gray-light: #c0c0c0;--gray-mid: #888;--text-main: #1a1a1a;--overlay: rgba(0,0,0,0.38);
            --sidebar-w: 220px;--nav-h: 68px;--radius-lg: 20px;--radius-md: 12px;--radius-sm: 8px;
            --shadow-card: 0 8px 32px rgba(0,0,0,0.35);
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: url('../background_smk2.jpg') no-repeat center center / cover fixed;
            min-height: 100vh; overflow-x: hidden;
        }
        body::before { content:"";position:fixed;inset:0;background:var(--overlay);z-index:0; }
        .navbar_yunifa {
            position:fixed;top:0;left:0;right:0;z-index:1000;height:var(--nav-h);
            background:var(--blue-navbar);display:flex;align-items:center;justify-content:space-between;
            padding:0 24px;box-shadow:0 2px 12px rgba(0,0,0,0.3);animation:fadeUp 0.3s ease both;
        }
        .navbar-logo_yunifa img { height:46px;width:46px;border-radius:50%;object-fit:cover;border:2px solid rgba(255,255,255,0.5); }
        .navbar-logo_yunifa .logo-fallback_yunifa {
            height:46px;width:46px;border-radius:50%;background:white;display:flex;align-items:center;justify-content:center;
            color:var(--blue-primary);font-size:22px;border:2px solid rgba(255,255,255,0.5);
        }
        .navbar-title_yunifa { color:var(--white);font-size:clamp(15px,2.2vw,22px);font-weight:800;text-align:center;flex:1;padding:0 16px; }
        .navbar-hamburger_yunifa {
            background:white;border:none;border-radius:var(--radius-md);width:44px;height:44px;display:flex;flex-direction:column;
            align-items:center;justify-content:center;gap:5px;cursor:pointer;transition:background 0.2s;flex-shrink:0;
        }
        .navbar-hamburger_yunifa:hover { background:#f0f0f0; }
        .navbar-hamburger_yunifa span { display:block;width:22px;height:2.5px;background:#222;border-radius:2px; }

        .sidebar-overlay_yunifa { display:none;position:fixed;inset:0;background:rgba(0,0,0,0.25);z-index:1100; }
        .sidebar-overlay_yunifa.open_yunifa { display:block; }
        .sidebar_yunifa {
            position:fixed;top:0;right:0;width:var(--sidebar-w);height:100%;background:white;z-index:1200;
            transform:translateX(100%);transition:transform 0.3s cubic-bezier(.4,0,.2,1);display:flex;flex-direction:column;
            padding-top:var(--nav-h);box-shadow:-4px 0 24px rgba(0,0,0,0.18);
        }
        .sidebar_yunifa.open_yunifa { transform:translateX(0); }
        .sidebar-menu_yunifa { flex:1;padding:12px 0; }
        .sidebar-item_yunifa {
            display:flex;align-items:center;gap:12px;padding:14px 24px;font-size:16px;font-weight:700;color:#1a1a1a;
            cursor:pointer;transition:background 0.15s;border:none;background:none;width:100%;text-align:left;text-decoration:none;
        }
        .sidebar-item_yunifa:hover, .sidebar-item_yunifa.active_yunifa { background:#f4f8ff;color:var(--blue-primary); }
        .sidebar-item_yunifa i { width:20px;text-align:center;color:var(--blue-primary);font-size:15px; }
        .sidebar-divider_yunifa { height:1px;background:#e5e5e5;margin:4px 20px; }
        .sidebar-user_yunifa { 
            padding:16px 24px;display:flex;align-items:center;gap:10px;border-top:1px solid #e5e5e5;
            font-size:15px;font-weight:700;color:#1a1a1a; 
        }
        .sidebar-user_yunifa i { font-size:20px;color:#555; }
        .main_yunifa {
            position:relative;z-index:1;padding-top:calc(var(--nav-h) + 28px);padding-bottom:40px;
            display:flex;justify-content:center;align-items:center;min-height:100vh;
        }
        .content-card_yunifa {
            background:rgba(255,255,255,0.93);border-radius:var(--radius-lg);box-shadow:var(--shadow-card);
            padding:48px;width:min(600px, 90vw);backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);
            text-align:center;animation:fadeUp 0.45s ease both;
        }
        .header-title_yunifa {
            font-size:clamp(24px,5vw,32px);font-weight:800;color:var(--text-main);margin-bottom:12px;
            background:linear-gradient(135deg,var(--blue-primary),#1e88e5);background-clip:text;-webkit-background-clip:text;-webkit-text-fill-color:transparent;
        }
        .header-subtitle_yunifa { color:var(--gray-mid);font-size:16px;margin-bottom:48px;line-height:1.6; }
        .user-buttons_yunifa {
            display:flex;flex-direction:column;gap:20px;max-width:400px;margin:0 auto;
        }
        .user-btn_yunifa {
            display:flex;align-items:center;justify-content:center;gap:16px;padding:20px 32px;
            border:none;border-radius:var(--radius-lg);font-family:'Plus Jakarta Sans',sans-serif;
            font-size:18px;font-weight:700;text-decoration:none;transition:all 0.3s;
            box-shadow:0 8px 24px rgba(0,0,0,0.15);
        }
        .btn-guru_yunifa {
            background:linear-gradient(135deg,#4caf50,#45a049);color:white;
        }
        .btn-guru_yunifa:hover {
            transform:translateY(-4px);box-shadow:0 12px 32px rgba(76,175,80,0.4);
        }
        .btn-siswa_yunifa {
            background:linear-gradient(135deg,var(--blue-primary),#1e88e5);color:white;border:2px solid transparent;
        }
        .btn-siswa_yunifa:hover {
            transform:translateY(-4px);box-shadow:0 12px 32px rgba(26,115,232,0.4);border-color:rgba(255,255,255,0.3);
        }
        .navbar-back_yunifa { background:white;border:none;border-radius:var(--radius-md);height:40px;padding:0 16px;display:flex;align-items:center;gap:8px;font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:700;color:#333;cursor:pointer;text-decoration:none;white-space:nowrap; }
        .navbar-back_yunifa:hover { background:#f0f0f0; }
        @keyframes fadeUp { from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)} }
        @media(max-width:600px){
            .content-card_yunifa{padding:32px 24px;}
            .user-btn_yunifa{padding:18px 24px;font-size:16px;}
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

<div class="sidebar-overlay_yunifa" id="sidebarOverlay_yunifa"></div>
<aside class="sidebar_yunifa" id="sidebar_yunifa">
    <div class="sidebar-menu_yunifa">
        <a href="../kalender_dashboardYunifa.php" class="sidebar-item_yunifa">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="/kalender_manageUserYunifa.php" class="sidebar-item_yunifa active_yunifa">
            <i class="fas fa-users-cog"></i> Kelola User
        </a>
        <div class="sidebar-divider_yunifa"></div>
        <a href="../profil/kalender_logoutYunifa.php?logout=1" class="sidebar-item_yunifa">
            <i class="fas fa-sign-out-alt"></i> Keluar
        </a>
    </div>
    <div class="sidebar-user_yunifa">
        <i class="fas fa-user-shield"></i> Admin
    </div>
</aside>

<main class="main_yunifa">
    <div class="content-card_yunifa">
        <h1 class="header-title_yunifa">
            <i class="fas fa-users-cog"></i> Kelola Pengguna
        </h1>
        <p class="header-subtitle_yunifa">
            Pilih kategori pengguna yang ingin Anda kelola
        </p>
        
        <div class="user-buttons_yunifa">
            <a href="kalender_manageGuruYunifa.php" class="user-btn_yunifa btn-guru_yunifa">
                <i class="fas fa-chalkboard-teacher" style="font-size:24px;"></i>
                <span>Kelola Guru<br><small>(NIP, Nama, Role, Email)</small></span>
            </a>
            
            <a href="kalender_manageSiswaYunifa.php" class="user-btn_yunifa btn-siswa_yunifa">
                <i class="fas fa-users" style="font-size:24px;"></i>
                <span>Kelola Siswa & Ortu<br><small>(NIS, Nama, Kelas, Email)</small></span>
            </a>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle
    const sidebar = document.getElementById('sidebar_yunifa');
    const sOverlay = document.getElementById('sidebarOverlay_yunifa');
    const menuToggle = document.getElementById('menuToggle_yunifa');
    
    menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('open_yunifa');
        sOverlay.classList.toggle('open_yunifa');
    });
    
    sOverlay.addEventListener('click', () => {
        sidebar.classList.remove('open_yunifa');
        sOverlay.classList.remove('open_yunifa');
    });
});
</script>

</body>
</html>