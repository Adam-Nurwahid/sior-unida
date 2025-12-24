<?php 
include '../../config.php';
include '../header.php'; 
include '../sidebar.php'; 

// Admin melihat semua kegiatan
$query = "SELECT * FROM tb_kegiatan ORDER BY status_perizinan ASC, created_at DESC";
$result = mysqli_query($conn, $query);
?>

<div class="flex-grow-1 p-4 bg-light">
    <nav class="navbar navbar-light bg-white shadow-sm mb-4 rounded">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Admin DKP - Proses Kegiatan Mahasiswa</span>
        </div>
    </nav>

    <div class="alert alert-warning">
        <strong>PEMBERITAHUAN:</strong> Harap cek ketersediaan ruangan ke SARPAS sebelum menyetujui.
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light"> 
                        <tr>
                            <th>No</th>
                            <th>Kegiatan</th>
                            <th>Penyelenggara</th>
                            <th>Tanggal</th>
                            <th>Status Saat Ini</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?>.</td>
                            <td><?= $row['nama_kegiatan']; ?></td>
                            <td><?= $row['penyelenggara']; ?></td>
                            <td><?= $row['tanggal_mulai']; ?></td>
                            <td class="fw-bold">
                                <?= $row['status_perizinan']; ?>
                            </td>
                            <td>
                                <a href="../uploads/<?= $row['file_proposal']; ?>" target="_blank" class="btn btn-sm btn-outline-primary mb-1">
                                    <i class="fas fa-download"></i> Proposal
                                </a>

                                <button type="button" class="btn btn-sm btn-dark mb-1" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalProses<?= $row['id']; ?>">
                                    Proses
                                </button>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalProses<?= $row['id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Proses Izin: <?= $row['nama_kegiatan']; ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="proses_admin_update.php" method="POST">
                                        <div class="modal-body">
                                            <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Ubah Status</label>
                                                <select name="status" class="form-select" required>
                                                    <option value="Disetujui">Disetujui</option>
                                                    <option value="Revisi">Revisi (Kembalikan ke Mhs)</option>
                                                    <option value="Ditolak">Ditolak</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Catatan (Wajib jika Revisi/Ditolak)</label>
                                                <textarea name="catatan" class="form-control" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="update_status" class="btn btn-primary">Simpan Keputusan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>