<?php
include '../../config.php';

if(isset($_POST['simpan'])) {
    $nama = $_POST['nama_kegiatan'];
    $penyelenggara = $_POST['penyelenggara'];
    $jenis = $_POST['jenis_kegiatan'];
    $lokasi = $_POST['lokasi_kegiatan'];
    $deskripsi = $_POST['deskripsi'];
    $tujuan = $_POST['tujuan'];
    $mulai = $_POST['tgl_mulai'];
    $selesai = $_POST['tgl_selesai'];
    $anggaran = $_POST['anggaran'];
    
    // Upload File Logic
    $file_name = $_FILES['proposal']['name'];
    $tmp_name = $_FILES['proposal']['tmp_name'];
    $target_dir = "../../uploads/";
    
    // Rename file agar unik
    $new_file_name = time() . "_" . $file_name;
    
    if(move_uploaded_file($tmp_name, $target_dir . $new_file_name)) {
        
        $query = "INSERT INTO tb_kegiatan 
        (nama_kegiatan, penyelenggara, jenis_kegiatan, lokasi_kegiatan, deskripsi, tujuan, tanggal_mulai, tanggal_selesai, estimasi_anggaran, file_proposal, status_perizinan) 
        VALUES 
        ('$nama', '$penyelenggara', '$jenis', '$lokasi', '$deskripsi', '$tujuan', '$mulai', '$selesai', '$anggaran', '$new_file_name', 'Pengajuan Baru')";

        if(mysqli_query($conn, $query)) {
            echo "<script>alert('Sukses! Kegiatan berhasil ditambahkan.'); window.location='kegiatan_mhs.php';</script>";
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
        }

    } else {
    // Tampilkan error spesifik
    echo "Gagal Upload. Debugging Info:<br>";
    echo "Error Code: " . $_FILES['proposal']['error'] . "<br>";
    echo "Target Path: " . $target_dir . $new_file_name . "<br>";
    
    // Cek permission folder
    if (!is_dir($target_dir)) {
        echo "Folder '$target_dir' tidak ditemukan!<br>";
    } else if (!is_writable($target_dir)) {
        echo "Folder '$target_dir' tidak bisa ditulis (permission denied)!<br>";
    }
}
}
?>