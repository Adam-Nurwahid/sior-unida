<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("location:sipandai_list.php");
    exit;
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// QUERY DETAIL DANA
// Menggunakan kolom 'nama' dari tabel master sesuai gambar database Anda
$query = "SELECT d.*, u.nama_lengkap AS nama_ormawa, 
                 jb.nama AS nama_bantuan, 
                 mb.nama AS nama_bank
          FROM trx_dana d
          JOIN users u ON d.user_id = u.id
          LEFT JOIN mst_jenis_bantuan jb ON d.id_jenis_bantuan = jb.id
          LEFT JOIN mst_jenis_bank mb ON d.id_bank = mb.id
          WHERE d.id = '$id'";

$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

// Validasi Akses
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='sipandai_list.php';</script>";
    exit;
}
// Jika Client mencoba akses data orang lain
if ($role != 'admin' && $data['user_id'] != $user_id) {
    echo "<script>alert('Akses Ditolak!'); window.location='sipandai_list.php';</script>";
    exit;
}

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
    <title>Detail Dana - SIPANDAI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="d-flex">
    <?php include '../sidebar.php'; ?>

    <div class="p-4 w-100 bg-light" style="height: 100vh; overflow-y: auto;">
        <div class="container" style="max-width: 900px;">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold text-success">Detail Pengajuan Dana</h3>
                <a href="sipandai_list.php" class="btn btn-secondary btn-sm">Kembali</a>
            </div>

            <div class="card shadow-sm mb-4 border-top border-4 border-success">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-1"><?php echo $data['judul_pengajuan']; ?></h5>
                        <p class="text-muted mb-0">Pemohon: <?php echo $data['nama_ormawa']; ?></p>
                    </div>
                    <div class="text-end">
                        <small class="d-block text-muted">Status:</small>
                        <span class="badge bg-<?php echo getDanaBadge($data['status_dana']); ?> fs-6">
                            <?php echo $data['status_dana']; ?>
                        </span>
                    </div>
                </div>
            </div>

            <?php if (!empty($data['catatan_verifikator'])): ?>
            <div class="alert alert-warning mb-4 shadow-sm">
                <strong><i class="bi bi-chat-left-text"></i> Catatan Verifikator:</strong><br>
                <?php echo nl2br($data['catatan_verifikator']); ?>
            </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-7">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white fw-bold">Rincian Pengajuan</div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted" width="35%">Jenis Bantuan</td>
                                    <td class="fw-bold"><?php echo $data['nama_bantuan']; ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Total Anggaran</td>
                                    <td class="fs-5 fw-bold text-success">Rp <?php echo number_format($data['total_anggaran'], 0, ',', '.'); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tanggal Ajuan</td>
                                    <td><?php echo date('d F Y H:i', strtotime($data['tgl_pengajuan'])); ?></td>
                                </tr>
                            </table>
                            <hr>
                            <h6>Keperluan:</h6>
                            <p class="text-muted"><?php echo nl2br($data['deskripsi_keperluan']); ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white fw-bold">Rekening Pencairan</div>
                        <div class="card-body">
                            <p class="mb-1 text-muted">Bank Tujuan:</p>
                            <h6 class="fw-bold"><?php echo $data['nama_bank']; ?></h6>
                            
                            <p class="mb-1 mt-3 text-muted">Nomor Rekening:</p>
                            <h5 class="fw-bold font-monospace"><?php echo $data['no_rekening']; ?></h5>
                            
                            <p class="mb-1 mt-3 text-muted">Atas Nama:</p>
                            <h6 class="fw-bold"><?php echo $data['atas_nama_rekening']; ?></h6>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-header bg-white fw-bold">Dokumen RAB</div>
                        <div class="card-body text-center">
                            <?php if (!empty($data['file_rab'])): ?>
                                <i class="bi bi-file-earmark-spreadsheet text-success display-4"></i>
                                <p class="small text-muted mt-2 mb-3"><?php echo $data['file_rab']; ?></p>
                                <a href="../uploads/<?php echo $data['file_rab']; ?>" target="_blank" class="btn btn-outline-success w-100">
                                    <i class="bi bi-download me-2"></i> Download RAB
                                </a>
                            <?php else: ?>
                                <p class="text-muted">Tidak ada file RAB.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>

            <?php if ($role == 'admin'): ?>
                <div class="d-grid mt-4">
                    <a href="sipandai_proses.php?id=<?php echo $data['id']; ?>" class="btn btn-warning fw-bold btn-lg">
                        <i class="bi bi-pencil-square me-2"></i> VERIFIKASI / PROSES DANA
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>