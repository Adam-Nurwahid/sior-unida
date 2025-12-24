<?php 
// Naik 2 tingkat (keluar dari 'profil', lalu keluar dari 'organisasi')
include '../../header.php'; 

// Naik 2 tingkat
include '../../sidebar.php'; 
?>

<div class="flex-grow-1 p-4 bg-light">
    
    <nav class="navbar navbar-light bg-white shadow-sm mb-4 rounded">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Periode Daftar Ulang</span>
        </div>
    </nav>

    <div class="card shadow-sm">
        <div class="card-body">
            
            <div class="mb-4">
                <button type="button" class="btn btn-primary fw-bold text-uppercase" data-bs-toggle="modal" data-bs-target="#modalTambahPeriode">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>

            <div class="mb-4 p-3 border rounded bg-white">
                <h5 class="fst-italic mb-3">Filter Pencarian</h5>
                <form action="" method="GET">
                    <div class="row mb-2">
                        <label for="search_nama" class="col-sm-3 col-form-label fw-bold">Nama Periode Daftar Ulang</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="search_nama" name="nama_periode" placeholder="Input nama...">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="search_tanggal" class="col-sm-3 col-form-label fw-bold">Periode Daftar Ulang</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" id="search_tanggal" name="tanggal_periode">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-success text-uppercase me-2">Cari</button>
                            <button type="reset" class="btn btn-warning text-white text-uppercase" style="background-color: #E6A666; border-color: #E6A666;">Batal</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="background-color: #ddd;">Jenis Berjalannya Periode</th>
                            <th>Nama Periode Daftar Ulang</th>
                            <th>Periode</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Sedang Berjalan</td>
                            <td>Daftar Ulang Semester Ganjil 2025</td>
                            <td>01 Jan 2025 - 30 Jan 2025</td>
                            <td>
                                <a href="#" class="btn btn-secondary btn-sm text-uppercase fw-bold">Lihat</a>
                            </td>
                        </tr>
                        <tr>
                            <td>Akan Datang</td>
                            <td>Daftar Ulang Semester Genap 2025</td>
                            <td>01 Jul 2025 - 30 Jul 2025</td>
                            <td>
                                <a href="#" class="btn btn-secondary btn-sm text-uppercase fw-bold">Lihat</a>
                                <a href="#" class="btn btn-warning btn-sm text-uppercase fw-bold">Update</a>
                                <a href="#" class="btn btn-danger btn-sm text-uppercase fw-bold">Delete</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div> 

<div class="modal fade" id="modalTambahPeriode" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalLabel">B-ORG 3.1 Tambah Periode Daftar Ulang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form action="proses_tambah.php" method="POST"> <p class="mb-3">Informasi Dasar</p>

                    <div class="mb-4">
                        <label for="namaPeriodeInput" class="form-label fw-bold">Nama Periode Daftar Ulang</label>
                        <input type="text" class="form-control" id="namaPeriodeInput" name="nama_periode" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Periode Daftar Ulang</label>
                        <div class="input-group">
                            <input type="date" class="form-control" name="tanggal_mulai" required>
                            <span class="input-group-text bg-white border-start-0 border-end-0">-</span>
                            <input type="date" class="form-control" name="tanggal_selesai" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-5">
                        <button type="button" class="btn btn-warning text-uppercase fw-bold" data-bs-dismiss="modal" style="background-color: #F4B084; border-color: #F4B084; color: black; min-width: 100px;">Batal</button>
                        <button type="submit" class="btn btn-success text-uppercase fw-bold" style="background-color: #A9D08E; border-color: #A9D08E; color: black; min-width: 100px;">Simpan</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php 
// Naik 2 tingkat
include '../../footer.php'; 
?>