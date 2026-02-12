<?php
session_start();
include "kalender_koneksiYunifa.php";

if (!isset($_SESSION['tipeYunifa'])) {
    header("Location: kalender_loginYunifa.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: kalender_dashboardYunifa.php");
    exit;
}

$id = intval($_GET['id']);

$query = mysqli_query($koneksiYunifa, "
    SELECT kegiatan_yunifa.*, guru_yunifa.nama AS nama_guru
    FROM kegiatan_yunifa
    JOIN guru_yunifa 
        ON kegiatan_yunifa.id_guru = guru_yunifa.id_guru
    WHERE id_kegiatan = '$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data tidak ditemukan.";
    exit;
}

function getTextColor($hexColor) {
    $hexColor = str_replace('#', '', $hexColor);
    $r = hexdec(substr($hexColor, 0, 2));
    $g = hexdec(substr($hexColor, 2, 2));
    $b = hexdec(substr($hexColor, 4, 2));
    $brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
    return ($brightness > 155) ? 'black' : 'white';
}

$textColor = getTextColor($data['warna']);

$bolehEdit = false;
$tanggalSekarang = date("Y-m-d");

if ($_SESSION['tipeYunifa'] == 'guru') {
    if (
        $_SESSION['roleYunifa'] == 'staff' ||
        str_contains($_SESSION['roleYunifa'], 'wakil kepala sekolah')
    ) {
        if ($data['tanggal_mulai'] >= $tanggalSekarang) {
            $bolehEdit = true;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Kegiatan</title>
</head>
<body>

<div style="
    max-width:600px;
    margin:30px auto;
    padding:25px;
    border-radius:10px;
    background: <?= $data['warna']; ?>;
    color: <?= $textColor ?>;
">

    <h2><?= $data['judul']; ?></h2>

    <p><b>Tanggal:</b>
        <?= date('d-m-Y', strtotime($data['tanggal_mulai'])); ?>

        <?php if ($data['seharian'] == 'tidak'): ?>
            | <?= $data['waktu_mulai']; ?> - <?= $data['waktu_selesai']; ?>
        <?php endif; ?>
    </p>

    <p><b>Deskripsi:</b><br>
        <?= nl2br($data['deskripsi']); ?>
    </p>

    <p><b>Hak Akses:</b> <?= $data['hak_akses']; ?></p>

    <p><b>Dibuat oleh:</b> <?= $data['nama_guru']; ?></p>

    <p><b>Dibuat pada:</b> 
        <?= date('d-m-Y H:i', strtotime($data['dibuat_pada'])); ?>
    </p>

    <?php if ($bolehEdit): ?>
        <hr>
        <a href="kalender_updateYunifa.php?id=<?= $data['id_kegiatan']; ?>" 
           style="color: <?= $textColor ?>;">
            ✏ Edit
        </a>
        |
        <a href="kalender_deleteYunifa.php?id=<?= $data['id_kegiatan']; ?>"
           onclick="return confirm('Yakin ingin menghapus kegiatan ini?')"
           style="color: <?= $textColor ?>;">
            🗑 Hapus
        </a>
    <?php endif; ?>

    <hr>
    <a href="kalender_dashboardYunifa.php" 
       style="color: <?= $textColor ?>;">
        ← Kembali ke Dashboard
    </a>

</div>

</body>
</html>
