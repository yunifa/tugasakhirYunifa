<?php
session_start();
$_SESSION = [];
session_destroy();
header("Location: ../kalender_loginYunifa.php");
exit;