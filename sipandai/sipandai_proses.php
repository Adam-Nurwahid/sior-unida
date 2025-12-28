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
    echo "<script>alert('Akses Ditolak! Anda bukan Admin.'); window.location='sipandai_list.php';</script>";
    exit;
}

// 2. Ambil ID
if (!isset($_GET['id'])) {
    header("location:sipandai_list.php");
    exit;
}
$id = $_GET['id'];

// 3. Ambil Data Lengkap
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

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='sipandai_list.php';</script>";
    exit;
}

// 4. PROSES SIMPAN VERIFIKASI
if (isset($_POST['simpan_verifikasi'])) {
    $status_baru = $_POST['status_dana'];
    $catatan = $_POST['catatan_verifikator'];

    // Validasi sederhana
    if ($status_baru == 'Ditolak' && empty(trim($catatan))) {
        echo "<script>alert('Mohon isi catatan alasan penolakan!');</script>";
    } else {
        $update = mysqli_query($koneksi, "UPDATE trx_dana SET 
                                          status_dana='$status_baru', 
                                          catatan_verifikator='$catatan' 
                                          WHERE id='$id'");
        
        if ($update) {
            echo "<script>alert('Status Dana Berhasil Diperbarui!'); window.location='sipandai_list.php';</script>";
        } else {
            echo "<script>alert('Gagal Update: ".mysqli_error($koneksi)."');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Proses Dana - SIPANDAI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="d-flex">
    <?php include '../sidebar.php'; ?>

    <div class="p-4 w-100 bg-light" style="height: 100vh; overflow-y: auto;">
        <div class="container" style="max-width: 1000px;">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold text-success">Verifikasi Pengajuan Dana</h3>
                <a href="sipandai_list.php" class="btn btn-secondary btn-sm">Kembali</a>
            </div>

            <div class="row">
                <div class="col-md-7">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white fw-bold">Detail Pengajuan</div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="30%" class="text-muted">Judul</td>
                                    <td class="fw-bold"><?php echo $data['judul_pengajuan']; ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Pemohon</td>
                                    <td><?php echo $data['nama_ormawa']; ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Jenis Bantuan</td>
                                    <td><?php echo $data['nama_bantuan']; ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Total Anggaran</td>
                                    <td class="text-success fw-bold fs-5">Rp <?php echo number_format($data['total_anggaran'], 0, ',', '.'); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tanggal Ajuan</td>
                                    <td><?php echo date('d M Y', strtotime($data['tgl_pengajuan'])); ?></td>
                                </tr>
                            </table>
                            
                            <hr>
                            
                            <h6 class="fw-bold">Keperluan:</h6>
                            <p class="text-secondary"><?php echo nl2br($data['deskripsi_keperluan']); ?></p>

                            <div class="alert alert-light border mt-3">
                                <strong><i class="bi bi-bank me-2"></i>Rekening Tujuan:</strong><br>
                                <?php echo $data['nama_bank']; ?> - <strong><?php echo $data['no_rekening']; ?></strong><br>
                                a.n <?php echo $data['atas_nama_rekening']; ?>
                            </div>

                            <hr>
                            
                            <h6 class="fw-bold">File RAB:</h6>
                            <?php if (!empty($data['file_rab'])): ?>
                                <div class="d-grid">
                                    <a href="../uploads/<?php echo $data['file_rab']; ?>" target="_blank" class="btn btn-outline-success">
                                        <i class="bi bi-file-earmark-spreadsheet me-2"></i> DOWNLOAD RAB
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-secondary text-center mb-0">Tidak ada file RAB</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="card shadow border-success">
                        <div class="card-header bg-success text-white fw-bold">
                            <i class="bi bi-shield-check me-2"></i> Keputusan Admin
                        </div>
                        <div class="card-body">
                            
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Status Saat Ini:</label><br>
                                    <span class="badge bg-secondary fs-6"><?php echo $data['status_dana']; ?></span>
                                </div>

                                <hr>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Update Status <span class="text-danger">*</span></label>
                                    <select name="status_dana" class="form-select form-select-lg" required>
                                        <option value="" disabled selected>-- Pilih Status --</option>
                                        <option value="Verifikasi" class="fw-bold text-info">⏳ Sedang Diverifikasi</option>
                                        <option value="Disetujui" class="fw-bold text-primary">✅ DISETUJUI (Menunggu Cair)</option>
                                        <option value="Dicairkan" class="fw-bold text-success">💰 SUDAH DICAIRKAN (Transfer)</option>
                                        <option value="Ditolak" class="fw-bold text-danger">❌ TOLAK PENGAJUAN</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Catatan Verifikator</label>
                                    <textarea name="catatan_verifikator" class="form-control" rows="5" placeholder="Contoh: Dana akan dicairkan tanggal 25, atau Alasan penolakan..."><?php echo $data['catatan_verifikator']; ?></textarea>
                                    <div class="form-text">Catatan ini akan terbaca oleh mahasiswa.</div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" name="simpan_verifikasi" class="btn btn-success btn-lg fw-bold">
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