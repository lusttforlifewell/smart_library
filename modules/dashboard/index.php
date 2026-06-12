<?php
require_once '../../includes/auth_check.php';
require_once __DIR__ . '/../../includes/header.php';

$role = $_SESSION['role'];
$userId = $_SESSION['user_id'];

// =====================
// DEFAULT VARIABLE (FIX ERROR)
// =====================
$myCount = 0;
$userCount = 0;
$adminCount = 0;

// =====================
// STATISTIK UMUM
// =====================
$statsBuku = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM buku");
$bukuTotal = mysqli_fetch_assoc($statsBuku)['total'];

$statsPinjam = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM peminjaman WHERE status='dipinjam'");
$pinjamAktif = mysqli_fetch_assoc($statsPinjam)['total'];

// =====================
// KHUSUS SISWA
// =====================
if ($role === 'siswa') {
    $myPinjam = mysqli_query($koneksi, "
        SELECT COUNT(*) as total 
        FROM peminjaman 
        WHERE user_id=$userId AND status='dipinjam'
    ");
    $myCount = mysqli_fetch_assoc($myPinjam)['total'];
}

// =====================
// KHUSUS SUPERADMIN
// =====================
if ($role === 'super_admin') {
    $totalUser = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users");
    $userCount = mysqli_fetch_assoc($totalUser)['total'];

    $totalAdmin = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users WHERE role='admin'");
    $adminCount = mysqli_fetch_assoc($totalAdmin)['total'];
}
?>

<h1 class="text-2xl font-bold mb-6">
    Dashboard <?php echo ucfirst($role); ?>
</h1>

<!-- ===================== -->
<!-- STAT CARD -->
<!-- ===================== -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    <!-- SUPERADMIN -->
    <?php if ($role === 'super_admin'): ?>

        <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-purple-500">
            <p class="text-slate-500 text-sm">Total User</p>
            <p class="text-3xl font-bold text-slate-800"><?php echo $userCount; ?></p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
            <p class="text-slate-500 text-sm">Total Buku</p>
            <p class="text-3xl font-bold text-slate-800"><?php echo $bukuTotal; ?></p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
            <p class="text-slate-500 text-sm">Total Admin</p>
            <p class="text-3xl font-bold text-slate-800"><?php echo $adminCount; ?></p>
        </div>

    <!-- ADMIN -->
    <?php elseif ($role === 'admin'): ?>

        <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
            <p class="text-slate-500 text-sm">Total Book Collection</p>
            <p class="text-3xl font-bold text-slate-800"><?php echo $bukuTotal; ?></p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-orange-500">
            <p class="text-slate-500 text-sm">Being Borrowed</p>
            <p class="text-3xl font-bold text-slate-800"><?php echo $pinjamAktif; ?></p>
        </div>

    <!-- SISWA -->
    <?php else: ?>

        <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
            <p class="text-slate-500 text-sm">Total Book Collection</p>
            <p class="text-3xl font-bold text-slate-800"><?php echo $bukuTotal; ?></p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
            <p class="text-slate-500 text-sm">Borrowed Books</p>
            <p class="text-3xl font-bold text-slate-800"><?php echo $myCount; ?></p>
        </div>

    <?php endif; ?>

</div>

<!-- ===================== -->
<!-- TABLE -->
<!-- ===================== -->
<?php if ($role !== 'siswa'): ?>
<div class="bg-white p-6 rounded-lg shadow-sm">
    <h3 class="font-bold text-lg mb-4">Latest Loan</h3>

    <table class="w-full text-sm text-left text-slate-600">
        <thead class="bg-slate-50 text-xs uppercase text-slate-500">
            <tr>
                <th class="px-4 py-3">Student</th>
                <th class="px-4 py-3">Book</th>
                <th class="px-4 py-3">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $recent = mysqli_query($koneksi, "
                SELECT p.*, u.nama, b.judul 
                FROM peminjaman p 
                JOIN users u ON p.user_id=u.id 
                JOIN buku b ON p.buku_id=b.id 
                ORDER BY p.id DESC 
                LIMIT 5
            ");
            while($row = mysqli_fetch_assoc($recent)):
            ?>
            <tr class="border-b">
                <td class="px-4 py-3"><?php echo $row['nama']; ?></td>
                <td class="px-4 py-3"><?php echo $row['judul']; ?></td>
                <td class="px-4 py-3">
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">
                        <?php echo $row['status']; ?>
                    </span>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php include __DIR__ . '/../../includes/footer.php'; ?>