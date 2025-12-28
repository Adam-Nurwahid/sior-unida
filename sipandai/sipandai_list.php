<?php
session_start();
include '../koneksi.php';

// 1. Cek Login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// 2. CEK PROFIL (Wajib isi profil dulu untuk User/Client)
if ($role != 'admin') {
    $cek_profil = mysqli_query($koneksi, "SELECT * FROM profil_organisasi WHERE user_id='$user_id'");
    if (mysqli_num_rows($cek_profil) == 0) {
        echo "<script>alert('Lengkapi Profil Organisasi terlebih dahulu!'); window.location='../organisasi/org_profil.php';</script>";
        exit;
    }
}

// 3. QUERY DATA DANA (DIPERBAIKI)
// Perbaikan: Menggunakan nama tabel 'mst_jenis_bank' & 'mst_jenis_bantuan'
// Perbaikan: Menggunakan kolom 'nama' lalu di-alias agar sesuai tampilan
if ($role == 'admin') {
    $query = "SELECT d.*, u.nama_lengkap AS nama_ormawa, 
                     jb.nama AS nama_bantuan, 
                     mb.nama AS nama_bank
              FROM trx_dana d
              JOIN users u ON d.user_id = u.id
              LEFT JOIN mst_jenis_bantuan jb ON d.id_jenis_bantuan = jb.id
              LEFT JOIN mst_jenis_bank mb ON d.id_bank = mb.id
              ORDER BY d.id DESC";
} else {
    $query = "SELECT d.*, u.nama_lengkap AS nama_ormawa, 
                     jb.nama AS nama_bantuan, 
                     mb.nama AS nama_bank
              FROM trx_dana d
              JOIN users u ON d.user_id = u.id
              LEFT JOIN mst_jenis_bantuan jb ON d.id_jenis_bantuan = jb.id
              LEFT JOIN mst_jenis_bank mb ON d.id_bank = mb.id
              WHERE d.user_id = '$user_id'
              ORDER BY d.id DESC";
}

$result = mysqli_query($koneksi, $query);

// --- PENGECEKAN ERROR QUERY (PENTING) ---
if (!$result) {
    die("<b>Error Database:</b> " . mysqli_error($koneksi));
}
// ----------------------------------------

// Helper Warna Badge Status Dana
function getDanaBadge($status) {
    switch ($status) {
        case 'Draft': return 'secondary';
        case 'Diajukan': return 'primary';
        case 'Verifikasi': return 'info text-dark';
        case 'Disetujui': return 'success';
        case 'Dicairkan': return 'success fw-bold';
        case 'Ditolak': return 'danger';
        default: return 'light text-dark';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Data Bantuan Dana - SIPANDAI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="d-flex">
    
    <?php include '../sidebar.php'; ?>

    <div class="p-4 w-100 bg-light" style="height: 100vh; overflow-y: auto;">
        <div class="container-fluid">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold text-success"><i class="bi bi-cash-coin me-2"></i>Data Pengajuan Dana</h3>
                
                <?php if($role != 'admin') { ?>
                    <a href="sipandai_tambah.php" class="btn btn-success">
                        <i class="bi bi-plus-lg me-2"></i>Ajukan Dana
                    </a>
                <?php } ?>
            </div>

            <div class="card shadow-sm border-0 border-top border-4 border-success">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Judul Pengajuan</th>
                                    <th>Jenis Bantuan</th>
                                    <?php if($role == 'admin') { ?> <th>Pemohon</th> <?php } ?>
                                    <th>Total Anggaran</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td class="fw-bold"><?php echo $row['judul_pengajuan']; ?></td>
                                    <td><?php echo $row['nama_bantuan']; ?></td>
                                    
                                    <?php if($role == 'admin') { ?> 
                                        <td><?php echo $row['nama_ormawa']; ?></td> 
                                    <?php } ?>

                                    <td class="text-end">Rp <?php echo number_format($row['total_anggaran'], 0, ',', '.'); ?></td>
                                    <td><small><?php echo date('d/m/Y', strtotime($row['tgl_pengajuan'])); ?></small></td>
                                    
                                    <td>
                                        <span class="badge bg-<?php echo getDanaBadge($row['status_dana']); ?>">
                                            <?php echo $row['status_dana']; ?>
                                        </span>
                                    </td>
                                    
                                    <td>
                                        <a href="sipandai_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info text-white me-1" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <?php if($role != 'admin') { ?>
                                            <?php if(in_array($row['status_dana'], ['Draft', 'Ditolak'])) { ?>
                                                <a href="sipandai_hapus.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus pengajuan ini?')" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <a href="sipandai_proses.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning text-dark" title="Verifikasi">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php 
                                    } 
                                } else {
                                    $cols = ($role == 'admin') ? 8 : 7;
                                    echo "<tr><td colspan='$cols' class='text-center py-4 text-muted'>Belum ada data pengajuan dana.</td></tr>";
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