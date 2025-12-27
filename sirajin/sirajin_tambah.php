<?php
session_start();
include '../koneksi.php';

// 1. Cek Login & Role
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../login.php");
    exit;
}

// Hanya Mahasiswa/UKM yang boleh akses halaman ini
if ($_SESSION['role'] == 'admin') {
    echo "<script>alert('Admin tidak perlu mengajukan kegiatan.'); window.location='sirajin_list.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// 2. PROSES SIMPAN DATA
if (isset($_POST['simpan_kegiatan'])) {
    // Ambil data dari form
    $nama_kegiatan = $_POST['nama_kegiatan'];
    $id_jenis = $_POST['id_jenis_kegiatan'];
    $deskripsi = $_POST['deskripsi'];
    $tujuan = $_POST['tujuan'];
    
    $tgl_mulai = $_POST['tgl_mulai'];
    $tgl_selesai = $_POST['tgl_selesai'];
    $jam_mulai = $_POST['waktu_mulai'];
    $jam_selesai = $_POST['waktu_selesai'];
    
    $id_lokasi = $_POST['id_lokasi'];
    $detail_tempat = $_POST['detail_tempat'];
    
    $listrik = $_POST['kebutuhan_listrik'];
    $biaya = $_POST['estimasi_biaya'];

    // Upload Proposal (PDF Only)
    $file_name = "";
    if (!empty($_FILES['file_proposal']['name'])) {
        $ext = pathinfo($_FILES['file_proposal']['name'], PATHINFO_EXTENSION);
        if (strtolower($ext) == 'pdf') {
            $file_name = time() . '_PROPOSAL_' . $_SESSION['username'] . '.pdf';
            $tmp_file = $_FILES['file_proposal']['tmp_name'];
            
            // Pastikan folder uploads ada di root
            move_uploaded_file($tmp_file, "../uploads/" . $file_name);
        } else {
            echo "<script>alert('File Proposal harus format PDF!');</script>";
        }
    }

    // Insert ke Database
    $query = "INSERT INTO trx_kegiatan 
              (user_id, nama_kegiatan, deskripsi, tujuan, id_jenis_kegiatan, id_lokasi, 
               tgl_mulai, tgl_selesai, waktu_mulai, waktu_selesai, detail_tempat, 
               kebutuhan_listrik, estimasi_biaya, file_proposal, status_perizinan)
              VALUES 
              ('$user_id', '$nama_kegiatan', '$deskripsi', '$tujuan', '$id_jenis', '$id_lokasi',
               '$tgl_mulai', '$tgl_selesai', '$jam_mulai', '$jam_selesai', '$detail_tempat',
               '$listrik', '$biaya', '$file_name', 'Pengajuan Baru')";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>
                alert('Sukses! Kegiatan berhasil diajukan.');
                window.location='sirajin_list.php';
              </script>";
    } else {
        echo "<script>alert('Gagal menyimpan: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah Kegiatan - SIRAJIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="d-flex">
    <?php include '../sidebar.php'; ?>

    <div class="p-4 w-100 bg-light" style="height: 100vh; overflow-y: auto;">
        <div class="container" style="max-width: 900px;">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold text-primary">Form Pengajuan Kegiatan</h3>
                <a href="sirajin_list.php" class="btn btn-secondary btn-sm">Kembali</a>
            </div>

            <form method="POST" enctype="multipart/form-data">
                
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold">1. Informasi Dasar</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kegiatan" class="form-control" required placeholder="Contoh: Seminar Nasional Teknologi 2024">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Kegiatan <span class="text-danger">*</span></label>
                            <select name="id_jenis_kegiatan" class="form-select" required>
                                <option value="">- Pilih Jenis -</option>
                                <?php
                                $q_jenis = mysqli_query($koneksi, "SELECT * FROM mst_jenis_kegiatan");
                                while ($j = mysqli_fetch_array($q_jenis)) {
                                    echo "<option value='" . $j['id'] . "'>" . $j['nama'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi Kegiatan</label>
                            <textarea name="deskripsi" class="form-control" rows="3" placeholder="Jelaskan secara singkat tentang kegiatan ini..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tujuan Kegiatan</label>
                            <textarea name="tujuan" class="form-control" rows="2" placeholder="Tujuan diadakan kegiatan ini..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold">2. Waktu & Tempat Pelaksanaan</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" name="tgl_mulai" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" name="tgl_selesai" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jam Mulai</label>
                                <input type="time" name="waktu_mulai" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jam Selesai</label>
                                <input type="time" name="waktu_selesai" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lokasi Kegiatan (Master)</label>
                            <select name="id_lokasi" class="form-select" required>
                                <option value="">- Pilih Lokasi -</option>
                                <?php
                                // Pastikan tabel mst_jenis_lokasi ada di DB Anda
                                $q_lok = mysqli_query($koneksi, "SELECT * FROM mst_jenis_lokasi"); 
                                if($q_lok){
                                    while ($l = mysqli_fetch_array($q_lok)) {
                                        echo "<option value='" . $l['id'] . "'>" . $l['nama'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Detail Tempat / Ruangan</label>
                            <input type="text" name="detail_tempat" class="form-control" placeholder="Contoh: Hall Gedung Utama Lt. 2">
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold">3. Kebutuhan & Anggaran</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kebutuhan Listrik (Watt)</label>
                                <input type="number" name="kebutuhan_listrik" class="form-control" value="0">
                                <div class="form-text">Isi 0 jika tidak memerlukan daya tambahan besar.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Estimasi Total Anggaran (Rp)</label>
                                <input type="number" name="estimasi_biaya" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold">4. Dokumen Pendukung</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Upload Proposal Lengkap (PDF) <span class="text-danger">*</span></label>
                            <input type="file" name="file_proposal" class="form-control" accept=".pdf" required>
                            <div class="form-text text-danger">Wajib format PDF. Maksimal ukuran file disarankan di bawah 2MB.</div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 mb-5">
                    <button type="submit" name="simpan_kegiatan" class="btn btn-success btn-lg fw-bold">
                        <i class="bi bi-send me-2"></i>AJUKAN KEGIATAN
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>