<?php
session_start();
include '../../koneksi.php';

// Cek Akses
if ($_SESSION['role'] != 'admin') { header("location:index.php"); exit; }

// Proses Simpan Data
if (isset($_POST['simpan'])) {
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];

    $simpan = mysqli_query($koneksi, "INSERT INTO mst_jenis_kegiatan (kode, nama) VALUES ('$kode', '$nama')");

    if ($simpan) {
        echo "<script>alert('Data Berhasil Disimpan!'); window.location='mst_kegiatan.php';</script>";
    } else {
        echo "<script>alert('Gagal Menyimpan Data');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Jenis Kegiatan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex">
        <?php include '../../sidebar.php'; ?>
        
        <div class="p-4 w-100 bg-light">
            <div class="container" style="max-width: 600px;">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Tambah Jenis Kegiatan</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Kode Kegiatan</label>
                                <input type="text" name="kode" class="form-control" placeholder="Contoh: KGT-001" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama Jenis Kegiatan</label>
                                <input type="text" name="nama" class="form-control" placeholder="Contoh: Seminar Nasional" required>
                            </div>
                            <div class="d-flex justify-content-between"> <a href="mst_kegiatan.php" class="btn btn-secondary">KELUAR</a>
                                <button type="submit" name="simpan" class="btn btn-success">SIMPAN</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>