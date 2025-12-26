<?php 
// File: admin/sirajin/detail_laporan_admin.php
include '../../config.php'; 
include '../header.php'; 
include '../sidebar.php'; 

$id = $_GET['id'];
$query = "SELECT * FROM tb_kegiatan WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);
?>

<div class="flex-grow-1 p-4 bg-light">
    
    <div class="d-flex justify-content-between mb-3">
        <h4 class="fw-bold">Validasi Laporan Kegiatan</h4>
        <a href="laporan_admin.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-info text-white">Bukti Laporan Mahasiswa</div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="30%" class="fw-bold">Kegiatan</td>
                            <td>: <?= $data['nama_kegiatan']; ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Link Video</td>
                            <td>: 
                                <?php if($data['link_video']): ?>
                                    <a href="<?= $data['link_video']; ?>" target="_blank" class="text-decoration-none">
                                        <i class="fab fa-youtube text-danger"></i> Lihat Video
                                    </a>
                                <?php else: ?> - <?php endif; ?>
                            </td>
                        </tr>
                    </table>

                    <div class="row mt-3">
                        <div class="col-md-6 text-center">
                            <h6>File LPJ (PDF)</h6>
                            <a href="../../uploads/<?= $data['file_lpj']; ?>" target="_blank" class="btn btn-outline-danger btn-sm w-100">
                                <i class="fas fa-file-pdf"></i> Download PDF
                            </a>
                        </div>
                        <div class="col-md-6 text-center">
                            <h6>Foto Dokumentasi</h6>
                            <?php if($data['foto_dokumentasi']): ?>
                                <img src="../../uploads/<?= $data['foto_dokumentasi']; ?>" class="img-thumbnail" style="max-height: 150px;">
                                <br>
                                <a href="../../uploads/<?= $data['foto_dokumentasi']; ?>" target="_blank" class="small">Lihat Full</a>
                            <?php else: ?>
                                <span class="text-muted">Tidak ada foto</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Keputusan Admin</div>
                <div class="card-body">
                    <form action="proses_laporan_admin.php" method="POST">
                        <input type="hidden" name="id" value="<?= $data['id']; ?>">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ubah Status LPJ:</label>
                            <select name="status_lpj" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Status --</option>
                                <option value="Laporan Disetujui" class="text-success fw-bold">✅ Laporan Disetujui (Selesai)</option>
                                <option value="Laporan Revisi" class="text-danger fw-bold">⚠️ Minta Revisi Laporan</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Catatan Admin (Opsional):</label>
                            <textarea name="catatan_lpj" class="form-control" rows="4" placeholder="Contoh: Video tidak bisa dibuka, atau Laporan kurang tanda tangan..."><?= $data['catatan_admin']; ?></textarea>
                        </div>

                        <button type="submit" name="simpan_validasi" class="btn btn-primary w-100">Simpan Validasi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>