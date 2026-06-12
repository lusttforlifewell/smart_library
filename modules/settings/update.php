<?php
require_once '../../config/database.php';

$field = key($_POST);
$value = $_POST[$field];

$allowed = [
    'nama_aplikasi',
    'nama_instansi',
    'lama_pinjam',
    'max_pinjam',
    'perpanjang_aktif',
    'notifikasi_aktif'
];

if (in_array($field, $allowed)) {
    mysqli_query($koneksi, "
        UPDATE pengaturan SET $field='$value' WHERE id=1
    ");
}