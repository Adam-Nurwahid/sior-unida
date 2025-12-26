<?php 
// File: admin/sirajin/laporan_admin.php
include '../../config.php'; 
include '../header.php'; 
include '../sidebar.php'; 

// Query: Ambil semua kegiatan yang status LPJ-nya SUDAH ada pergerakan (bukan 'Belum Lapor')
$query = "SELECT * FROM tb_kegiatan 
          WHERE status_lpj != 'Belum Lapor' 
          ORDER BY FIELD(status_lpj, 'Menunggu Review', 'Laporan Revisi', 'Laporan Disetujui'), created_at DESC";

$result = mysqli_query($conn, $query);
?>

<div class="flex-grow-1 p-4 bg-light">
    
    <nav class="navbar navbar-light bg-white shadow-sm mb-4 rounded">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Admin - Pemeriksaan Laporan (LPJ)</span>
        </div>
    </nav>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>No</th>
                            <th>Nama Kegiatan</th>
                            <th>Penyelenggara</th>
                            <th>Status LPJ</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($result) > 0): ?>
                            <?php $no = 1; while($row = mysqli_fetch_assoc($result)) : ?>
                            <tr>
                                <td class="text-center"><?= $no++; ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($row['nama_kegiatan']); ?></strong>
                                    <br><small class="text-muted">Tgl: <?= $row['tanggal_mulai']; ?></small>
                                </td>
                                <td><?= htmlspecialchars($row['penyelenggara']); ?></td>
                                <td>
                                    <?php 
                                        $s = $row['status_lpj'];
                                        $badge = 'bg-secondary';
                                        if($s == 'Menunggu Review') $badge = 'bg-warning text-dark blink_me';
                                        if($s == 'Laporan Disetujui') $badge = 'bg-success';
                                        if($s == 'Laporan Revisi') $badge = 'bg-danger';
                                    ?>
                                    <span class="badge <?= $badge; ?>"><?= $s; ?></span>
                                </td>
                                <td class="text-center">
                                    <a href="detail_laporan_admin.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-dark">
                                        <i class="fas fa-search"></i> Periksa Laporan
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center py-4">Belum ada laporan masuk.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
/* Efek kedip halus untuk status Menunggu Review */
.blink_me {
  animation: blinker 1.5s linear infinite;
}
@keyframes blinker {
  50% { opacity: 0.6; }
}
</style>

<?php include '../footer.php'; ?>