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
    SELECT * FROM kegiatan_yunifa 
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
            alert('Kegiatan sudah lewat dan tidak bisa diedit');
            window.location='kalender_dashboardYunifa.php';
          </script>";
    exit;
}

if (isset($_POST['updateYunifa'])) {

    $judul = $_POST['judulYunifa'];
    $deskripsi = $_POST['deskripsiYunifa'];
    $hakAkses = $_POST['hakAksesYunifa'];
    $warna = $_POST['warnaYunifa'];

    if (isset($_POST['seharianYunifa'])) {

        $seharian = "ya";
        $tanggalMulai = $_POST['tanggalMulaiFullYunifa'];
        $tanggalSelesai = $_POST['tanggalSelesaiFullYunifa'];
        $waktuMulai = NULL;
        $waktuSelesai = NULL;

    } else {

        $seharian = "tidak";
        $tanggalMulai = $_POST['tanggalMulaiYunifa'];
        $tanggalSelesai = $_POST['tanggalMulaiYunifa'];
        $waktuMulai = $_POST['waktuMulaiYunifa'];
        $waktuSelesai = $_POST['waktuSelesaiYunifa'];
    }

    mysqli_query($koneksiYunifa, "
        UPDATE kegiatan_yunifa SET
        judul = '$judul',
        deskripsi = '$deskripsi',
        tanggal_mulai = '$tanggalMulai',
        tanggal_selesai = '$tanggalSelesai',
        waktu_mulai = '$waktuMulai',
        waktu_selesai = '$waktuSelesai',
        seharian = '$seharian',
        hak_akses = '$hakAkses',
        warna = '$warna'
        WHERE id_kegiatan = '$id'
    ");

    header("Location: kalender_detailYunifa.php?id=$id");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Kegiatan</title>
</head>
<body>

<h2>Edit Kegiatan</h2>

<form method="POST">

Judul<br>
<input type="text" name="judulYunifa" value="<?= $data['judul']; ?>" required><br><br>

Deskripsi<br>
<textarea name="deskripsiYunifa"><?= $data['deskripsi']; ?></textarea><br><br>

<input type="checkbox" id="seharianYunifa" name="seharianYunifa"
<?= $data['seharian'] == 'ya' ? 'checked' : ''; ?>>
<label for="seharianYunifa">Seharian</label>
<br><br>

<div id="modeNormalYunifa">
    Tanggal<br>
    <input type="date" name="tanggalMulaiYunifa"
    value="<?= $data['tanggal_mulai']; ?>"><br><br>

    Jam Mulai<br>
    <input type="time" name="waktuMulaiYunifa"
    value="<?= $data['waktu_mulai']; ?>"><br><br>

    Jam Selesai<br>
    <input type="time" name="waktuSelesaiYunifa"
    value="<?= $data['waktu_selesai']; ?>"><br><br>
</div>

<div id="modeFullYunifa" style="display:none;">
    Dari Tanggal<br>
    <input type="date" name="tanggalMulaiFullYunifa"
    value="<?= $data['tanggal_mulai']; ?>"><br><br>

    Sampai Tanggal<br>
    <input type="date" name="tanggalSelesaiFullYunifa"
    value="<?= $data['tanggal_selesai']; ?>"><br><br>
</div>

Hak Akses<br>
<select name="hakAksesYunifa" required>
    <option value="guru" <?= $data['hak_akses']=='guru'?'selected':''; ?>>Guru</option>
    <option value="internal" <?= $data['hak_akses']=='internal'?'selected':''; ?>>Internal</option>
    <option value="publik" <?= $data['hak_akses']=='publik'?'selected':''; ?>>Publik</option>
</select>
<br><br>

Warna<br>
<input type="color" name="warnaYunifa" value="<?= $data['warna']; ?>"><br><br>

<button type="submit" name="updateYunifa">Update</button>

</form>

<script>
const checkbox = document.getElementById("seharianYunifa");
const normal = document.getElementById("modeNormalYunifa");
const full = document.getElementById("modeFullYunifa");

function toggleMode() {
    if (checkbox.checked) {
        normal.style.display = "none";
        full.style.display = "block";
    } else {
        normal.style.display = "block";
        full.style.display = "none";
    }
}

checkbox.addEventListener("change", toggleMode);
toggleMode();
</script>

</body>
</html>
