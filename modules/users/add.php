<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../includes/auth_check.php';
include '../../config/database.php';
require_once '../../includes/log.php'; // 🔥 WAJIB untuk aktivitas

// 🔐 hanya super admin
if ($_SESSION['role'] !== 'super_admin') {
    header("Location: ../dashboard/index.php");
    exit;
}

// 🔥 PROSES SIMPAN (HARUS DI ATAS)
if (isset($_POST['simpan'])) {

    // sanitasi sederhana
    $nama  = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $role  = $_POST['role'];

    // 🔐 gunakan hash yang lebih aman (disarankan)
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // 🔍 cek email sudah ada
    $cek = mysqli_query($koneksi, "SELECT id FROM users WHERE email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        header("Location: index.php?status=email_exists");
        exit;
    }

    // simpan user
    $insert = mysqli_query($koneksi, "
        INSERT INTO users (nama, email, password, role)
        VALUES ('$nama', '$email', '$password', '$role')
    ");

    if (!$insert) {
        die("Insert Error: " . mysqli_error($koneksi));
    }

    // =========================
    // 🔥 LOG AKTIVITAS
    // =========================
    $userLogin = $_SESSION['nama']; // yang melakukan aksi
    logAktivitas($koneksi, $userLogin, "Menambahkan user: $nama ($role)");

    // redirect (AMAN)
    header("Location: index.php?status=added");
    exit;
}

// BARU LOAD TAMPILAN
include '../../includes/header.php';
?>

<h2 class="text-xl font-bold mb-4">Tambah User</h2>

<form method="POST" class="bg-white p-6 rounded-xl shadow space-y-4">

    <input type="text" name="nama" placeholder="Nama" required
        class="w-full p-3 bg-gray-100 rounded">

    <input type="email" name="email" placeholder="Email" required
        class="w-full p-3 bg-gray-100 rounded">

    <input type="password" name="password" placeholder="Password" required
        class="w-full p-3 bg-gray-100 rounded">

    <select name="role" class="w-full p-3 bg-gray-100 rounded">
        <option value="admin">Admin</option>
        <option value="siswa">Siswa</option>
    </select>

    <button name="simpan"
        class="bg-blue-600 text-white px-4 py-2 rounded">
        Simpan
    </button>
</form>

<?php include '../../includes/footer.php'; ?>