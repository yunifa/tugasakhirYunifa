<?php
session_start();
include "kalender_koneksiYunifa.php";
header('Content-Type: application/json');

$events_yunifa = [];
$tipe_yunifa = $_SESSION['tipe_yunifa'] ?? null;
$role_yunifa = $_SESSION['role_yunifa'] ?? 0;

if (!isset($_SESSION['tipe_yunifa'])) {
    $filter_yunifa = "hak_akses_yunifa = 'publik'";
} else {
    if ($tipe_yunifa === 'admin' || ($tipe_yunifa === 'guru' && $role_yunifa >= 1 && $role_yunifa <= 6)) {
        $filter_yunifa = "1=1";
    } elseif ($tipe_yunifa === 'guru') {
        $filter_yunifa = "hak_akses_yunifa IN ('publik','internal','guru')";
    } else {
        $filter_yunifa = "hak_akses_yunifa IN ('publik','internal')";
    }
}

$query_yunifa = mysqli_query($koneksiYunifa, "
    SELECT k.*, g.nama_yunifa AS nama_guru
    FROM kegiatan_yunifa k
    LEFT JOIN guru_yunifa g ON k.id_guru_yunifa = g.id_guru_yunifa
    WHERE $filter_yunifa
");

while ($row_yunifa = mysqli_fetch_assoc($query_yunifa)) {

    if ($row_yunifa['seharian_yunifa'] == 'ya') {
        $start = $row_yunifa['tanggal_mulai_yunifa'];
        $end   = date('Y-m-d', strtotime($row_yunifa['tanggal_selesai_yunifa'] . ' +1 day'));
        $allDay = true;
    } else {
        $start  = $row_yunifa['tanggal_mulai_yunifa'] . "T" . $row_yunifa['waktu_mulai_yunifa'];
        $end    = $row_yunifa['tanggal_selesai_yunifa'] . "T" . $row_yunifa['waktu_selesai_yunifa'];
        $allDay = false;
    }

    $events_yunifa[] = [
        "id"    => $row_yunifa['id_kegiatan_yunifa'],
        "title" => $row_yunifa['judul_yunifa'],
        "start" => $start,
        "end"   => $end,
        "allDay"=> $allDay,
        "color" => $row_yunifa['warna_yunifa'],
        "extendedProps" => [
            "deskripsi"       => $row_yunifa['deskripsi_yunifa'],
            "seharian"        => $row_yunifa['seharian_yunifa'],
            "tanggal_mulai"   => $row_yunifa['tanggal_mulai_yunifa'],
            "tanggal_selesai" => $row_yunifa['tanggal_selesai_yunifa'],
            "waktu_mulai"     => $row_yunifa['waktu_mulai_yunifa'],
            "waktu_selesai"   => $row_yunifa['waktu_selesai_yunifa'],
            "nama_guru"       => $row_yunifa['nama_guru'],
            "hak_akses"       => $row_yunifa['hak_akses_yunifa'],
        ]
    ];
}

echo json_encode($events_yunifa);
?>
