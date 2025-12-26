<?php 
// File: admin/sirajin/kegiatan_admin.php
include '../../config.php'; 
include '../header.php'; 
include '../sidebar.php'; 

// Query mengambil data kegiatan, diurutkan dari yang terbaru
// Prioritaskan yang statusnya 'Pengajuan Baru' atau 'Pengajuan Revisi' di paling atas
$query = "SELECT * FROM tb_kegiatan ORDER BY 
          CASE WHEN status_perizinan IN ('Pengajuan Baru', 'Pengajuan Revisi') THEN 0 ELSE 1 END,
          created_at DESC";
$result = mysqli_query($conn, $query);
?>

<div class="flex-grow-1 p-4 bg-light">
    
    <nav class="navbar navbar-light bg-white shadow-sm mb-4 rounded">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Admin DKP - Proses Perizinan</span>
        </div>
    </nav>

    <div class="alert alert-warning border-start border-5 border-warning shadow-sm">
        <strong><i class="fas fa-exclamation-triangle"></i> PEMBERITAHUAN PENTING:</strong><br>
        Sebelum menyetujui kegiatan, harap pastikan:
        <ul class="mb-0">
            <li>Sudah memeriksa ketersediaan ruangan ke bagian SARPAS.</li>
            <li>Jadwal tidak bentrok dengan kegiatan universitas.</li>
        </ul>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light text-secondary"> 
                        <tr>
                            <th class="px-4">Kegiatan</th>
                            <th>Penyelenggara</th>
                            <th>Jadwal</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)) : ?>
                            <tr>
                                <td class="px-4 fw-bold">
                                    <?= htmlspecialchars($row['nama_kegiatan']); ?>
                                    <br>
                                    <small class="text-muted fw-normal">Anggaran: Rp<?= number_format($row['estimasi_anggaran']); ?></small>
                                </td>
                                <td><?= htmlspecialchars($row['penyelenggara']); ?></td>
                                <td>
                                    <?= date('d M', strtotime($row['tanggal_mulai'])); ?> - 
                                    <?= date('d M Y', strtotime($row['tanggal_selesai'])); ?>
                                </td>
                                <td>
                                    <?php 
                                        $status = $row['status_perizinan'];
                                        $badge = 'bg-secondary';
                                        if($status == 'Pengajuan Baru' || $status == 'Pengajuan Revisi') $badge = 'bg-info text-dark blink_me'; // Efek kedip agar admin notice
                                        if($status == 'Disetujui') $badge = 'bg-success';
                                        if($status == 'Revisi') $badge = 'bg-warning text-dark';
                                        if($status == 'Ditolak') $badge = 'bg-danger';
                                    ?>
                                    <span class="badge <?= $badge; ?> rounded-pill"><?= $status; ?></span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="../../uploads/<?= $row['file_proposal']; ?>" target="_blank" class="btn btn-sm btn-outline-primary" title="Lihat Proposal">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>

                                        <button type="button" class="btn btn-sm btn-dark" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalProses<?= $row['id']; ?>">
                                            <i class="fas fa-gavel"></i> Proses
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <div class="modal fade" id="modalProses<?= $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-dark text-white">
                                            <h5 class="modal-title">Proses: <?= htmlspecialchars($row['nama_kegiatan']); ?></h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="proses_persetujuan.php" method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                                
                                                <div class="mb-3">
                                                    <label class="fw-bold mb-1">Keputusan Admin:</label>
                                                    <select name="status" class="form-select" id="statusSelect<?= $row['id']; ?>" required onchange="cekStatus(<?= $row['id']; ?>)">
                                                        <option value="" disabled selected>-- Pilih Keputusan --</option>
                                                        <option value="Disetujui" class="text-success fw-bold">✅ SETUJUI (Selesai)</option>
                                                        <option value="Revisi" class="text-warning fw-bold">⚠️ Minta REVISI (Kembalikan ke Mhs)</option>
                                                        <option value="Ditolak" class="text-danger fw-bold">❌ TOLAK (Tidak bisa revisi)</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="fw-bold mb-1">Catatan / Alasan:</label>
                                                    <textarea name="catatan" id="catatanArea<?= $row['id']; ?>" class="form-control" rows="3" placeholder="Contoh: Anggaran terlalu besar, atau Tanggal bentrok..."></textarea>
                                                    <small class="text-danger fst-italic">*Wajib diisi jika status Revisi/Ditolak</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" name="simpan_keputusan" class="btn btn-primary">Simpan Keputusan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">Belum ada pengajuan kegiatan masuk.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function cekStatus(id) {
    var status = document.getElementById('statusSelect' + id).value;
    var catatan = document.getElementById('catatanArea' + id);
    
    if (status === 'Revisi' || status === 'Ditolak') {
        catatan.required = true; // Wajib isi jika ditolak/revisi
    } else {
        catatan.required = false;
    }
}
</script>

<?php include '../footer.php'; ?>