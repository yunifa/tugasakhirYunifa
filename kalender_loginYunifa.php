<?php
session_start();
include 'kalender_koneksiYunifa.php';

if (isset($_POST['login'])) {
    $usernameYunifa = $_POST['username'];
    $passwordYunifa = $_POST['password'];

    // 1️⃣ CEK GURU (NIP)
    $queryGuruYunifa = mysqli_query(
        $koneksiYunifa,
        "SELECT * FROM guru_yunifa 
         WHERE nip='$usernameYunifa' AND password='$passwordYunifa'"
    );

    if (mysqli_num_rows($queryGuruYunifa) > 0) {
        $guruYunifa = mysqli_fetch_assoc($queryGuruYunifa);

        $_SESSION['id_userYunifa'] = $guruYunifa['id_guru'];
        $_SESSION['roleYunifa']    = $guruYunifa['role'];
        $_SESSION['tipeYunifa']    = 'guru';

        header("Location: kalender_dashboardYunifa.php");
        exit;
    }

    // 2️⃣ CEK SISWA (NIS)
    $querySiswaYunifa = mysqli_query(
        $koneksiYunifa,
        "SELECT * FROM siswa_yunifa 
         WHERE nis='$usernameYunifa' AND password_siswa='$passwordYunifa'"
    );

    if (mysqli_num_rows($querySiswaYunifa) > 0) {
        $siswaYunifa = mysqli_fetch_assoc($querySiswaYunifa);

        $_SESSION['id_userYunifa'] = $siswaYunifa['id_siswa'];
        $_SESSION['tipeYunifa']   = 'siswa';

        header("Location: kalender_dashboardYunifa.php");
        exit;
    }

    $querySiswaYunifa = mysqli_query(
        $koneksiYunifa,
        "SELECT * FROM siswa_yunifa 
         WHERE email_ortu='$usernameYunifa' AND password_ortu='$passwordYunifa'"
    );

    if (mysqli_num_rows($querySiswaYunifa) > 0) {
        $siswaYunifa = mysqli_fetch_assoc($querySiswaYunifa);

        $_SESSION['id_userYunifa'] = $siswaYunifa['id_siswa'];
        $_SESSION['tipeYunifa']   = 'siswa';

        header("Location: kalender_dashboardYunifa.php");
        exit;
    }

    // 3️⃣ GAGAL
    echo "<script>alert('NIP / NIS atau password salah');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Yunifa</title>
</head>
<body>
    <form method="post" action="">
        <input type="text" name="username" placeholder="NIP / NIS" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
</body>
</html>
