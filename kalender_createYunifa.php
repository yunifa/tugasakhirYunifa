<?php
session_start();
include "kalender_koneksiYunifa.php";

if (!isset($_SESSION['tipeYunifa'])) {
    header("Location: kalender_loginYunifa.php");
    exit;
}

$idGuruYunifa = $_SESSION['id_userYunifa'] ?? null;

if(isset($_POST['simpanYunifa'])){

    $judulYunifa       = $_POST['judulYunifa'];
    $deskripsiYunifa   = $_POST['deskripsiYunifa'];
    $hakAksesYunifa    = $_POST['hakAksesYunifa'];
    $warnaYunifa       = $_POST['warnaYunifa'];


    if(isset($_POST['seharianYunifa'])){

        $seharianYunifa = "ya";
        $tanggalMulaiYunifa   = $_POST['tanggalMulaiFullYunifa'];
        $tanggalSelesaiYunifa = $_POST['tanggalSelesaiFullYunifa'];

        $waktuMulaiYunifa   = NULL;
        $waktuSelesaiYunifa = NULL;

    } else {

        $seharianYunifa = "tidak";
        $tanggalMulaiYunifa   = $_POST['tanggalMulaiYunifa'];
        $tanggalSelesaiYunifa = $_POST['tanggalMulaiYunifa'];

        $waktuMulaiYunifa   = $_POST['waktuMulaiYunifa'];
        $waktuSelesaiYunifa = $_POST['waktuSelesaiYunifa'];
    }

    $queryYunifa = "INSERT INTO kegiatan_yunifa
    (judul, deskripsi, tanggal_mulai, waktu_mulai, tanggal_selesai, waktu_selesai, seharian, hak_akses, warna, id_guru)
    VALUES
    ('$judulYunifa','$deskripsiYunifa','$tanggalMulaiYunifa','$waktuMulaiYunifa',
     '$tanggalSelesaiYunifa','$waktuSelesaiYunifa','$seharianYunifa','$hakAksesYunifa','$warnaYunifa','$idGuruYunifa')";

    $simpanYunifa = mysqli_query($koneksiYunifa, $queryYunifa);

    if($simpanYunifa){
        echo "<script>
            alert('Kegiatan berhasil ditambahkan');
            window.location='kalender_dashboardYunifa.php';
        </script>";
        exit;
    } else {
        echo "Error: " . mysqli_error($koneksiYunifa);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Kegiatan Yunifa</title>
</head>
<body>

<h2>Tambah Kegiatan</h2>

<form method="POST">

Judul<br>
<input type="text" name="judulYunifa" required><br><br>

Deskripsi<br>
<textarea name="deskripsiYunifa"></textarea><br><br>

<input type="checkbox" id="seharianYunifa" name="seharianYunifa">
<label for="seharianYunifa">Seharian</label>
<br><br>

<div id="modeNormalYunifa">
    Tanggal<br>
    <input type="date" name="tanggalMulaiYunifa"><br><br>

    Jam Mulai<br>
    <input type="time" name="waktuMulaiYunifa"><br><br>

    Jam Selesai<br>
    <input type="time" name="waktuSelesaiYunifa"><br><br>
</div>

<div id="modeFullYunifa" style="display:none;">
    Dari Tanggal<br>
    <input type="date" name="tanggalMulaiFullYunifa"><br><br>

    Sampai Tanggal<br>
    <input type="date" name="tanggalSelesaiFullYunifa"><br><br>
</div>

Hak Akses<br>
<select name="hakAksesYunifa" required>
    <option value="guru">Guru</option>
    <option value="internal">Internal</option>
    <option value="publik">Publik</option>
</select>
<br><br>

Warna<br>
<input type="color" name="warnaYunifa" value="#3788d8"><br><br>

<button type="submit" name="simpanYunifa">Simpan</button>

</form>

<script>
const checkboxYunifa = document.getElementById("seharianYunifa");
const normalYunifa = document.getElementById("modeNormalYunifa");
const fullYunifa = document.getElementById("modeFullYunifa");

checkboxYunifa.addEventListener("change", function(){
    if(this.checked){
        normalYunifa.style.display = "none";
        fullYunifa.style.display = "block";
    } else {
        normalYunifa.style.display = "block";
        fullYunifa.style.display = "none";
    }
});
</script>

</body>
</html>
