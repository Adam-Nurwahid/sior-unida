<?php
session_start();
include '../koneksi.php';

// 1. Cek Login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "ID Kegiatan tidak ditemukan.";
    exit;
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// 2. Ambil Data Kegiatan & Profil Organisasi Penyelenggara
// Kita JOIN ke profil_organisasi untuk mengambil nama Ketua/Singkatan jika ada
$query = "SELECT k.*, u.nama_lengkap AS nama_akun, p.nama_organisasi, p.singkatan, p.nama_pembimbing, p.niy_pembimbing
          FROM trx_kegiatan k
          JOIN users u ON k.user_id = u.id
          LEFT JOIN profil_organisasi p ON k.user_id = p.user_id
          WHERE k.id = '$id'";

$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

// Validasi Akses
if (!$data) { die("Data tidak ditemukan."); }
if ($role != 'admin' && $data['user_id'] != $user_id) { die("Akses Ditolak."); }

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Cetak Ringkasan - <?php echo $data['nama_kegiatan']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #eee; /* Abu-abu di layar */
        }
        .page-sheet {
            background: white;
            width: 210mm; /* Ukuran A4 */
            min-height: 297mm;
            margin: 20px auto;
            padding: 20mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        /* CSS KHUSUS SAAT DICETAK (PRINT) */
        @media print {
            body {
                background: none;
                margin: 0;
            }
            .page-sheet {
                width: 100%;
                margin: 0;
                padding: 0;
                box-shadow: none;
                border: none;
            }
            .no-print {
                display: none !important;
            }
        }

        .kop-surat {
            border-bottom: 3px double black;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .ttd-box {
            margin-top: 50px;
            text-align: center;
        }
        .ttd-name {
            margin-top: 70px;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container text-center mt-3 mb-3 no-print">
        <a href="sirajin_list.php" class="btn btn-secondary">Kembali</a>
        <button onclick="window.print()" class="btn btn-primary fw-bold">
            Cetak Ringkasan (PDF)
        </button>
    </div>

    <div class="page-sheet">
        
        <div class="kop-surat d-flex align-items-center justify-content-center">
            <div class="text-center">
                <h5 class="mb-0 fw-bold">UNIVERSITAS DARUSSALAM GONTOR</h5>
                <h6 class="mb-0 fw-bold">DIREKTORAT KEPENGASUHAN & KEMAHASISWAAN</h6>
                <small>Jl. Raya Siman Km. 6, Ponorogo, Jawa Timur. Telp: (0352) 123456</small>
            </div>
        </div>

        <div class="text-center mb-4">
            <h4 class="fw-bold text-uppercase">RINGKASAN PROPOSAL KEGIATAN</h4>
            <p>Nomor Registrasi Sistem: #SRJ-<?php echo str_pad($data['id'], 4, '0', STR_PAD_LEFT); ?></p>
        </div>

        <table class="table table-bordered">
            <tr>
                <th width="30%" class="bg-light">Nama Organisasi</th>
                <td><?php echo $data['nama_organisasi'] ?? $data['nama_akun']; ?></td>
            </tr>
            <tr>
                <th class="bg-light">Nama Kegiatan</th>
                <td class="fw-bold"><?php echo $data['nama_kegiatan']; ?></td>
            </tr>
            <tr>
                <th class="bg-light">Waktu Pelaksanaan</th>
                <td>
                    <?php 
                    echo date('d F Y', strtotime($data['tgl_mulai']));
                    if($data['tgl_mulai'] != $data['tgl_selesai']) {
                        echo ' s/d ' . date('d F Y', strtotime($data['tgl_selesai']));
                    }
                    echo " (Pukul " . date('H:i', strtotime($data['waktu_mulai'])) . " WIB)";
                    ?>
                </td>
            </tr>
            <tr>
                <th class="bg-light">Tempat / Lokasi</th>
                <td><?php echo $data['detail_tempat']; ?></td>
            </tr>
            <tr>
                <th class="bg-light">Kebutuhan Listrik</th>
                <td><?php echo $data['kebutuhan_listrik']; ?> Watt</td>
            </tr>
            <tr>
                <th class="bg-light">Estimasi Anggaran</th>
                <td>Rp <?php echo number_format($data['estimasi_biaya'], 0, ',', '.'); ?></td>
            </tr>
            <tr>
                <th class="bg-light">Tujuan Kegiatan</th>
                <td><?php echo nl2br($data['tujuan']); ?></td>
            </tr>
        </table>

        <p class="mt-4">
            Demikian ringkasan proposal ini kami sampaikan sebagai bahan pertimbangan. Atas perhatian dan kerjasamanya kami ucapkan terima kasih.
        </p>

        <div class="row ttd-box">
            <div class="col-6">
                <p>Ketua Pelaksana,</p>
                <div class="ttd-name">
                    ( ..................................... )
                </div>
                <small>NIM: ...........................</small>
            </div>

            <div class="col-6">
                <p>Ketua <?php echo $data['singkatan'] ?? 'Ormawa'; ?>,</p>
                <div class="ttd-name">
                    ( ..................................... )
                </div>
                <small>NIM: ...........................</small>
            </div>
        </div>

        <div class="row ttd-box justify-content-center">
            <div class="col-8">
                <p>Mengetahui,<br>Pembimbing / Kaprodi</p>
                <div class="ttd-name">
                    ( <?php echo $data['nama_pembimbing'] ?? '.....................................'; ?> )
                </div>
                <small>NIY: <?php echo $data['niy_pembimbing'] ?? '...........................'; ?></small>
            </div>
        </div>

        <div class="mt-5 text-end fst-italic small text-muted">
            Dicetak melalui Sistem Informasi Organisasi (SIOR) pada <?php echo date('d-m-Y H:i'); ?>
        </div>

    </div>

</body>
</html>