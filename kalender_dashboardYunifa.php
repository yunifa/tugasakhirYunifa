<?php
session_start();
include "kalender_koneksiYunifa.php";

if (!isset($_SESSION['tipeYunifa'])) {
    header("Location: kalender_loginYunifa.php");
    exit;
}

$tipeYunifa   = $_SESSION['tipeYunifa'];
$roleYunifa   = $_SESSION['roleYunifa'] ?? '';
$idGuruYunifa = $_SESSION['id_userYunifa'] ?? null;

if ($tipeYunifa == 'guru') {

    $queryEventYunifa = mysqli_query($koneksiYunifa, "
        SELECT kegiatan_yunifa.*, guru_yunifa.nama AS nama_guru
        FROM kegiatan_yunifa
        JOIN guru_yunifa 
            ON kegiatan_yunifa.id_guru = guru_yunifa.id_guru
        WHERE hak_akses IN ('publik','internal','guru')
        ORDER BY tanggal_mulai ASC
    ");

} else {

    $queryEventYunifa = mysqli_query($koneksiYunifa, "
        SELECT kegiatan_yunifa.*, guru_yunifa.nama AS nama_guru
        FROM kegiatan_yunifa
        JOIN guru_yunifa 
            ON kegiatan_yunifa.id_guru = guru_yunifa.id_guru
        WHERE hak_akses IN ('publik','internal')
        ORDER BY tanggal_mulai ASC
    ");
}

function getTextColor($hexColor) {

    $hexColor = str_replace('#', '', $hexColor);

    $r = hexdec(substr($hexColor, 0, 2));
    $g = hexdec(substr($hexColor, 2, 2));
    $b = hexdec(substr($hexColor, 4, 2));

    $brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

    return ($brightness > 155) ? 'black' : 'white';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Kalender</title>
</head>
<body>

<h2>Dashboard Kalender</h2>

<?php if ($tipeYunifa == 'guru'): ?>
    <p>Login sebagai <b>Guru</b></p>
    <p>Role: <?= $roleYunifa ?></p>

    <?php if (
        $roleYunifa == 'staff' ||
        str_contains($roleYunifa, 'wakil kepala sekolah')
    ): ?>
        <p><a href="kalender_createYunifa.php">➕ Tambah Kegiatan</a></p>
    <?php endif; ?>

<?php elseif ($tipeYunifa == 'siswa'): ?>
    <p>Login sebagai <b>Siswa</b></p>
<?php endif; ?>

<hr>

<h3>Daftar Kegiatan</h3>

<?php while ($dataYunifa = mysqli_fetch_assoc($queryEventYunifa)) : ?>

    <?php $textColor = getTextColor($dataYunifa['warna']); ?>

    <a href="kalender_detailYunifa.php?id=<?= $dataYunifa['id_kegiatan']; ?>" 
       style="text-decoration:none;">

        <div style="
            margin-bottom:12px;
            padding:15px;
            border-radius:8px;
            background: <?= $dataYunifa['warna']; ?>;
            color: <?= $textColor ?>;
            font-weight:bold;
            font-size:16px;
        ">
            <?= $dataYunifa['judul']; ?>
        </div>

    </a>

<?php endwhile; ?>



<hr>
<p><a href="kalender_logoutYunifa.php">Logout</a></p>

</body>
</html>
