<?php
session_start();
if ($_SESSION['status'] != "login") {
    header("location:login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

    <div class="d-flex">
        
        <?php include 'sidebar.php'; ?>

        <div class="p-4 w-100 bg-light">
            <div class="container-fluid">
                <h2 class="mb-4">Selamat Datang, Admin!</h2>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Karyawan</h5>
                                <p class="card-text display-4 fw-bold">
                                    <?php 
                                    include 'koneksi.php';
                                    $data = mysqli_query($koneksi, "SELECT * FROM karyawan");
                                    echo mysqli_num_rows($data);
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    </div>

                <div class="alert alert-info mt-3">
                    Silakan pilih menu <strong>Data Karyawan</strong> di sebelah kiri untuk mengelola data.
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>