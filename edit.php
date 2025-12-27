<?php
include 'koneksi.php';
$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM karyawan WHERE id='$id'");
$d = mysqli_fetch_array($data);

if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $posisi = $_POST['posisi'];
    $gaji = $_POST['gaji'];

    mysqli_query($koneksi, "UPDATE karyawan SET nama='$nama', posisi='$posisi', gaji='$gaji' WHERE id='$id'");
    header("location:index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <div class="card w-50 mx-auto shadow">
        <div class="card-header bg-warning text-white">Edit Data Karyawan</div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="<?php echo $d['nama']; ?>" required>
                </div>
                <div class="mb-3">
                    <label>Posisi</label>
                    <input type="text" name="posisi" class="form-control" value="<?php echo $d['posisi']; ?>" required>
                </div>
                <div class="mb-3">
                    <label>Gaji</label>
                    <input type="number" name="gaji" class="form-control" value="<?php echo $d['gaji']; ?>" required>
                </div>
                <a href="index.php" class="btn btn-secondary">Kembali</a>
                <button type="submit" name="update" class="btn btn-warning text-white">Update Data</button>
            </form>
        </div>
    </div>
</body>
</html>