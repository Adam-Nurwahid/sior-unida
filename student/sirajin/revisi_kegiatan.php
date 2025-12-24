<?php 
// File: student/sirajin/revisi_kegiatan.php
include '../../config.php'; 
include '../header.php'; 
include '../sidebar.php'; 

$id = $_GET['id'];
$query = "SELECT * FROM tb_kegiatan WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Cek apakah status boleh direvisi (Hanya jika status 'Revisi' atau 'Dikembalikan')
if ($data['status_perizinan'] != 'Revisi' && $data['status_perizinan'] != 'Dikembalikan') {
    echo "<script>alert('Kegiatan ini tidak dalam status Revisi!'); window.location='kegiatan_mhs.php';</script>";
    exit;
}
?>

<div class="flex-grow-1 p-4 bg-light">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0"><i class="fas fa-edit"></i> Form Revisi Kegiatan</h5>
        </div>
        <div class="card-body">
            
            <div class="alert alert-danger">
                <strong>Catatan Revisi dari Admin:</strong> <br>
                <?= htmlspecialchars($data['catatan_admin']); ?>
            </div>

            <form action="proses_update_kegiatan.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $data['id']; ?>">
                <input type="hidden" name="file_lama" value="<?= $data['file_proposal']; ?>">

                <div class="mb-3">
                    <label class="form-label">Nama Kegiatan</label>
                    <input type="text" name="nama_kegiatan" class="form-control" value="<?= $data['nama_kegiatan']; ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3" required><?= $data['deskripsi']; ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Estimasi Anggaran</label>
                        <input type="number" name="anggaran" class="form-control" value="<?= $data['estimasi_anggaran']; ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload Proposal Baru (Opsional)</label>
                    <input type="file" name="proposal" class="form-control" accept=".pdf">
                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah file proposal.</small>
                    <div class="mt-1 small">File saat ini: <a href="../../uploads/<?= $data['file_proposal']; ?>" target="_blank"><?= $data['file_proposal']; ?></a></div>
                </div>

                <hr>
                <div class="d-flex justify-content-end gap-2">
                    <a href="kegiatan_mhs.php" class="btn btn-secondary">Batal</a>
                    <button type="submit" name="update" class="btn btn-primary">Simpan Revisi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>