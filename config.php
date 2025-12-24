<?php
// config.php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "sior_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Mulai sesi di setiap halaman yang include file ini

?>