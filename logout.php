<?php
session_start();

// 1. Hapus semua sesi
$_SESSION = [];
session_unset();
session_destroy();

// 2. Coba redirect pakai PHP
header("Location: login.php");

// 3. JAGA-JAGA: Jika redirect PHP gagal (misal karena ada spasi), 
// gunakan JavaScript untuk paksa pindah halaman
echo "<script>window.location='login.php';</script>";
exit;
?>