<?php
session_start();
include '../../koneksi.php';

if ($_SESSION['role'] != 'admin') { header("location:index.php"); exit; }

// Ambil ID dari URL
$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM mst_jenis_org WHERE id='$id'");
$row = mysqli_fetch_array($data);

// Proses Update Data
if (isset($_POST['update'])) {
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];

    $update = mysqli_query($koneksi, "UPDATE mst_jenis_org SET kode='$kode', nama='$nama' WHERE id='$id'");

    if ($update) {
        echo "<script>alert('Data Berhasil Diupdate!'); window.location='mst_tipe_org.php';</script>";
    } else {
        echo "<script>alert('Gagal Update Data');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Jenis Organisasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex">
        <?php include '../../sidebar.php'; ?>
        
        <div class="p-4 w-100 bg-light">
            <div class="container" style="max-width: 600px;">
                <div class="card shadow">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0">Edit Jenis Organisasi</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Kode Organisasi</label>
                                <input type="text" name="kode" class="form-control" value="<?php echo $row['kode']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama Jenis Organisasi</label>
                                <input type="text" name="nama" class="form-control" value="<?php echo $row['nama']; ?>" required>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="mst_tipe_org.php" class="btn btn-secondary">KELUAR</a>
                                <button type="submit" name="update" class="btn btn-warning text-white">UPDATE</button>
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