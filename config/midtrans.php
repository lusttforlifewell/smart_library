<?php

require_once dirname(__DIR__) . '/config/env.php';
require_once dirname(__DIR__) . '/midtrans/Midtrans.php';

$serverKey = getenv('MIDTRANS_SERVER_KEY');
if ($serverKey === false) {
    $serverKey = $_ENV['MIDTRANS_SERVER_KEY'] ?? $_SERVER['MIDTRANS_SERVER_KEY'] ?? null;
}

$clientKey = getenv('MIDTRANS_CLIENT_KEY');
if ($clientKey === false) {
    $clientKey = $_ENV['MIDTRANS_CLIENT_KEY'] ?? $_SERVER['MIDTRANS_CLIENT_KEY'] ?? null;
}

$serverKey = $serverKey !== null ? trim($serverKey) : null;
$clientKey = $clientKey !== null ? trim($clientKey) : null;

if (empty($serverKey) || empty($clientKey)) {
    error_log('Midtrans config: failed to load Midtrans key values from environment.');
    error_log('Midtrans config: MIDTRANS_SERVER_KEY ' . (empty($serverKey) ? 'MISSING' : 'OK'));
    error_log('Midtrans config: MIDTRANS_CLIENT_KEY ' . (empty($clientKey) ? 'MISSING' : 'OK'));
}

\Midtrans\Config::$serverKey = $serverKey;
\Midtrans\Config::$clientKey = $clientKey;
\Midtrans\Config::$curlOptions = [
    CURLOPT_CAINFO => dirname(__DIR__) . '/midtrans/data/cacert.pem',
];
\Midtrans\Config::$isProduction = true;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;
