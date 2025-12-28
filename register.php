<?php
include 'koneksi.php';

if (isset($_POST['register'])) {
    $nama_lengkap = $_POST['nama_lengkap'];
    $username = $_POST['username'];
    
    // Enkripsi Password (Aman)
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    
    // PERBAIKAN: Set default role menjadi 'client'
    $role = 'client'; 

    // 1. Cek apakah username sudah ada?
    $cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    
    if (mysqli_num_rows($cek_user) > 0) {
        echo "<script>alert('Username sudah digunakan! Silakan cari username lain.');</script>";
    } else {
        // 2. Insert ke database
        $query = "INSERT INTO users (nama_lengkap, username, password, role) VALUES ('$nama_lengkap', '$username', '$password', '$role')";
        
        if (mysqli_query($koneksi, $query)) {
            echo "<script>
                    alert('Registrasi Berhasil! Silakan Login.');
                    window.location='login.php';
                  </script>";
        } else {
            echo "<script>alert('Registrasi Gagal: ".mysqli_error($koneksi)."');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Registrasi Organisasi - SIOR UNIDA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .card-register {
            width: 100%;
            max-width: 450px;
            padding: 20px;
            border-radius: 10px;
        }
    </style>
</head>
<body>

    <div class="card card-register shadow bg-white">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-primary">SIOR UNIDA</h3>
            <p class="text-muted">Registrasi Akun Organisasi (Client)</p>
        </div>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nama Organisasi / UKM</label>
                <input type="text" name="nama_lengkap" class="form-control" placeholder="Contoh: UKM Olahraga" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Buat username akun" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Buat password" required>
            </div>

            <div class="d-grid gap-2 mb-3">
                <button type="submit" name="register" class="btn btn-primary fw-bold">DAFTAR SEKARANG</button>
            </div>

            <div class="text-center">
                <p class="mb-0">Sudah punya akun? <a href="login.php" class="text-decoration-none">Login di sini</a></p>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>