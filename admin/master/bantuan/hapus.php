<?php
include '../../../config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus data berdasarkan ID
    $query = "DELETE FROM master_jenis_bantuan WHERE id = '$id'";
    $hapus = mysqli_query($conn, $query);

    if ($hapus) {
        echo "<script>
                alert('Data berhasil dihapus!');
                window.location='jenis_bantuan.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus data!');
                window.location='jenis_bantuan.php';
              </script>";
    }
}

?>