<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 1. Cek username
    $result = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        // 2. LOGIKA CEK PASSWORD (HYBRID)
        // Cek A: Apakah cocok menggunakan Enkripsi (password_verify)?
        if (password_verify($password, $row['password'])) {
            $login_sukses = true;
        } 
        // Cek B: Jika gagal, apakah cocok dengan Plain Text (password lama)?
        else if ($password == $row['password']) {
            $login_sukses = true;
            
            // OPSIONAL: Otomatis update password jadi ter-enkripsi biar aman kedepannya
            $new_hash = password_hash($password, PASSWORD_DEFAULT);
            $uid = $row['id'];
            mysqli_query($koneksi, "UPDATE users SET password='$new_hash' WHERE id='$uid'");
        } 
        else {
            $login_sukses = false;
        }

        // 3. PROSES LOGIN
        if ($login_sukses) {
            $_SESSION['status'] = "login";
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['nama_lengkap'] = $row['nama_lengkap']; 
            $_SESSION['role'] = $row['role']; // admin / client
            
            header("Location: index.php");
            exit;
        } else {
            $error = true;
            $pesan_error = "Password salah!";
        }

    } else {
        $error = true;
        $pesan_error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login SIOR UNIDA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .login-card { width: 100%; max-width: 400px; padding: 30px; border-radius: 10px; }
    </style>
</head>
<body>

    <div class="card login-card shadow bg-white">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-primary">SIOR LOGIN</h3>
            <p class="text-muted">Masuk ke Sistem</p>
        </div>

        <?php if(isset($error)) : ?>
            <div class="alert alert-danger text-center mb-3" role="alert">
                <?php echo $pesan_error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            
            <div class="d-grid gap-2 mb-3">
                <button type="submit" name="login" class="btn btn-primary fw-bold">LOGIN</button>
            </div>

            <div class="text-center">
                <p class="mb-0">Belum punya akun?</p>
                <a href="register.php" class="fw-bold text-decoration-none">Daftarkan Organisasi</a>
            </div>
        </form>
    </div>

</body>
</html>