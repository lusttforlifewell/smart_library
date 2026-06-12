<?php
require_once '../../includes/auth_check.php';
require_once __DIR__ . '/../../config/database.php';
include __DIR__ . '/../../includes/header.php';

// Query Data Peminjaman
$query = "SELECT p.*, u.nama as nama_siswa, b.judul as judul_buku 
          FROM peminjaman p 
          JOIN users u ON p.user_id = u.id 
          JOIN buku b ON p.buku_id = b.id 
          ORDER BY p.tanggal_pinjam DESC";
$result = mysqli_query($koneksi, $query);
?>

<div class="max-w-7xl mx-auto fade-in">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-slate-800">Borrow report</h1>
        <button onclick="window.print()" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition flex items-center gap-2 no-print">
            <i class="fas fa-print"></i> Print Report
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="bg-slate-100 text-xs uppercase text-slate-700 font-bold">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">student Name</th>
                        <th class="px-6 py-4">Tittle Book</th>
                        <th class="px-6 py-4 text-center">Borrow Date</th>
                        <th class="px-6 py-4 text-center">Deadline</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php 
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)): 
                    ?>
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-medium"><?php echo $no++; ?></td>
                        <td class="px-6 py-4 font-semibold text-slate-800"><?php echo htmlspecialchars($row['nama_siswa']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($row['judul_buku']); ?></td>
                        <td class="px-6 py-4 text-center"><?php echo $row['tanggal_pinjam']; ?></td>
                        <td class="px-6 py-4 text-center"><?php echo $row['tanggal_jatuh_tempo']; ?></td>
                        <td class="px-6 py-4 text-center">
                            <?php if($row['status'] == 'dipinjam'): ?>
                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-medium">Dipinjam</span>
                            <?php elseif($row['status'] == 'dikembalikan'): ?>
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">Selesai</span>
                            <?php else: ?>
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-medium"><?php echo ucfirst($row['status']); ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php if(mysqli_num_rows($result) == 0): ?>
            <p class="text-center p-8 text-slate-500">There is no borrow data yet.</p>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>