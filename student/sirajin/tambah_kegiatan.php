<?php 
include '../../config.php';
include '../header.php'; 
include '../sidebar.php'; 
?>

<div class="flex-grow-1 p-4 bg-light">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">C-RJN 1.1 Tambah Kegiatan Mahasiswa</h5>
        </div>
        <div class="card-body">
            <form action="proses_simpan_kegiatan.php" method="POST" enctype="multipart/form-data">
                
                <input type="hidden" name="penyelenggara" value="Himpunan Mahasiswa Informatika">

                <div class="mb-3">
                    <label class="form-label">Nama Kegiatan*</label>
                    <input type="text" name="nama_kegiatan" class="form-control" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jenis Kegiatan*</label>
                        <select name="jenis_kegiatan" class="form-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Seminar">Seminar</option>
                            <option value="Lomba">Lomba</option>
                            <option value="Sosial">Bakti Sosial</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Lokasi Kegiatan*</label>
                        <select name="lokasi_kegiatan" class="form-select" required>
                            <option value="Hall CIOS">Hall CIOS</option>
                            <option value="Gedung Utama">Gedung Utama</option>
                            <option value="Luar Kampus">Luar Kampus</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi & Tujuan*</label>
                    <textarea name="deskripsi" class="form-control mb-2" placeholder="Deskripsi Kegiatan" rows="3" required></textarea>
                    <textarea name="tujuan" class="form-control" placeholder="Tujuan Kegiatan" rows="3" required></textarea>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tgl_mulai" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="tgl_selesai" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Estimasi Anggaran (Rp)</label>
                        <input type="number" name="anggaran" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload Proposal (PDF, Max 2MB)*</label>
                    <input type="file" name="proposal" class="form-control" accept=".pdf" required>
                    <small class="text-danger">Sesuai Proposal wajib diupload.</small>
                </div>

                <hr>
                <div class="d-flex justify-content-end gap-2">
                    <a href="kegiatan_mhs.php" class="btn btn-secondary">BATAL</a>
                    <button type="submit" name="simpan" class="btn btn-success">SIMPAN</button>
                </div>

            </form>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>