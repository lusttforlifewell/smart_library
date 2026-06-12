<?php
require_once '../../includes/auth_check.php';
require_once __DIR__ . '/../../config/database.php';

include __DIR__ . '/../../includes/header.php';

$role = $_SESSION['role'];

// QUERY EBOOK
$query = "
    SELECT *
    FROM ebook
    ORDER BY id DESC
";

$result = mysqli_query($koneksi, $query);

?>

<div class="max-w-7xl mx-auto fade-in">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-8">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">
                Ebook Management
            </h1>

            <p class="text-slate-500 mt-1">
                Manage digital books and ebook catalog
            </p>

        </div>

        <?php if ($role === 'admin' || $role === 'super_admin'): ?>

            <a
                href="add.php"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl shadow-lg text-sm font-semibold transition"
            >
                + Add Ebook
            </a>

        <?php endif; ?>

    </div>

    <!-- GRID -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

        <?php while ($ebook = mysqli_fetch_assoc($result)): ?>

            <div class="bg-white rounded-2xl shadow-md border border-slate-100 overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">

                <!-- COVER -->
                <div class="h-60 bg-slate-200 overflow-hidden">
<img 
    src="../../assets/img/<?php echo $ebook['cover']; ?>"
    alt="Cover Ebook"
    class="w-full h-64 object-cover"
>

                </div>

                <!-- CONTENT -->
                <div class="p-4">

                    <!-- TITLE -->
                    <h3 class="font-bold text-lg text-slate-800 truncate">

                        <?php echo htmlspecialchars($ebook['judul']); ?>

                    </h3>

                    <!-- AUTHOR -->
                    <p class="text-xs text-slate-500 mt-1 mb-3">

                        By:
                        <?php echo htmlspecialchars($ebook['penulis']); ?>

                    </p>

                    <!-- STOCK -->
                    <div class="flex items-center justify-between mb-3">

                        <span class="text-sm text-slate-500">
                            Stock:
                            <b><?php echo $ebook['stok']; ?></b>
                        </span>

                        <?php if ($ebook['stok'] > 0): ?>

                            <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-medium">
                                Available
                            </span>

                        <?php else: ?>

                            <span class="bg-red-100 text-red-700 text-xs px-3 py-1 rounded-full font-medium">
                                Empty
                            </span>

                        <?php endif; ?>

                    </div>

                    <!-- PRICE -->
                    <div class="mb-4">

                        <span class="text-blue-600 font-bold text-lg">

                            Rp
                            <?php
                            echo number_format(
                                $ebook['harga'],
                                0,
                                ',',
                                '.'
                            );
                            ?>

                        </span>

                    </div>

                    <!-- BUTTON SISWA -->
                    <?php if ($role === 'siswa'): ?>

<?php

$user_id = $_SESSION['user_id'];

/*
|--------------------------------------------------------------------------
| CEK APAKAH SUDAH MEMBELI
|--------------------------------------------------------------------------
*/

$cek_beli = mysqli_query($koneksi, "
    SELECT *
    FROM transaksi_ebook
    WHERE user_id='$user_id'
    AND ebook_id='".$ebook['id']."'
");

$sudah_beli = mysqli_num_rows($cek_beli) > 0;

?>

<div class="flex gap-2 flex-wrap">

    <!-- PREVIEW -->
    <a
        href="preview.php?id=<?php echo $ebook['id']; ?>"
        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-semibold transition"
    >

        👁 Preview

    </a>

    <?php if ($sudah_beli): ?>

        <!-- READ -->
        <a
            href="read.php?id=<?php echo $ebook['id']; ?>"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition"
        >

            📖 Read

        </a>

    <?php else: ?>

        <!-- BUY -->
        <a
            href="add_to_cart.php?id=<?php echo $ebook['id']; ?>"
            class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-xl text-sm font-semibold transition"
        >

            🛒 Buy

        </a>

    <?php endif; ?>

</div>

<?php endif; ?>

                    <!-- ADMIN BUTTON -->
                    <?php if ($role === 'admin' || $role === 'super_admin'): ?>

                        <div class="flex gap-2 mt-4">

                            <a
                                href="edit.php?id=<?php echo $ebook['id']; ?>"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition"
                            >

                                Edit

                            </a>

                            <a
                                href="delete.php?id=<?php echo $ebook['id']; ?>"
                                onclick="return confirm('Yakin ingin menghapus ebook?')"
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition"
                            >

                                Delete

                            </a>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        <?php endwhile; ?>

    </div>

</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>