<?php
// 1. Auth Check (MUST BE FIRST to allow redirects)
require_once '../../includes/auth_check.php';

// 2. Database Connection
require_once __DIR__ . '/../../config/database.php';

// 3. Headers for No-Cache (MUST BE BEFORE ANY HTML OUTPUT)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// 4. Include Layout (This outputs HTML, so headers must be above this)
include __DIR__ . '/../../includes/header.php';

// Hitung Total Pendapatan
$totalQuery = mysqli_query($koneksi, "SELECT SUM(total_harga) as total FROM ebook_transactions WHERE status_pembayaran='lunas'");
$totalPendapatan = mysqli_fetch_assoc($totalQuery)['total'] ?? 0;

// Query Transaksi Ebook
$query = "SELECT te.*, u.nama as nama_siswa, e.judul as judul_ebook 
          FROM ebook_transactions te 
          JOIN users u ON te.user_id = u.id 
          JOIN ebook e ON te.ebook_id = e.id 
          ORDER BY te.id DESC";
$result = mysqli_query($koneksi, $query);
?>

<div class="max-w-7xl mx-auto fade-in">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Ebook transaction report</h1>
            <p class="text-sm text-slate-500 mt-1">
                Total Income: <span class="font-bold text-green-600">Rp <?php echo number_format($totalPendapatan, 0, ',', '.'); ?></span>
            </p>
            <p class="text-xs text-slate-400 mt-1">🔄 Data updated last: <?php echo date("d M Y, H:i:s"); ?></p>
        </div>
        <div class="flex gap-2">
            <button onclick="location.reload()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center gap-2 no-print">
                <i class="fas fa-sync-alt"></i> Refresh Data
            </button>
            <button onclick="window.print()" class="bg-slate-200 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-300 transition flex items-center gap-2 no-print">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="bg-slate-100 text-xs uppercase text-slate-700 font-bold">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Student Name</th>
                        <th class="px-6 py-4">Tittle Ebook</th>
                        <th class="px-6 py-4 text-center">Time to buy</th>
                        <th class="px-6 py-4 text-right">Total Price</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php 
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)): 
                    ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-medium"><?php echo $no++; ?></td>
                        <td class="px-6 py-4 font-semibold text-slate-800"><?php echo htmlspecialchars($row['nama_siswa']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($row['judul_ebook']); ?></td>
                        <td class="px-6 py-4 text-center"><?php echo $row['tanggal_beli']; ?></td>
                        <td class="px-6 py-4 text-right font-medium">Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium"><?php echo ucfirst($row['status_pembayaran']); ?></span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php if(mysqli_num_rows($result) == 0): ?>
            <p class="text-center p-8 text-slate-500">There are no ebook transactions yet.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Script Auto-Refresh -->
<script>
    setInterval(() => { location.reload(); }, 15000);
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
