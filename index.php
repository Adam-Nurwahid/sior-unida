<?php
session_start();
include 'koneksi.php'; // Panggil koneksi di paling atas

// 1. Cek Login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:login.php");
    exit;
}

// 2. Logic Cek Periode Daftar Ulang
$q_periode = mysqli_query($koneksi, "SELECT * FROM mst_periode WHERE id='1' AND status_aktif='1'");
$per = mysqli_fetch_array($q_periode);

$show_alert = false;
$periode_tahun = "";

if ($per) {
    $now = date('Y-m-d H:i:s');
    $periode_tahun = $per['tahun'];
    // Cek apakah hari ini ada dalam rentang tanggal mulai & selesai
    if ($now >= $per['tgl_mulai'] && $now <= $per['tgl_selesai']) {
        $show_alert = true;
    }
}

// 3. Logic Statistik Dashboard (Contoh Sederhana)
// Hitung jumlah user (Organisasi)
$query_ukm = mysqli_query($koneksi, "SELECT * FROM users WHERE role != 'admin'");
$jml_ukm = mysqli_num_rows($query_ukm);

// Hitung jumlah profil yang sudah diisi
$query_profil_isi = mysqli_query($koneksi, "SELECT * FROM profil_organisasi");
$jml_profil = mysqli_num_rows($query_profil_isi);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard SIOR UNIDA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

    <div class="d-flex">
        
        <?php include 'sidebar.php'; ?>

        <div class="p-4 w-100 bg-light" style="min-height: 100vh;">
            <div class="container-fluid">
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold text-dark">Dashboard</h2>
                        <p class="text-muted">Selamat Datang, <strong><?php echo $_SESSION['nama_lengkap']; ?></strong> (<?php echo ucfirst($_SESSION['role']); ?>)</p>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-primary fs-6">
                            <i class="bi bi-calendar-event me-1"></i> <?php echo date('d F Y'); ?>
                        </span>
                    </div>
                </div>

                <?php if ($show_alert && $_SESSION['role'] != 'admin'): ?>
                <div class="alert alert-warning shadow-sm border-warning d-flex align-items-start" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-4 me-3 mt-1"></i>
                    <div>
                        <h4 class="alert-heading fw-bold">Pemberitahuan!</h4>
                        <p class="mb-2">
                            Periode ini adalah masa periode daftar ulang UKM tahun <strong><?php echo $periode_tahun; ?></strong>. 
                            Harap lengkapi <strong>Profil Organisasi</strong> dan <strong>Susunan Pengurus</strong> dengan data terbaru untuk dapat mengakses layanan sistem (Surat & Dana).
                        </p>
                        <hr>
                        <a href="organisasi/org_profil.php" class="btn btn-dark btn-sm">
                            <i class="bi bi-pencil-square me-1"></i> Lengkapi Profil Sekarang
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <div class="row">
                    
                    <div class="col-md-4 mb-3">
                        <div class="card text-white bg-primary shadow-sm h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="me-3">
                                    <i class="bi bi-people-fill display-4"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-0">Total Organisasi</h5>
                                    <p class="card-text fs-2 fw-bold"><?php echo $jml_ukm; ?></p>
                                    <small>Akun Terdaftar</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="card text-white bg-success shadow-sm h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="me-3">
                                    <i class="bi bi-file-earmark-check-fill display-4"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-0">Profil Lengkap</h5>
                                    <p class="card-text fs-2 fw-bold"><?php echo $jml_profil; ?></p>
                                    <small>Organisasi telah update profil</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="card text-white <?php echo $show_alert ? 'bg-warning' : 'bg-secondary'; ?> shadow-sm h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="me-3">
                                    <i class="bi bi-clock-history display-4"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-0">Periode Daftar Ulang</h5>
                                    <p class="card-text fs-4 fw-bold mt-2">
                                        <?php echo $show_alert ? "SEDANG AKTIF" : "TUTUP"; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-white fw-bold">
                        <i class="bi bi-info-circle me-2"></i> Informasi Sistem
                    </div>
                    <div class="card-body">
                        <p>Selamat datang di Sistem Informasi Organisasi Mahasiswa (SIOR) Universitas Darussalam Gontor.</p>
                        <ul>
                            <li>Gunakan menu <strong>Master Data</strong> untuk mengelola referensi sistem (Khusus Admin).</li>
                            <li>Gunakan menu <strong>Organisasi</strong> untuk melengkapi data profil dan kepengurusan.</li>
                            <li>Menu <strong>SIRAJIN</strong> dan <strong>SIPANDAI</strong> akan terbuka setelah profil dilengkapi.</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>