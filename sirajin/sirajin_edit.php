<?php
session_start();
include '../koneksi.php';

// 1. Cek Login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../login.php");
    exit;
}

// 2. Cek Parameter ID
if (!isset($_GET['id'])) {
    header("location:sirajin_list.php");
    exit;
}
$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// 3. Ambil Data Lama
$query = mysqli_query($koneksi, "SELECT * FROM trx_kegiatan WHERE id='$id'");
$data = mysqli_fetch_array($query);

// 4. Validasi Hak Akses (PENTING)
// Cek 1: Apakah data ada?
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='sirajin_list.php';</script>";
    exit;
}
// Cek 2: Apakah yang login adalah pemilik data? (Kecuali admin, tapi admin biasanya hanya proses)
if ($_SESSION['role'] != 'admin' && $data['user_id'] != $user_id) {
    echo "<script>alert('Akses Ditolak! Anda tidak berhak mengedit data ini.'); window.location='sirajin_list.php';</script>";
    exit;
}
// Cek 3: Apakah statusnya BOLEH diedit? (Hanya Draft dan Perlu Revisi)
if (!in_array($data['status_perizinan'], ['Draft', 'Perlu Revisi', 'Pengajuan Baru'])) {
    echo "<script>alert('Data tidak dapat diedit karena sedang diproses atau sudah selesai.'); window.location='sirajin_list.php';</script>";
    exit;
}

// 5. PROSES UPDATE DATA
if (isset($_POST['update_kegiatan'])) {
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

    // Logic File Upload
    $file_name = $data['file_proposal']; // Default pakai file lama
    
    if (!empty($_FILES['file_proposal']['name'])) {
        $ext = pathinfo($_FILES['file_proposal']['name'], PATHINFO_EXTENSION);
        if (strtolower($ext) == 'pdf') {
            // Hapus file lama jika ada
            if (file_exists("../uploads/" . $data['file_proposal']) && $data['file_proposal'] != "") {
                unlink("../uploads/" . $data['file_proposal']);
            }

            // Upload file baru
            $file_name = time() . '_REVISI_' . $_SESSION['username'] . '.pdf';
            move_uploaded_file($_FILES['file_proposal']['tmp_name'], "../uploads/" . $file_name);
        } else {
            echo "<script>alert('Gagal! File harus format PDF.');</script>";
        }
    }

    // Jika status sebelumnya 'Perlu Revisi', kembalikan ke 'Pengajuan Baru' agar Admin notis
    $status_baru = ($data['status_perizinan'] == 'Perlu Revisi') ? 'Pengajuan Baru' : $data['status_perizinan'];

    $sql_update = "UPDATE trx_kegiatan SET 
                   nama_kegiatan='$nama_kegiatan', deskripsi='$deskripsi', tujuan='$tujuan',
                   id_jenis_kegiatan='$id_jenis', id_lokasi='$id_lokasi',
                   tgl_mulai='$tgl_mulai', tgl_selesai='$tgl_selesai', 
                   waktu_mulai='$jam_mulai', waktu_selesai='$jam_selesai', detail_tempat='$detail_tempat',
                   kebutuhan_listrik='$listrik', estimasi_biaya='$biaya',
                   file_proposal='$file_name', status_perizinan='$status_baru'
                   WHERE id='$id'";

    if (mysqli_query($koneksi, $sql_update)) {
        echo "<script>alert('Data Berhasil Diperbarui!'); window.location='sirajin_list.php';</script>";
    } else {
        echo "<script>alert('Gagal Update: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Kegiatan - SIRAJIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="d-flex">
    <?php include '../sidebar.php'; ?>

    <div class="p-4 w-100 bg-light" style="height: 100vh; overflow-y: auto;">
        <div class="container" style="max-width: 900px;">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold text-warning">Edit / Revisi Kegiatan</h3>
                <a href="sirajin_list.php" class="btn btn-secondary btn-sm">Batal</a>
            </div>

            <?php if($data['status_perizinan'] == 'Perlu Revisi'): ?>
            <div class="alert alert-danger">
                <strong><i class="bi bi-exclamation-triangle"></i> PERHATIAN:</strong> Proposal ini dikembalikan oleh Admin. 
                Silakan perbaiki data sesuai catatan berikut:<br>
                <em>"<?php echo $data['catatan_revisi']; ?>"</em>
            </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold">1. Informasi Dasar</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Kegiatan</label>
                            <input type="text" name="nama_kegiatan" class="form-control" value="<?php echo $data['nama_kegiatan']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Kegiatan</label>
                            <select name="id_jenis_kegiatan" class="form-select" required>
                                <option value="">- Pilih Jenis -</option>
                                <?php
                                $q_jenis = mysqli_query($koneksi, "SELECT * FROM mst_jenis_kegiatan");
                                while ($j = mysqli_fetch_array($q_jenis)) {
                                    $selected = ($data['id_jenis_kegiatan'] == $j['id']) ? 'selected' : '';
                                    echo "<option value='" . $j['id'] . "' $selected>" . $j['nama'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi Kegiatan</label>
                            <textarea name="deskripsi" class="form-control" rows="3"><?php echo $data['deskripsi']; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tujuan Kegiatan</label>
                            <textarea name="tujuan" class="form-control" rows="2"><?php echo $data['tujuan']; ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold">2. Waktu & Tempat</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" name="tgl_mulai" class="form-control" value="<?php echo $data['tgl_mulai']; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" name="tgl_selesai" class="form-control" value="<?php echo $data['tgl_selesai']; ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jam Mulai</label>
                                <input type="time" name="waktu_mulai" class="form-control" value="<?php echo date('H:i', strtotime($data['waktu_mulai'])); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jam Selesai</label>
                                <input type="time" name="waktu_selesai" class="form-control" value="<?php echo date('H:i', strtotime($data['waktu_selesai'])); ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lokasi Kegiatan</label>
                            <select name="id_lokasi" class="form-select" required>
                                <option value="">- Pilih Lokasi -</option>
                                <?php
                                $q_lok = mysqli_query($koneksi, "SELECT * FROM mst_jenis_lokasi");
                                while ($l = mysqli_fetch_array($q_lok)) {
                                    $selected = ($data['id_lokasi'] == $l['id']) ? 'selected' : '';
                                    echo "<option value='" . $l['id'] . "' $selected>" . $l['nama'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Detail Tempat</label>
                            <input type="text" name="detail_tempat" class="form-control" value="<?php echo $data['detail_tempat']; ?>">
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold">3. Kebutuhan & Anggaran</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kebutuhan Listrik (Watt)</label>
                                <input type="number" name="kebutuhan_listrik" class="form-control" value="<?php echo $data['kebutuhan_listrik']; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Estimasi Anggaran</label>
                                <input type="number" name="estimasi_biaya" class="form-control" value="<?php echo $data['estimasi_biaya']; ?>" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold">4. Dokumen Pendukung</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Upload Proposal Revisi (PDF)</label>
                            <input type="file" name="file_proposal" class="form-control" accept=".pdf">
                            <div class="form-text">Biarkan kosong jika tidak ingin mengganti file proposal.</div>
                            
                            <?php if(!empty($data['file_proposal'])): ?>
                                <div class="mt-2 small text-success">
                                    File saat ini: <strong><?php echo $data['file_proposal']; ?></strong>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 mb-5">
                    <button type="submit" name="update_kegiatan" class="btn btn-warning text-white btn-lg fw-bold">
                        SIMPAN PERUBAHAN
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>