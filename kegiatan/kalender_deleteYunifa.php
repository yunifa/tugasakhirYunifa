<?php
session_start();
include "../kalender_koneksiYunifa.php";

if (!isset($_SESSION['tipe_yunifa'])) {
    header("Location: ../kalender_loginYunifa.php");
    exit;
}

if ($_SESSION['tipe_yunifa'] != 'guru' || $_SESSION['role_yunifa'] > 6) {
    header("Location: ../kalender_dashboardYunifa.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: ../kalender_dashboardYunifa.php");
    exit;
}

$id = intval($_GET['id']);

$query = mysqli_query($koneksiYunifa, "
    SELECT tanggal_mulai_yunifa, waktu_mulai_yunifa, seharian_yunifa
    FROM kegiatan_yunifa 
    WHERE id_kegiatan_yunifa = '$id'
");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: ../kalender_dashboardYunifa.php");
    exit;
}

$sekarang_dt = new DateTime();

if ($data['seharian_yunifa'] == 'ya') {
    $mulai_dt = new DateTime($data['tanggal_mulai_yunifa'] . ' 00:00:00');
} else {
    $mulai_dt = new DateTime($data['tanggal_mulai_yunifa'] . ' ' . ($data['waktu_mulai_yunifa'] ?? '00:00:00'));
}

if ($sekarang_dt >= $mulai_dt) {
    echo "<script>
        alert('Kegiatan sudah dimulai dan tidak bisa dihapus.');
        window.location='../kalender_dashboardYunifa.php';
    </script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>
        if (confirm('Yakin ingin menghapus kegiatan ini?')) {
            fetch(window.location.href, { method: 'POST' })
            .then(function(){ window.location='../kalender_dashboardYunifa.php?deleted=1'; });
        } else {
            window.location='../kalender_dashboardYunifa.php';
        }
    </script>";
    exit;
}

mysqli_query($koneksiYunifa, "
    DELETE FROM kegiatan_yunifa 
    WHERE id_kegiatan_yunifa = '$id'
");

header("Location: ../kalender_dashboardYunifa.php?deleted=1");
exit;
?>
