<?php 
// File: student/sirajin/input_laporan.php
include '../../config.php'; 
include '../header.php'; 
include '../sidebar.php'; 

$id = $_GET['id'];
$query = "SELECT * FROM tb_kegiatan WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);
?>

<div class="flex-grow-1 p-4 bg-light">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">C-RJN 8 Pelaporan Kegiatan</h5>
        </div>
        <div class="card-body">
            
            <form action="proses_upload_lpj.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $data['id']; ?>">

                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Kegiatan</label>
                    <input type="text" class="form-control" value="<?= $data['nama_kegiatan']; ?>" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Dokumen Laporan (PDF, Max 5MB)*</label>
                    <input type="file" name="file_lpj" class="form-control" accept=".pdf" required>
                    <small class="text-muted">Upload laporan pertanggungjawaban lengkap.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Dokumentasi Foto (JPG/PNG)*</label>
                    <input type="file" name="foto_dokumentasi" class="form-control" accept=".jpg, .jpeg, .png" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Link Video (Youtube/Drive)</label>
                    <input type="text" name="link_video" class="form-control" placeholder="https://..." required>
                </div>

                <hr>
                <div class="d-flex justify-content-end gap-2">
                    <a href="laporan_kegiatan.php" class="btn btn-secondary">Batal</a>
                    <button type="submit" name="upload_lpj" class="btn btn-primary">SIMPAN LAPORAN</button> </div>
            </form>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>