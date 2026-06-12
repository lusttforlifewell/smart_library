<?php

session_start();

include '../../config/database.php';

if (!isset($_SESSION['user_id'])) {

    die("User belum login");

}

$user_id = $_SESSION['user_id'];

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {

    die("Cart kosong");

}

$cart = $_SESSION['cart'];

foreach ($cart as $item) {

    $ebook_id = (int)$item['id'];
    $qty = isset($item['qty']) ? (int)$item['qty'] : 1;
    $harga = isset($item['harga']) ? (float)$item['harga'] : 0;
    $total_harga = $harga * $qty;

    $cek = mysqli_query($koneksi, "
        SELECT id
        FROM transaksi_ebook
        WHERE user_id = '$user_id'
        AND ebook_id = '$ebook_id'
    ");

    if (mysqli_num_rows($cek) == 0) {

        $insertAccess = mysqli_query($koneksi, "
            INSERT INTO transaksi_ebook
            (
                user_id,
                ebook_id,
                tanggal_beli,
                status_pembayaran
            )
            VALUES
            (
                '$user_id',
                '$ebook_id',
                NOW(),
                'lunas'
            )
        ");

        if ($insertAccess && $total_harga > 0) {

            mysqli_query($koneksi, "
                INSERT INTO ebook_transactions
                (
                    user_id,
                    ebook_id,
                    tanggal_beli,
                    total_harga,
                    status_pembayaran
                )
                VALUES
                (
                    '$user_id',
                    '$ebook_id',
                    CURDATE(),
                    '$total_harga',
                    'lunas'
                )
            ");

        }

    }

}

echo "success";
