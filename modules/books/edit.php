<?php
ob_start(); // OPTIONAL tapi aman untuk cegah error header

require_once '../../includes/auth_check.php';
require_once '../../config/database.php';

// Proteksi: hanya admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../dashboard/");
    exit();
}

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data buku
$result = mysqli_query($koneksi, "SELECT * FROM buku WHERE id='$id'");
$book = mysqli_fetch_assoc($result);

// Jika tidak ditemukan
if (!$book) {
    header("Location: index.php?error=notfound");
    exit();
}

// Ambil kategori
$categories = mysqli_query($koneksi, "SELECT * FROM kategori");

// Proses update
if (isset($_POST['update'])) {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $penulis = mysqli_real_escape_string($koneksi, $_POST['penulis']);
    $stok = intval($_POST['stok']);
    $kategori = intval($_POST['kategori']);

    $update = mysqli_query($koneksi, "UPDATE buku 
        SET judul='$judul', penulis='$penulis', stok='$stok', kategori_id='$kategori' 
        WHERE id='$id'");

    if ($update) {
        header("Location: index.php?success=updated");
        exit();
    } else {
        $error = "Gagal memperbarui data: " . mysqli_error($koneksi);
    }
}

// 🔽 HEADER HTML TARUH DI SINI (SETELAH SEMUA LOGIC PHP)
include '../../includes/header.php';
?>

<h1 class="text-2xl font-bold mb-6 text-slate-800">Edit</h1>

<div class="bg-white p-6 rounded-lg shadow-sm max-w-2xl fade-in">

    <?php if(isset($error)): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded mb-4">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label>Book <Title></Title></label>
                <input type="text" name="judul"
                    value="<?= htmlspecialchars($book['judul']); ?>"
                    class="w-full border p-2 rounded" required>
            </div>

            <div>
                <label>Writer</label>
                <input type="text" name="penulis"
                    value="<?= htmlspecialchars($book['penulis']); ?>"
                    class="w-full border p-2 rounded" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label>Category</label>
                <select name="kategori" class="w-full border p-2 rounded" required>
                    <option value="">Select Category</option>
                    <?php while($k = mysqli_fetch_assoc($categories)): ?>
                        <option value="<?= $k['id']; ?>"
                            <?= $book['kategori_id'] == $k['id'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($k['nama_kategori']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div>
                <label>Stock</label>
                <input type="number" name="stok"
                    value="<?= $book['stok']; ?>"
                    class="w-full border p-2 rounded"
                    min="0" required>
            </div>
        </div>

        <div class="flex gap-3 mt-4">
            <button type="submit" name="update"
                class="bg-blue-600 text-white px-4 py-2 rounded">
                Save Changes
            </button>

            <a href="index.php"
                class="border px-4 py-2 rounded">
               Cancelled
            </a>
        </div>
    </form>
</div>

<?php
include '../../includes/footer.php';
ob_end_flush();
?>