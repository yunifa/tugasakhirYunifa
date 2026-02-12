<?php
include "kalender_koneksiYunifa.php";

$query = mysqli_query($koneksiYunifa, "
    SELECT kegiatan_yunifa.*, guru_yunifa.nama AS nama_guru
    FROM kegiatan_yunifa
    JOIN guru_yunifa 
        ON kegiatan_yunifa.id_guru = guru_yunifa.id_guru
    WHERE hak_akses = 'publik'
    ORDER BY tanggal_mulai ASC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kalender Kegiatan Sekolah</title>
</head>
<body>

<h2>Kalender Kegiatan (Publik)</h2>

<?php while ($data = mysqli_fetch_assoc($query)) : ?>

    <div style="
        margin-bottom:15px;
        padding:15px;
        border-radius:8px;
        background: <?= $data['warna']; ?>;
        color:white;
    ">
        <strong><?= $data['judul']; ?></strong><br>

        <?= date('d-m-Y', strtotime($data['tanggal_mulai'])); ?>

        <?php if ($data['seharian'] == 'tidak'): ?>
            | <?= $data['waktu_mulai']; ?> - <?= $data['waktu_selesai']; ?>
        <?php endif; ?>

        <br><br>
        <a href="kalender_detailPublicYunifa.php?id=<?= $data['id_kegiatan']; ?>"
           style="color:white;">
           Lihat Detail
        </a>
    </div>

<?php endwhile; ?>

<hr>
<a href="kalender_loginYunifa.php">Login Guru / Siswa</a>

</body>
</html>
