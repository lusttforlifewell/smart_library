<?php
// 🔥 WAJIB: START OUTPUT BUFFER (ANTI ERROR HEADER)
ob_start();

require_once '../../includes/auth_check.php';

// 🔥 NORMALISASI ROLE (BIAR AMAN)
$role = strtolower(trim($_SESSION['role']));

// 🔥 VALIDASI HARUS DI ATAS (SEBELUM HTML)
if ($role !== 'admin' && $role !== 'super_admin') {
    header("Location: ../dashboard/");
    exit();
}

// 🔥 BARU LOAD HEADER (HTML)
include '../../includes/header.php';

// ================= QUERY =================
$totalBooks = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM buku"))['total'];
$totalBorrow = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM peminjaman"))['total'];
$totalActiveBorrow = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM peminjaman WHERE status='dipinjam'"))['total'];
$revenueData = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COALESCE(SUM(total_harga), 0) as total FROM ebook_transactions WHERE status_pembayaran='lunas'"));
$totalRevenue = $revenueData['total'];
?>

<!-- 🔥 FIX DISINI (BIAR MEPEET KE SIDEBAR) -->
<div class="w-full pl-4 pr-6 md:pl-6 md:pr-8 lg:pl-6 lg:pr-10 fade-in">

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Library report</h1>
            <p class="text-slate-500 mt-1">Summary of collection, loan, and transaction data.</p>
        </div>
        <button onclick="window.print()" class="mt-4 md:mt-0 bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-print"></i> Print summary
        </button>
    </div>

    <!-- Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <!-- Card 1 -->
        <a href="books.php" class="group bg-white rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition p-6 flex items-start gap-4">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-xl">
                <i class="fas fa-book"></i>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-lg text-slate-800">Book data report</h3>
                <p class="text-sm text-slate-500 mb-2">Collection and stock summary.</p>
                <div class="text-2xl font-bold text-blue-600">
                    <?= number_format($totalBooks, 0, ',', '.') ?>
                    <span class="text-sm text-slate-400">Titles</span>
                </div>
            </div>
        </a>

        <!-- Card 2 -->
        <a href="borrowings.php" class="group bg-white rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition p-6 flex items-start gap-4">
            <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center text-xl">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-lg text-slate-800">Borrow report</h3>
                <p class="text-sm text-slate-500 mb-2">History of lending activity.</p>
                <div class="text-2xl font-bold text-orange-600">
                    <?= number_format($totalBorrow, 0, ',', '.') ?>
                    <span class="text-sm text-slate-400">Transaksi</span>
                </div>
                <p class="text-xs text-orange-500 mt-1">
                    <?= number_format($totalActiveBorrow, 0, ',', '.') ?> still borrowed
                </p>
            </div>
        </a>

        <!-- Card 3 -->
        <a href="ebook_transactions.php" class="group bg-white rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition p-6 flex items-start gap-4">
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center text-xl">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-lg text-slate-800">Ebook transaction report</h3>
                <p class="text-sm text-slate-500 mb-2">Ebook revenue and sales.</p>
                <div class="text-2xl font-bold text-green-600">
                    Rp <?= number_format($totalRevenue, 0, ',', '.') ?>
                </div>
                <p class="text-xs text-green-500 mt-1">Total Income</p>
            </div>
        </a>

    </div>

    <!-- Info -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 text-blue-800">
        <h3 class="font-bold mb-2">
            <i class="fas fa-info-circle mr-2"></i>Report Information
        </h3>
        <p class="text-sm">
            Click on a card to view a detailed report. Data is real-time from the database.
        </p>
    </div>

</div>

<?php include '../../includes/footer.php'; ?>

<?php
ob_end_flush();
?>
