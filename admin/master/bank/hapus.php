<?php
include '../../../config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus data berdasarkan ID
    $query = "DELETE FROM master_nama_bank WHERE id = '$id'";
    $hapus = mysqli_query($conn, $query);

    if ($hapus) {
        echo "<script>
                alert('Data berhasil dihapus!');
                window.location='nama_bank.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus data!');
                window.location='nama_bank.php';
              </script>";
    }
}

?>