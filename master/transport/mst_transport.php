<?php
session_start();
include '../../koneksi.php'; // Panggil koneksi database

// Cek Login & Role
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:login.php");
    exit;
}
if ($_SESSION['role'] != 'admin') {
    header("location:index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Master Jenis Lokasi - SIOR UNIDA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="d-flex">
        
        <?php include '../../sidebar.php'; ?>

        <div class="p-4 w-100 bg-light" style="height: 100vh; overflow-y: auto;">
            <div class="container-fluid">
                <h2 class="mb-4 fw-bold text-primary">Master Jenis Transportasi</h2>
                
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-dark">Daftar Jenis Transportasi</h6>
                        <a href="mst_lokasi_tambah.php" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> TAMBAH
                        </a>
                    </div>
                    
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover align-middle">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th width="50">No</th>
                                        <th width="150">Kode</th>
                                        <th>Nama Jenis Transportasi</th>
                                        <th width="200">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    // Query ambil data dari database
                                    $query = mysqli_query($koneksi, "SELECT * FROM mst_jenis_transportasi ORDER BY kode ASC");
                                    while ($row = mysqli_fetch_array($query)) { 
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $no++; ?></td>
                                        <td class="text-center fw-bold"><?php echo $row['kode']; ?></td>
                                        <td><?php echo $row['nama']; ?></td>
                                        <td class="text-center">
                                            <a href="mst_lokasi_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning text-white me-1">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                            <a href="mst_lokasi_hapus.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data <?php echo $row['nama']; ?>?')">
                                                <i class="bi bi-trash"></i> Hapus
                                            </a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>