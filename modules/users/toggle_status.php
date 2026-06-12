<?php

include '../../config/database.php';

$id = (int) $_GET['id'];

$user = mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT status FROM users WHERE id = $id")
);

if(!$user){
    header("Location: index.php");
    exit;
}

$statusBaru = ($user['status'] == 'aktif')
    ? 'nonaktif'
    : 'aktif';

mysqli_query(
    $koneksi,
    "UPDATE users SET status='$statusBaru' WHERE id=$id"
);

header("Location: index.php?status=updated");
exit;