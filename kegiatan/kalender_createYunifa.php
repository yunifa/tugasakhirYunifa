<?php
session_start();
include "../kalender_koneksiYunifa.php";

if (!isset($_SESSION['tipe_yunifa'])) {
    header("Location: ../kalender_loginYunifa.php");
    exit;
}

// Hanya guru waka yang boleh akses
if ($_SESSION['tipe_yunifa'] != 'guru' || $_SESSION['role_yunifa'] > 6) {
    echo "AKSES_DITOLAK";
    exit;
}

$id_guru_yunifa = $_SESSION['id_user_yunifa'] ?? null;

if (isset($_POST['simpan_yunifa'])) {

    $judul_yunifa     = mysqli_real_escape_string($koneksiYunifa, $_POST['judul_yunifa']);
    $deskripsi_yunifa = mysqli_real_escape_string($koneksiYunifa, $_POST['deskripsi_yunifa']);
    $hak_akses_yunifa = mysqli_real_escape_string($koneksiYunifa, $_POST['hak_akses_yunifa']);
    $warna_yunifa     = mysqli_real_escape_string($koneksiYunifa, $_POST['warna_yunifa']);

    if (isset($_POST['seharian_yunifa'])) {

        $seharian_yunifa        = "ya";
        $tanggal_mulai_yunifa   = $_POST['tanggal_mulai_full_yunifa'];
        $tanggal_selesai_yunifa = !empty($_POST['tanggal_selesai_full_yunifa'])
            ? $_POST['tanggal_selesai_full_yunifa']
            : $tanggal_mulai_yunifa;

        $waktu_mulai_yunifa   = '00:00';
        $waktu_selesai_yunifa = '23:59';

    } else {

        $seharian_yunifa        = "tidak";
        $tanggal_mulai_yunifa   = $_POST['tanggal_mulai_yunifa'];
        $tanggal_selesai_yunifa = !empty($_POST['tanggal_selesai_yunifa'])
            ? $_POST['tanggal_selesai_yunifa']
            : $tanggal_mulai_yunifa;
        $waktu_mulai_yunifa     = $_POST['waktu_mulai_yunifa'];
        $waktu_selesai_yunifa   = $_POST['waktu_selesai_yunifa'];

        if ($waktu_mulai_yunifa > $waktu_selesai_yunifa) {
            echo "Error: Jam tidak valid";
            exit;
        }
        if ($tanggal_mulai_yunifa > $tanggal_selesai_yunifa) {
            echo "Error: Tanggal tidak valid";
            exit;
        }
    }

    $query_yunifa = "INSERT INTO kegiatan_yunifa
        (judul_yunifa, deskripsi_yunifa, tanggal_mulai_yunifa, waktu_mulai_yunifa, tanggal_selesai_yunifa, waktu_selesai_yunifa, seharian_yunifa, hak_akses_yunifa, warna_yunifa, id_guru_yunifa)
        VALUES
        ('$judul_yunifa','$deskripsi_yunifa','$tanggal_mulai_yunifa','$waktu_mulai_yunifa',
         '$tanggal_selesai_yunifa','$waktu_selesai_yunifa','$seharian_yunifa','$hak_akses_yunifa','$warna_yunifa','$id_guru_yunifa')";

    $result_yunifa = mysqli_query($koneksiYunifa, $query_yunifa);

    if ($result_yunifa) {
        echo "OK";
    } else {
        echo "Error: " . mysqli_error($koneksiYunifa);
    }

} else {
    echo "Error: Tidak ada data yang dikirim";
}
?>
