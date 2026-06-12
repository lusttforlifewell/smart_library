<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/notification_helper.php';

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['user_id'])){
    exit;
}

$user_id = $_SESSION['user_id'];
$role    = strtolower(trim($_SESSION['role']));

// ==========================
// QUERY NOTIF
// ==========================
$notifQuery = smartLibraryGetNotificationQuery($koneksi, $user_id);

?>

<?php smartLibraryRenderNotificationList($notifQuery); ?>
