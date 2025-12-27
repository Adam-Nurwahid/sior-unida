<?php
session_start();
// Mundur 2 folder untuk koneksi (karena file ada di folder sirajin)
include '../koneksi.php'; 

// 1. Cek Login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// 2. CEK PROFIL ORGANISASI (Hanya untuk Client/UKM)
// Sesuai dokumen: Akses dilarang jika profil belum diisi
if ($role != 'admin') {
    $cek_profil = mysqli_query($koneksi, "SELECT * FROM profil_organisasi WHERE user_id='$user_id'");
    if (mysqli_num_rows($cek_profil) == 0) {
        echo "<script>
            alert('PEMBERITAHUAN: Anda harus melengkapi Profil Organisasi terlebih dahulu sebelum mengakses menu Kegiatan.');
            window.location.href='../organisasi/org_profil.php';
        </script>";
        exit;
    }
}

// 3. QUERY DATA KEGIATAN BERDASARKAN ROLE
if ($role == 'admin') {
    // ADMIN: Melihat SEMUA data, join ke tabel users untuk tahu siapa yang mengajukan
    $query = "SELECT k.*, u.nama_lengkap AS nama_ormawa, j.nama AS jenis_kegiatan 
              FROM trx_kegiatan k
              JOIN users u ON k.user_id = u.id
              LEFT JOIN mst_jenis_kegiatan j ON k.id_jenis_kegiatan = j.id
              ORDER BY k.id DESC";
} else {
    // CLIENT: Hanya melihat data MILIKNYA SENDIRI
    $query = "SELECT k.*, u.nama_lengkap AS nama_ormawa, j.nama AS jenis_kegiatan 
              FROM trx_kegiatan k
              JOIN users u ON k.user_id = u.id
              LEFT JOIN mst_jenis_kegiatan j ON k.id_jenis_kegiatan = j.id
              WHERE k.user_id = '$user_id'
              ORDER BY k.id DESC";
}

$result = mysqli_query($koneksi, $query);

// Fungsi untuk warna status badge
function getStatusBadge($status) {
    switch ($status) {
        case 'Draft': return 'secondary';
        case 'Pengajuan Baru': return 'primary';
        case 'Sedang Diproses': return 'info text-dark';
        case 'Perlu Revisi': return 'warning text-dark';
        case 'Disetujui': return 'success';
        case 'Ditolak': return 'danger';
        default: return 'light text-dark';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Data Kegiatan Mahasiswa - SIRAJIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="d-flex">
    
    <?php include '../sidebar.php'; ?>

    <div class="p-4 w-100 bg-light" style="height: 100vh; overflow-y: auto;">
        <div class="container-fluid">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-primary"><i class="bi bi-calendar-event me-2"></i>Data Kegiatan Mahasiswa</h2>
                
                <?php if($role != 'admin') { ?>
                    <a href="sirajin_tambah.php" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-2"></i>Tambah Kegiatan
                    </a>
                <?php } ?>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-dark">List Daftar Kegiatan</h6>
                </div>
                <div class="card-body">
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama Kegiatan</th>
                                    <th>Jenis</th>
                                    <?php if($role == 'admin') { ?> <th>Penyelenggara</th> <?php } ?>
                                    <th>Tanggal Pelaksanaan</th>
                                    <th>Anggaran (Est)</th>
                                    <th>Status Perizinan</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $tgl = date('d M Y', strtotime($row['tgl_mulai']));
                                        if($row['tgl_mulai'] != $row['tgl_selesai']){
                                            $tgl .= ' - ' . date('d M Y', strtotime($row['tgl_selesai']));
                                        }
                                        $rp = "Rp " . number_format($row['estimasi_biaya'], 0, ',', '.');
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td class="fw-bold"><?php echo $row['nama_kegiatan']; ?></td>
                                    <td><?php echo $row['jenis_kegiatan']; ?></td>
                                    
                                    <?php if($role == 'admin') { ?> 
                                        <td><?php echo $row['nama_ormawa']; ?></td> 
                                    <?php } ?>

                                    <td><small><?php echo $tgl; ?></small></td>
                                    <td><?php echo $rp; ?></td>
                                    
                                    <td class="text-center">
                                        <span class="badge bg-<?php echo getStatusBadge($row['status_perizinan']); ?>">
                                            <?php echo $row['status_perizinan']; ?>
                                        </span>
                                    </td>
                                    
                                    <td class="text-center">
                                        <a href="sirajin_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info text-white mb-1" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <?php if($role != 'admin') { ?>
                                            <?php if(in_array($row['status_perizinan'], ['Draft', 'Perlu Revisi'])) { ?>
                                                <a href="sirajin_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning text-white mb-1" title="Edit/Revisi">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            <?php } ?>

                                            <?php if($row['status_perizinan'] == 'Draft') { ?>
                                                <a href="sirajin_hapus.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Yakin hapus?')" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            <?php } ?>

                                        <?php } else { ?>
                                            <a href="sirajin_proses.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success mb-1" title="Proses Perizinan">
                                                <i class="bi bi-check-circle"></i> Proses
                                            </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php 
                                    } 
                                } else {
                                    $colspan = ($role == 'admin') ? 8 : 7;
                                    echo "<tr><td colspan='$colspan' class='text-center text-muted py-4'>Belum ada data kegiatan.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>