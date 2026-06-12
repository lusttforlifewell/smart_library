<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../includes/auth_check.php';
include '../../config/database.php';

if ($_SESSION['role'] !== 'super_admin') {
    header("Location: ../dashboard/index.php");
    exit;
}

include '../../includes/header.php';

$search = isset($_GET['search'])
    ? mysqli_real_escape_string($koneksi, $_GET['search'])
    : '';

$query = mysqli_query($koneksi, "
    SELECT * FROM users
    WHERE nama LIKE '%$search%'
    OR email LIKE '%$search%'
    ORDER BY id DESC
");

if (!$query) {
    die("Query Error: " . mysqli_error($koneksi));
}
?>

<!-- TOAST -->
<?php if(isset($_GET['status'])): ?>

    <?php if($_GET['status'] == 'added'): ?>
        <div id="toast" class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-white shadow-lg rounded-xl px-6 py-4 border-l-4 border-blue-500 z-50">
            <div class="flex items-center gap-3">
                <div class="bg-blue-500 text-white w-6 h-6 flex items-center justify-center rounded-full">✔</div>
                <div>
                    <p class="font-semibold">Berhasil</p>
                    <p class="text-sm text-gray-500">User berhasil ditambahkan</p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if($_GET['status'] == 'updated'): ?>
        <div id="toast" class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-white shadow-lg rounded-xl px-6 py-4 border-l-4 border-green-500 z-50">
            <div class="flex items-center gap-3">
                <div class="bg-green-500 text-white w-6 h-6 flex items-center justify-center rounded-full">✔</div>
                <div>
                    <p class="font-semibold">Berhasil</p>
                    <p class="text-sm text-gray-500">Status user berhasil diperbarui</p>
                </div>
            </div>
        </div>
    <?php endif; ?>

<?php endif; ?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Kelola User</h1>

    <a href="add.php" class="bg-blue-600 text-white px-5 py-2 rounded-xl">
        + Tambah User
    </a>
</div>

<form method="GET" class="mb-4">
    <input
        type="text"
        name="search"
        value="<?= htmlspecialchars($search) ?>"
        placeholder="Cari nama / email..."
        class="w-full bg-gray-100 px-4 py-3 rounded-xl">
</form>

<div class="bg-white rounded-xl shadow overflow-hidden">
<table class="w-full">
    <tr class="bg-gray-100">
        <th class="p-4">Nama</th>
        <th class="p-4">Email</th>
        <th class="p-4">Role</th>
        <th class="p-4">Status</th>
        <th class="p-4 text-center">Aksi</th>
    </tr>

<?php while($u = mysqli_fetch_assoc($query)): ?>
    <tr class="border-t hover:bg-gray-50">
        <td class="p-4"><?= htmlspecialchars($u['nama']) ?></td>
        <td class="p-4"><?= htmlspecialchars($u['email']) ?></td>

        <td class="p-4">
            <span class="px-3 py-1 rounded-full text-sm
            <?= $u['role']=='super_admin' ? 'bg-purple-100 text-purple-600' : '' ?>
            <?= $u['role']=='admin' ? 'bg-blue-100 text-blue-600' : '' ?>
            <?= $u['role']=='siswa' ? 'bg-green-100 text-green-600' : '' ?>">
                <?= ucfirst($u['role']) ?>
            </span>
        </td>

        <td class="p-4">
            <?php if($u['status'] == 'aktif'): ?>
                <span class="px-3 py-1 rounded-full bg-green-100 text-green-600 text-sm">
                    Aktif
                </span>
            <?php else: ?>
                <span class="px-3 py-1 rounded-full bg-red-100 text-red-600 text-sm">
                    Nonaktif
                </span>
            <?php endif; ?>
        </td>

        <td class="p-4 text-center">
            <a href="edit.php?id=<?= $u['id'] ?>" class="text-blue-600">
                Edit
            </a>

            <?php if($u['role'] !== 'super_admin'): ?>
                |

                <a
                    href="toggle_status.php?id=<?= $u['id'] ?>"
                    class="<?= $u['status'] == 'aktif'
                        ? 'text-red-600'
                        : 'text-green-600' ?>">

                    <?= $u['status'] == 'aktif'
                        ? 'Nonaktifkan'
                        : 'Aktifkan' ?>

                </a>
            <?php endif; ?>
        </td>
    </tr>
<?php endwhile; ?>

</table>
</div>

<script>
setTimeout(() => {
    let toast = document.getElementById('toast');
    if (toast) toast.remove();
}, 3000);
</script>

<?php include '../../includes/footer.php'; ?>