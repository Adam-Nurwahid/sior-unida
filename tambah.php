<?php
include 'koneksi.php';

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $posisi = $_POST['posisi'];
    $gaji = $_POST['gaji'];

    mysqli_query($koneksi, "INSERT INTO karyawan VALUES('', '$nama', '$posisi', '$gaji')");
    header("location:index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <div class="card w-50 mx-auto shadow">
        <div class="card-header bg-success text-white">Tambah Karyawan Baru</div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Posisi</label>
                    <input type="text" name="posisi" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Gaji</label>
                    <input type="number" name="gaji" class="form-control" required>
                </div>
                <a href="index.php" class="btn btn-secondary">Kembali</a>
                <button type="submit" name="simpan" class="btn btn-success">Simpan Data</button>
            </form>
        </div>
    </div>
</body>
</html>