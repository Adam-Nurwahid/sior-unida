<?php
include '../../../config.php';

if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];

    if (empty($id)) {
        // --- LOGIKA INSERT (TAMBAH DATA BARU) ---
        $query = "INSERT INTO master_jenis_kepemilikan (kode, nama_jenis_kepemilikan) VALUES ('$kode', '$nama')";
        $pesan = "Data berhasil ditambahkan!";
    } else {
        // --- LOGIKA UPDATE (EDIT DATA) ---
        $query = "UPDATE master_jenis_kepemilikan SET kode = '$kode', nama_jenis_kepemilikan = '$nama' WHERE id = '$id'";
        $pesan = "Data berhasil diubah!";
    }

    $simpan = mysqli_query($conn, $query);

    if ($simpan) {
        echo "<script>
                alert('$pesan');
                window.location='kepemilikan.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menyimpan data!');
                window.location='kepemilikan.php';
              </script>";
    }
}


?>