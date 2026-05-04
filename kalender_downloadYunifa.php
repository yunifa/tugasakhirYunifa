<?php
session_start();
$nama_yunifa = $_SESSION['nama_yunifa'] ?? 'Tamu';
$tipe_yunifa = $_SESSION['tipe_yunifa'] ?? null;

$namaBulan_yunifa = [
    1=>'Januari', 2=>'Februari', 3=>'Maret',     4=>'April',
    5=>'Mei',     6=>'Juni',     7=>'Juli',       8=>'Agustus',
    9=>'September',10=>'Oktober',11=>'November',  12=>'Desember'
];
$tahunSekarang_yunifa = (int)date('Y');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Laporan – Kalender Akademik SMKN 2 Cimahi</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --blue-primary: #1A73E8;
            --blue-dark:    #1558B0;
            --blue-navbar:  #1565D8;
            --white:        #ffffff;
            --overlay:      rgba(0,0,0,0.38);
            --sidebar-w:    220px;
            --nav-h:        68px;
            --radius-lg:    20px;
            --radius-md:    12px;
            --shadow-card:  0 8px 32px rgba(0,0,0,0.35);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: url('background_smk2.jpg') no-repeat center center / cover fixed;
            min-height: 100vh;
            overflow-x: hidden;
        }
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background: var(--overlay);
            z-index: 0;
        }
        .navbar_yunifa {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            height: var(--nav-h);
            background: var(--blue-navbar);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.3);
            animation: fade_up_yunifa 0.3s ease both;
        }
        .navbar_logo_yunifa img {
            height: 46px; width: 46px;
            border-radius: 50%; object-fit: cover;
            border: 2px solid rgba(255,255,255,0.5);
        }
        .navbar_logo_yunifa .logo_fallback_yunifa {
            height: 46px; width: 46px;
            border-radius: 50%; background: white;
            display: flex; align-items: center; justify-content: center;
            color: var(--blue-primary); font-size: 22px;
            border: 2px solid rgba(255,255,255,0.5);
        }
        .navbar_title_yunifa {
            color: var(--white);
            font-size: clamp(15px, 2.2vw, 22px);
            font-weight: 800;
            text-align: center;
            flex: 1;
            padding: 0 16px;
        }
        .navbar-back_yunifa {
            background: white; border: none;
            border-radius: var(--radius-md);
            height: 40px; padding: 0 16px;
            display: flex; align-items: center; gap: 8px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px; font-weight: 700; color: #333;
            cursor: pointer; text-decoration: none; white-space: nowrap;
        }
        .navbar-back_yunifa:hover { background: #f0f0f0; }
        .sidebar_overlay_yunifa {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.25); z-index: 1100;
        }
        .sidebar_overlay_yunifa.open { display: block; }
        .sidebar_yunifa {
            position: fixed; top: 0; right: 0;
            width: var(--sidebar-w); height: 100%;
            background: white; z-index: 1200;
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(.4,0,.2,1);
            display: flex; flex-direction: column;
            padding-top: var(--nav-h);
            box-shadow: -4px 0 24px rgba(0,0,0,0.18);
        }
        .sidebar_yunifa.open { transform: translateX(0); }
        .sidebar_menu_yunifa { flex: 1; padding: 12px 0; }
        .sidebar_item_yunifa {
            display: flex; align-items: center; gap: 12px;
            padding: 14px 24px; font-size: 16px; font-weight: 700;
            color: #1a1a1a; cursor: pointer;
            transition: background 0.15s;
            border: none; background: none;
            width: 100%; text-align: left; text-decoration: none;
        }
        .sidebar_item_yunifa:hover { background: #f4f8ff; color: var(--blue-primary); }
        .sidebar_item_yunifa i { width: 20px; text-align: center; color: var(--blue-primary); font-size: 15px; }
        .sidebar_user_yunifa {
            padding: 16px 24px; display: flex; align-items: center; gap: 10px;
            border-top: 1px solid #e5e5e5;
            font-size: 15px; font-weight: 700; color: #1a1a1a;
        }
        .sidebar_user_yunifa i { font-size: 20px; color: #555; }

        .main_yunifa {
            position: relative; z-index: 1;
            padding-top: calc(var(--nav-h) + 28px);
            padding-bottom: 40px;
            display: flex; justify-content: center;
            align-items: flex-start; min-height: 100vh;
        }

        .download_card_yunifa {
            background: rgba(255,255,255,0.93);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-card);
            padding: 28px 28px 32px;
            width: min(940px, 96vw);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            animation: fade_up_yunifa 0.45s ease both;
        }
        .download_card_yunifa h2 {
            font-size: 22px; font-weight: 800;
            color: #1a1a1a; margin-bottom: 6px;
            text-align: center;
        }
        .download_card_yunifa p.sub_yunifa {
            font-size: 13px; color: #777;
            margin-bottom: 28px;
            text-align: center;
        }
        .filter_section_yunifa { margin-bottom: 20px; }
        .filter_section_yunifa select {
            width: 100%; padding: 14px 16px;
            border-radius: 12px; border: 1.5px solid #ddd;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px; color: #222;
            background: #fafafa;
            appearance: none; -webkit-appearance: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            text-align: center;
        }
        .filter_section_yunifa select:focus {
            outline: none;
            border-color: var(--blue-primary);
            box-shadow: 0 0 0 3px rgba(26,115,232,0.12);
            background: #fff;
        }
        .fields_container_yunifa {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }
        @media (max-width: 600px) {
            .fields_container_yunifa { grid-template-columns: 1fr; }
        }
        .form_field_yunifa {
            background: white;
            padding: 18px;
            border-radius: 14px;
            border: 1px solid #eee;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .form_field_yunifa:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.09);
        }
        .form_field_yunifa label {
            font-size: 13px; font-weight: 700;
            color: #444; display: block;
            margin-bottom: 8px;
        }
        .form_field_yunifa select,
        .form_field_yunifa input[type="date"] {
            width: 100%; padding: 11px 14px;
            border-radius: 10px; border: 1.5px solid #ddd;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px; color: #222;
            background: #fafafa;
            appearance: none; -webkit-appearance: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form_field_yunifa select:focus,
        .form_field_yunifa input[type="date"]:focus {
            outline: none;
            border-color: var(--blue-primary);
            box-shadow: 0 0 0 3px rgba(26,115,232,0.12);
            background: #fff;
        }
        .range_start_yunifa { border-left: 4px solid #22c55e; }
        .range_end_yunifa   { border-left: 4px solid #ef4444; }

        .btn_download_yunifa {
            padding: 13px 36px;
            background: var(--blue-primary); color: white;
            border: none; border-radius: 12px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px; font-weight: 700;
            cursor: pointer;
            display: inline-flex; align-items: center; gap: 8px;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 14px rgba(26,115,232,0.35);
            display: flex;
            justify-content: center;
            margin: 16px auto 0;
        }
        .btn_download_yunifa:hover {
            background: var(--blue-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(26,115,232,0.4);
        }
        .btn_download_yunifa:active { transform: translateY(0); }

        @keyframes fade_up_yunifa {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @media (max-width: 600px) {
            .navbar_title_yunifa { font-size: 14px; }
            .download_card_yunifa { padding: 20px 14px 24px; }
            .sidebar_yunifa { width: 200px; }
        }
    </style>
</head>
<body>

<nav class="navbar_yunifa">
    <div class="navbar_logo_yunifa">
        <img src="logo_smk2.png" alt="Logo SMKN 2 Cimahi"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
        <div class="logo_fallback_yunifa" style="display:none;">
            <i class="fas fa-school"></i>
        </div>
    </div>
    <div class="navbar_title_yunifa">Kalender Akademik SMKN 2 Cimahi</div>
    <a class="navbar-back_yunifa" href="kalender_dashboardYunifa.php">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</nav>

<div class="sidebar_overlay_yunifa" id="sidebar_overlay_yunifa"></div>
<aside class="sidebar_yunifa" id="sidebar_yunifa">
    <div class="sidebar_menu_yunifa">
        <a class="sidebar_item_yunifa" href="kalender_dashboardYunifa.php">
            <i class="fas fa-calendar-alt"></i> Kalender
        </a>
    </div>
    <div class="sidebar_user_yunifa">
        <i class="fas fa-user-circle"></i>
        <?php echo htmlspecialchars($nama_yunifa); ?>
    </div>
</aside>

<div class="main_yunifa">
    <div class="download_card_yunifa">

        <h2><i class="fas fa-file-pdf" style="color:#e53935; margin-right:8px;"></i>Download Laporan Kegiatan</h2>
        <p class="sub_yunifa">Pilih jenis laporan dan periode yang ingin diunduh sebagai PDF.</p>

        <form action="kalender_fpdfDownloadYunifa.php" method="GET" id="form_download_yunifa">

            <div class="filter_section_yunifa">
                <select name="filter" id="filter_yunifa" onchange="updateFieldsYunifa()" required>
                    <option value="">-- Pilih Jenis Laporan --</option>
                    <option value="tahun">Tahunan</option>
                    <option value="rentang_bulan">Bulanan</option>
                    <option value="rentang_minggu">Mingguan</option>
                    <option value="hari">Harian</option>
                </select>
            </div>

            <div class="fields_container_yunifa" id="fields_container_yunifa"></div>

            <button type="submit" class="btn_download_yunifa">
                <i class="fas fa-download"></i> Download PDF
            </button>

        </form>
    </div>
</div>

<script>
    const namaBulan_yunifa = {
        <?php foreach ($namaBulan_yunifa as $k => $v): ?>
        <?= $k ?>: '<?= $v ?>',
        <?php endforeach; ?>
    };
    const tahunNow_yunifa  = <?= $tahunSekarang_yunifa ?>;
    const bulanNow_yunifa  = <?= (int)date('n') ?>;
    const tanggalNow_yunifa = '<?= date('Y-m-d') ?>';

    function buatSelect_yunifa(name, options, selectedVal) {
        let html = '<select name="' + name + '" id="' + name + '_yunifa">';
        for (let val in options) {
            let sel = (String(val) === String(selectedVal)) ? ' selected' : '';
            html += '<option value="' + val + '"' + sel + '>' + options[val] + '</option>';
        }
        html += '</select>';
        return html;
    }

    function buatCard_yunifa(label, inputHtml, extraClass) {
        let div = document.createElement('div');
        div.className = 'form_field_yunifa' + (extraClass ? ' ' + extraClass : '');
        div.innerHTML = '<label>' + label + '</label>' + inputHtml;
        return div;
    }

    function opsiTahun_yunifa() {
        let opts = {};
        for (let i = tahunNow_yunifa + 1; i >= tahunNow_yunifa - 5; i--) {
            opts[i] = i;
        }
        return opts;
    }

    function opsiBulan_yunifa() {
        let opts = {};
        for (let i = 1; i <= 12; i++) {
            opts[i] = namaBulan_yunifa[i];
        }
        return opts;
    }

    function updateFieldsYunifa() {
        const jenis     = document.getElementById('filter_yunifa').value;
        const container = document.getElementById('fields_container_yunifa');
        container.innerHTML = '';

        const bulanOpts = opsiBulan_yunifa();
        const tahunOpts = opsiTahun_yunifa();

        if (jenis === 'tahun') {
            const cardTahun = buatCard_yunifa(
                'Tahun',
                buatSelect_yunifa('tahun', tahunOpts, tahunNow_yunifa)
            );
            cardTahun.style.gridColumn = "span 2";
            container.appendChild(cardTahun);

        } else if (jenis === 'rentang_bulan') {
            container.appendChild(buatCard_yunifa('Bulan Awal',
                buatSelect_yunifa('bulan_awal', bulanOpts, bulanNow_yunifa),
                'range_start_yunifa'));
            container.appendChild(buatCard_yunifa('Tahun Awal',
                buatSelect_yunifa('tahun_awal', tahunOpts, tahunNow_yunifa),
                'range_start_yunifa'));
            container.appendChild(buatCard_yunifa('Bulan Akhir',
                buatSelect_yunifa('bulan_akhir', bulanOpts, bulanNow_yunifa),
                'range_end_yunifa'));
            container.appendChild(buatCard_yunifa('Tahun Akhir',
                buatSelect_yunifa('tahun_akhir', tahunOpts, tahunNow_yunifa),
                'range_end_yunifa'));

            ['bulan_awal_yunifa','tahun_awal_yunifa','bulan_akhir_yunifa','tahun_akhir_yunifa'].forEach(function(id) {
                document.getElementById(id).addEventListener('change', syncRentang_yunifa);
            });

        } else if (jenis === 'rentang_minggu') {
            container.appendChild(buatCard_yunifa('Bulan',
                buatSelect_yunifa('bulan_rm', bulanOpts, bulanNow_yunifa)));
            container.appendChild(buatCard_yunifa('Tahun',
                buatSelect_yunifa('tahun_rm', tahunOpts, tahunNow_yunifa)));

            const tglMulaiCard = buatCard_yunifa('Tanggal Mulai',
                '<input type="date" name="tgl_mulai_rm" id="tgl_mulai_rm_yunifa">',
                'range_start_yunifa');
            container.appendChild(tglMulaiCard);

            const tglAkhirCard = buatCard_yunifa('Tanggal Akhir (otomatis hari ke-7)',
                '<input type="date" name="tgl_akhir_rm" id="tgl_akhir_rm_yunifa" readonly style="background:#f0f0f0;cursor:not-allowed;">',
                'range_end_yunifa');
            container.appendChild(tglAkhirCard);

            updateMinMaxTglMulai_yunifa();
            document.getElementById('bulan_rm_yunifa').addEventListener('change', updateMinMaxTglMulai_yunifa);
            document.getElementById('tahun_rm_yunifa').addEventListener('change', updateMinMaxTglMulai_yunifa);
            document.getElementById('tgl_mulai_rm_yunifa').addEventListener('change', autoIsiTglAkhir_yunifa);

        } else if (jenis === 'hari') {
            const cardHari = buatCard_yunifa('Tanggal',
                '<input type="date" name="tanggal" id="tanggal_yunifa" value="' + tanggalNow_yunifa + '">');
            cardHari.style.gridColumn = "span 2";
            container.appendChild(cardHari);
        }
    }

    function syncRentang_yunifa() {
        const ba = parseInt(document.getElementById('bulan_awal_yunifa').value);
        const ta = parseInt(document.getElementById('tahun_awal_yunifa').value);
        const bk = parseInt(document.getElementById('bulan_akhir_yunifa').value);
        const tk = parseInt(document.getElementById('tahun_akhir_yunifa').value);

        if (new Date(tk, bk - 1) < new Date(ta, ba - 1)) {
            document.getElementById('bulan_akhir_yunifa').value = ba;
            document.getElementById('tahun_akhir_yunifa').value = ta;
        }
    }

    function updateMinMaxTglMulai_yunifa() {
        const bulan = parseInt(document.getElementById('bulan_rm_yunifa').value);
        const tahun = parseInt(document.getElementById('tahun_rm_yunifa').value);
        const hariTerakhir = new Date(tahun, bulan, 0).getDate();
        const pad = n => String(n).padStart(2, '0');
        const minVal = tahun + '-' + pad(bulan) + '-01';
        const maxTgl = hariTerakhir - 6;
        const maxVal = tahun + '-' + pad(bulan) + '-' + pad(maxTgl);

        const inputMulai = document.getElementById('tgl_mulai_rm_yunifa');
        inputMulai.min   = minVal;
        inputMulai.max   = maxVal;
        if (!inputMulai.value || inputMulai.value < minVal || inputMulai.value > maxVal) {
            inputMulai.value = minVal;
        }
        autoIsiTglAkhir_yunifa();
    }

    function autoIsiTglAkhir_yunifa() {
        const inputMulai = document.getElementById('tgl_mulai_rm_yunifa');
        const inputAkhir = document.getElementById('tgl_akhir_rm_yunifa');
        if (!inputMulai || !inputAkhir || !inputMulai.value) return;

        const d = new Date(inputMulai.value);
        d.setDate(d.getDate() + 6);
        const pad = n => String(n).padStart(2, '0');
        inputAkhir.value = d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate());
    }

    document.getElementById('form_download_yunifa').addEventListener('submit', function(e) {
        const jenis = document.getElementById('filter_yunifa').value;

        if (!jenis) {
            e.preventDefault();
            alert('Pilih jenis laporan terlebih dahulu.');
            return;
        }

        if (jenis === 'rentang_bulan') {
            const ba = parseInt(document.getElementById('bulan_awal_yunifa').value);
            const ta = parseInt(document.getElementById('tahun_awal_yunifa').value);
            const bk = parseInt(document.getElementById('bulan_akhir_yunifa').value);
            const tk = parseInt(document.getElementById('tahun_akhir_yunifa').value);

            if (new Date(tk, bk - 1) < new Date(ta, ba - 1)) {
                e.preventDefault();
                alert('Bulan akhir tidak boleh lebih awal dari bulan awal.');
            }
        }

        if (jenis === 'rentang_minggu') {
            const mulai = document.getElementById('tgl_mulai_rm_yunifa');
            const akhir = document.getElementById('tgl_akhir_rm_yunifa');
            if (!mulai || !mulai.value || !akhir || !akhir.value) {
                e.preventDefault();
                alert('Pilih tanggal mulai terlebih dahulu.');
            }
        }
    });
</script>
</body>
</html>