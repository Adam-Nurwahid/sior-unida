<?php
session_start();
include '../koneksi.php';

// 1. Cek Login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../login.php");
    exit;
}

// 2. CEK ROLE: HANYA ADMIN YANG BOLEH AKSES
if ($_SESSION['role'] != 'admin') {
    echo "<script>alert('Akses Ditolak! Halaman ini hanya untuk Admin DKP.'); window.location.href='../index.php';</script>";
    exit;
}

// 3. PROSES UPDATE PERIODE
if (isset($_POST['simpan_periode'])) {
    $tahun = $_POST['tahun'];
    $mulai = $_POST['tgl_mulai'];
    $selesai = $_POST['tgl_selesai'];
    $catatan = $_POST['catatan'];
    $status = $_POST['status_aktif'];

    // Kita selalu update ID 1 agar data tunggal
    $sql = "UPDATE mst_periode SET 
            tahun='$tahun', tgl_mulai='$mulai', tgl_selesai='$selesai', 
            status_aktif='$status', catatan='$catatan' 
            WHERE id='1'";
            
    if (mysqli_query($koneksi, $sql)) {
        echo "<script>alert('Periode Berhasil Diupdate!'); window.location='org_periode.php';</script>";
    } else {
        echo "<script>alert('Gagal Update!');</script>";
    }
}

// AMBIL DATA PERIODE SAAT INI
$q_periode = mysqli_query($koneksi, "SELECT * FROM mst_periode WHERE id='1'");
$d = mysqli_fetch_array($q_periode);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Kelola Periode Daftar Ulang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="d-flex">
    <?php include '../sidebar.php'; ?>

    <div class="p-4 w-100 bg-light" style="height: 100vh; overflow-y: auto;">
        <div class="container" style="max-width: 800px;">
            
            <h2 class="mb-4 fw-bold text-primary">Kelola Periode Daftar Ulang</h2>

            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h6 class="m-0 fw-bold"><i class="bi bi-calendar-range me-2"></i>Setting Jadwal Daftar Ulang</h6>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Tahun Periode</label>
                            <input type="text" name="tahun" class="form-control" value="<?php echo $d['tahun']; ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="datetime-local" name="tgl_mulai" class="form-control" value="<?php echo $d['tgl_mulai']; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="datetime-local" name="tgl_selesai" class="form-control" value="<?php echo $d['tgl_selesai']; ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status Periode</label>
                            <select name="status_aktif" class="form-select">
                                <option value="1" <?php echo ($d['status_aktif'] == 1) ? 'selected' : ''; ?>>Aktif</option>
                                <option value="0" <?php echo ($d['status_aktif'] == 0) ? 'selected' : ''; ?>>Non-Aktif (Tutup)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan / Keterangan</label>
                            <textarea name="catatan" class="form-control" rows="3"><?php echo $d['catatan']; ?></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="simpan_periode" class="btn btn-primary">SIMPAN PERUBAHAN</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-muted small">
                    Note: Jika periode ini aktif dan tanggal hari ini masuk dalam rentang, notifikasi akan muncul di dashboard Mahasiswa.
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>