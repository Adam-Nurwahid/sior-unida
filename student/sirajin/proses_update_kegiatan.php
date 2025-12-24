<?php
// File: student/sirajin/proses_update_kegiatan.php
include '../../config.php';

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama_kegiatan'];
    $deskripsi = $_POST['deskripsi'];
    $anggaran = $_POST['anggaran'];
    $file_lama = $_POST['file_lama'];
    
    // Logika Upload File Baru (Opsional)
    $file_proposal = $file_lama; // Default pakai file lama
    
    // Cek jika user memilih file baru
    if ($_FILES['proposal']['error'] === 0) {
        $file_name = $_FILES['proposal']['name'];
        $tmp_name = $_FILES['proposal']['tmp_name'];
        $target_dir = "../../uploads/"; // Path naik 2 tingkat
        
        // Buat nama unik baru
        $new_file_name = time() . "_REVISI_" . $file_name;
        
        if (move_uploaded_file($tmp_name, $target_dir . $new_file_name)) {
            $file_proposal = $new_file_name;
            
            // (Opsional) Hapus file lama agar hemat storage
            if (file_exists($target_dir . $file_lama)) {
                unlink($target_dir . $file_lama);
            }
        } else {
            echo "<script>alert('Gagal upload file baru!'); window.history.back();</script>";
            exit;
        }
    }

    // Update Database
    // Penting: Status diubah jadi 'Pengajuan Revisi' agar admin tahu ini data baru
    $query = "UPDATE tb_kegiatan SET 
              nama_kegiatan = '$nama',
              deskripsi = '$deskripsi',
              estimasi_anggaran = '$anggaran',
              file_proposal = '$file_proposal',
              status_perizinan = 'Pengajuan Revisi' 
              WHERE id = '$id'";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Revisi Berhasil Disimpan!'); window.location='kegiatan_mhs.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>