<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; // Tanpa md5() sesuai data di screenshot

    // Cek ke tabel 'users'
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);

        // Simpan data penting ke Session
        $_SESSION['status'] = "login";
        $_SESSION['user_id'] = $data['id'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['nama_lengkap'] = $data['nama_lengkap']; 
        $_SESSION['role'] = $data['role']; 
        
        header("location:index.php");
    } else {
        echo "<script>alert('Username atau Password Salah!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login SIOR UNIDA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card p-4 shadow" style="width: 350px;">
        <h3 class="text-center mb-4">Login Sistem</h3>
        <form method="POST">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100">Masuk</button>
        </form>
    </div>
</body>
</html>