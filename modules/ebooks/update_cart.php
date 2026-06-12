<?php

session_start();

if (!isset($_GET['id']) || !isset($_GET['action'])) {

    die("Parameter tidak lengkap");

}

$id = intval($_GET['id']);
$action = $_GET['action'];

if (!isset($_SESSION['cart'])) {

    $_SESSION['cart'] = [];

}

foreach ($_SESSION['cart'] as $key => &$item) {

    if ($item['id'] == $id) {

        // TAMBAH
        if ($action == 'plus') {

            $item['qty']++;

        }

        // KURANG
        if ($action == 'minus') {

            $item['qty']--;

            // HAPUS JIKA 0
            if ($item['qty'] <= 0) {

                unset($_SESSION['cart'][$key]);

            }

        }

        break;

    }

}

header("Location: cart.php");
exit;

?>