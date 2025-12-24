<?php
// admin_dana.php
include '../header.php'; 
include '../sidebar.php'; 
include '../../config.php';

// --- LOGIKA UPDATE STATUS ---
if (isset($_POST['update_status'])) {
    $id = $_POST['id'];
    $status = $_POST['status']; // 'disetujui', 'dikembalikan', 'ditolak'
    $catatan = $_POST['catatan'];
    
    $query = "UPDATE dana_bantuan SET status = '$status', catatan_admin = '$catatan' WHERE id = '$id'";
    mysqli_query($conn, $query);
    echo "<script>alert('Status berhasil diperbarui!'); window.location='dana.php';</script>";
}

// --- LOGIKA HAPUS ---
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM dana_bantuan WHERE id = '$id'"); // Cascade delete akan menghapus rencana & pj
    echo "<script>window.location='dana.php';</script>";
}
?>

<div class="flex-grow-1 p-4 bg-light">
    <h3>Kelola Data Dana Bantuan (DKP)</h3>
    <p class="text-muted">SIPANDAI - Sistem Informasi Pendanaan</p>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">Daftar Pengajuan Masuk</div>
        <div class="card-body">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Judul & Jenis</th>
                        <th>Total Pengajuan</th>
                        <th>Tgl Pengajuan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q = mysqli_query($conn, "SELECT * FROM dana_bantuan ORDER BY tanggal_pengajuan DESC");
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($q)) {
                        // Hitung Total Dana dari tabel relasi
                        $q_total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as total FROM dana_rencana WHERE dana_id='".$row['id']."'"));
                        $total_dana = $q_total['total'] ?: 0;
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td>
                            <strong><?= $row['judul_bantuan']; ?></strong><br>
                            <span class="badge bg-secondary"><?= $row['jenis_bantuan']; ?></span>
                        </td>
                        <td>Rp <?= number_format($total_dana); ?></td>
                        <td><?= date('d M Y H:i', strtotime($row['tanggal_pengajuan'])); ?></td>
                        <td>
                            <?php 
                                if($row['status']=='pending') echo '<span class="badge bg-warning text-dark">Pending</span>';
                                elseif($row['status']=='disetujui') echo '<span class="badge bg-success">Disetujui</span>';
                                elseif($row['status']=='dikembalikan') echo '<span class="badge bg-info">Dikembalikan</span>';
                                elseif($row['status']=='ditolak') echo '<span class="badge bg-danger">Ditolak</span>';
                            ?>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#modalDetail<?= $row['id']; ?>"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalAksi<?= $row['id']; ?>"><i class="fas fa-edit"></i></button>
                            <a href="?hapus=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>

                    <div class="modal fade" id="modalDetail<?= $row['id']; ?>" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detail Pengajuan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <h6>Deskripsi</h6>
                                    <p><?= nl2br($row['deskripsi']); ?></p>
                                    
                                    <h6 class="mt-3">Rencana Dana</h6>
                                    <ul class="list-group mb-3">
                                        <?php 
                                        $q_rencana = mysqli_query($conn, "SELECT * FROM dana_rencana WHERE dana_id='".$row['id']."'");
                                        while($r = mysqli_fetch_assoc($q_rencana)){
                                            echo "<li class='list-group-item d-flex justify-content-between'><span>{$r['nama_kegiatan']}</span> <span>Rp ".number_format($r['nominal'])."</span></li>";
                                        }
                                        ?>
                                    </ul>

                                    <h6>Penanggung Jawab</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <tr><th>Jabatan</th><th>Nama</th><th>NIM</th><th>No HP</th></tr>
                                            <?php 
                                            $q_pj = mysqli_query($conn, "SELECT * FROM dana_pj WHERE dana_id='".$row['id']."'");
                                            while($pj = mysqli_fetch_assoc($q_pj)){
                                                echo "<tr><td>{$pj['jabatan']}</td><td>{$pj['nama']}</td><td>{$pj['nim']}</td><td>{$pj['no_hp']}</td></tr>";
                                            }
                                            ?>
                                        </table>
                                    </div>
                                    <div class="mt-3">
                                        <strong>Rekening:</strong> <?= $row['bank_nama']; ?> - <?= $row['bank_rekening']; ?> (a.n <?= $row['bank_nim']; ?>)
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modalAksi<?= $row['id']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Proses Pengajuan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST">
                                    <div class="modal-body">
                                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                        <div class="mb-3">
                                            <label>Status Keputusan</label>
                                            <select name="status" class="form-select" required>
                                                <option value="disetujui">Setujui (Approved)</option>
                                                <option value="dikembalikan">Kembalikan (Bisa Direvisi)</option>
                                                <option value="ditolak">Tolak (Final/Tidak Bisa Revisi)</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label>Catatan Admin</label>
                                            <textarea name="catatan" class="form-control" rows="3" required placeholder="Alasan penolakan atau catatan revisi..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="update_status" class="btn btn-primary">Simpan Keputusan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include '../footer.php'; ?>