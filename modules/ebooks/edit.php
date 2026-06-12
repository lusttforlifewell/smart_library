<?php

require_once '../../includes/auth_check.php';
require_once __DIR__ . '/../../config/database.php';

$id = $_GET['id'];

$query = mysqli_query(
    $koneksi,
    "SELECT * FROM ebook WHERE id='$id'"
);

$ebook = mysqli_fetch_assoc($query);

// UPDATE
if (isset($_POST['update'])) {

    $judul     = $_POST['judul'];
    $penulis   = $_POST['penulis'];
    $harga     = $_POST['harga'];
    $stok      = $_POST['stok'];
    $sinopsis  = $_POST['sinopsis'];

    mysqli_query(
        $koneksi,
        "UPDATE ebook SET
            judul='$judul',
            penulis='$penulis',
            harga='$harga',
            stok='$stok',
            sinopsis='$sinopsis'
        WHERE id='$id'"
    );

    header('Location: index.php');
}

include __DIR__ . '/../../includes/header.php';

?>

<div class="max-w-3xl mx-auto">

    <div class="bg-white rounded-2xl shadow-lg p-8">

        <h1 class="text-3xl font-bold text-slate-800 mb-2">
            Edit Ebook
        </h1>

        <p class="text-slate-500 mb-8">
            Update ebook information
        </p>

        <form method="POST">

            <!-- JUDUL -->
            <div class="mb-5">

                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Judul Ebook
                </label>

                <input
                    type="text"
                    name="judul"
                    value="<?php echo $ebook['judul']; ?>"
                    class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                    required
                >

            </div>

            <!-- PENULIS -->
            <div class="mb-5">

                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Penulis
                </label>

                <input
                    type="text"
                    name="penulis"
                    value="<?php echo $ebook['penulis']; ?>"
                    class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                    required
                >

            </div>

            <!-- HARGA -->
            <div class="mb-5">

                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Harga Ebook
                </label>

                <input
                    type="number"
                    name="harga"
                    value="<?php echo $ebook['harga']; ?>"
                    class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                    required
                >

            </div>

            <!-- STOK -->
            <div class="mb-5">

                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Stok Ebook
                </label>

                <input
                    type="number"
                    name="stok"
                    value="<?php echo $ebook['stok']; ?>"
                    class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                    required
                >

            </div>

            <!-- SINOPSIS -->
            <div class="mb-6">

                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Sinopsis
                </label>

                <textarea
                    name="sinopsis"
                    rows="5"
                    class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                ><?php echo $ebook['sinopsis']; ?></textarea>

            </div>

            <!-- BUTTON -->
            <div class="flex gap-3">

                <button
                    type="submit"
                    name="update"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg transition"
                >

                    Update Ebook

                </button>

                <a
                    href="index.php"
                    class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-6 py-3 rounded-xl font-semibold transition"
                >

                    Cancel

                </a>

            </div>

        </form>

    </div>

</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>