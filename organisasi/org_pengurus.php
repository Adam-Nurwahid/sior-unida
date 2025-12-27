<?php
session_start();
include '../koneksi.php'; 

// 1. Cek Login & Role
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../login.php");
    exit;
}
// Keamanan: Admin DKP tidak boleh masuk sini
if ($_SESSION['role'] == 'admin') {
    echo "<script>alert('Akses Ditolak! Halaman ini khusus Organisasi.'); window.location.href='../index.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// --- LOGIKA 1: SIMPAN INFO PEMBIMBING & PERIODE ---
if (isset($_POST['simpan_info'])) {
    $p_mulai = $_POST['periode_mulai'];
    $p_selesai = $_POST['periode_selesai'];
    $pembimbing = $_POST['nama_pembimbing'];
    $niy = $_POST['niy_pembimbing'];
    
    // Upload SK
    $file_sk = ""; 
    if(!empty($_FILES['file_sk']['name'])){
        $file_sk = time() . '_SK_' . $_FILES['file_sk']['name'];
        move_uploaded_file($_FILES['file_sk']['tmp_name'], "../uploads/" . $file_sk);
    }

    // Cek apakah data profil sudah ada
    $cek = mysqli_query($koneksi, "SELECT * FROM profil_organisasi WHERE user_id='$user_id'");
    if(mysqli_num_rows($cek) > 0){
        // UPDATE
        $sql_update = "UPDATE profil_organisasi SET 
                       periode_mulai='$p_mulai', periode_selesai='$p_selesai', 
                       nama_pembimbing='$pembimbing', niy_pembimbing='$niy'";
        if($file_sk != "") {
            $sql_update .= ", file_sk='$file_sk'"; 
        }
        $sql_update .= " WHERE user_id='$user_id'";
        mysqli_query($koneksi, $sql_update);
    } else {
        // INSERT (Jaga-jaga)
        mysqli_query($koneksi, "INSERT INTO profil_organisasi (user_id, periode_mulai, periode_selesai, nama_pembimbing, niy_pembimbing, file_sk) VALUES ('$user_id', '$p_mulai', '$p_selesai', '$pembimbing', '$niy', '$file_sk')");
    }
    echo "<script>alert('Info Kepengurusan Disimpan!'); window.location='org_pengurus.php';</script>";
}

// --- LOGIKA 2: TAMBAH ANGGOTA PENGURUS ---
if (isset($_POST['tambah_anggota'])) {
    $id_jabatan = $_POST['id_jabatan'];
    $nama = $_POST['nama_lengkap'];
    $nim = $_POST['nim'];
    $prodi = $_POST['prodi'];
    $email = $_POST['email'];

    $simpan = mysqli_query($koneksi, "INSERT INTO org_pengurus (user_id, id_jabatan, nama_lengkap, nim, prodi, email) VALUES ('$user_id', '$id_jabatan', '$nama', '$nim', '$prodi', '$email')");
    
    if($simpan) {
        // Refresh halaman
        echo "<script>window.location='org_pengurus.php';</script>";
    } else {
        echo "<script>alert('Gagal menambah anggota!');</script>";
    }
}

// --- LOGIKA 3: HAPUS ANGGOTA ---
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM org_pengurus WHERE id='$id_hapus' AND user_id='$user_id'");
    echo "<script>window.location='org_pengurus.php';</script>";
}

// AMBIL DATA PROFIL UTAMA
$q_profil = mysqli_query($koneksi, "SELECT * FROM profil_organisasi WHERE user_id='$user_id'");
$d_profil = mysqli_fetch_array($q_profil);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Pengurus Organisasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="d-flex">
    <?php include '../sidebar.php'; ?>

    <div class="p-4 w-100 bg-light" style="height: 100vh; overflow-y: auto;">
        <div class="container-fluid">
            
            <h2 class="mb-4 fw-bold text-primary">Pengurus Organisasi</h2>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-dark">SUSUNAN KEPENGURUSAN</h6>
                </div>
                <div class="card-body">
                    
                    <form method="POST" class="row g-2 mb-3 align-items-end p-3 bg-light border rounded">
                        <div class="col-md-2">
                            <label class="form-label small">Jabatan</label>
                            <select name="id_jabatan" class="form-select form-select-sm" required>
                                <option value="">- Pilih -</option>
                                <?php
                                // Mengambil kolom 'nama' dari mst_jenis_jabatan
                                $q_jbt = mysqli_query($koneksi, "SELECT * FROM mst_jenis_jabatan");
                                while($j = mysqli_fetch_array($q_jbt)){
                                    echo "<option value='".$j['id']."'>".$j['nama']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">NIM</label>
                            <input type="text" name="nim" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Prodi</label>
                            <input type="text" name="prodi" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Email</label>
                            <input type="email" name="email" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" name="tambah_anggota" class="btn btn-sm btn-primary w-100" title="Tambah">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Jabatan</th>
                                    <th>Nama</th>
                                    <th>NIM</th>
                                    <th>Prodi</th>
                                    <th>Email</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                // JOIN tabel pengurus dengan master jabatan untuk ambil nama jabatan
                                $query_string = "SELECT p.*, j.nama 
                 FROM org_pengurus p 
                 LEFT JOIN mst_jenis_jabatan j ON p.id_jabatan = j.id
                 WHERE p.user_id='$user_id'
                 ORDER BY j.id ASC";

$q_list = mysqli_query($koneksi, $query_string);

// --- TAMBAHKAN PENGECEKAN ERROR INI ---
if (!$q_list) {
    die("<b>SQL Error:</b> " . mysqli_error($koneksi)); 
}
// --------------------------------------

// Jika kosong
if(mysqli_num_rows($q_list) == 0){
    echo "<tr><td colspan='7' class='text-center text-muted'>Belum ada data pengurus.</td></tr>";
}

                                while($row = mysqli_fetch_array($q_list)){
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td><?php echo $row['nama']; ?></td>
                                    <td><?php echo $row['nama_lengkap']; ?></td>
                                    <td><?php echo $row['nim']; ?></td>
                                    <td><?php echo $row['prodi']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td class="text-center">
                                        <a href="org_pengurus.php?hapus=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus anggota ini?')">
                                            <i class="bi bi-trash"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <form method="POST" enctype="multipart/form-data">
                <div class="card shadow-sm mb-5">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 fw-bold text-dark">INFO PERIODE & PEMBIMBING</h6>
                    </div>
                    <div class="card-body">
                        
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Periode Pergantian Pengurus</label>
                            <div class="col-sm-4">
                                <input type="datetime-local" name="periode_mulai" class="form-control" value="<?php echo $d_profil['periode_mulai'] ?? ''; ?>">
                            </div>
                            <div class="col-sm-1 text-center align-self-center">s/d</div>
                            <div class="col-sm-4">
                                <input type="datetime-local" name="periode_selesai" class="form-control" value="<?php echo $d_profil['periode_selesai'] ?? ''; ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Nama Pembimbing *</label>
                            <div class="col-sm-9">
                                <input type="text" name="nama_pembimbing" class="form-control" value="<?php echo $d_profil['nama_pembimbing'] ?? ''; ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">NIY Pembimbing</label>
                            <div class="col-sm-9">
                                <input type="text" name="niy_pembimbing" class="form-control" value="<?php echo $d_profil['niy_pembimbing'] ?? ''; ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Lampiran Kesediaan (PDF)</label>
                            <div class="col-sm-9">
                                <input type="file" name="file_sk" class="form-control">
                                <?php if(!empty($d_profil['file_sk'])): ?>
                                    <div class="mt-2 text-success">
                                        <i class="bi bi-file-earmark-check"></i> File Tersimpan: <?php echo $d_profil['file_sk']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer bg-white text-end">
                        <button type="submit" name="simpan_info" class="btn btn-success px-4">SIMPAN INFO</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>