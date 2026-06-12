<?php

require_once '../config/database.php';

// AMBIL DATA JSON
$data = json_decode(file_get_contents("php://input"), true);

// CEK DATA
if(!$data){

    echo "Data kosong";

    exit;

}

$id = $data['id'];

// UPDATE STOK
$query = mysqli_query($koneksi, "
    UPDATE ebook
    SET stok = stok - 1
    WHERE id = '$id'
");

// RESPONSE
if($query){

    echo "success";

} else {

    echo mysqli_error($koneksi);

}