<?php
session_start();
include '../../koneksi.php';

// Cek Keamanan
if ($_SESSION['role'] != 'admin') { 
    header("location:index.php"); 
    exit; 
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $hapus = mysqli_query($koneksi, "DELETE FROM mst_jenis_bantuan WHERE id='$id'");

    if ($hapus) {
        echo "<script>
                alert('Data Berhasil Dihapus!');
                window.location.href='mst_bantuan.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal Menghapus Data!');
                window.location.href='mst_bantuan.php';
              </script>";
    }
} else {
    header("location:mst_bantuan.php");
}
?>