<?php
// File: admin/sirajin/proses_laporan_admin.php
include '../../config.php';

if(isset($_POST['simpan_validasi'])) {
    $id = $_POST['id'];
    $status_lpj = $_POST['status_lpj'];
    // Kita simpan catatan validasi LPJ di kolom catatan_admin juga (atau buat kolom baru jika perlu)
    $catatan = $_POST['catatan_lpj'];

    $query = "UPDATE tb_kegiatan SET 
              status_lpj = '$status_lpj',
              catatan_admin = '$catatan' 
              WHERE id = '$id'";

    if(mysqli_query($conn, $query)) {
        echo "<script>alert('Validasi Laporan Berhasil Disimpan!'); window.location='laporan_admin.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>