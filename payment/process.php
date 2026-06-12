<?php

header('Content-Type: application/json');

require_once '../config/midtrans.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {

    echo json_encode([
        'error' => 'Data kosong'
    ]);

    exit;

}

$harga = (int) floatval($data['harga']);

$params = [

    'transaction_details' => [
        'order_id' => 'ORDER-' . time(),
        'gross_amount' => $harga,
    ],

    'item_details' => [[
        'id' => $data['id'],
        'price' => $harga,
        'quantity' => 1,
        'name' => substr($data['judul'], 0, 50)
    ]]

];

try {

    $snapToken = \Midtrans\Snap::getSnapToken($params);

    echo json_encode([
        'token' => $snapToken
    ]);

} catch (Exception $e) {

    echo json_encode([
        'error' => $e->getMessage()
    ]);

}