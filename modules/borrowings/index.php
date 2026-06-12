<?php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';
include '../../includes/header.php';

$role = $_SESSION['role'];

// ===============================
// QUERY
// ===============================
if (
    $role === 'admin' ||
    $role === 'super_admin'
) {

    $query = "
        SELECT 
            p.*, 
            u.nama as siswa_nama, 
            b.judul 
        FROM peminjaman p 
        JOIN users u 
            ON p.user_id = u.id 
        JOIN buku b 
            ON p.buku_id = b.id 
        ORDER BY p.id DESC
    ";

} else {

    $query = "
        SELECT 
            p.*, 
            b.judul 
        FROM peminjaman p 
        JOIN buku b 
            ON p.buku_id = b.id 
        WHERE p.user_id = '" . intval($_SESSION['user_id']) . "'
        ORDER BY p.id DESC
    ";

}

$result = mysqli_query($koneksi, $query);
?>

<h1 class="text-2xl font-bold mb-6">

    <?php echo (
        $role === 'admin' ||
        $role === 'super_admin'
    )
        ? 'Borrowing Management'
        : 'My Borrowing History'; ?>

</h1>

<div class="bg-white rounded-lg shadow-sm overflow-hidden">

<table class="w-full text-sm text-left text-slate-600">

<thead class="bg-slate-50 text-xs uppercase text-slate-500">

<tr>

    <?php if(
        $role === 'admin' ||
        $role === 'super_admin'
    ): ?>

        <th class="px-4 py-3">
            Student
        </th>

    <?php endif; ?>

    <th class="px-4 py-3">
        Book
    </th>

    <th class="px-4 py-3">
        Book Code
    </th>

    <th class="px-4 py-3">
        Borrow Date
    </th>

    <th class="px-4 py-3">
        Deadline
    </th>

    <th class="px-4 py-3">
        Status
    </th>

    <th class="px-4 py-3 text-center">
        Action
    </th>

</tr>

</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($result)): ?>

<?php

$status = strtolower(trim($row['status']));

// ===============================
// STATUS STYLE
// ===============================
if ($status == 'dipinjam') {

    $statusText  = 'Borrowed';
    $statusClass = 'bg-orange-100 text-orange-700';

} elseif ($status == 'waiting') {

    $statusText  = 'Waiting';
    $statusClass = 'bg-yellow-100 text-yellow-700';

} elseif ($status == 'dikembalikan') {

    $statusText  = 'Returned';
    $statusClass = 'bg-green-100 text-green-700';

} else {

    $statusText  = 'Unknown';
    $statusClass = 'bg-gray-100 text-gray-700';

}

?>

<tr class="border-b hover:bg-slate-50">

<!-- STUDENT -->
<?php if(
    $role === 'admin' ||
    $role === 'super_admin'
): ?>

<td class="px-4 py-3 font-medium">

    <?php echo htmlspecialchars($row['siswa_nama']); ?>

</td>

<?php endif; ?>

<!-- BOOK -->
<td class="px-4 py-3">

    <?php echo htmlspecialchars($row['judul']); ?>

</td>

<!-- BOOK CODE -->
<td class="px-4 py-3">

    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-bold">

        <?php echo htmlspecialchars($row['kode_buku']); ?>

    </span>

</td>

<!-- BORROW DATE -->
<td class="px-4 py-3">

    <?php echo date(
        'd M Y',
        strtotime($row['tanggal_pinjam'])
    ); ?>

</td>

<!-- DEADLINE -->
<td class="px-4 py-3">

    <?php echo date(
        'd M Y',
        strtotime($row['tanggal_jatuh_tempo'])
    ); ?>

</td>

<!-- STATUS -->
<td class="px-4 py-3">

    <span class="<?php echo $statusClass; ?> px-2 py-1 rounded text-xs">

        <?php echo $statusText; ?>

    </span>

</td>

<!-- ACTION -->
<td class="px-4 py-3 text-center">

<?php if($role === 'siswa'): ?>

    <!-- ===================== -->
    <!-- ROLE SISWA -->
    <!-- ===================== -->

    <?php if($status == 'dipinjam'): ?>

        <a 
            href="request_return.php?id=<?php echo $row['id']; ?>"
            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs"
        >

            Request Return

        </a>

    <?php elseif($status == 'waiting'): ?>

        <button
            class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded text-xs cursor-not-allowed"
        >

            Waiting

        </button>

    <?php elseif($status == 'dikembalikan'): ?>

        <span class="text-gray-400 text-xs">

            Done

        </span>

    <?php else: ?>

        <span class="text-red-400 text-xs">

            Unknown

        </span>

    <?php endif; ?>

<?php else: ?>

    <!-- ===================== -->
    <!-- ROLE ADMIN -->
    <!-- ===================== -->

    <?php if($status == 'waiting'): ?>

        <a 
            href="request_return.php?id=<?php echo $row['id']; ?>"
            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs"
        >

            Accept

        </a>

    <?php elseif($status == 'dipinjam'): ?>

        <span class="text-orange-500 text-xs">

            Borrowed

        </span>

    <?php elseif($status == 'dikembalikan'): ?>

        <span class="text-gray-400 text-xs">

            Done

        </span>

    <?php else: ?>

        <span class="text-red-400 text-xs">

            Unknown

        </span>

    <?php endif; ?>

<?php endif; ?>

</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>

<?php include '../../includes/footer.php'; ?>