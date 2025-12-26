<?php
// File: admin/sirajin/proses_persetujuan.php
include '../../config.php';

if(isset($_POST['simpan_keputusan'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $catatan = $_POST['catatan'];

    // Validasi sederhana: Jika Revisi/Ditolak, catatan tidak boleh kosong
    if(($status == 'Revisi' || $status == 'Ditolak') && empty(trim($catatan))) {
        echo "<script>alert('Error: Catatan wajib diisi untuk status Revisi atau Ditolak!'); window.history.back();</script>";
        exit;
    }

    // Update Query
    $query = "UPDATE tb_kegiatan SET 
              status_perizinan = '$status', 
              catatan_admin = '$catatan' 
              WHERE id = '$id'";

    if(mysqli_query($conn, $query)) {
        // Pesan sukses berbeda tergantung status
        $msg = "Status berhasil diperbarui!";
        if($status == 'Disetujui') $msg = "Kegiatan Disetujui! Mahasiswa dapat melihat status Selesai.";
        if($status == 'Revisi') $msg = "Kegiatan dikembalikan ke Mahasiswa untuk Revisi.";
        
        echo "<script>alert('$msg'); window.location='kegiatan_admin.php';</script>";
    } else {
        echo "Error Update: " . mysqli_error($conn);
    }
}
?>