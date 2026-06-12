<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/notification_helper.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !smartLibraryNotificationTableExists($koneksi)) {
    echo json_encode([
        'success' => false
    ]);
    exit;
}

$user_id = intval($_SESSION['user_id']);

mysqli_query($koneksi, "
    UPDATE notifikasi
    SET dibaca = 1
    WHERE user_id = '$user_id'
");

echo json_encode([
    'success' => true
]);

?>
