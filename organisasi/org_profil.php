<?php
session_start();
// Sesuaikan path koneksi
include '../koneksi.php'; 

// 1. Cek Keamanan Login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../login.php");
    exit;
}
if ($_SESSION['role'] == 'admin') {
    echo "<script>
            alert('Akses Ditolak! Halaman ini hanya untuk Organisasi Kemahasiswaan.');
            window.location.href='../index.php'; // Kembali ke Dashboard
          </script>";
    exit; // Stop script agar konten di bawah tidak dimuat
}
$user_id = $_SESSION['user_id']; // ID User yang sedang login

// 2. LOGIKA READ: Ambil Data Profil User Ini (Jika Ada)
$query_profil = mysqli_query($koneksi, "SELECT * FROM profil_organisasi WHERE user_id='$user_id'");
$data = mysqli_fetch_array($query_profil);
$mode_edit = ($data) ? true : false; // True jika data sudah ada (Mode Update)

// 3. LOGIKA CREATE / UPDATE: Saat tombol simpan ditekan
if (isset($_POST['simpan'])) {
    // Ambil semua data inputan
    $nama_org = $_POST['nama_organisasi'];
    $singkatan = $_POST['singkatan'];
    $id_tipe = $_POST['id_tipe_org'];
    $visi = $_POST['visi'];
    $misi = $_POST['misi'];
    
    $alamat_sek = $_POST['alamat_sekretariat'];
    $alamat_surat = $_POST['alamat_surat'];
    $telp_fax = $_POST['telp_fax'];
    $email = $_POST['email_org'];
    $website = $_POST['website'];
    
    $fb = $_POST['sosmed_fb'];
    $ig = $_POST['sosmed_ig'];
    $twitter = $_POST['sosmed_twitter'];
    $line = $_POST['sosmed_line'];
    
    $tahun = $_POST['tahun_berdiri'];
    $fasilitas = $_POST['fasilitas_dimiliki'];
    $prestasi = $_POST['prestasi'];
    $jml_aktif = $_POST['jml_anggota_aktif'];
    $jml_total = $_POST['jml_anggota_seluruh'];
    $ukuran_ruang = $_POST['ukuran_ruang'];

    $jadwal = $_POST['jadwal_kegiatan'];
    $tempat = $_POST['tempat_kegiatan'];
    $keg_besar = $_POST['kegiatan_besar'];
    $rencana = $_POST['rencana_kegiatan'];
    $keg_rutin = $_POST['kegiatan_rutin'];
    $keg_non = $_POST['kegiatan_non_rutin'];
    
    // --- LOGIKA UPLOAD FOTO ---
    
    // 1. Upload Logo
    $logo_name = $data['logo'] ?? ''; // Default pakai nama lama
    if(!empty($_FILES['logo']['name'])){
        $logo_name = time() . '_LOGO_' . $_FILES['logo']['name'];
        move_uploaded_file($_FILES['logo']['tmp_name'], "../uploads/" . $logo_name);
    }

    // 2. Upload Foto Kegiatan
    $foto_keg_name = $data['foto_kegiatan'] ?? ''; // Default pakai nama lama
    if(!empty($_FILES['foto_kegiatan']['name'])){
        $foto_keg_name = time() . '_KEG_' . $_FILES['foto_kegiatan']['name'];
        move_uploaded_file($_FILES['foto_kegiatan']['tmp_name'], "../uploads/" . $foto_keg_name);
    }

    // --- EKSEKUSI QUERY ---

    if ($mode_edit) {
        // === INI ADALAH BAGIAN UPDATE ===
        // Update data yang sudah ada berdasarkan user_id
        $sql = "UPDATE profil_organisasi SET 
                nama_organisasi='$nama_org', singkatan='$singkatan', id_tipe_org='$id_tipe',
                visi='$visi', misi='$misi', 
                alamat_sekretariat='$alamat_sek', alamat_surat='$alamat_surat', telp_fax='$telp_fax',
                email_org='$email', website='$website',
                sosmed_fb='$fb', sosmed_ig='$ig', sosmed_twitter='$twitter', sosmed_line='$line',
                tahun_berdiri='$tahun', fasilitas_dimiliki='$fasilitas', prestasi='$prestasi',
                jml_anggota_aktif='$jml_aktif', jml_anggota_seluruh='$jml_total', ukuran_ruang='$ukuran_ruang',
                jadwal_kegiatan='$jadwal', tempat_kegiatan='$tempat', kegiatan_besar='$keg_besar',
                rencana_kegiatan='$rencana', kegiatan_rutin='$keg_rutin', kegiatan_non_rutin='$keg_non',
                logo='$logo_name', foto_kegiatan='$foto_keg_name'
                WHERE user_id='$user_id'";
    } else {
        // === INI ADALAH BAGIAN INSERT (CREATE) ===
        // Buat data baru jika belum ada
        $sql = "INSERT INTO profil_organisasi 
                (user_id, nama_organisasi, singkatan, id_tipe_org, visi, misi, alamat_sekretariat, alamat_surat, telp_fax, email_org, website, sosmed_fb, sosmed_ig, sosmed_twitter, sosmed_line, tahun_berdiri, fasilitas_dimiliki, prestasi, jml_anggota_aktif, jml_anggota_seluruh, ukuran_ruang, jadwal_kegiatan, tempat_kegiatan, kegiatan_besar, rencana_kegiatan, kegiatan_rutin, kegiatan_non_rutin, logo, foto_kegiatan)
                VALUES 
                ('$user_id', '$nama_org', '$singkatan', '$id_tipe', '$visi', '$misi', '$alamat_sek', '$alamat_surat', '$telp_fax', '$email', '$website', '$fb', '$ig', '$twitter', '$line', '$tahun', '$fasilitas', '$prestasi', '$jml_aktif', '$jml_total', '$ukuran_ruang', '$jadwal', '$tempat', '$keg_besar', '$rencana', '$keg_rutin', '$keg_non', '$logo_name', '$foto_keg_name')";
    }

    if(mysqli_query($koneksi, $sql)){
        echo "<script>alert('Data Profil Berhasil Disimpan!'); window.location='org_profil.php';</script>";
    } else {
        echo "<script>alert('Gagal Menyimpan: ".mysqli_error($koneksi)."');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Profil Organisasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="d-flex">
    <?php include '../sidebar.php'; ?>

    <div class="p-4 w-100 bg-light" style="height: 100vh; overflow-y: auto;">
        <div class="container-fluid">
            
            <h2 class="mb-4 fw-bold text-primary">Profil Organisasi</h2>

            <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2"></i>
                <div>
                    <strong>Pemberitahuan!</strong> Periode ini adalah masa periode daftar ulang UKM, harap lengkapi profil.
                </div>
            </div>

            <form method="POST" enctype="multipart/form-data">
                <div class="card shadow-sm mb-5">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 fw-bold text-dark">DATA DASAR</h6>
                    </div>
                    <div class="card-body">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Organisasi *</label>
                                <input type="text" name="nama_organisasi" class="form-control" value="<?php echo $data['nama_organisasi'] ?? ''; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Singkatan *</label>
                                <input type="text" name="singkatan" class="form-control" value="<?php echo $data['singkatan'] ?? ''; ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipe Organisasi *</label>
                            <select name="id_tipe_org" class="form-select" required>
                                <option value="">-- Pilih Tipe --</option>
                                <?php
                                $q_tipe = mysqli_query($koneksi, "SELECT * FROM mst_jenis_org");
                                while($row_tipe = mysqli_fetch_array($q_tipe)) {
                                    $selected = ($data && $data['id_tipe_org'] == $row_tipe['id']) ? 'selected' : '';
                                    // Menampilkan Nama tipe organisasi
                                    echo "<option value='".$row_tipe['id']."' $selected>".$row_tipe['nama']."</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Visi *</label>
                                <textarea name="visi" class="form-control" rows="3"><?php echo $data['visi'] ?? ''; ?></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Misi *</label>
                                <textarea name="misi" class="form-control" rows="3"><?php echo $data['misi'] ?? ''; ?></textarea>
                            </div>
                        </div>

                         <div class="mb-3">
                            <label class="form-label">Logo Organisasi</label>
                            <input type="file" name="logo" class="form-control">
                            <?php if(!empty($data['logo'])): ?>
                                <small class="text-success">File saat ini: <?php echo $data['logo']; ?></small>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>

                <div class="card shadow-sm mb-5">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 fw-bold text-dark">KONTAK & MEDSOS</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Alamat Sekretariat</label>
                                <textarea name="alamat_sekretariat" class="form-control" rows="2"><?php echo $data['alamat_sekretariat'] ?? ''; ?></textarea>
                            </div>
                             <div class="col-md-6 mb-3">
                                <label class="form-label">Alamat Surat</label>
                                <input type="text" name="alamat_surat" class="form-control" value="<?php echo $data['alamat_surat'] ?? ''; ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Telp/Fax</label>
                                <input type="text" name="telp_fax" class="form-control" value="<?php echo $data['telp_fax'] ?? ''; ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email_org" class="form-control" value="<?php echo $data['email_org'] ?? ''; ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Website</label>
                                <input type="text" name="website" class="form-control" value="<?php echo $data['website'] ?? ''; ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Facebook</label>
                                <input type="text" name="sosmed_fb" class="form-control" value="<?php echo $data['sosmed_fb'] ?? ''; ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Instagram</label>
                                <input type="text" name="sosmed_ig" class="form-control" value="<?php echo $data['sosmed_ig'] ?? ''; ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Twitter/X</label>
                                <input type="text" name="sosmed_twitter" class="form-control" value="<?php echo $data['sosmed_twitter'] ?? ''; ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Line</label>
                                <input type="text" name="sosmed_line" class="form-control" value="<?php echo $data['sosmed_line'] ?? ''; ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-5">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 fw-bold text-dark">DATA DETAIL & KEGIATAN</h6>
                    </div>
                    <div class="card-body">
                         <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tahun Berdiri</label>
                                <input type="date" name="tahun_berdiri" class="form-control" value="<?php echo $data['tahun_berdiri'] ?? ''; ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Jml Anggota Aktif</label>
                                <input type="number" name="jml_anggota_aktif" class="form-control" value="<?php echo $data['jml_anggota_aktif'] ?? ''; ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Total Anggota</label>
                                <input type="number" name="jml_anggota_seluruh" class="form-control" value="<?php echo $data['jml_anggota_seluruh'] ?? ''; ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Fasilitas Dimiliki</label>
                            <textarea name="fasilitas_dimiliki" class="form-control"><?php echo $data['fasilitas_dimiliki'] ?? ''; ?></textarea>
                        </div>
                         <div class="mb-3">
                            <label class="form-label">Prestasi</label>
                            <textarea name="prestasi" class="form-control"><?php echo $data['prestasi'] ?? ''; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bentuk/Ukuran Ruang Sekretariat</label>
                            <input type="text" name="ukuran_ruang" class="form-control" value="<?php echo $data['ukuran_ruang'] ?? ''; ?>">
                        </div>

                         <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jadwal Kegiatan/Latihan</label>
                                <input type="text" name="jadwal_kegiatan" class="form-control" value="<?php echo $data['jadwal_kegiatan'] ?? ''; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tempat Kegiatan/Latihan</label>
                                <input type="text" name="tempat_kegiatan" class="form-control" value="<?php echo $data['tempat_kegiatan'] ?? ''; ?>">
                            </div>
                        </div>

                         <div class="mb-3">
                            <label class="form-label">Kegiatan Besar Tahun Sebelumnya</label>
                            <textarea name="kegiatan_besar" class="form-control"><?php echo $data['kegiatan_besar'] ?? ''; ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Lampiran Foto Kegiatan (RAR/ZIP/JPG)</label>
                            <input type="file" name="foto_kegiatan" class="form-control">
                             <?php if(!empty($data['foto_kegiatan'])): ?>
                                <small class="text-success">File saat ini: <?php echo $data['foto_kegiatan']; ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Rencana Kegiatan Tahun Berikutnya</label>
                            <textarea name="rencana_kegiatan" class="form-control"><?php echo $data['rencana_kegiatan'] ?? ''; ?></textarea>
                        </div>

                         <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kegiatan Rutin</label>
                                <textarea name="kegiatan_rutin" class="form-control"><?php echo $data['kegiatan_rutin'] ?? ''; ?></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kegiatan Non Rutin</label>
                                <textarea name="kegiatan_non_rutin" class="form-control"><?php echo $data['kegiatan_non_rutin'] ?? ''; ?></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer bg-white text-end">
                        <button type="submit" name="simpan" class="btn btn-success px-5 fw-bold">SIMPAN</button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>