<?php
    session_start();
    include "../kalender_koneksiYunifa.php";

    if (!isset($_SESSION['role_yunifa']) || $_SESSION['role_yunifa'] != 8) {
        header('Location: ../kalender_loginYunifa.php');
        exit;
    }

    if (isset($_GET['hapus'])) {
        $id = intval($_GET['hapus']);
        mysqli_query($koneksiYunifa, "DELETE FROM guru_yunifa WHERE id_guru_yunifa = $id");
        header('Location: kalender_manageGuruYunifa.php?deleted=1');
        exit;
    }

    if (isset($_POST['tambah_yunifa'])) {
        $nip      = mysqli_real_escape_string($koneksiYunifa, $_POST['nip_yunifa']);
        $nama     = mysqli_real_escape_string($koneksiYunifa, $_POST['nama_yunifa']);
        $email    = mysqli_real_escape_string($koneksiYunifa, $_POST['email_yunifa']) ?: 'NULL';
        $id_role  = intval($_POST['id_role_yunifa']);
        $password = password_hash($_POST['password_yunifa'], PASSWORD_DEFAULT);
        $email_val = $email === 'NULL' ? 'NULL' : "'$email'";

        mysqli_query($koneksiYunifa, "
            INSERT INTO guru_yunifa (id_role_yunifa, email_guru_yunifa, nip_yunifa, nama_yunifa, password_yunifa)
            VALUES ($id_role, $email_val, '$nip', '$nama', '$password')
        ");
        header('Location: kalender_manageGuruYunifa.php?added=1');
        exit;
    }

    if (isset($_POST['edit_yunifa'])) {
        $id      = intval($_POST['id_yunifa']);
        $nip     = mysqli_real_escape_string($koneksiYunifa, $_POST['nip_yunifa']);
        $nama    = mysqli_real_escape_string($koneksiYunifa, $_POST['nama_yunifa']);
        $email   = mysqli_real_escape_string($koneksiYunifa, $_POST['email_yunifa']);
        $id_role = intval($_POST['id_role_yunifa']);
        $email_val = $email ? "'$email'" : 'NULL';

        $sql = "UPDATE guru_yunifa SET
            nip_yunifa='$nip', nama_yunifa='$nama',
            email_guru_yunifa=$email_val, id_role_yunifa=$id_role";

        if (!empty($_POST['password_yunifa'])) {
            $pass = password_hash($_POST['password_yunifa'], PASSWORD_DEFAULT);
            $sql .= ", password_yunifa='$pass'";
        }
        $sql .= " WHERE id_guru_yunifa=$id";
        mysqli_query($koneksiYunifa, $sql);
        header('Location: kalender_manageGuruYunifa.php?updated=1');
        exit;
    }

    $cari = isset($_GET['cari']) ? mysqli_real_escape_string($koneksiYunifa, $_GET['cari']) : '';
    $where = $cari ? "WHERE g.nama_yunifa LIKE '%$cari%' OR g.nip_yunifa LIKE '%$cari%'" : '';

    $hasil = mysqli_query($koneksiYunifa, "
        SELECT g.*, r.role_yunifa
        FROM guru_yunifa g
        JOIN role_yunifa r ON g.id_role_yunifa = r.id_role_yunifa
        $where
        ORDER BY g.id_role_yunifa ASC, g.nama_yunifa ASC
    ");

    $roles = mysqli_query($koneksiYunifa, "SELECT * FROM role_yunifa WHERE id_role_yunifa != 9 ORDER BY id_role_yunifa");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Guru – Kalender Akademik SMKN 2 Cimahi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --blue-primary:#1A73E8;--blue-dark:#1558B0;--blue-navbar:#1565D8;--blue-light:#EBF3FD;
            --white:#fff;--gray-mid:#888;--text-main:#1a1a1a;--text-sub:#555;
            --overlay:rgba(0,0,0,0.38);--nav-h:68px;--radius-lg:20px;--radius-md:12px;--radius-sm:8px;
            --shadow-card:0 8px 32px rgba(0,0,0,0.35);
        }
        body { font-family:'Plus Jakarta Sans',sans-serif;background:url('../background_smk2.jpg') no-repeat center/cover fixed;min-height:100vh;overflow-x:hidden; }
        body::before { content:"";position:fixed;inset:0;background:var(--overlay);z-index:0; }
        .navbar_yunifa { position:fixed;top:0;left:0;right:0;z-index:1000;height:var(--nav-h);background:var(--blue-navbar);display:flex;align-items:center;justify-content:space-between;padding:0 24px;box-shadow:0 2px 12px rgba(0,0,0,0.3); }
        .navbar_yunifa img { height:46px;width:46px;border-radius:50%;object-fit:cover;border:2px solid rgba(255,255,255,0.5); }
        .navbar-logo-fallback_yunifa { height:46px;width:46px;border-radius:50%;background:white;display:flex;align-items:center;justify-content:center;color:var(--blue-primary);font-size:22px; }
        .navbar-title_yunifa { color:#fff;font-size:clamp(14px,2vw,20px);font-weight:800;text-align:center;flex:1;padding:0 16px; }
        .navbar-back_yunifa { background:white;border:none;border-radius:var(--radius-md);height:40px;padding:0 16px;display:flex;align-items:center;gap:8px;font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:700;color:#333;cursor:pointer;text-decoration:none;white-space:nowrap; }
        .navbar-back_yunifa:hover { background:#f0f0f0; }
        .main_yunifa { position:relative;z-index:1;padding-top:calc(var(--nav-h)+24px);padding-bottom:40px;display:flex;justify-content:center; top:100px;}
        .page-card_yunifa { background:rgba(255,255,255,0.95);border-radius:var(--radius-lg);box-shadow:var(--shadow-card);width:min(1100px,96vw);backdrop-filter:blur(6px);overflow:hidden;animation:fadeUp 0.4s ease both; }
        .toolbar_yunifa { display:flex;align-items:center;gap:12px;padding:20px 24px;border-bottom:1px solid #eee;flex-wrap:wrap; }
        .toolbar_yunifa h2 { font-size:18px;font-weight:800;color:var(--text-main);flex-shrink:0; }
        .search-wrap_yunifa { display:flex;align-items:center;gap:8px;flex:1;min-width:200px; }
        .search-wrap_yunifa input { flex:1;padding:9px 14px;border:1.5px solid #dde4f0;border-radius:var(--radius-sm);font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;outline:none;transition:border-color 0.2s; }
        .search-wrap_yunifa input:focus { border-color:var(--blue-primary); }
        .btn_yunifa { padding:9px 18px;border-radius:var(--radius-sm);font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;font-weight:700;cursor:pointer;border:none;display:inline-flex;align-items:center;gap:7px;transition:all 0.15s;text-decoration:none;white-space:nowrap; }
        .btn-primary_yunifa { background:var(--blue-primary);color:#fff;box-shadow:0 2px 8px rgba(26,115,232,0.25); }
        .btn-primary_yunifa:hover { background:var(--blue-dark); }
        .btn-search_yunifa { background:#f0f4fd;color:var(--blue-dark);border:1.5px solid #d0daf7; }
        .btn-search_yunifa:hover { background:#e2eafc; }
        .table-wrap_yunifa { overflow-x:auto; }
        table { width:100%;border-collapse:collapse; }
        thead tr { background:var(--blue-light); }
        th { padding:12px 16px;font-size:12px;font-weight:700;color:var(--blue-dark);text-transform:uppercase;letter-spacing:0.4px;text-align:left;white-space:nowrap; }
        td { padding:12px 16px;font-size:13px;color:var(--text-main);border-bottom:1px solid #f0f0f0;vertical-align:middle; }
        tr:hover td { background:#fafcff; }
        .badge-role_yunifa { display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;background:#f0f4fd;color:var(--blue-dark);white-space:nowrap; }
        .badge-admin_yunifa { background:#fef2f2;color:#dc2626; }
        .badge-waka_yunifa  { background:#f0fdf4;color:#16a34a; }
        .badge-staff_yunifa { background:#fff7ed;color:#ea580c; }
        .aksi-btns_yunifa { display:flex;gap:8px;align-items:center; }
        .btn-edit_yunifa { padding:6px 12px;background:#EBF3FD;color:var(--blue-dark);border-radius:var(--radius-sm);font-size:12px;font-weight:700;border:none;cursor:pointer;display:flex;align-items:center;gap:5px;text-decoration:none; }
        .btn-edit_yunifa:hover { background:#d0e4f8; }
        .btn-hapus_yunifa { padding:6px 12px;background:#fef2f2;color:#dc2626;border-radius:var(--radius-sm);font-size:12px;font-weight:700;border:none;cursor:pointer;display:flex;align-items:center;gap:5px; }
        .btn-hapus_yunifa:hover { background:#fde8e8; }
        .empty-state_yunifa { text-align:center;padding:48px;color:var(--gray-mid);font-size:14px; }
        .empty-state_yunifa i { font-size:40px;margin-bottom:12px;display:block;opacity:0.4; }
        .modal-overlay_yunifa { display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:2000;align-items:center;justify-content:center;padding:20px;overflow-y:auto; }
        .modal-overlay_yunifa.open_yunifa { display:flex; }
        .modal-box_yunifa { background:white;border-radius:var(--radius-lg);width:min(520px,100%);box-shadow:0 24px 64px rgba(0,0,0,0.35);animation:modalIn 0.3s cubic-bezier(.34,1.56,.64,1) both;overflow:hidden; }
        @keyframes modalIn { from{opacity:0;transform:scale(0.92) translateY(20px)}to{opacity:1;transform:scale(1) translateY(0)} }
        .modal-header_yunifa { background:linear-gradient(135deg,var(--blue-dark),var(--blue-primary));padding:18px 24px;display:flex;align-items:center;justify-content:space-between; }
        .modal-header_yunifa h3 { color:#fff;font-size:16px;font-weight:800;display:flex;align-items:center;gap:10px; }
        .modal-close_yunifa { background:rgba(255,255,255,0.2);border:none;color:#fff;width:32px;height:32px;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:14px; }
        .modal-close_yunifa:hover { background:rgba(255,255,255,0.35); }
        .modal-body_yunifa { padding:24px; }
        .form-group_yunifa { margin-bottom:16px; }
        .form-group_yunifa label { display:block;font-size:11px;font-weight:700;color:var(--text-sub);margin-bottom:5px;text-transform:uppercase;letter-spacing:0.4px; }
        .form-group_yunifa input,.form-group_yunifa select { width:100%;padding:10px 13px;border:1.5px solid #dde4f0;border-radius:var(--radius-sm);font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;color:var(--text-main);background:#fafdff;outline:none;transition:border-color 0.2s; }
        .form-group_yunifa input:focus,.form-group_yunifa select:focus { border-color:var(--blue-primary);box-shadow:0 0 0 3px rgba(26,115,232,0.1); }
        .form-hint_yunifa { font-size:11px;color:var(--gray-mid);margin-top:4px; }
        .modal-footer_yunifa { display:flex;justify-content:flex-end;gap:10px;padding:0 24px 24px; }
        .toast_yunifa { position:fixed;bottom:28px;left:50%;transform:translateX(-50%) translateY(20px);background:#22c55e;color:#fff;padding:11px 26px;border-radius:40px;font-size:13px;font-weight:700;box-shadow:0 4px 16px rgba(0,0,0,0.2);opacity:0;pointer-events:none;transition:opacity 0.3s,transform 0.3s;z-index:9999; }
        .toast_yunifa.show_yunifa { opacity:1;transform:translateX(-50%) translateY(0); }
        .toast-red_yunifa { background:#ef4444; }
        @keyframes fadeUp { from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)} }
    </style>
</head>
<body>
    <nav class="navbar_yunifa">
        <img src="../logo_smk2.png" alt="Logo" onerror="this.style.display='none'">
        <div class="navbar-title_yunifa"><i class="fas fa-chalkboard-teacher" style="margin-right:8px;"></i>Kalender Akademik SMKN 2 Cimahi</div>
        <a class="navbar-back_yunifa" href="kalender_manageUserYunifa.php">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </nav>

    <main class="main_yunifa">
        <div class="page-card_yunifa">
            <div class="toolbar_yunifa">
                <h2><i class="fas fa-users" style="color:var(--blue-primary);margin-right:6px;"></i>Data Guru</h2>
                <form method="GET" class="search-wrap_yunifa">
                    <input type="text" name="cari" placeholder="Cari nama atau NIP..."
                        value="<?= htmlspecialchars($cari) ?>">
                    <button type="submit" class="btn_yunifa btn-search_yunifa">
                        <i class="fas fa-search"></i>
                    </button>
                    <?php if ($cari): ?>
                        <a href="kalender_manageGuruYunifa.php" class="btn_yunifa btn-search_yunifa">
                            <i class="fas fa-times"></i>
                        </a>
                    <?php endif; ?>
                </form>
                <button class="btn_yunifa btn-primary_yunifa" onclick="openTambah()">
                    <i class="fas fa-plus"></i> Tambah Guru
                </button>
            </div>

            <div class="table-wrap_yunifa">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = 1;
                    $roleClass = [1=>'badge-waka_yunifa',2=>'badge-waka_yunifa',3=>'badge-waka_yunifa',
                                4=>'badge-waka_yunifa',5=>'badge-waka_yunifa',6=>'badge-staff_yunifa',
                                7=>'',8=>'badge-admin_yunifa'];
                    if (mysqli_num_rows($hasil) == 0): ?>
                        <tr><td colspan="6">
                            <div class="empty-state_yunifa">
                                <i class="fas fa-user-slash"></i>
                                Tidak ada data guru ditemukan.
                            </div>
                        </td></tr>
                    <?php else:
                        while ($row = mysqli_fetch_assoc($hasil)):
                            $rc = $roleClass[$row['id_role_yunifa']] ?? '';
                    ?>
                        <tr>
                            <td style="color:var(--gray-mid);font-size:12px;"><?= $no++ ?></td>
                            <td style="font-family:monospace;font-size:12px;"><?= $row['nip_yunifa'] ?></td>
                            <td style="font-weight:600;"><?= htmlspecialchars($row['nama_yunifa']) ?></td>
                            <td><span class="badge-role_yunifa <?= $rc ?>"><?= htmlspecialchars($row['role_yunifa']) ?></span></td>
                            <td style="color:var(--gray-mid);"><?= $row['email_guru_yunifa'] ?: '<span style="color:#ccc;">—</span>' ?></td>
                            <td>
                                <div class="aksi-btns_yunifa">
                                    <button class="btn-edit_yunifa" onclick='openEdit(<?= json_encode($row) ?>)'>
                                        <i class="fas fa-pen"></i> Edit
                                    </button>
                                    <button class="btn-hapus_yunifa" onclick="hapus(<?= $row['id_guru_yunifa'] ?>, '<?= htmlspecialchars(addslashes($row['nama_yunifa'])) ?>')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div class="modal-overlay_yunifa" id="modalTambah_yunifa">
        <div class="modal-box_yunifa">
            <div class="modal-header_yunifa">
                <h3><i class="fas fa-user-plus"></i> Tambah Guru</h3>
                <button class="modal-close_yunifa" onclick="closeTambah()"><i class="fas fa-times"></i></button>
            </div>
            <form method="POST">
                <div class="modal-body_yunifa">
                    <div class="form-group_yunifa">
                        <label>NIP</label>
                        <input type="text" name="nip_yunifa" placeholder="Contoh: 197507122008011009" required>
                    </div>
                    <div class="form-group_yunifa">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_yunifa" placeholder="Nama guru" required>
                    </div>
                    <div class="form-group_yunifa">
                        <label>Role</label>
                        <select name="id_role_yunifa" required>
                            <?php
                            mysqli_data_seek($roles, 0);
                            while ($r = mysqli_fetch_assoc($roles)):
                            ?>
                            <option value="<?= $r['id_role_yunifa'] ?>"><?= htmlspecialchars($r['role_yunifa']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group_yunifa">
                        <label>Email</label>
                        <input type="email" name="email_yunifa" placeholder="Opsional">
                    </div>
                    <div class="form-group_yunifa">
                        <label>Password</label>
                        <input type="text" name="password_yunifa" placeholder="Masukkan password" required>
                        <div class="form-hint_yunifa">Password akan di-hash secara otomatis.</div>
                    </div>
                </div>
                <div class="modal-footer_yunifa">
                    <button type="button" class="btn_yunifa btn-search_yunifa" onclick="closeTambah()">Batal</button>
                    <button type="submit" name="tambah_yunifa" class="btn_yunifa btn-primary_yunifa">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay_yunifa" id="modalEdit_yunifa">
        <div class="modal-box_yunifa">
            <div class="modal-header_yunifa">
                <h3><i class="fas fa-pen"></i> Edit Guru</h3>
                <button class="modal-close_yunifa" onclick="closeEdit()"><i class="fas fa-times"></i></button>
            </div>
            <form method="POST">
                <input type="hidden" name="id_yunifa" id="editId_yunifa">
                <div class="modal-body_yunifa">
                    <div class="form-group_yunifa">
                        <label>NIP</label>
                        <input type="text" name="nip_yunifa" id="editNip_yunifa" required>
                    </div>
                    <div class="form-group_yunifa">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_yunifa" id="editNama_yunifa" required>
                    </div>
                    <div class="form-group_yunifa">
                        <label>Role</label>
                        <select name="id_role_yunifa" id="editRole_yunifa">
                            <?php
                            mysqli_data_seek($roles, 0);
                            while ($r = mysqli_fetch_assoc($roles)):
                            ?>
                            <option value="<?= $r['id_role_yunifa'] ?>"><?= htmlspecialchars($r['role_yunifa']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group_yunifa">
                        <label>Email</label>
                        <input type="email" name="email_yunifa" id="editEmail_yunifa">
                    </div>
                    <div class="form-group_yunifa">
                        <label>Password Baru</label>
                        <input type="text" name="password_yunifa" id="editPass_yunifa" placeholder="Kosongkan jika tidak diubah">
                        <div class="form-hint_yunifa">Isi hanya jika ingin mengganti password.</div>
                    </div>
                </div>
                <div class="modal-footer_yunifa">
                    <button type="button" class="btn_yunifa btn-search_yunifa" onclick="closeEdit()">Batal</button>
                    <button type="submit" name="edit_yunifa" class="btn_yunifa btn-primary_yunifa">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="toast_yunifa" id="toast_yunifa"></div>

    <script>
        function openTambah() { document.getElementById('modalTambah_yunifa').classList.add('open_yunifa'); document.body.style.overflow='hidden'; }
        function closeTambah() { document.getElementById('modalTambah_yunifa').classList.remove('open_yunifa'); document.body.style.overflow=''; }

        function openEdit(data) {
            document.getElementById('editId_yunifa').value    = data.id_guru_yunifa;
            document.getElementById('editNip_yunifa').value   = data.nip_yunifa;
            document.getElementById('editNama_yunifa').value  = data.nama_yunifa;
            document.getElementById('editEmail_yunifa').value = data.email_guru_yunifa || '';
            document.getElementById('editRole_yunifa').value  = data.id_role_yunifa;
            document.getElementById('editPass_yunifa').value  = '';
            document.getElementById('modalEdit_yunifa').classList.add('open_yunifa');
            document.body.style.overflow = 'hidden';
        }
        function closeEdit() { document.getElementById('modalEdit_yunifa').classList.remove('open_yunifa'); document.body.style.overflow=''; }

        ['modalTambah_yunifa','modalEdit_yunifa'].forEach(function(id){
            document.getElementById(id).addEventListener('click', function(e){
                if (e.target === this) { this.classList.remove('open_yunifa'); document.body.style.overflow=''; }
            });
        });

        function hapus(id, nama) {
            if (confirm('Hapus guru "' + nama + '"?\nKegiatan yang dibuat guru ini mungkin ikut terpengaruh.')) {
                window.location.href = 'kalender_manageGuruYunifa.php?hapus=' + id;
            }
        }

        (function(){
            var p = new URLSearchParams(window.location.search);
            var msg = p.get('added') ? '✓ Guru berhasil ditambahkan!'
                    : p.get('updated') ? '✓ Data guru berhasil diperbarui!'
                    : p.get('deleted') ? '🗑 Guru berhasil dihapus!' : '';
            if (msg) {
                var t = document.getElementById('toast_yunifa');
                if (p.get('deleted')) t.classList.add('toast-red_yunifa');
                t.textContent = msg;
                t.classList.add('show_yunifa');
                setTimeout(function(){ t.classList.remove('show_yunifa'); }, 3000);
            }
        })();
    </script>
</body>
</html>
