<?php
include '../../../config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus data berdasarkan ID
    $query = "DELETE FROM master_lokasi_kegiatan WHERE id = '$id'";
    $hapus = mysqli_query($conn, $query);

    if ($hapus) {
        echo "<script>
                alert('Data berhasil dihapus!');
                window.location='lokasi_kegiatan.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus data!');
                window.location='lokasi_kegiatan.php';
              </script>";
    }
}

?>