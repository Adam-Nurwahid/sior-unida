<?php 
// Naik 2 tingkat
include '../../header.php'; 
include '../../sidebar.php'; 
include '../../../config.php';

$id = $_POST['id'];
$nama_organisasi = $_POST['nama_organisasi'];
$singkatan = $_POST['singkatan'];
$visi = $_POST['visi'];
$misi = $_POST['misi'];
$alamat_sekretariat = $_POST['alamat_sekretariat'];
$alamat_surat = $_POST['alamat_surat'];
$fasilitas = $_POST['fasilitas'];
$prestasi = $_POST['prestasi'];
$jml_anggota_aktif = $_POST['jml_anggota_aktif'];
$jml_anggota_total = $_POST['jml_anggota_total'];
$email_organisasi = $_POST['email_organisasi'];

// Sosmed & Info Lain
$website = $_POST['website'];
$facebook = $_POST['facebook'];
$instagram = $_POST['instagram'];
$twitter = $_POST['twitter'];
$line = $_POST['line'];
$tahun_berdiri = $_POST['tahun_berdiri'];
$tipe_organisasi = $_POST['tipe_organisasi'];
$telp = $_POST['telp'];
$ruang_sekretariat = $_POST['ruang_sekretariat'];
$jadwal_kegiatan = $_POST['jadwal_kegiatan'];
$tempat_kegiatan = $_POST['tempat_kegiatan'];
$kegiatan_sebelumnya = $_POST['kegiatan_sebelumnya'];
$rencana_kegiatan = $_POST['rencana_kegiatan'];
$kegiatan_rutin = $_POST['kegiatan_rutin'];
$kegiatan_non_rutin = $_POST['kegiatan_non_rutin'];

// --- LOGIKA UPLOAD LOGO ---
$logo_query = "";
if ($_FILES['logo_organisasi']['name'] != "") {
    $nama_file_logo = $_FILES['logo_organisasi']['name'];
    $tmp_logo = $_FILES['logo_organisasi']['tmp_name'];
    $lokasi_simpan = "../../uploads/" . $nama_file_logo;
    move_uploaded_file($tmp_logo, $lokasi_simpan);
    
    $logo_query = ", logo_organisasi='$nama_file_logo'";
}

// --- LOGIKA UPLOAD FOTO KEGIATAN ---
$file_query = "";
if ($_FILES['foto_kegiatan']['name'] != "") {
    $nama_file_kegiatan = $_FILES['foto_kegiatan']['name'];
    $tmp_kegiatan = $_FILES['foto_kegiatan']['tmp_name'];
    $lokasi_simpan_file = "../../uploads/" . $nama_file_kegiatan;
    move_uploaded_file($tmp_kegiatan, $lokasi_simpan_file);
    
    $file_query = ", foto_kegiatan='$nama_file_kegiatan'";
}

// UPDATE QUERY
$query_update = "UPDATE profil_organisasi SET 
    nama_organisasi='$nama_organisasi',
    singkatan='$singkatan',
    visi='$visi',
    misi='$misi',
    alamat_sekretariat='$alamat_sekretariat',
    alamat_surat='$alamat_surat',
    fasilitas='$fasilitas',
    prestasi='$prestasi',
    jml_anggota_aktif='$jml_anggota_aktif',
    jml_anggota_total='$jml_anggota_total',
    email_organisasi='$email_organisasi',
    website='$website',
    facebook='$facebook',
    instagram='$instagram',
    twitter='$twitter',
    line='$line',
    tahun_berdiri='$tahun_berdiri',
    tipe_organisasi='$tipe_organisasi',
    telp='$telp',
    ruang_sekretariat='$ruang_sekretariat',
    jadwal_kegiatan='$jadwal_kegiatan',
    tempat_kegiatan='$tempat_kegiatan',
    kegiatan_sebelumnya='$kegiatan_sebelumnya',
    rencana_kegiatan='$rencana_kegiatan',
    kegiatan_rutin='$kegiatan_rutin',
    kegiatan_non_rutin='$kegiatan_non_rutin'
    $logo_query
    $file_query
    WHERE id='$id'";

if (mysqli_query($conn, $query_update)) {
    echo "<script>alert('Data Berhasil Diupdate'); window.location='profil.php';</script>";
} else {
    echo "Error updating record: " . mysqli_error($conn);
}

?>

<div class="flex-grow-1 p-4 bg-light">
    
    <nav class="navbar navbar-light bg-white shadow-sm mb-4 rounded">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Edit Profil</span>
        </div>
    </nav>

    <div class="card shadow-sm">
        <div class="card-body">
            
            <form action="proses_update_profil.php" method="POST" enctype="multipart/form-data">
                
                <div class="row">
                    <div class="col-md-6">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Organisasi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_organisasi" value="Himpunan Mahasiswa Teknik Informatika" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Singkatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="singkatan" value="HMPTI" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Visi Organisasi <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="visi" rows="3" required>Menjadi himpunan yang unggul dalam teknologi dan berakhlak mulia.</textarea>
                            <small class="text-muted fst-italic">Field ini bisa di-adjust panjang ke bawah.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Misi Organisasi <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="misi" rows="3" required>1. Mengembangkan minat bakat.</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat Sekretariat <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="alamat_sekretariat" rows="2" required>Gedung UKM Lt. 2</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Logo Organisasi Kemahasiswaan <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="logo_organisasi" accept="image/*">
                            <div class="mt-2">
                                <img src="https://via.placeholder.com/80" alt="Current Logo" class="img-thumbnail" width="80">
                                <small class="text-muted d-block">Logo saat ini</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat Surat/dihubungi <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="alamat_surat" rows="2" required>Jl. Raya Siman</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Fasilitas/Properti yang dimiliki <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="fasilitas" rows="2" required>Komputer, Printer</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Prestasi yang pernah diraih <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="prestasi" rows="2" required>Juara 1 Web Design</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Jumlah Anggota Aktif <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="jml_anggota_aktif" value="50" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Jumlah Anggota Seluruhnya <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="jml_anggota_total" value="150" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Organisasi <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email_organisasi" value="hmpti@unida.ac.id" required>
                        </div>

                    </div>

                    <div class="col-md-6">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Website</label>
                            <input type="text" class="form-control" name="website" value="hmpti.unida.ac.id">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Facebook</label>
                            <input type="text" class="form-control" name="facebook">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Instagram</label>
                            <input type="text" class="form-control" name="instagram" value="@hmpti_unida">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Twitter</label>
                            <input type="text" class="form-control" name="twitter">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Line</label>
                            <input type="text" class="form-control" name="line">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tahun Berdiri <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tahun_berdiri" value="2014-09-18">
                        </div>

                        <div class="mb-3">
    <label class="form-label fw-bold">Tipe Organisasi <span class="text-danger">*</span></label>
    <select class="form-select" name="id_tipe_organisasi">
        <option value="">-- Pilih Tipe --</option>
        <?php
        $query_tipe = mysqli_query($conn, "SELECT * FROM master_tipe_organisasi");
        
        while($tipe = mysqli_fetch_array($query_tipe)){
            $selected = ($data['id_tipe_organisasi'] == $tipe['id']) ? 'selected' : '';
            echo "<option value='".$tipe['id']."' $selected>".$tipe['nama_tipe']."</option>";
        }
        ?>
    </select>
</div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Telp/Fax <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="telp" value="081234567890">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Bentuk/ukuran ruang sekretariat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="ruang_sekretariat" value="Ruangan Kelas (6x6m)">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Jadwal kegiatan/latihan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="jadwal_kegiatan" value="Kamis & Jumat">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tempat kegiatan/latihan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="tempat_kegiatan" value="Lab Terpadu">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Kegiatan besar tahun sebelumnya <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="kegiatan_sebelumnya" rows="2">IT Fest</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Lampirkan foto kegiatan <span class="text-muted small">(Max 5MB)</span></label>
                            <input type="file" class="form-control" name="foto_kegiatan">
                            <small class="text-muted">File sebelumnya: <a href="#">FotoKegiatan.rar</a></small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Rencana kegiatan tahun berikutnya <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="rencana_kegiatan" rows="2">Hackathon</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Kegiatan rutin <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="kegiatan_rutin" value="Ngoding">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Kegiatan non rutin <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="kegiatan_non_rutin" value="Rihlah">
                        </div>

                    </div>
                </div>

                <hr>
                <div class="d-flex justify-content-end gap-2">
                    <a href="profil.php" class="btn btn-warning fw-bold text-uppercase" style="background-color: #F4B084; border-color: #F4B084; color: black; min-width: 120px;">Batal</a>
                    <button type="submit" class="btn btn-success fw-bold text-uppercase" style="background-color: #A9D08E; border-color: #A9D08E; color: black; min-width: 120px;">Simpan</button>
                </div>

            </form>

        </div>
    </div>
</div> 

<?php 
// Naik 2 tingkat
include '../../footer.php'; 
?>