<?php
session_start();
include '../koneksi.php';

// 1. Cek Login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../login.php");
    exit;
}

// Hanya Mahasiswa/UKM yang boleh mengajukan
if ($_SESSION['role'] == 'admin') {
    header("location:sipandai_list.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// 2. PROSES SIMPAN DATA
if (isset($_POST['simpan_dana'])) {
    $judul = $_POST['judul_pengajuan'];
    $id_bantuan = $_POST['id_jenis_bantuan'];
    $deskripsi = $_POST['deskripsi_keperluan'];
    $total = $_POST['total_anggaran'];
    
    $id_bank = $_POST['id_bank'];
    $norek = $_POST['no_rekening'];
    $an = $_POST['atas_nama_rekening'];

    // Upload File RAB (PDF/Excel)
    $file_rab = "";
    if (!empty($_FILES['file_rab']['name'])) {
        $ext = pathinfo($_FILES['file_rab']['name'], PATHINFO_EXTENSION);
        // Izinkan PDF dan Excel
        if (in_array(strtolower($ext), ['pdf', 'xls', 'xlsx'])) {
            $file_rab = time() . '_RAB_' . $_SESSION['username'] . '.' . $ext;
            move_uploaded_file($_FILES['file_rab']['tmp_name'], "../uploads/" . $file_rab);
        } else {
            echo "<script>alert('Format file harus PDF atau Excel!');</script>";
        }
    }

    // Insert Data
    $query = "INSERT INTO trx_dana 
              (user_id, id_jenis_bantuan, judul_pengajuan, deskripsi_keperluan, total_anggaran, 
               id_bank, no_rekening, atas_nama_rekening, file_rab, status_dana)
              VALUES 
              ('$user_id', '$id_bantuan', '$judul', '$deskripsi', '$total', 
               '$id_bank', '$norek', '$an', '$file_rab', 'Diajukan')";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Pengajuan Dana Berhasil Disimpan!'); window.location='sipandai_list.php';</script>";
    } else {
        echo "<script>alert('Gagal Menyimpan: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Ajukan Dana - SIPANDAI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="d-flex">
    <?php include '../sidebar.php'; ?>

    <div class="p-4 w-100 bg-light" style="height: 100vh; overflow-y: auto;">
        <div class="container" style="max-width: 900px;">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold text-success">Form Pengajuan Dana</h3>
                <a href="sipandai_list.php" class="btn btn-secondary btn-sm">Kembali</a>
            </div>

            <form method="POST" enctype="multipart/form-data">
                
                <div class="card shadow-sm mb-4 border-top border-3 border-success">
                    <div class="card-header bg-white fw-bold">1. Rincian Kebutuhan</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Judul Pengajuan <span class="text-danger">*</span></label>
                            <input type="text" name="judul_pengajuan" class="form-control" placeholder="Contoh: Dana Konsumsi Seminar IT" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Jenis Bantuan <span class="text-danger">*</span></label>
                            <select name="id_jenis_bantuan" class="form-select" required>
                                <option value="">- Pilih Jenis Bantuan -</option>
                                <?php
                                // Mengambil data dari mst_jenis_bantuan (kolom: id, nama)
                                $q_bantuan = mysqli_query($koneksi, "SELECT * FROM mst_jenis_bantuan");
                                while ($b = mysqli_fetch_array($q_bantuan)) {
                                    echo "<option value='" . $b['id'] . "'>" . $b['nama'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Total Anggaran Diajukan (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="total_anggaran" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi Keperluan</label>
                            <textarea name="deskripsi_keperluan" class="form-control" rows="3" placeholder="Jelaskan penggunaan dana secara singkat..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold">2. Info Rekening Penerima</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Bank Tujuan <span class="text-danger">*</span></label>
                                <select name="id_bank" class="form-select" required>
                                    <option value="">- Pilih Bank -</option>
                                    <?php
                                    // Mengambil data dari mst_jenis_bank (kolom: id, kode, nama)
                                    $q_bank = mysqli_query($koneksi, "SELECT * FROM mst_jenis_bank");
                                    while ($bk = mysqli_fetch_array($q_bank)) {
                                        // Menampilkan Nama Bank
                                        echo "<option value='" . $bk['id'] . "'>" . $bk['nama'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nomor Rekening <span class="text-danger">*</span></label>
                                <input type="number" name="no_rekening" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Atas Nama <span class="text-danger">*</span></label>
                                <input type="text" name="atas_nama_rekening" class="form-control" required>
                            </div>
                        </div>
                        <div class="alert alert-info py-2 small">
                            <i class="bi bi-info-circle me-1"></i> Pastikan rekening atas nama Organisasi atau Bendahara yang sah.
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold">3. Dokumen Pendukung</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Upload RAB (Rincian Anggaran Biaya) <span class="text-danger">*</span></label>
                            <input type="file" name="file_rab" class="form-control" accept=".pdf, .xls, .xlsx" required>
                            <div class="form-text">Format yang diperbolehkan: PDF, Excel (.xls, .xlsx). Maks 2MB.</div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 mb-5">
                    <button type="submit" name="simpan_dana" class="btn btn-success btn-lg fw-bold">
                        <i class="bi bi-paper-plane me-2"></i>AJUKAN DANA
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>