<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base_url = "http://localhost/sior-unida"; 

// Mendapatkan URL saat ini untuk penanda menu aktif
$current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>

<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px; min-height: 100vh; overflow-y: auto;">
    <a href="<?php echo $base_url; ?>/index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <span class="fs-4 fw-bold">SIOR UNIDA</span>
    </a>
    <hr>
    
    <ul class="nav nav-pills flex-column mb-auto">
        
        <li class="nav-item mb-1">
            <a href="<?php echo $base_url; ?>/index.php" class="nav-link text-white <?php echo strpos($current_url, '/index.php') !== false ? 'active bg-primary' : ''; ?>">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>

        <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin') { ?> 
        
        <li class="nav-item mb-1">
            <a href="#menuMaster" data-bs-toggle="collapse" class="nav-link text-white dropdown-toggle">
                <i class="bi bi-database me-2"></i> Master Data
            </a>
            <div class="collapse <?php echo strpos($current_url, '/master/') !== false ? 'show' : ''; ?>" id="menuMaster">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ms-4">
                    
                    <li><a href="<?php echo $base_url; ?>/master/kegiatan/mst_kegiatan.php" class="nav-link text-white-50">Jenis Kegiatan</a></li>
                    <li><a href="<?php echo $base_url; ?>/master/lokasi/mst_lokasi.php" class="nav-link text-white-50">Lokasi Kegiatan</a></li>
                    
                    <li><a href="<?php echo $base_url; ?>/master/jabatan/mst_jabatan.php" class="nav-link text-white-50">Jenis Jabatan</a></li>
                    <li><a href="<?php echo $base_url; ?>/master/transport/mst_transport.php" class="nav-link text-white-50">Jenis Transportasi</a></li>
                    <li><a href="<?php echo $base_url; ?>/master/kepemilikan/mst_kepemilikan.php" class="nav-link text-white-50">Kepemilikan</a></li>
                    <li><a href="<?php echo $base_url; ?>/master/tipe_org/mst_tipe_org.php" class="nav-link text-white-50">Tipe Organisasi</a></li>
                    <li><a href="<?php echo $base_url; ?>/master/bantuan/mst_bantuan.php" class="nav-link text-white-50">Jenis Bantuan</a></li>
                    <li><a href="<?php echo $base_url; ?>/master/bank/mst_bank.php" class="nav-link text-white-50">Nama Bank</a></li>
                </ul>
            </div>
        </li>

        <?php } ?>

        <li class="nav-item mb-1">
            <a href="#menuOrganisasi" data-bs-toggle="collapse" class="nav-link text-white dropdown-toggle">
                <i class="bi bi-people me-2"></i> Organisasi
            </a>
            <div class="collapse <?php echo strpos($current_url, '/organisasi/') !== false || strpos($current_url, 'org_') !== false ? 'show' : ''; ?>" id="menuOrganisasi">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ms-4">
                    <li><a href="<?php echo $base_url; ?>/organisasi/org_profil.php" class="nav-link text-white-50">Profil Organisasi</a></li>
                    <li><a href="<?php echo $base_url; ?>/organisasi/org_pengurus.php" class="nav-link text-white-50">Pengurus Organisasi</a></li>
                    <li><a href="<?php echo $base_url; ?>/organisasi/org_periode.php" class="nav-link text-white-50">Periode Daftar Ulang</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item mb-1">
            <a href="#menuSirajin" data-bs-toggle="collapse" class="nav-link text-white dropdown-toggle">
                <i class="bi bi-calendar-check me-2"></i> SIRAJIN
            </a>
            <div class="collapse <?php echo strpos($current_url, '/sirajin/') !== false || strpos($current_url, 'sirajin_') !== false ? 'show' : ''; ?>" id="menuSirajin">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ms-4">
                    <li><a href="<?php echo $base_url; ?>/sirajin/sirajin_list.php" class="nav-link text-white-50">Data Kegiatan Mhs</a></li>
                    <li><a href="<?php echo $base_url; ?>/sirajin/sirajin_proses.php" class="nav-link text-white-50">Proses Data Kegiatan</a></li>
                    <li><a href="<?php echo $base_url; ?>/sirajin/sirajin_laporan.php" class="nav-link text-white-50">Laporan Kegiatan</a></li>
                    <li><a href="<?php echo $base_url; ?>/sirajin/sirajin_download.php" class="nav-link text-white-50">Download Ringkasan</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item mb-1">
            <a href="#menuSipandai" data-bs-toggle="collapse" class="nav-link text-white dropdown-toggle">
                <i class="bi bi-cash-coin me-2"></i> SIPANDAI
            </a>
            <div class="collapse <?php echo strpos($current_url, '/sipandai/') !== false || strpos($current_url, 'sipandai_') !== false ? 'show' : ''; ?>" id="menuSipandai">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ms-4">
                     <li><a href="<?php echo $base_url; ?>/sipandai/sipandai_list.php" class="nav-link text-white-50">Dana Bantuan Mhs</a></li>
                    <li><a href="<?php echo $base_url; ?>/sipandai/sipandai_download.php" class="nav-link text-white-50">Download Ringkasan</a></li>
                </ul>
            </div>
        </li>

    </ul>
    
    <hr>
    
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://via.placeholder.com/32" alt="" width="32" height="32" class="rounded-circle me-2">
            <strong><?php echo isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : 'User'; ?></strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="<?php echo $base_url; ?>/logout.php">Sign out</a></li>
        </ul>
    </div>
</div>