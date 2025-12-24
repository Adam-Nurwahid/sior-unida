<?php
// File: student/sirajin/hapus_kegiatan.php
include '../../config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 1. Ambil nama file dulu untuk dihapus dari folder
    $query_cek = "SELECT file_proposal FROM tb_kegiatan WHERE id = '$id'";
    $result_cek = mysqli_query($conn, $query_cek);
    $data = mysqli_fetch_assoc($result_cek);

    // 2. Hapus File Fisik (Jika ada)
    $target_file = "../../uploads/" . $data['file_proposal'];
    if (file_exists($target_file)) {
        unlink($target_file);
    }

    // 3. Hapus Data dari Database
    $query_delete = "DELETE FROM tb_kegiatan WHERE id = '$id'";
    
    if (mysqli_query($conn, $query_delete)) {
        echo "<script>alert('Data berhasil dihapus!'); window.location='kegiatan_mhs.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>