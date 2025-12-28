<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// QUERY: Hanya ambil kegiatan yang status perizinannya 'Disetujui'
if ($role == 'admin') {
    $query = "SELECT k.*, u.nama_lengkap AS nama_ormawa 
              FROM trx_kegiatan k
              JOIN users u ON k.user_id = u.id
              WHERE k.status_perizinan = 'Disetujui'
              ORDER BY k.id DESC";
} else {
    $query = "SELECT k.*, u.nama_lengkap AS nama_ormawa 
              FROM trx_kegiatan k
              JOIN users u ON k.user_id = u.id
              WHERE k.user_id = '$user_id' AND k.status_perizinan = 'Disetujui'
              ORDER BY k.id DESC";
}

$result = mysqli_query($koneksi, $query);

function getLaporanBadge($status) {
    switch ($status) {
        case 'Belum Upload': return 'secondary';
        case 'Menunggu Verifikasi': return 'primary';
        case 'Diterima': return 'success';
        case 'Perlu Revisi': return 'danger';
        default: return 'light';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Laporan Kegiatan - SIRAJIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="d-flex">
    <?php include '../sidebar.php'; ?>

    <div class="p-4 w-100 bg-light" style="height: 100vh; overflow-y: auto;">
        <div class="container-fluid">
            
            <h3 class="fw-bold text-primary mb-4"><i class="bi bi-file-earmark-text me-2"></i>Laporan Pertanggungjawaban (LPJ)</h3>

            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-dark">Daftar Kegiatan (Disetujui)</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kegiatan</th>
                                    <?php if($role == 'admin') { ?> <th>Penyelenggara</th> <?php } ?>
                                    <th>Tgl Pelaksanaan</th>
                                    <th>Status Laporan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $tgl = date('d M Y', strtotime($row['tgl_mulai']));
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td class="fw-bold"><?php echo $row['nama_kegiatan']; ?></td>
                                    
                                    <?php if($role == 'admin') { ?> 
                                        <td><?php echo $row['nama_ormawa']; ?></td> 
                                    <?php } ?>

                                    <td><?php echo $tgl; ?></td>
                                    
                                    <td class="text-center">
                                        <span class="badge bg-<?php echo getLaporanBadge($row['status_laporan']); ?>">
                                            <?php echo $row['status_laporan']; ?>
                                        </span>
                                    </td>
                                    
                                    <td class="text-center">
                                        <?php if($role != 'admin'): ?>
                                            <a href="sirajin_laporan_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="bi bi-upload me-1"></i> Upload LPJ
                                            </a>
                                        <?php else: ?>
                                            <a href="sirajin_laporan_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning text-dark">
                                                <i class="bi bi-search me-1"></i> Periksa LPJ
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php 
                                    } 
                                } else {
                                    $colspan = ($role == 'admin') ? 6 : 5;
                                    echo "<tr><td colspan='$colspan' class='text-center py-4 text-muted'>Tidak ada kegiatan yang perlu dilaporkan (Belum ada yang disetujui).</td></tr>";
                                }
                                ?>
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