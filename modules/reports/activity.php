<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../includes/auth_check.php';
include '../../config/database.php';

// 🔐 hanya super admin
if ($_SESSION['role'] !== 'super_admin') {
    header("Location: ../dashboard/index.php");
    exit;
}

include '../../includes/header.php';

// 🔍 ambil data
$query = mysqli_query($koneksi, "SELECT * FROM aktivitas ORDER BY id DESC");

if (!$query) {
    die("Query Error: " . mysqli_error($koneksi));
}
?>

<!-- 🔥 TOAST RESET -->
<?php if(isset($_GET['status']) && $_GET['status']=='reset'): ?>
<div id="toast" class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-white shadow-lg rounded-xl px-6 py-4 border-l-4 border-red-500 z-50">
    <div class="flex items-center gap-3">
        <div class="bg-red-500 text-white w-6 h-6 flex items-center justify-center rounded-full">✔</div>
        <div>
            <p class="font-semibold">Succes</p>
            <p class="text-sm text-gray-500">All activity has been successfully deleted</p>
        </div>
    </div>
</div>
<?php endif; ?>

<h1 class="text-2xl font-bold mb-6">System Activity</h1>

<div class="bg-white rounded-xl shadow overflow-hidden">
<table class="w-full">
<tr class="bg-gray-100 text-left">
    <th class="p-4">User</th>
    <th class="p-4">Aktivity</th>
    <th class="p-4">Time</th>
</tr>

<?php if(mysqli_num_rows($query) > 0): ?>
    <?php while($a = mysqli_fetch_assoc($query)): ?>
    <tr class="border-t hover:bg-gray-50">
        <td class="p-4"><?= htmlspecialchars($a['user']) ?></td>
        <td class="p-4"><?= htmlspecialchars($a['aksi']) ?></td>
        <td class="p-4"><?= date('d M Y H:i', strtotime($a['created_at'])) ?></td>
    </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr>
        <td colspan="3" class="p-6 text-center text-gray-500">
            There is no system activity yet
        </td>
    </tr>
<?php endif; ?>
</table>
</div>

<!-- 🔥 BUTTON RESET (KANAN BAWAH) -->
<button onclick="openResetModal()" 
    class="fixed bottom-5 right-5 bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-full shadow-lg z-50">
    Reset
</button>

<!-- 🔥 MODAL RESET -->
<div id="resetModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded-xl shadow-lg w-80 text-center">
        <h2 class="text-lg font-bold mb-3">Reset Activity</h2>
        <p class="mb-4 text-gray-600">Are you sure you want to delete all activities?</p>

        <div class="flex justify-center gap-3">
            <button onclick="closeResetModal()" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>

            <a href="reset.php" class="px-4 py-2 bg-red-600 text-white rounded">
                Delete
            </a>
        </div>
    </div>
</div>

<script>
function openResetModal() {
    document.getElementById('resetModal').classList.remove('hidden');
    document.getElementById('resetModal').classList.add('flex');
}

function closeResetModal() {
    document.getElementById('resetModal').classList.add('hidden');
}

// auto hilang toast
setTimeout(() => {
    let toast = document.getElementById('toast');
    if (toast) toast.remove();
}, 3000);
</script>

<?php include '../../includes/footer.php'; ?>