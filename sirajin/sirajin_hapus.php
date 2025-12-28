<?php
session_start();
include '../koneksi.php';

// Cek Login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['role'];

    // 1. Ambil nama file proposal dulu untuk dihapus dari folder
    // Pastikan user hanya menghapus miliknya sendiri (kecuali admin)
    if ($role == 'admin') {
        $query_cek = "SELECT * FROM trx_kegiatan WHERE id='$id'";
    } else {
        // Client hanya boleh hapus jika status Draft
        $query_cek = "SELECT * FROM trx_kegiatan WHERE id='$id' AND user_id='$user_id' AND status_perizinan IN ('Draft', 'Pengajuan Baru')";
    }

    $result = mysqli_query($koneksi, $query_cek);
    $data = mysqli_fetch_assoc($result);

    if ($data) {
        // Hapus File Fisik
        if (!empty($data['file_proposal'])) {
            $file_path = "../uploads/" . $data['file_proposal'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        // Hapus Data Database
        mysqli_query($koneksi, "DELETE FROM trx_kegiatan WHERE id='$id'");
        echo "<script>alert('Data Berhasil Dihapus!'); window.location='sirajin_list.php';</script>";
    } else {
        echo "<script>alert('Gagal Hapus! Data tidak ditemukan atau Anda tidak memiliki izin (Status bukan Draft).'); window.location='sirajin_list.php';</script>";
    }
}
?>