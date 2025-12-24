<?php
// Cek Login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role']; // Ambil role dari session
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SIOR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        /* Styling Sederhana untuk Sidebar */
        #wrapper { display: flex; width: 100%; }
        #sidebar-wrapper { min-height: 100vh; margin-left: -15rem; transition: margin .25s ease-out; background: #343a40; color: white; }
        #sidebar-wrapper .sidebar-heading { padding: 0.875rem 1.25rem; font-size: 1.2rem; background: #212529; }
        #sidebar-wrapper .list-group-item { padding: 10px 20px; background: #343a40; color: #cfd2d6; border: none; }
        #sidebar-wrapper .list-group-item:hover { background: #495057; color: white; text-decoration: none; }
        #page-content-wrapper { width: 100%; }
        #wrapper.toggled #sidebar-wrapper { margin-left: 0; }
        .dropdown-toggle::after { float: right; margin-top: 8px; }
        /* Warna Submenu */
        .submenu { background: #454d55; padding-left: 20px; }
        @media (min-width: 768px) { #sidebar-wrapper { margin-left: 0; } #page-content-wrapper { min-width: 0; width: 100%; } #wrapper.toggled #sidebar-wrapper { margin-left: -15rem; } }
    </style>
</head>
<body>

<div class="d-flex" id="wrapper">
    <div class="border-end" id="sidebar-wrapper">
        <div class="sidebar-heading border-bottom">SIOR UNIDA</div>
        <div class="list-group list-group-flush">
            
            <a href="index.php" class="list-group-item list-group-item-action"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>

            <?php if ($role == 'dkp') : ?>
                
                <a href="#masterSubmenu" class="list-group-item list-group-item-action dropdown-toggle" data-bs-toggle="collapse">
                    <i class="bi bi-database me-2"></i> Master Data
                </a>
                <div class="collapse" id="masterSubmenu">
                    <a href="master_kegiatan.php" class="list-group-item list-group-item-action submenu">Jenis Kegiatan</a>
                    <a href="master_lokasi.php" class="list-group-item list-group-item-action submenu">Lokasi Kegiatan</a>
                    <a href="master_jabatan.php" class="list-group-item list-group-item-action submenu">Jenis Jabatan</a>
                    <a href="master_transport.php" class="list-group-item list-group-item-action submenu">Jenis Transportasi</a>
                    <a href="master_kepemilikan.php" class="list-group-item list-group-item-action submenu">Kepemilikan</a>
                    <a href="master_tipe_org.php" class="list-group-item list-group-item-action submenu">Tipe Organisasi</a>
                    <a href="master_bantuan.php" class="list-group-item list-group-item-action submenu">Jenis Bantuan</a>
                    <a href="master_bank.php" class="list-group-item list-group-item-action submenu">Nama Bank</a>
                </div>

                <a href="#orgSubmenu" class="list-group-item list-group-item-action dropdown-toggle" data-bs-toggle="collapse">
                    <i class="bi bi-building me-2"></i> Organisasi
                </a>
                <div class="collapse" id="orgSubmenu">
                    <a href="org_profil.php" class="list-group-item list-group-item-action submenu">Profil Organisasi</a>
                    <a href="org_pengurus.php" class="list-group-item list-group-item-action submenu">Pengurus</a>
                    <a href="org_periode.php" class="list-group-item list-group-item-action submenu">Periode Daftar Ulang</a>
                </div>

            <?php endif; ?>

            <?php if ($role == 'mahasiswa') : ?>
                <a href="profil_ukm.php" class="list-group-item list-group-item-action"><i class="bi bi-person-circle me-2"></i> Kelola Profil</a>
            <?php endif; ?>

            <a href="#sirajinSubmenu" class="list-group-item list-group-item-action dropdown-toggle" data-bs-toggle="collapse">
                <i class="bi bi-clipboard-check me-2"></i> SIRAJIN
            </a>
            <div class="collapse" id="sirajinSubmenu">
                <a href="sirajin_list.php" class="list-group-item list-group-item-action submenu">List Kegiatan</a>
                <?php if ($role == 'mahasiswa') : ?>
                    <a href="sirajin_tambah.php" class="list-group-item list-group-item-action submenu">Pengajuan Kegiatan</a>
                <?php endif; ?>
                <a href="sirajin_laporan.php" class="list-group-item list-group-item-action submenu">Laporan Kegiatan</a>
            </div>

            <a href="#sipandaiSubmenu" class="list-group-item list-group-item-action dropdown-toggle" data-bs-toggle="collapse">
                <i class="bi bi-cash-coin me-2"></i> SIPANDAI
            </a>
            <div class="collapse" id="sipandaiSubmenu">
                <a href="sipandai_list.php" class="list-group-item list-group-item-action submenu">List Dana Bantuan</a>
                <?php if ($role == 'mahasiswa') : ?>
                     <a href="sipandai_tambah.php" class="list-group-item list-group-item-action submenu">Ajukan Dana</a>
                <?php endif; ?>
            </div>

            <a href="logout.php" class="list-group-item list-group-item-action bg-danger text-white mt-4"><i class="bi bi-box-arrow-left me-2"></i> Logout</a>
        </div>
    </div>
    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom px-3">
            <button class="btn btn-primary" id="sidebarToggle"><i class="bi bi-list"></i></button>
            
            <div class="ms-auto d-flex align-items-center">
                <span class="navbar-text me-3">
                    Halo, <b><?php echo $_SESSION['nama']; ?></b> (<?php echo strtoupper($role); ?>)
                </span>
                </div>
        </nav>

        <div class="container-fluid py-4">
            ```

---

### 5. Halaman Dashboard (`index.php`)

[cite_start]Halaman ini menggabungkan layout dan menampilkan konten dashboard sesuai role[cite: 5, 7].

```php
<?php
require 'config.php';
include 'layout.php'; // Memuat Sidebar dan Header
?>

            <h2 class="mb-4">Dashboard</h2>

            <?php if ($role == 'dkp') : ?>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white mb-3">
                            <div class="card-body">
                                <h5 class="card-title">UKM Aktif</h5>
                                <p class="card-text display-4">12</p> </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Pengajuan Baru</h5>
                                <p class="card-text display-4">5</p>
                            </div>
                        </div>
                    </div>
                </div>

            <?php else : ?>
                <div class="alert alert-info" role="alert">
                    Selamat datang di Sistem Informasi Organisasi Kemahasiswaan (SIOR).
                    <br>Silakan lengkapi profil organisasi Anda sebelum mengajukan kegiatan.
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                         <div class="card mb-3">
                            <div class="card-header">Status Organisasi</div>
                            <div class="card-body">
                                <h5 class="card-title">Himpunan Mahasiswa TI</h5>
                                <p class="card-text"><span class="badge bg-success">Aktif</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div> </div> </div> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle Sidebar Script
    document.getElementById("sidebarToggle").onclick = function() {
        document.getElementById("wrapper").classList.toggle("toggled");
    };
</script>
</body>
</html>