<?php
session_start();
include '../koneksi.php';

// 1. Cek Login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../login.php");
    exit;
}

// 2. Ambil ID dari URL
if (!isset($_GET['id'])) {
    header("location:sirajin_list.php");
    exit;
}
$id_kegiatan = $_GET['id'];
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// 3. Query Data Detail
// Kita JOIN dengan tabel user, jenis kegiatan, dan lokasi untuk mendapatkan nama aslinya
$query = "SELECT k.*, u.nama_lengkap AS nama_ormawa, j.nama AS jenis_kegiatan, l.nama AS nama_lokasi 
          FROM trx_kegiatan k
          JOIN users u ON k.user_id = u.id
          LEFT JOIN mst_jenis_kegiatan j ON k.id_jenis_kegiatan = j.id
          LEFT JOIN mst_jenis_lokasi l ON k.id_lokasi = l.id
          WHERE k.id = '$id_kegiatan'";

$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

// 4. Validasi Keamanan Data
// Jika data tidak ditemukan
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='sirajin_list.php';</script>";
    exit;
}
// Jika Client mencoba akses data orang lain
if ($role != 'admin' && $data['user_id'] != $user_id) {
    echo "<script>alert('Akses Ditolak! Ini bukan data kegiatan Anda.'); window.location='sirajin_list.php';</script>";
    exit;
}

// Fungsi helper warna status
function getStatusBadge($status) {
    switch ($status) {
        case 'Draft': return 'secondary';
        case 'Pengajuan Baru': return 'primary';
        case 'Sedang Diproses': return 'info text-dark';
        case 'Perlu Revisi': return 'warning text-dark';
        case 'Disetujui': return 'success';
        case 'Ditolak': return 'danger';
        default: return 'light text-dark';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Detail Kegiatan - SIRAJIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="d-flex">
    <?php include '../sidebar.php'; ?>

    <div class="p-4 w-100 bg-light" style="height: 100vh; overflow-y: auto;">
        <div class="container" style="max-width: 900px;">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold text-primary">Detail Kegiatan</h3>
                <a href="sirajin_list.php" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
            </div>

            <div class="card shadow-sm mb-4 border-top border-4 border-primary">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="fw-bold mb-1"><?php echo $data['nama_kegiatan']; ?></h4>
                            <p class="text-muted mb-2">
                                <i class="bi bi-person-badge me-1"></i> Penyelenggara: <strong><?php echo $data['nama_ormawa']; ?></strong>
                            </p>
                            <span class="badge bg-secondary"><?php echo $data['jenis_kegiatan']; ?></span>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <small class="text-muted d-block mb-1">Status Perizinan:</small>
                            <span class="badge bg-<?php echo getStatusBadge($data['status_perizinan']); ?> fs-6 px-3 py-2">
                                <?php echo $data['status_perizinan']; ?>
                            </span>
                        </div>
                    </div>

                    <?php if (!empty($data['catatan_revisi'])): ?>
                    <div class="alert alert-warning mt-3 mb-0">
                        <strong><i class="bi bi-exclamation-circle-fill"></i> Catatan Admin:</strong><br>
                        <?php echo nl2br($data['catatan_revisi']); ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-7">
                    
                    <div class="card shadow-sm mb-4 h-100">
                        <div class="card-header bg-white fw-bold">Tentang Kegiatan</div>
                        <div class="card-body">
                            <h6 class="fw-bold text-dark">Deskripsi:</h6>
                            <p class="text-secondary"><?php echo nl2br($data['deskripsi']); ?></p>
                            
                            <hr>
                            
                            <h6 class="fw-bold text-dark">Tujuan:</h6>
                            <p class="text-secondary mb-0"><?php echo nl2br($data['tujuan']); ?></p>
                        </div>
                    </div>

                </div>

                <div class="col-md-5">
                    
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white fw-bold">Waktu & Tempat</div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-3">
                                    <i class="bi bi-calendar-check text-primary me-2"></i> 
                                    <strong>Tanggal:</strong><br>
                                    <span class="ms-4 text-muted">
                                        <?php 
                                        echo date('d M Y', strtotime($data['tgl_mulai']));
                                        if($data['tgl_mulai'] != $data['tgl_selesai']) {
                                            echo ' - ' . date('d M Y', strtotime($data['tgl_selesai']));
                                        }
                                        ?>
                                    </span>
                                </li>
                                <li class="mb-3">
                                    <i class="bi bi-clock text-primary me-2"></i> 
                                    <strong>Waktu:</strong><br>
                                    <span class="ms-4 text-muted">
                                        <?php echo date('H:i', strtotime($data['waktu_mulai'])) . ' - ' . date('H:i', strtotime($data['waktu_selesai'])); ?> WIB
                                    </span>
                                </li>
                                <li>
                                    <i class="bi bi-geo-alt text-primary me-2"></i> 
                                    <strong>Lokasi:</strong><br>
                                    <span class="ms-4 text-muted">
                                        <?php echo $data['nama_lokasi']; ?> 
                                        <?php if(!empty($data['detail_tempat'])) echo "({$data['detail_tempat']})"; ?>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white fw-bold">Teknis & Anggaran</div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                <span>Estimasi Biaya:</span>
                                <span class="fw-bold text-success">Rp <?php echo number_format($data['estimasi_biaya'], 0, ',', '.'); ?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Kebutuhan Listrik:</span>
                                <span class="fw-bold"><?php echo $data['kebutuhan_listrik']; ?> Watt</span>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-header bg-white fw-bold">Dokumen</div>
                        <div class="card-body text-center">
                            <i class="bi bi-file-earmark-pdf text-danger display-4"></i>
                            <p class="small text-muted mt-2 mb-3">Proposal Kegiatan</p>
                            
                            <?php if (!empty($data['file_proposal'])): ?>
                                <a href="../uploads/<?php echo $data['file_proposal']; ?>" target="_blank" class="btn btn-outline-danger w-100">
                                    <i class="bi bi-download me-2"></i> Download PDF
                                </a>
                            <?php else: ?>
                                <button class="btn btn-secondary w-100" disabled>Tidak ada file</button>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>

            <?php if ($role != 'admin' && in_array($data['status_perizinan'], ['Draft', 'Perlu Revisi'])): ?>
                <div class="mt-4 text-end">
                    <a href="sirajin_edit.php?id=<?php echo $data['id']; ?>" class="btn btn-warning text-white">
                        <i class="bi bi-pencil-square me-2"></i> Edit / Revisi Proposal
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>