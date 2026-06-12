<?php
require_once '../../includes/auth_check.php';
include '../../config/database.php';

// 🔥 VALIDASI DULU (JANGAN DI BAWAH)
if ($_SESSION['role'] !== 'super_admin') {
    header("Location: ../dashboard/index.php");
    exit;
}

// 🔥 AMBIL DATA USER
$id = $_GET['id'] ?? 0;

$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM users WHERE id='$id'"));

if (!$data) {
    die("User tidak ditemukan");
}

// 🔥 PROSES UPDATE (HARUS DI ATAS JUGA)
if (isset($_POST['update'])) {

    $nama  = $_POST['nama'];
    $email = $_POST['email'];
    $role  = $_POST['role'];

    mysqli_query($koneksi, "
        UPDATE users 
        SET nama='$nama', email='$email', role='$role'
        WHERE id='$id'
    ");

    // 🔥 REDIRECT (AMAN karena belum ada output)
    header("Location: index.php");
    exit;
}

// BARU LOAD TAMPILAN
include '../../includes/header.php';
?>

<h2 class="text-xl font-bold mb-4">Edit User</h2>

<form method="POST" class="bg-white p-6 rounded-xl shadow space-y-4">

    <input type="text" name="nama" value="<?= $data['nama'] ?>"
        class="w-full p-3 bg-gray-100 rounded">

    <input type="email" name="email" value="<?= $data['email'] ?>"
        class="w-full p-3 bg-gray-100 rounded">

    <select name="role" class="w-full p-3 bg-gray-100 rounded">
        <option value="admin" <?= $data['role']=='admin'?'selected':'' ?>>Admin</option>
        <option value="siswa" <?= $data['role']=='siswa'?'selected':'' ?>>Siswa</option>
        <option value="super_admin" <?= $data['role']=='super_admin'?'selected':'' ?>>Super Admin</option>
    </select>

    <button name="update"
        class="bg-green-600 text-white px-4 py-2 rounded">
        Update
    </button>
</form>

<?php include '../../includes/footer.php'; ?>