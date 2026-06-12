<?php

session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require_once __DIR__ . '/../../config/midtrans.php';




// ==============================
// AMBIL CART
// ==============================

$cart = $_SESSION['cart'] ?? [];

if(empty($cart)){

    echo json_encode([
        'error' => 'Cart kosong'
    ]);

    exit;

}


// ==============================
// HITUNG TOTAL
// ==============================

$total = 0;

$item_details = [];

foreach($cart as $item){

    $qty = isset($item['qty']) ? (int)$item['qty'] : 1;

    $harga = (int)$item['harga'];

    $subtotal = $harga * $qty;

    $total += $subtotal;

    $item_details[] = [

        'id' => 'BOOK-' . $item['id'],

        'price' => $harga,

        'quantity' => $qty,

        'name' => substr($item['judul'], 0, 50)

    ];

}


// ==============================
// VALIDASI TOTAL
// ==============================

if($total <= 0){

    echo json_encode([
        'error' => 'Total pembayaran tidak valid'
    ]);

    exit;

}


// ==============================
// ORDER ID UNIK
// ==============================

$order_id = 'ORDER-' . time() . '-' . rand(1000,9999);


// ==============================
// PARAMETER MIDTRANS
// ==============================

$params = [

    'transaction_details' => [

        'order_id' => $order_id,

        'gross_amount' => $total

    ],

    'item_details' => $item_details,

    'customer_details' => [

        'first_name' => 'Siswa',

        'email' => 'siswa@gmail.com'

    ]

];


// ==============================
// GENERATE TOKEN
// ==============================

try{

    $snapToken = \Midtrans\Snap::getSnapToken($params);

    echo json_encode([

        'token' => $snapToken

    ]);

}catch(Exception $e){

    echo json_encode([

        'error' => $e->getMessage()

    ]);

}

?>