<?php 
// File: student/sirajin/laporan_kegiatan.php
include '../../config.php'; 
include '../header.php'; 
include '../sidebar.php'; 

// SESSION LOGIN (Ganti sesuai session asli nanti)
$organisasi_login = 'Himpunan Mahasiswa Informatika'; 

// Hanya tampilkan kegiatan yang SUDAH DISETUJUI perizinannya 
$query = "SELECT * FROM tb_kegiatan 
          WHERE penyelenggara = '$organisasi_login' 
          AND status_perizinan = 'Disetujui' 
          ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<div class="flex-grow-1 p-4 bg-light">
    <nav class="navbar navbar-light bg-white shadow-sm mb-4 rounded">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Laporan Kegiatan Mahasiswa (LPJ)</span>
        </div>
    </nav>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Daftar ini hanya menampilkan kegiatan yang status perizinannya sudah <strong>DISETUJUI</strong>.
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Kegiatan</th>
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
                                    <strong><?= htmlspecialchars($row['nama_kegiatan']); ?></strong><br>
                                    <small class="text-muted">Pelaksanaan: <?= $row['tanggal_mulai']; ?></small>
                                </td>
                                <td>
                                    <?php 
                                        $st_lpj = $row['status_lpj'];
                                        $cls = 'bg-secondary';
                                        if($st_lpj == 'Menunggu Review') $cls = 'bg-warning text-dark';
                                        if($st_lpj == 'Laporan Disetujui') $cls = 'bg-success';
                                        if($st_lpj == 'Laporan Revisi') $cls = 'bg-danger';
                                    ?>
                                    <span class="badge <?= $cls; ?>"><?= $st_lpj; ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if($st_lpj == 'Belum Lapor' || $st_lpj == 'Laporan Revisi'): ?>
                                        <a href="input_laporan.php?id=<?= $row['id']; ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-upload"></i> Lapor Kegiatan
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-success btn-sm" disabled>
                                            <i class="fas fa-check"></i> Sudah Upload
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center">Belum ada kegiatan yang disetujui untuk dilaporkan.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>