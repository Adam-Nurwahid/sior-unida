<?php
include '../../../config.php';

if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];

    if (empty($id)) {
        // --- LOGIKA INSERT (TAMBAH DATA BARU) ---
        $query = "INSERT INTO master_nama_bank (kode, nama_bank) VALUES ('$kode', '$nama')";
        $pesan = "Data berhasil ditambahkan!";
    } else {
        // --- LOGIKA UPDATE (EDIT DATA) ---
        $query = "UPDATE master_nama_bank SET kode = '$kode', nama_bank = '$nama' WHERE id = '$id'";
        $pesan = "Data berhasil diubah!";
    }

    $simpan = mysqli_query($conn, $query);

    if ($simpan) {
        echo "<script>
                alert('$pesan');
                window.location='nama_bank.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menyimpan data!');
                window.location='nama_bank.php';
              </script>";
    }
}


?>