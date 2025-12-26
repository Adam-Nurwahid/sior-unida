<?php
session_start();
include 'config.php'; // Pastikan path config.php benar

// Cek jika sudah login, langsung lempar ke dashboard
if (isset($_SESSION['login'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin/index.php"); // Sesuaikan halaman admin utama
    } else {
        header("Location: student/index.php"); // Sesuaikan halaman student utama
    }
    exit;
}

// Proses Login hanya jika tombol ditekan
if (isset($_POST['login'])) {
    
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query Cek User
    // Gunakan mysqli_real_escape_string untuk keamanan sederhana
    $username = mysqli_real_escape_string($conn, $username);
    
    // Asumsi tabel user bernama 'tb_users' atau 'users', sesuaikan dengan database Anda
    // Dan kolom username/password sesuai database
    $query = "SELECT * FROM tb_users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);


    // Cek apakah user ditemukan
    if (mysqli_num_rows($result) === 1) {
        
        // Ambil data user
        $row = mysqli_fetch_assoc($result);
        
        // Verifikasi Password (Jika di database password polos/tidak di-hash, pakai: if ($password == $row['password']))
        // Jika pakai password_hash, pakai: if (password_verify($password, $row['password']))
        if ($password == $row['password']) { 
            
            // Set Session
            $_SESSION['login'] = true;
            $_SESSION['id_user'] = $row['id'];
            $_SESSION['nama_lengkap'] = $row['nama_lengkap']; // Pastikan kolom ini ada di DB
            $_SESSION['role'] = $row['role']; // admin / student

            // Redirect sesuai Role
            if ($row['role'] == 'admin') {
                header("Location: admin/sirajin/kegiatan_admin.php");
            } else if ($row['role'] == 'student') {
                header("Location: student/sirajin/kegiatan_mhs.php");
            } else {
                echo "<script>alert('Role tidak dikenali!');</script>";
            }
            exit;
        }
    }
    
    $error = true; // Flag untuk menampilkan pesan error di HTML bawah
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIRAJIN & SIPANDAI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .login-card { max-width: 400px; margin: 100px auto; }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow login-card">
        <div class="card-body p-4">
            <h4 class="text-center mb-4">Login System</h4>
            
            <?php if (isset($error)) : ?>
                <div class="alert alert-danger text-center">
                    Username atau Password Salah!
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100">Masuk</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>