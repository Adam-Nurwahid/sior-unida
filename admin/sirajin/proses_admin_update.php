<?php
include '../../config.php';

if(isset($_POST['update_status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $catatan = $_POST['catatan'];

    $query = "UPDATE tb_kegiatan SET status_perizinan='$status', catatan_admin='$catatan' WHERE id='$id'";

    if(mysqli_query($conn, $query)) {
        echo "<script>alert('Status berhasil diperbarui!'); window.location='kegiatan_admin.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>