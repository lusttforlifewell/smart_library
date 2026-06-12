<?php
ob_start();
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';

if ($_SESSION['role'] !== 'super_admin') {
    header("Location: ../dashboard/");
    exit;
}

$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM pengaturan LIMIT 1"));

include '../../includes/header.php';
?>

<div class="p-6 bg-gradient-to-br from-slate-100 to-slate-200 min-h-screen">

    <!-- TITLE -->
    <h1 class="text-3xl font-bold mb-8 flex items-center gap-3">
        ⚙️ <span>System Settings</span>
    </h1>

    <!-- GRID -->
    <div class="flex flex-col gap-6 max-w-3xl">

        <!-- GENERAL -->
        <div class="bg-white/80 backdrop-blur p-6 rounded-2xl shadow-lg hover:shadow-xl transition">
            <h2 class="font-semibold text-lg mb-5 text-pink-500">📌 General</h2>

            <div class="mb-4">
                <label class="text-sm text-gray-500">App Name</label>
                <input type="text" value="<?= $data['nama_aplikasi'] ?>"
                onchange="updateSetting('nama_aplikasi', this.value)"
                class="w-full mt-1 bg-gray-100 p-3 rounded-xl focus:ring-2 focus:ring-pink-400 outline-none">
            </div>

            <div>
                <label class="text-sm text-gray-500">Institution</label>
                <input type="text" value="<?= $data['nama_instansi'] ?>"
                onchange="updateSetting('nama_instansi', this.value)"
                class="w-full mt-1 bg-gray-100 p-3 rounded-xl focus:ring-2 focus:ring-pink-400 outline-none">
            </div>
        </div>

        <!-- BORROW -->
        <div class="bg-white/80 backdrop-blur p-6 rounded-2xl shadow-lg hover:shadow-xl transition">
            <h2 class="font-semibold text-lg mb-5 text-blue-500">📚 Borrow Settings</h2>

            <div class="flex justify-between items-center mb-4">
                <span class="text-gray-600">Loan Duration</span>
                <input type="number" value="<?= $data['lama_pinjam'] ?>"
                onchange="updateSetting('lama_pinjam', this.value)"
                class="bg-gray-100 px-4 py-2 rounded-xl w-20 text-center focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="flex justify-between items-center mb-4">
                <span class="text-gray-600">Max Borrow</span>
                <input type="number" value="<?= $data['max_pinjam'] ?>"
                onchange="updateSetting('max_pinjam', this.value)"
                class="bg-gray-100 px-4 py-2 rounded-xl w-20 text-center focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="flex justify-between items-center">
                <span class="text-gray-600">Extend Allowed</span>

                <!-- TOGGLE -->
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox"
                    <?= $data['perpanjang_aktif'] ? 'checked' : '' ?>
                    onchange="updateSetting('perpanjang_aktif', this.checked ? 1 : 0)"
                    class="sr-only peer">

                    <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-blue-500 transition"></div>
                    <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition peer-checked:translate-x-5"></div>
                </label>
            </div>
        </div>

        <!-- NOTIF -->
        <div class="bg-white/80 backdrop-blur p-6 rounded-2xl shadow-lg hover:shadow-xl transition">
            <h2 class="font-semibold text-lg mb-5 text-purple-500">🔔 Notification</h2>

            <div class="flex justify-between items-center">
                <span class="text-gray-600">Enable Notification</span>

                <!-- TOGGLE -->
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox"
                    <?= $data['notifikasi_aktif'] ? 'checked' : '' ?>
                    onchange="updateSetting('notifikasi_aktif', this.checked ? 1 : 0)"
                    class="sr-only peer">

                    <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-purple-500 transition"></div>
                    <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition peer-checked:translate-x-5"></div>
                </label>
            </div>
        </div>

    </div>

</div>

<!-- AUTO SAVE -->
<script>
function updateSetting(field, value) {
    fetch('update.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: field + '=' + value
    });
}
</script>

<?php include '../../includes/footer.php'; ?>
<?php ob_end_flush(); ?>