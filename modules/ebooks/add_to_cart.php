<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include '../../config/database.php';

/*
|--------------------------------------------------------------------------
| VALIDASI ID
|--------------------------------------------------------------------------
*/

if (!isset($_GET['id'])) {

    die("Ebook ID not found");

}

$id = intval($_GET['id']);

/*
|--------------------------------------------------------------------------
| AMBIL DATA EBOOK
|--------------------------------------------------------------------------
*/

$query = mysqli_query($koneksi, "
    SELECT *
    FROM ebook
    WHERE id = '$id'
");

if (!$query) {

    die(mysqli_error($koneksi));

}

if (mysqli_num_rows($query) == 0) {

    die("Ebook data not found");

}

$data = mysqli_fetch_assoc($query);

/*
|--------------------------------------------------------------------------
| SESSION CART
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['cart'])) {

    $_SESSION['cart'] = [];

}

/*
|--------------------------------------------------------------------------
| CEK APAKAH SUDAH ADA DI CART
|--------------------------------------------------------------------------
*/

$found = false;

foreach ($_SESSION['cart'] as &$item) {

    if ($item['id'] == $id) {

        if (!isset($item['qty'])) {

            $item['qty'] = 1;

        }

        $item['qty']++;

        $found = true;

        break;

    }

}

/*
|--------------------------------------------------------------------------
| JIKA BELUM ADA → TAMBAHKAN
|--------------------------------------------------------------------------
*/

if (!$found) {

    $_SESSION['cart'][] = [

        'id'     => $data['id'],
        'judul'  => $data['judul'],
        'harga'  => $data['harga'],
        'cover'  => $data['cover'],
        'qty'    => 1

    ];

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Success</title>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>

body{
    background: #f1f5f9;
    font-family: Arial, sans-serif;
}

</style>

</head>

<body>

<script>

Swal.fire({

    icon: 'success',

    title: 'Successfully Added!',

    html: `
        <div style="font-size:15px;color:#555;margin-top:10px;">
            Ebook <b><?php echo htmlspecialchars($data['judul']); ?></b>
            has been added to your cart.
        </div>
    `,

    showCancelButton: true,

    confirmButtonText: '🛒 View Cart',

    cancelButtonText: '📚 Continue Shopping',

    confirmButtonColor: '#16a34a',

    cancelButtonColor: '#6366f1',

    background: '#ffffff',

    borderRadius: '20px'

}).then((result) => {

    if (result.isConfirmed) {

        window.location.href = 'cart.php';

    } else {

        window.location.href = 'index.php';

    }

});

</script>

</body>
</html>