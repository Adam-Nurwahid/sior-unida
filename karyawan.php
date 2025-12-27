<?php
session_start();
if ($_SESSION['status'] != "login") {
    header("location:login.php");
}
include 'koneksi.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="d-flex">
        
        <?php include 'sidebar.php'; ?>

        <div class="p-4 w-100">
            <h2 class="mb-4">Kelola Data Karyawan</h2>
            
            <div class="card shadow">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Tabel Karyawan</h6>
                    <a href="tambah.php" class="btn btn-sm btn-primary">+ Tambah Data</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Posisi</th>
                                <th>Gaji</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $data = mysqli_query($koneksi, "SELECT * FROM karyawan");
                            while ($d = mysqli_fetch_array($data)) {
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $d['nama']; ?></td>
                                <td><?php echo $d['posisi']; ?></td>
                                <td>Rp <?php echo number_format($d['gaji'], 0, ',', '.'); ?></td>
                                <td>
                                    <a href="edit.php?id=<?php echo $d['id']; ?>" class="btn btn-sm btn-warning text-white">Edit</a>
                                    <a href="hapus.php?id=<?php echo $d['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>