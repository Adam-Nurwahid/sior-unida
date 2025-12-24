<?php 
// --- 1. INCLUDE HEADER & SIDEBAR ---
include '../../../config.php'; 
include '../../header.php';   // header ada di folder admin (naik 1 folder)
include '../../sidebar.php';  // sidebar ada di folder admin (naik 1 folder)

// --- 2. QUERY DATA DARI DATABASE ---
$query = "SELECT * FROM master_jenis_jabatan ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<style>
    /* Override background utama agar gelap sesuai gambar */
    .content-area {
        background-color: #1e1e1e !important;
        color: #ffffff;
        min-height: 100vh;
    }
    /* Style Navbar agar gelap transparan */
    .custom-navbar {
        background-color: #2c2c2c !important;
        color: white !important;
        border-bottom: 1px solid #444;
    }
    .custom-navbar span {
        color: white !important;
    }
    /* Style Tabel */
    .table-dark-custom thead th {
        background-color: #000000; /* Hitam pekat */
        color: white;
        border-bottom: 2px solid #555;
    }
    .table-dark-custom tbody td {
        background-color: #2c2c2c; /* Abu gelap */
        color: white;
        border-color: #444;
    }
    /* Tombol Tambah */
    .btn-tambah {
        background-color: #5b7c99;
        color: white;
        border: 1px solid white;
        padding: 8px 25px;
        border-radius: 0;
    }
    .btn-tambah:hover {
        background-color: #4a6b88;
        color: white;
    }
  
</style>

<div class="flex-grow-1 p-4 bg-light">
    
    <nav class="navbar navbar-light bg-white shadow-sm mb-4 rounded">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Jenis Jabatan</span>
        </div>
    </nav>

    <div class="mb-3">
        <button type="button" class="btn btn-primary" onclick="bukaModalTambah()">
            <i class="fas fa-plus"></i> Tambah Data
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-dark"> <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="15%">Kode</th>
                            <th>Nama Jenis Kegiatan</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result)) :
                        ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
        <td><?= $row['kode']; ?></td>
        <td><?= $row['nama_jenis_jabatan']; ?></td> 
        <td class="text-center">
            <button class="btn btn-sm btn-warning text-white" 
                    onclick="bukaModalEdit('<?= $row['id']; ?>', '<?= $row['kode']; ?>', '<?= $row['nama_jenis_jabatan']; ?>')">
                <i class="fas fa-edit"></i>
            </button>
            <a href="hapus.php?id=<?= $row['id']; ?>&type=jenis_jabatan" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data ini?')">
                <i class="fas fa-trash"></i>
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

<div class="modal fade" id="modalForm" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Form Jenis Kegiatan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <form action="proses_simpan.php" method="POST">
          <div class="modal-body">
            
            <input type="hidden" name="id" id="inputId">

            <div class="mb-3">
                <label for="inputKode" class="form-label">Kode</label>
                <input type="text" class="form-control" id="inputKode" name="kode" required placeholder="Contoh: K001">
            </div>
            
            <div class="mb-3">
                <label for="inputNama" class="form-label">Nama Jenis Kegiatan</label>
                <input type="text" class="form-control" id="inputNama" name="nama" required placeholder="Masukkan nama kegiatan">
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary" name="simpan">Simpan</button>
          </div>
      </form>

    </div>
  </div>
</div>

<?php 
// --- 5. INCLUDE FOOTER ---
include '../../footer.php'; 
?>

<script>
    // Inisialisasi Modal Bootstrap 5
    var modalForm = new bootstrap.Modal(document.getElementById('modalForm'));

    // Fungsi Buka Modal Tambah
    function bukaModalTambah() {
        document.getElementById('modalLabel').innerText = "Tambah Jenis Kegiatan";
        document.getElementById('inputId').value = '';
        document.getElementById('inputKode').value = '';
        document.getElementById('inputNama').value = '';
        modalForm.show();
    }

    // Fungsi Buka Modal Edit
    function bukaModalEdit(id, kode, nama) {
        document.getElementById('modalLabel').innerText = "Edit Jenis Kegiatan";
        document.getElementById('inputId').value = id;
        document.getElementById('inputKode').value = kode;
        document.getElementById('inputNama').value = nama;
        modalForm.show();
    }
</script>