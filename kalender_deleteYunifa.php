<?php
session_start();
include "kalender_koneksiYunifa.php";

if (!isset($_SESSION['tipeYunifa'])) {
    header("Location: kalender_loginYunifa.php");
    exit;
}

if ($_SESSION['tipeYunifa'] != 'guru') {
    header("Location: kalender_dashboardYunifa.php");
    exit;
}

if (
    $_SESSION['roleYunifa'] != 'staff' &&
    !str_contains($_SESSION['roleYunifa'], 'wakil kepala sekolah')
) {
    header("Location: kalender_dashboardYunifa.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: kalender_dashboardYunifa.php");
    exit;
}

$id = intval($_GET['id']);

$query = mysqli_query($koneksiYunifa, "
    SELECT tanggal_mulai 
    FROM kegiatan_yunifa 
    WHERE id_kegiatan = '$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: kalender_dashboardYunifa.php");
    exit;
}

$tanggalSekarang = date("Y-m-d");

if ($data['tanggal_mulai'] < $tanggalSekarang) {
    echo "<script>
            alert('Kegiatan sudah lewat dan tidak bisa dihapus');
            window.location='kalender_dashboardYunifa.php';
          </script>";
    exit;
}

mysqli_query($koneksiYunifa, "
    DELETE FROM kegiatan_yunifa 
    WHERE id_kegiatan = '$id'
");

header("Location: kalender_dashboardYunifa.php");
exit;
