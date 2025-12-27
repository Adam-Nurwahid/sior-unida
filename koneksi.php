<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "sior_unida"; // Sesuai nama DB di screenshot

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}
?>