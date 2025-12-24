<style>
    /* CSS Tambahan Khusus Sidebar agar rapi */
    .sidebar {
        height: 100vh; /* Full height */
        overflow-y: auto; /* Bisa discroll jika menu panjang */
        background-color: #212529;
    }
    
    /* Style untuk link di dalam sidebar */
    .sidebar .nav-link {
        color: #adb5bd;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .sidebar .nav-link:hover {
        color: #fff;
        background-color: rgba(255,255,255,0.1);
    }

    .sidebar .nav-link.active {
        color: #fff;
        background-color: #0d6efd;
    }

    /* Indentasi untuk Sub-menu agar terlihat menjorok ke dalam */
    .sub-menu {
        background-color: rgba(0, 0, 0, 0.2); /* Sedikit lebih gelap */
        padding-left: 20px;
    }
    
    /* Ukuran font icon chevron kecil */
    .fa-chevron-down {
        font-size: 0.8rem;
        transition: transform 0.3s;
    }
    
    /* Putar panah saat menu terbuka (opsional, butuh JS tambahan/CSS logic) */
    .nav-link[aria-expanded="true"] .fa-chevron-down {
        transform: rotate(180deg);
    }
</style>

<div class="sidebar p-3 d-flex flex-column flex-shrink-0 text-white" style="width: 250px;">
    
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <i class="fa-solid fa-sitemap me-2 fs-4"></i>
        <span class="fs-4">Organisasi</span>
    </a>
    <hr>
    
    <ul class="nav nav-pills flex-column mb-auto">
        
        <li class="nav-item mb-1">
            <a href="/sior-unida/student/index.php" class="nav-link active">
                <div>
                    <i class="fa-solid fa-home me-2" style="width:20px"></i> Dashboard
                </div>
            </a>
        </li>

        <li class="nav-item mb-1">
            <a href="#menuProduk" data-bs-toggle="collapse" class="nav-link text-white" aria-expanded="false">
                <div>
                    <i class="fa-solid fa-sitemap me-2" style="width:20px"></i> Organisasi
                </div>
                <i class="fa-solid fa-chevron-down"></i>
            </a>
            <div class="collapse" id="menuProduk">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a href="/sior-unida/student/organisasi/profil/profil.php" class="nav-link text-white">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a href="/sior-unida/student/organisasi/pengurus/pengurus.php" class="nav-link text-white">Pengurus</a>
                    </li>
                    <li class="nav-item">
                        <a href="/sior-unida/student/organisasi/periode_daftar_ulang/daftar_ulang.php" class="nav-link text-white">Periode Daftar Ulang</a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item mb-1">
            <a href="#menuTransaksi" data-bs-toggle="collapse" class="nav-link text-white" aria-expanded="false">
                <div>
                    <i class="fa-solid fa-clipboard-list me-2" style="width:20px"></i> SIRAJIN
                </div>
                <i class="fa-solid fa-chevron-down"></i>
            </a>
            <div class="collapse" id="menuTransaksi">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a href="/sior-unida/student/sirajin/kegiatan_mhs.php" class="nav-link text-white">Data Kegiatan Mahasiswa</a>
                    </li>
                    <li class="nav-item">
                        <a href="/sior-unida/student/sirajin/proses_kegiatan.php" class="nav-link text-white">Proses Data Kegiatan Mahasiswa </a>
                    </li>
                    <li class="nav-item">
                        <a href="/sior-unida/student/sirajin/laporan_kegiatan.php" class="nav-link text-white">Laporan Data Kegiatan Mahasiswa</a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item mb-1">
            <a href="/sior-unida/student/danabantuan/dana.php" class="nav-link text-white">
                <div>
                    <i class="fa-solid fa-hand-holding-dollar me-2" style="width:20px"></i> Dana Bantuan
                </div>
            </a>
        </li>

        <li class="nav-item mb-1">
            <a href="#menuSettings" data-bs-toggle="collapse" class="nav-link text-white" aria-expanded="false">
                <div>
                    <i class="fa-solid fa-gear me-2" style="width:20px"></i> Pengaturan
                </div>
                <i class="fa-solid fa-chevron-down"></i>
            </a>
            <div class="collapse" id="menuSettings">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white">Profil Toko</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white">Manajemen Akun</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white">Backup Database</a>
                    </li>
                </ul>
            </div>
        </li>

    </ul>
    
    <hr>
    
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
            <strong>Admin</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Sign out</a></li>
        </ul>
    </div>
</div>