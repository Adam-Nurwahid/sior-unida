<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("location:sirajin_laporan.php");
    exit;
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Ambil Data
$query = "SELECT k.*, u.nama_lengkap AS nama_ormawa 
          FROM trx_kegiatan k
          JOIN users u ON k.user_id = u.id
          WHERE k.id = '$id'";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

// Validasi Akses
if (!$data || ($role != 'admin' && $data['user_id'] != $user_id)) {
    echo "<script>alert('Akses Ditolak!'); window.location='sirajin_laporan.php';</script>";
    exit;
}

// --- LOGIKA 1: MAHASISWA UPLOAD LAPORAN ---
if (isset($_POST['upload_lpj'])) {
    if (!empty($_FILES['file_lpj']['name'])) {
        $ext = pathinfo($_FILES['file_lpj']['name'], PATHINFO_EXTENSION);
        if (strtolower($ext) == 'pdf') {
            $filename = time() . '_LPJ_' . $_SESSION['username'] . '.pdf';
            
            // Hapus file lama jika ada
            if(!empty($data['file_laporan']) && file_exists("../uploads/" . $data['file_laporan'])){
                unlink("../uploads/" . $data['file_laporan']);
            }

            move_uploaded_file($_FILES['file_lpj']['tmp_name'], "../uploads/" . $filename);
            
            // Update Database: Status jadi 'Menunggu Verifikasi'
            $now = date('Y-m-d H:i:s');
            $sql = "UPDATE trx_kegiatan SET file_laporan='$filename', tgl_upload_laporan='$now', status_laporan='Menunggu Verifikasi' WHERE id='$id'";
            mysqli_query($koneksi, $sql);
            
            echo "<script>alert('Laporan Berhasil Diupload!'); window.location='sirajin_laporan.php';</script>";
        } else {
            echo "<script>alert('File harus PDF!');</script>";
        }
    } else {
        echo "<script>alert('Pilih file terlebih dahulu!');</script>";
    }
}

// --- LOGIKA 2: ADMIN VERIFIKASI LAPORAN ---
if (isset($_POST['verifikasi_lpj'])) {
    $status_baru = $_POST['status_laporan'];
    $catatan = $_POST['catatan_laporan'];
    
    $sql = "UPDATE trx_kegiatan SET status_laporan='$status_baru', catatan_laporan='$catatan' WHERE id='$id'";
    mysqli_query($koneksi, $sql);
    
    echo "<script>alert('Status Laporan Diperbarui!'); window.location='sirajin_laporan.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Detail Laporan - SIRAJIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="d-flex">
    <?php include '../sidebar.php'; ?>

    <div class="p-4 w-100 bg-light" style="height: 100vh; overflow-y: auto;">
        <div class="container" style="max-width: 900px;">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold text-primary">Detail Laporan Kegiatan</h3>
                <a href="sirajin_laporan.php" class="btn btn-secondary btn-sm">Kembali</a>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold"><?php echo $data['nama_kegiatan']; ?></h5>
                    <p class="text-muted mb-0">Penyelenggara: <?php echo $data['nama_ormawa']; ?></p>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Tanggal Kegiatan:</strong><br>
                            <?php echo date('d M Y', strtotime($data['tgl_mulai'])); ?>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <strong>Status Laporan Saat Ini:</strong><br>
                            <span class="badge bg-primary fs-6"><?php echo $data['status_laporan']; ?></span>
                        </div>
                    </div>
                    
                    <?php if(!empty($data['catatan_laporan'])): ?>
                    <div class="alert alert-warning mt-3 mb-0">
                        <strong><i class="bi bi-chat-left-text"></i> Catatan Admin:</strong><br>
                        <?php echo nl2br($data['catatan_laporan']); ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white fw-bold">File Laporan (LPJ)</div>
                        <div class="card-body text-center d-flex flex-column justify-content-center">
                            <?php if(!empty($data['file_laporan'])): ?>
                                <i class="bi bi-file-earmark-pdf text-danger display-1"></i>
                                <p class="mt-2 text-muted"><?php echo $data['file_laporan']; ?></p>
                                <p class="small text-muted">Diupload: <?php echo $data['tgl_upload_laporan']; ?></p>
                                <a href="../uploads/<?php echo $data['file_laporan']; ?>" target="_blank" class="btn btn-danger w-100 mt-auto">
                                    <i class="bi bi-download me-2"></i> DOWNLOAD LPJ
                                </a>
                            <?php else: ?>
                                <i class="bi bi-file-earmark-x text-muted display-1"></i>
                                <p class="mt-2 text-muted">Belum ada file laporan yang diupload.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm h-100 border-top border-4 border-info">
                        <div class="card-header bg-white fw-bold">Aksi Laporan</div>
                        <div class="card-body">
                            
                            <?php if ($role != 'admin'): ?>
                                <?php if ($data['status_laporan'] == 'Diterima'): ?>
                                    <div class="alert alert-success text-center">
                                        <i class="bi bi-check-circle-fill fs-1"></i><br>
                                        <strong>Laporan Sudah Diterima!</strong><br>
                                        Terima kasih telah menyelesaikan administrasi kegiatan ini.
                                    </div>
                                <?php else: ?>
                                    <form method="POST" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label class="form-label">Upload File LPJ (PDF)</label>
                                            <input type="file" name="file_lpj" class="form-control" accept=".pdf" required>
                                            <div class="form-text">Maksimal 2MB. Format .PDF</div>
                                        </div>
                                        <div class="d-grid">
                                            <button type="submit" name="upload_lpj" class="btn btn-primary fw-bold">
                                                <i class="bi bi-upload me-2"></i> UPLOAD LAPORAN
                                            </button>
                                        </div>
                                    </form>
                                <?php endif; ?>

                            <?php else: ?>
                                <?php if(empty($data['file_laporan'])): ?>
                                    <div class="alert alert-secondary text-center">
                                        Mahasiswa belum mengupload laporan.
                                    </div>
                                <?php else: ?>
                                    <form method="POST">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Verifikasi Laporan</label>
                                            <select name="status_laporan" class="form-select mb-3" required>
                                                <option value="" disabled selected>- Pilih Status -</option>
                                                <option value="Diterima" class="text-success fw-bold">✅ Terima Laporan (Selesai)</option>
                                                <option value="Perlu Revisi" class="text-danger fw-bold">⚠️ Minta Revisi</option>
                                            </select>
                                            
                                            <label class="form-label">Catatan / Feedback</label>
                                            <textarea name="catatan_laporan" class="form-control" rows="4" placeholder="Tulis catatan jika ada revisi..."><?php echo $data['catatan_laporan']; ?></textarea>
                                        </div>
                                        <div class="d-grid">
                                            <button type="submit" name="verifikasi_lpj" class="btn btn-success fw-bold">
                                                SIMPAN VERIFIKASI
                                            </button>
                                        </div>
                                    </form>
                                <?php endif; ?>

                            <?php endif; ?>

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