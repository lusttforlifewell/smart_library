<?php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';

if (!isset($_GET['id'])) {
    die('Ebook tidak ditemukan');
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

/*
|--------------------------------------------------------------------------
| AMBIL DATA EBOOK
|--------------------------------------------------------------------------
*/

$query = mysqli_query($koneksi, "
    SELECT * FROM ebook
    WHERE id='$id'
");

$ebook = mysqli_fetch_assoc($query);

if (!$ebook) {
    die('Data ebook tidak ada');
}

/*
|--------------------------------------------------------------------------
| CEK APAKAH SUDAH MEMBELI
|--------------------------------------------------------------------------
|
| Ganti nama tabel sesuai database kamu
|
*/

$cek = mysqli_query($koneksi, "
    SELECT *
    FROM transaksi_ebook
    WHERE user_id='$user_id'
    AND ebook_id='$id'
");

/*
|--------------------------------------------------------------------------
| JIKA BELUM MEMBELI
|--------------------------------------------------------------------------
*/

if (mysqli_num_rows($cek) == 0) {

    echo "
    <div style='font-family:Arial;padding:50px;text-align:center'>
        <h1>🔒 Ebook Premium</h1>
        <p>Kamu harus membeli ebook terlebih dahulu.</p>

        <a href='preview.php?id=$id'>
            Kembali
        </a>
    </div>
    ";

    exit;
}

$pdf = "../../assets/ebook/" . $ebook['file_url'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo $ebook['judul']; ?></title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-slate-900">

<!-- HEADER -->
<div class="bg-slate-800 text-white px-6 py-4 flex justify-between items-center shadow-lg">

    <div>

        <h1 class="text-2xl font-bold">
            <?php echo $ebook['judul']; ?>
        </h1>

        <p class="text-slate-300 text-sm">
            Happy Reading 📖
        </p>

    </div>

    <a
        href="index.php"
        class="bg-white text-slate-800 px-5 py-2 rounded-xl font-semibold"
    >
        Back
    </a>

</div>

<!-- PDF -->
<iframe
    src="../../assets/ebooks/<?php echo $ebook['file_url']; ?>"
    width="100%"
    height="1000px"
    class="bg-white"
></iframe>

</body>
</html>