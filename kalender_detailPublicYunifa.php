<?php
include "kalender_koneksiYunifa.php";

if (!isset($_GET['id'])) {
    header("Location: kalender_publicYunifa.php");
    exit;
}

$id = intval($_GET['id']);

$query = mysqli_query($koneksiYunifa, "
    SELECT kegiatan_yunifa.*, guru_yunifa.nama AS nama_guru
    FROM kegiatan_yunifa
    JOIN guru_yunifa 
        ON kegiatan_yunifa.id_guru = guru_yunifa.id_guru
    WHERE id_kegiatan = '$id'
    AND hak_akses = 'publik'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data tidak ditemukan.";
    exit;
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
    color:white;
">

    <h2><?= $data['judul']; ?></h2>

    <p>
        <?= date('d-m-Y', strtotime($data['tanggal_mulai'])); ?>

        <?php if ($data['seharian'] == 'tidak'): ?>
            | <?= $data['waktu_mulai']; ?> - <?= $data['waktu_selesai']; ?>
        <?php endif; ?>
    </p>

    <p><?= nl2br($data['deskripsi']); ?></p>

    <p>Dibuat oleh: <?= $data['nama_guru']; ?></p>

    <hr>
    <a href="kalender_publicYunifa.php" style="color:white;">
        ← Kembali
    </a>

</div>

</body>
</html>
