<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['role'];

    // Query Cek Hak Akses
    if ($role == 'admin') {
        $query = "SELECT * FROM trx_dana WHERE id='$id'";
    } else {
        // User hanya boleh hapus Draft atau Ditolak
        $query = "SELECT * FROM trx_dana WHERE id='$id' AND user_id='$user_id' AND status_dana IN ('Draft', 'Ditolak')";
    }

    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);

    if ($data) {
        // Hapus File RAB Fisik
        if (!empty($data['file_rab'])) {
            $path = "../uploads/" . $data['file_rab'];
            if (file_exists($path)) {
                unlink($path);
            }
        }

        // Hapus Database
        mysqli_query($koneksi, "DELETE FROM trx_dana WHERE id='$id'");
        echo "<script>alert('Data Pengajuan Berhasil Dihapus.'); window.location='sipandai_list.php';</script>";
    } else {
        echo "<script>alert('Gagal Hapus! Data tidak ditemukan atau status tidak mengizinkan.'); window.location='sipandai_list.php';</script>";
    }
}
?>