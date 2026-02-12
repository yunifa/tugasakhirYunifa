<?php
$koneksiYunifa = mysqli_connect("localhost","root","","db_kalender_sekolah_yunifa_rizky");
if (mysqli_connect_errno()) {
    echo "Koneksi database gagal : " . mysqli_connect_error();
}
?>