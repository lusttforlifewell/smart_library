<?php
require_once '../../includes/auth_check.php';
include '../../config/database.php';
require_once '../../includes/log.php'; // 🔥 WAJIB

// 🔐 hanya super admin
if ($_SESSION['role'] !== 'super_admin') {
    header("Location: ../dashboard/index.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {

    // 🔍 ambil data user (biar tahu nama)
    $result = mysqli_query($koneksi, "SELECT nama, role FROM users WHERE id='$id'");
    $data   = mysqli_fetch_assoc($result);

    if ($data && $data['role'] !== 'super_admin') {

        // 🗑️ hapus user
        mysqli_query($koneksi, "DELETE FROM users WHERE id='$id'");

        // =========================
        // 🔥 LOG AKTIVITAS
        // =========================
        $userLogin = $_SESSION['nama']; // yang melakukan aksi
        $namaUser  = $data['nama'];     // yang dihapus

        logAktivitas($koneksi, $userLogin, "Menghapus user: $namaUser (ID: $id)");
    }
}

// 🔄 redirect
header("Location: index.php?status=deleted");
exit;