<?php

session_start();

include '../../config/database.php';

if(isset($_SESSION['cart'])){

    foreach($_SESSION['cart'] as $item){

        $id = $item['id'];

        $qty = isset($item['qty']) ? $item['qty'] : 1;

        // KURANGI STOK
        mysqli_query($koneksi, "

            UPDATE ebook 
            SET stok = stok - $qty
            WHERE id = '$id'

        ");

    }

    // HAPUS CART
    unset($_SESSION['cart']);

}

echo "success";