<?php
session_start();
// Jika belum login, tendang ke halaman login
if (!isset($_SESSION['role'])) {
    header("Location: ../modules/auth/login.php");
    exit();
}
?>