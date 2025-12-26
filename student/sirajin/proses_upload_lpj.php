<?php
// File: student/sirajin/proses_upload_lpj.php
include '../../config.php';

if(isset($_POST['upload_lpj'])) {
    $id = $_POST['id'];
    $link_video = $_POST['link_video'];
    $target_dir = "../../uploads/";

    // 1. Upload File LPJ (PDF)
    $lpj_name = time() . "_LPJ_" . $_FILES['file_lpj']['name'];
    $lpj_tmp = $_FILES['file_lpj']['tmp_name'];
    move_uploaded_file($lpj_tmp, $target_dir . $lpj_name);

    // 2. Upload Foto Dokumentasi
    $foto_name = time() . "_FOTO_" . $_FILES['foto_dokumentasi']['name'];
    $foto_tmp = $_FILES['foto_dokumentasi']['tmp_name'];
    move_uploaded_file($foto_tmp, $target_dir . $foto_name);

    // 3. Update Database & Ubah Status jadi 'Menunggu Review'
    $query = "UPDATE tb_kegiatan SET 
              file_lpj = '$lpj_name',
              foto_dokumentasi = '$foto_name',
              link_video = '$link_video',
              status_lpj = 'Menunggu Review'
              WHERE id = '$id'";

    if(mysqli_query($conn, $query)) {
        echo "<script>alert('Laporan Berhasil Diupload!'); window.location='laporan_kegiatan.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>