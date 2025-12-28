<?php
session_start();
include '../koneksi.php';

// 1. Cek Login & Role
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../login.php");
    exit;
}

// HANYA ADMIN YANG BOLEH AKSES
if ($_SESSION['role'] != 'admin') {
    echo "<script>alert('Akses Ditolak! Anda bukan Admin.'); window.location='sirajin_list.php';</script>";
    exit;
}

// 2. Ambil Data Kegiatan
if (!isset($_GET['id'])) {
    header("location:sirajin_list.php");
    exit;
}
$id = $_GET['id'];

// Ambil data lengkap dengan nama mahasiswa/ormawa
$query = "SELECT k.*, u.nama_lengkap AS nama_ormawa, j.nama AS jenis_kegiatan 
          FROM trx_kegiatan k
          JOIN users u ON k.user_id = u.id
          LEFT JOIN mst_jenis_kegiatan j ON k.id_jenis_kegiatan = j.id
          WHERE k.id = '$id'";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='sirajin_list.php';</script>";
    exit;
}

// 3. PROSES SIMPAN KEPUTUSAN
if (isset($_POST['simpan_keputusan'])) {
    $status_baru = $_POST['status_perizinan'];
    $catatan = $_POST['catatan_revisi'];

    // Validasi: Jika status Revisi/Tolak, catatan wajib diisi
    if (($status_baru == 'Perlu Revisi' || $status_baru == 'Ditolak') && empty(trim($catatan))) {
        echo "<script>alert('Harap isi CATATAN jika status Ditolak atau Revisi!');</script>";
    } else {
        $update = mysqli_query($koneksi, "UPDATE trx_kegiatan SET 
                                          status_perizinan='$status_baru', 
                                          catatan_revisi='$catatan' 
                                          WHERE id='$id'");
        
        if ($update) {
            echo "<script>alert('Status Berhasil Diperbarui!'); window.location='sirajin_list.php';</script>";
        } else {
            echo "<script>alert('Gagal Update!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Proses Perizinan - SIRAJIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="d-flex">
    <?php include '../sidebar.php'; ?>

    <div class="p-4 w-100 bg-light" style="height: 100vh; overflow-y: auto;">
        <div class="container" style="max-width: 1000px;">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold text-primary">Proses Perizinan Kegiatan</h3>
                <a href="sirajin_list.php" class="btn btn-secondary btn-sm">Kembali</a>
            </div>

            <div class="row">
                <div class="col-md-7">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white fw-bold">Detail Pengajuan</div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="30%" class="text-muted">Nama Kegiatan</td>
                                    <td class="fw-bold"><?php echo $data['nama_kegiatan']; ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Penyelenggara</td>
                                    <td><?php echo $data['nama_ormawa']; ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Jenis Kegiatan</td>
                                    <td><?php echo $data['jenis_kegiatan']; ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Waktu</td>
                                    <td>
                                        <?php echo date('d M Y', strtotime($data['tgl_mulai'])); ?>
                                        <br>
                                        <small>(<?php echo date('H:i', strtotime($data['waktu_mulai'])); ?> - Selesai)</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tempat</td>
                                    <td><?php echo $data['detail_tempat']; ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Anggaran</td>
                                    <td class="text-success fw-bold">Rp <?php echo number_format($data['estimasi_biaya'], 0, ',', '.'); ?></td>
                                </tr>
                            </table>
                            
                            <hr>
                            
                            <h6 class="fw-bold">File Proposal:</h6>
                            <?php if (!empty($data['file_proposal'])): ?>
                                <div class="d-grid">
                                    <a href="../uploads/<?php echo $data['file_proposal']; ?>" target="_blank" class="btn btn-outline-danger">
                                        <i class="bi bi-file-earmark-pdf me-2"></i> BACA PROPOSAL (PDF)
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-secondary text-center mb-0">Tidak ada file proposal</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="card shadow border-primary">
                        <div class="card-header bg-primary text-white fw-bold">
                            <i class="bi bi-gavel me-2"></i> Keputusan Admin
                        </div>
                        <div class="card-body">
                            
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Status Saat Ini:</label><br>
                                    <span class="badge bg-secondary fs-6"><?php echo $data['status_perizinan']; ?></span>
                                </div>

                                <hr>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tindakan / Status Baru <span class="text-danger">*</span></label>
                                    <select name="status_perizinan" class="form-select form-select-lg" required>
                                        <option value="" disabled selected>-- Pilih Keputusan --</option>
                                        <option value="Disetujui" class="text-success fw-bold">✅ SETUJUI (ACC)</option>
                                        <option value="Perlu Revisi" class="text-warning fw-bold">⚠️ MINTA REVISI</option>
                                        <option value="Ditolak" class="text-danger fw-bold">❌ TOLAK KEGIATAN</option>
                                        <option value="Sedang Diproses">⏳ Sedang Diproses (Pending)</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Catatan / Feedback</label>
                                    <textarea name="catatan_revisi" class="form-control" rows="5" placeholder="Tuliskan alasan penolakan atau bagian yang perlu direvisi..."><?php echo $data['catatan_revisi']; ?></textarea>
                                    <div class="form-text text-danger">Wajib diisi jika status Revisi atau Ditolak.</div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" name="simpan_keputusan" class="btn btn-primary btn-lg fw-bold">
                                        SIMPAN KEPUTUSAN
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>