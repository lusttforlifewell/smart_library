<?php
require_once '../../includes/auth_check.php';
include '../../config/database.php';

if ($_SESSION['role'] !== 'super_admin') {
    header("Location: ../dashboard/index.php");
    exit;
}

// hapus semua aktivitas
mysqli_query($koneksi, "TRUNCATE TABLE aktivitas");

// redirect + status
header("Location: activity.php?status=reset");
exit;