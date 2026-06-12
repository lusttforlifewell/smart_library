<?php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';

$id = $_GET['id'];

$query = mysqli_query($koneksi, "
    SELECT *
    FROM ebook
    WHERE id='$id'
");

$ebook = mysqli_fetch_assoc($query);

if (!$ebook) {
    die('Ebook tidak ditemukan');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Preview Ebook</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gradient-to-br from-slate-100 via-blue-50 to-indigo-100 min-h-screen">

<div class="max-w-7xl mx-auto py-10 px-5">

    <div class="grid lg:grid-cols-2 gap-10 items-start">

        <!-- COVER -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

           <img
    src="../../assets/img/<?php echo htmlspecialchars($ebook['cover']); ?>"
    class="w-full h-[700px] object-contain bg-white p-6"
>

        </div>

        <!-- CONTENT -->
        <div class="bg-white rounded-3xl shadow-2xl p-8">

            <div class="mb-5">

                <span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-bold">
                    📚 Ebook Premium
                </span>

            </div>

            <h1 class="text-5xl font-black text-slate-800 leading-tight mb-4">

                <?php echo $ebook['judul']; ?>

            </h1>

            <p class="text-slate-500 text-lg mb-6">

                ✍ Penulis:
                <b><?php echo $ebook['penulis']; ?></b>

            </p>

            <!-- PRICE -->
            <div class="mb-8">

                <span class="text-5xl font-black text-blue-600">

                    Rp
                    <?php echo number_format($ebook['harga'],0,',','.'); ?>

                </span>

            </div>

            <!-- STOCK -->
            <div class="flex gap-4 mb-8">

                <div class="bg-green-100 text-green-700 px-5 py-3 rounded-2xl font-bold">
                    📦 Stock:
                    <?php echo $ebook['stok']; ?>
                </div>

                <div class="bg-yellow-100 text-yellow-700 px-5 py-3 rounded-2xl font-bold">
                    ⭐ 4.9 Rating
                </div>

            </div>

            <!-- SINOPSIS -->
            <div class="bg-slate-100 rounded-3xl p-6 mb-8">

                <h2 class="text-2xl font-bold text-slate-800 mb-5">

                    📖 Sinopsis

                </h2>

                <p class="text-slate-600 leading-relaxed text-lg">

                    <?php
                    echo nl2br(htmlspecialchars($ebook['sinopsis']));
                    ?>

                </p>

            </div>

            <!-- BUTTON -->
            <div class="flex flex-wrap gap-4">

                <a
                    href="add_to_cart.php?id=<?php echo $ebook['id']; ?>"
                    class="bg-gradient-to-r from-pink-500 to-orange-500 hover:scale-105 text-white px-8 py-5 rounded-2xl text-lg font-bold shadow-2xl transition-all duration-300"
                >

                    ✨ Unlock Full Ebook

                </a>

                <a
                    href="index.php"
                    class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-8 py-5 rounded-2xl text-lg font-bold transition"
                >

                    ← Back

                </a>

            </div>

        </div>

    </div>

</div>

</body>
</html>