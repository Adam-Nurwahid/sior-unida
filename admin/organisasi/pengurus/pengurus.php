<?php 
// Naik 2 tingkat
include '../../header.php'; 
include '../../sidebar.php'; 
?>

<div class="flex-grow-1 p-4 bg-light">
    
    <nav class="navbar navbar-light bg-white shadow-sm mb-4 rounded">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Edit Kepengurusan Organisasi Kemahasiswaan</span>
        </div>
    </nav>

    <div class="card shadow-sm">
        <div class="card-body">
            
            <form action="proses_simpan_pengurus.php" method="POST" enctype="multipart/form-data">

                <h5 class="mb-3">Susunan Kepengurusan</h5>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle" id="tabelPengurus">
                        <thead class="table-light text-center">
                            <tr>
                                <th width="15%" style="background-color: #FF00FF; color: white; font-weight: bold;">Jabatan</th>
                                <th>Nama</th>
                                <th width="12%">NIM</th>
                                <th width="8%">Prodi</th>
                                <th>Email</th>
                                <th width="18%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <tr>
                                <td>
                                    <select class="form-select" name="jabatan[]" required>
                                        <option value="">-Pilih-</option>
                                        <option value="Ketua">Ketua</option>
                                        <option value="Wakil Ketua">Wakil Ketua</option>
                                        <option value="Sekretaris">Sekretaris</option>
                                        <option value="Bendahara">Bendahara</option>
                                        <option value="Anggota">Anggota</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="nama[]" placeholder="Nama Lengkap" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="nim[]" placeholder="NIM" required>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="prodi[]" placeholder="Prodi" required>
                                </td>
                                <td>
                                    <input type="email" class="form-control" name="email[]" placeholder="email@unida.ac.id">
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm fw-bold text-white w-50" style="background-color: #00FF00; border: 1px solid #00AA00;" onclick="addRow()">TAMBAH</button>
                                        <button type="button" class="btn btn-sm fw-bold text-white w-50" style="background-color: #FF0000; border: 1px solid #AA0000;" onclick="deleteRow(this)">DELETE</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="fw-bold mb-1">Periode Pergantian Pengurus</label>
                        <div class="input-group">
                            <input type="datetime-local" class="form-control" name="periode_mulai" required>
                            <span class="input-group-text bg-white border-start-0 border-end-0">~</span>
                            <input type="datetime-local" class="form-control" name="periode_selesai" required>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="fw-bold mb-1">Nama Pembimbing <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_pembimbing" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="fw-bold mb-1">NIY Pembimbing</label>
                        <input type="text" class="form-control" name="niy_pembimbing">
                    </div>

                    <div class="col-md-12 mb-4">
                        <label class="fw-bold mb-1">Lampirkan Kesediaan Sebagai Dosen Pembimbing UKM/HMP</label>
                        <input type="file" class="form-control mb-1" name="file_kesediaan">
                        <small class="d-block"><a href="#" class="text-decoration-none">Berkaslampiran.pdf</a> (File saat ini)</small>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn fw-bold px-4" style="background-color: #A9D08E; border: 1px solid #7E9C68;">SIMPAN</button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
    // Fungsi Menambah Baris Baru
    function addRow() {
        // Ambil elemen tbody
        var tableBody = document.getElementById("tableBody");
        
        // Buat elemen tr baru
        var newRow = document.createElement("tr");
        
        // Isi HTML baris baru (sama persis dengan baris pertama)
        newRow.innerHTML = `
            <td>
                <select class="form-select" name="jabatan[]" required>
                    <option value="">-Pilih-</option>
                    <option value="Ketua">Ketua</option>
                    <option value="Wakil Ketua">Wakil Ketua</option>
                    <option value="Sekretaris">Sekretaris</option>
                    <option value="Bendahara">Bendahara</option>
                    <option value="Anggota">Anggota</option>
                </select>
            </td>
            <td>
                <input type="text" class="form-control" name="nama[]" placeholder="Nama Lengkap" required>
            </td>
            <td>
                <input type="number" class="form-control" name="nim[]" placeholder="NIM" required>
            </td>
            <td>
                <input type="text" class="form-control" name="prodi[]" placeholder="Prodi" required>
            </td>
            <td>
                <input type="email" class="form-control" name="email[]" placeholder="email@unida.ac.id">
            </td>
            <td>
                <div class="d-flex gap-1">
                    <button type="button" class="btn btn-sm fw-bold text-white w-50" style="background-color: #00FF00; border: 1px solid #00AA00;" onclick="addRow()">TAMBAH</button>
                    <button type="button" class="btn btn-sm fw-bold text-white w-50" style="background-color: #FF0000; border: 1px solid #AA0000;" onclick="deleteRow(this)">DELETE</button>
                </div>
            </td>
        `;
        
        // Masukkan baris baru ke dalam tbody
        tableBody.appendChild(newRow);
    }

    // Fungsi Menghapus Baris
    function deleteRow(button) {
        var tableBody = document.getElementById("tableBody");
        var row = button.closest("tr"); // Cari elemen tr terdekat dari tombol yang diklik
        
        // Cek jumlah baris agar tidak habis semua (minimal sisa 1)
        if (tableBody.rows.length > 1) {
            row.remove();
        } else {
            alert("Minimal harus ada satu pengurus!");
        }
    }
</script>

<?php 
// Naik 2 tingkat
include '../../footer.php'; 
?>