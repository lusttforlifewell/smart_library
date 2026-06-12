<?php
require_once '../../includes/auth_check.php';
require_once __DIR__ . '/../../config/database.php';
include __DIR__ . '/../../includes/header.php';

// Query Data Buku
$query = "SELECT b.*, k.nama_kategori 
          FROM buku b 
          LEFT JOIN kategori k ON b.kategori_id = k.id 
          ORDER BY b.judul ASC";
$result = mysqli_query($koneksi, $query);
?>

<div class="max-w-7xl mx-auto fade-in">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-slate-800">Book data report</h1>
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center gap-2 no-print">
            <i class="fas fa-print"></i> Print Report
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="bg-slate-100 text-xs uppercase text-slate-700 font-bold">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">tittle Book</th>
                        <th class="px-6 py-4">Writer</th>
                        <th class="px-6 py-4">Catalog</th>
                        <th class="px-6 py-4 text-center">Stock</th>
                        <th class="px-6 py-4">Year</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php 
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)): 
                    ?>
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-medium"><?php echo $no++; ?></td>
                        <td class="px-6 py-4 font-semibold text-slate-800"><?php echo htmlspecialchars($row['judul']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($row['penulis']); ?></td>
                        <td class="px-6 py-4"><span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs"><?php echo htmlspecialchars($row['nama_kategori'] ?: 'Umum'); ?></span></td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 rounded text-xs font-bold <?php echo $row['stok'] > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                                <?php echo $row['stok']; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4"><?php echo $row['tahun_terbit'] ?: '-'; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php if(mysqli_num_rows($result) == 0): ?>
            <p class="text-center p-8 text-slate-500">No book data found.</p>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>