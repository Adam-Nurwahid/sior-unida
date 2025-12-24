<?php 
// File: student/sirajin/detail_kegiatan.php
include '../../config.php'; // Naik 2 tingkat ke root
include '../header.php'; 
include '../sidebar.php'; 

// Ambil ID dari URL
$id = $_GET['id'];

// Query Data Kegiatan berdasarkan ID
$query = "SELECT * FROM tb_kegiatan WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='kegiatan_mhs.php';</script>";
    exit;
}
?>

<div class="flex-grow-1 p-4 bg-light">
    <nav class="navbar navbar-light bg-white shadow-sm mb-4 rounded">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Detail Kegiatan Mahasiswa</span>
        </div>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Dasar</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="30%" class="fw-bold">Nama Kegiatan</td>
                            <td>: <?= htmlspecialchars($data['nama_kegiatan']); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Penyelenggara</td>
                            <td>: <?= htmlspecialchars($data['penyelenggara']); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Jenis Kegiatan</td>
                            <td>: <?= htmlspecialchars($data['jenis_kegiatan']); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Lokasi</td>
                            <td>: <?= htmlspecialchars($data['lokasi_kegiatan']); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Tanggal Pelaksanaan</td>
                            <td>: <?= date('d M Y', strtotime($data['tanggal_mulai'])); ?> s/d <?= date('d M Y', strtotime($data['tanggal_selesai'])); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Estimasi Anggaran</td>
                            <td>: Rp<?= number_format($data['estimasi_anggaran'], 0, ',', '.'); ?></td>
                        </tr>
                    </table>

                    <hr>
                    <h6 class="fw-bold">Deskripsi Kegiatan:</h6>
                    <p class="text-muted"><?= nl2br(htmlspecialchars($data['deskripsi'])); ?></p>
                    
                    <h6 class="fw-bold">Tujuan Kegiatan:</h6>
                    <p class="text-muted"><?= nl2br(htmlspecialchars($data['tujuan'])); ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Status & History</h5>
                </div>
                <div class="card-body">
                    <div class="alert <?php 
                        if($data['status_perizinan'] == 'Disetujui') echo 'alert-success';
                        elseif($data['status_perizinan'] == 'Ditolak') echo 'alert-danger';
                        else echo 'alert-secondary'; 
                    ?>">
                        <strong>Status Terkini:</strong> <br>
                        <?= $data['status_perizinan']; ?>
                    </div>

                    <?php if(!empty($data['catatan_admin'])): ?>
                    <div class="alert alert-info">
                        <strong>Catatan Admin:</strong> <br>
                        <?= htmlspecialchars($data['catatan_admin']); ?>
                    </div>
                    <?php endif; ?>

                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Pengajuan Baru
                            <span class="text-muted"><?= substr($data['created_at'], 0, 10); ?></span>
                        </li>
                        </ul>
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-body text-center">
                    <h6 class="card-title fw-bold">File Proposal</h6>
                    <p class="card-text text-muted small"><?= $data['file_proposal']; ?></p>
                    <a href="../../uploads/<?= $data['file_proposal']; ?>" target="_blank" class="btn btn-outline-primary w-100">
                        <i class="fas fa-download"></i> Download Proposal
                    </a>
                </div>
            </div>

            <div class="d-grid gap-2">
                <a href="kegiatan_mhs.php" class="btn btn-secondary">KEMBALI</a>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>