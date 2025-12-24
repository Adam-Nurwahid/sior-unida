<?php
// mahasiswa_dana.php
include '../header.php';
 include '../sidebar.php'; 
 include '../../config.php';
$user_id = 1; 
$is_open = true; 

// --- LOGIKA TAMBAH DATA ---
if (isset($_POST['simpan_pengajuan'])) {
    $judul = $_POST['judul'];
    $jenis = $_POST['jenis'];
    $deskripsi = $_POST['deskripsi'];
    
    // Info Bank
    $bank_nim = $_POST['bank_nim'];
    $bank_nama = $_POST['bank_nama'];
    $bank_rek = $_POST['bank_rekening'];
    
    // Upload Proposal (Simplifikasi)
    $file = "proposal_".time().".pdf"; // Move uploaded file logic here

    // 1. Insert ke Tabel Utama
    $sql = "INSERT INTO dana_bantuan (user_id, judul_bantuan, jenis_bantuan, deskripsi, file_proposal, bank_nim, bank_nama, bank_rekening) 
            VALUES ('$user_id', '$judul', '$jenis', '$deskripsi', '$file', '$bank_nim', '$bank_nama', '$bank_rek')";
    
    if(mysqli_query($conn, $sql)){
        $dana_id = mysqli_insert_id($conn);

        // 2. Insert Rencana Dana (Looping Array)
        if(isset($_POST['kegiatan_nama'])){
            foreach($_POST['kegiatan_nama'] as $key => $val){
                $keg = $_POST['kegiatan_nama'][$key];
                $nom = $_POST['kegiatan_nominal'][$key];
                mysqli_query($conn, "INSERT INTO dana_rencana (dana_id, nama_kegiatan, nominal) VALUES ('$dana_id', '$keg', '$nom')");
            }
        }

        // 3. Insert Penanggung Jawab (Looping Array)
        if(isset($_POST['pj_nama'])){
            foreach($_POST['pj_nama'] as $key => $val){
                $jab = $_POST['pj_jabatan'][$key];
                $nim = $_POST['pj_nim'][$key];
                $nam = $_POST['pj_nama'][$key];
                $hp  = $_POST['pj_hp'][$key];
                $mail= $_POST['pj_email'][$key];
                mysqli_query($conn, "INSERT INTO dana_pj (dana_id, jabatan, nim, nama, no_hp, email) VALUES ('$dana_id', '$jab', '$nim', '$nam', '$hp', '$mail')");
            }
        }
        echo "<script>alert('Sukses! Pengajuan berhasil ditambahkan.'); window.location='dana.php';</script>";
    }
}

// --- LOGIKA HAPUS ---
if (isset($_GET['hapus'])) {
    mysqli_query($conn, "DELETE FROM dana_bantuan WHERE id='$_GET[hapus]' AND user_id='$user_id'");
    echo "<script>window.location='dana.php';</script>";
}
?>

<div class="flex-grow-1 p-4 bg-light">
    <div class="d-flex justify-content-between mb-4">
        <h3>Daftar Dana Bantuan Kegiatan</h3>
        <?php if($is_open): ?>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah"><i class="fas fa-plus"></i> Tambah Bantuan Kegiatan</button>
        <?php endif; ?>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q = mysqli_query($conn, "SELECT * FROM dana_bantuan WHERE user_id='$user_id' ORDER BY id DESC");
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($q)) {
                        // Logic Revisi [cite: 7, 519]
                        // Bisa revisi jika: (Status Pending AND Revisi Count < 1) OR (Status Dikembalikan)
                        // Tidak bisa revisi jika: Ditolak atau Disetujui
                        $bisa_revisi = false;
                        if($row['status'] == 'dikembalikan') {
                            $bisa_revisi = true;
                        } elseif ($row['status'] == 'pending' && $row['revisi_count'] < 1) {
                            $bisa_revisi = true;
                        }
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $row['judul_bantuan']; ?></td>
                        <td><?= date('Y-m-d H:i', strtotime($row['tanggal_pengajuan'])); ?></td>
                        <td>
                            <span class="badge bg-secondary"><?= strtoupper($row['status']); ?></span>
                            <?php if($row['catatan_admin']) echo "<br><small class='text-danger'>Note: {$row['catatan_admin']}</small>"; ?>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info text-white">Detail</button>
                            
                            <?php if($bisa_revisi): ?>
                                <a href="revisi_dana.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning">Ajukan Revisi</a>
                            <?php else: ?>
                                <button class="btn btn-sm btn-secondary" disabled>Revisi</button>
                            <?php endif; ?>

                            <a href="?hapus=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Anda Yakin Hapus?')">Hapus</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> 
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTambahLabel">Tambah Bantuan Kegiatan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <h6 class="text-primary mb-3"><i class="fas fa-info-circle"></i> Informasi Dasar</h6>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Bantuan Kegiatan*</label>
                        <input type="text" name="judul" class="form-control" placeholder="Contoh: Donor Darah Angkatan 2025" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Jenis Bantuan*</label>
                        <select name="jenis" class="form-select" required>
                            <option value="">-- Pilih Jenis Bantuan --</option>
                            <option value="Bantuan Dana UKM">Bantuan Dana UKM</option>
                            <option value="Bantuan Event">Bantuan Event</option>
                            <option value="Bantuan Insidental">Bantuan Insidental</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi Kegiatan*</label>
                        <textarea name="deskripsi" class="form-control" rows="4" placeholder="Jelaskan detail kegiatan..." required></textarea>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-primary m-0"><i class="fas fa-money-bill-wave"></i> Rencana Penggunaan Dana</h6>
                        <button type="button" class="btn btn-success btn-sm" id="addRencana">
                            <i class="fas fa-plus"></i> Baris
                        </button>
                    </div>
                    
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-striped" id="tableRencana">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Keperluan</th>
                                    <th style="width: 35%;">Nominal (Rp)</th>
                                    <th style="width: 10%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="kegiatan_nama[]" class="form-control" placeholder="Contoh: Konsumsi" required></td>
                                    <td><input type="number" name="kegiatan_nominal[]" class="form-control" placeholder="0" required></td>
                                    <td class="text-center"><button type="button" class="btn btn-danger btn-sm hapusRow" disabled><i class="fas fa-times"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-primary m-0"><i class="fas fa-users"></i> Penanggung Jawab (Panitia)</h6>
                        <button type="button" class="btn btn-success btn-sm" id="addPJ">
                            <i class="fas fa-plus"></i> PJ
                        </button>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-striped text-nowrap" id="tablePJ">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 20%;">Jabatan</th>
                                    <th>NIM</th>
                                    <th>Nama Lengkap</th>
                                    <th>No HP (WA)</th>
                                    <th>Email</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="pj_jabatan[]" class="form-select" required>
                                            <option value="Ketua">Ketua</option>
                                            <option value="Sekretaris">Sekretaris</option>
                                            <option value="Bendahara">Bendahara</option>
                                            <option value="Anggota">Anggota</option>
                                        </select>
                                    </td>
                                    <td><input type="text" name="pj_nim[]" class="form-control" required></td>
                                    <td><input type="text" name="pj_nama[]" class="form-control" required></td>
                                    <td><input type="text" name="pj_hp[]" class="form-control" required></td>
                                    <td><input type="email" name="pj_email[]" class="form-control" required></td>
                                    <td class="text-center"><button type="button" class="btn btn-danger btn-sm hapusRow" disabled><i class="fas fa-times"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <hr class="my-4">

                    <div class="row g-3">
                        <div class="col-md-12">
                            <h6 class="text-primary"><i class="fas fa-file-pdf"></i> Dokumen Pendukung</h6>
                            <label class="form-label">Upload Proposal Lengkap (PDF, Maks 5MB)*</label>
                            <input type="file" name="proposal" class="form-control" accept=".pdf" required>
                            <div class="form-text">Pastikan proposal sudah ditandatangani basah/digital.</div>
                        </div>
                        
                        <div class="col-md-12 mt-4">
                            <h6 class="text-primary"><i class="fas fa-university"></i> Rekening Pencairan Dana</h6>
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Nama Bank</label>
                                            <select name="bank_nama" class="form-select" required>
                                                <option value="">-- Pilih Bank --</option>
                                                <option value="BSI">Bank Syariah Indonesia (BSI)</option>
                                                <option value="Muamalat">Bank Muamalat</option>
                                                <option value="BNI">BNI</option>
                                                <option value="BRI">BRI</option>
                                                <option value="Mandiri">Mandiri</option>
                                                <option value="BCA">BCA</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Nomor Rekening</label>
                                            <input type="number" name="bank_rekening" class="form-control" placeholder="Contoh: 7123456789" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Atas Nama (Sesuai Buku Tabungan)</label>
                                            <input type="text" name="bank_nim" class="form-control" placeholder="Nama Pemilik Rekening" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="simpan_pengajuan" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i> Ajukan Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('addRencana').addEventListener('click', function() {
        let table = document.getElementById('tableRencana').getElementsByTagName('tbody')[0];
        let newRow = table.insertRow(table.rows.length);
        newRow.innerHTML = `<td><input type="text" name="kegiatan_nama[]" class="form-control"></td>
                            <td><input type="number" name="kegiatan_nominal[]" class="form-control"></td>
                            <td><button type="button" class="btn btn-danger btn-sm hapusRow" onclick="this.closest('tr').remove()">-</button></td>`;
    });

    document.getElementById('addPJ').addEventListener('click', function() {
        let table = document.getElementById('tablePJ').getElementsByTagName('tbody')[0];
        let newRow = table.insertRow(table.rows.length);
        newRow.innerHTML = `<td><select name="pj_jabatan[]" class="form-select"><option>Ketua</option><option>Anggota</option></select></td>
                            <td><input type="text" name="pj_nim[]" class="form-control"></td>
                            <td><input type="text" name="pj_nama[]" class="form-control"></td>
                            <td><input type="text" name="pj_hp[]" class="form-control"></td>
                            <td><input type="email" name="pj_email[]" class="form-control"></td>
                            <td><button type="button" class="btn btn-danger btn-sm hapusRow" onclick="this.closest('tr').remove()">-</button></td>`;
    });
</script>

<?php include '../footer.php'; ?>