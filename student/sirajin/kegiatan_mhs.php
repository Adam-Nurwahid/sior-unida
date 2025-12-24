<?php 
include '../../config.php'; // Sesuaikan path
include '../header.php'; 
include '../sidebar.php'; 

// Ganti 'Himpunan Mahasiswa Informatika' dengan SESSION login UKM
$organisasi_login = 'Himpunan Mahasiswa Informatika'; 

$query = "SELECT * FROM tb_kegiatan WHERE penyelenggara = '$organisasi_login' ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<div class="flex-grow-1 p-4 bg-light">
    <nav class="navbar navbar-light bg-white shadow-sm mb-4 rounded">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">SIRAJIN - List Kegiatan Mahasiswa</span>
        </div>
    </nav>

    <div class="mb-3">
        <a href="tambah_kegiatan.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Kegiatan
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Kegiatan</th>
                            <th>Estimasi Anggaran</th>
                            <th>Status Perizinan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['nama_kegiatan']); ?></td>
                            <td>Rp<?= number_format($row['estimasi_anggaran'], 0, ',', '.'); ?></td>
                            <td>
                                <?php 
                                    $badge = 'bg-secondary';
                                    if($row['status_perizinan'] == 'Disetujui') $badge = 'bg-success';
                                    if($row['status_perizinan'] == 'Revisi') $badge = 'bg-warning text-dark';
                                    if($row['status_perizinan'] == 'Ditolak') $badge = 'bg-danger';
                                ?>
                                <span class="badge <?= $badge; ?>"><?= $row['status_perizinan']; ?></span>
                            </td>
                            <td>
                                <a href="detail_kegiatan.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-info text-white">Detail</a>
                                <?php if($row['status_perizinan'] == 'Revisi') : ?>
                                    <a href="revisi_kegiatan.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning">Revisi</a>
                                <?php endif; ?>
                                <a href="hapus_kegiatan.php?id=<?= $row['id']; ?>" 
   class="btn btn-sm btn-danger" 
   onclick="return confirm('Anda Yakin Hapus? (Sesuai )')">
   <i class="fas fa-trash"></i> Hapus
</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>