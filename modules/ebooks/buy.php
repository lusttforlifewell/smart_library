<?php
require_once '../../includes/auth_check.php';
require_once __DIR__ . '/../../config/database.php';

// Validasi Role
if ($_SESSION['role'] !== 'siswa') {
    header("Location: ../dashboard/");
    exit();
}

$user_id = $_SESSION['user_id'];

echo "<h2>DEBUG SESSION</h2>";
echo "Nama : " . $_SESSION['nama'];
echo "<br>User ID : " . $_SESSION['user_id'];
echo "<br>Role : " . $_SESSION['role'];
exit;

// DEBUG SESSION
echo "<div style='padding:20px;background:#f3f4f6;border:1px solid #ccc;margin:20px'>";
echo "<h3>DEBUG LOGIN</h3>";
echo "Nama User : " . ($_SESSION['nama'] ?? 'Tidak ada');
echo "<br>";
echo "User ID : " . ($_SESSION['user_id'] ?? 'Tidak ada');
echo "<br>";
echo "Role : " . ($_SESSION['role'] ?? 'Tidak ada');
echo "</div>";

// ==========================
// AMBIL DATA EBOOK
// ==========================
$ebook = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT * FROM ebook 
    WHERE id='$ebook_id'
"));

if (!$ebook) {

    echo "
    <script>
        alert('Ebook tidak ditemukan!');
        window.location.href='index.php';
    </script>
    ";

    exit();
}

// ==========================
// DEFAULT VALUE
// ==========================
$kode_buku = !empty($ebook['kode_buku'])
    ? $ebook['kode_buku']
    : 'EBK-' . str_pad($ebook['id'], 3, '0', STR_PAD_LEFT);

$stok = isset($ebook['stok'])
    ? (int)$ebook['stok']
    : 0;

// ==========================
// PROSES PEMBELIAN
// ==========================
if (isset($_POST['confirm_buy'])) {

    // Cek stok
    if ($stok <= 0) {

        echo "
        <script>
            alert('Stok ebook habis!');
            window.history.back();
        </script>
        ";

        exit();
    }

    // Cek apakah user sudah membeli
    $check = mysqli_query($koneksi, "
        SELECT id 
        FROM ebook_transactions 
        WHERE user_id='$user_id' 
        AND ebook_id='$ebook_id'
    ");

    if (mysqli_num_rows($check) > 0) {

        echo "
        <script>
            alert('Anda sudah memiliki ebook ini!');
            window.location.href='index.php';
        </script>
        ";

        exit();
    }

    // Simpan transaksi
    $query = "
        INSERT INTO ebook_transactions
        (
            user_id,
            ebook_id,
            tanggal_beli,
            total_harga,
            status_pembayaran
        )
        VALUES
        (
            '$user_id',
            '$ebook_id',
            CURDATE(),
            '{$ebook['harga']}',
            'lunas'
        )
    ";

    if (mysqli_query($koneksi, $query)) {

        // Kurangi stok
        mysqli_query($koneksi, "
            UPDATE ebook
            SET stok = stok - 1
            WHERE id='$ebook_id'
        ");

        // Notifikasi admin
        $adminQuery = mysqli_query($koneksi, "
            SELECT id 
            FROM users 
            WHERE role='admin'
            OR role='super_admin'
        ");

        if ($adminQuery) {

            while ($admin = mysqli_fetch_assoc($adminQuery)) {

                $pesan = mysqli_real_escape_string(
    $koneksi,
    "Transaksi baru: " .
    $_SESSION['nama'] .
    " membeli ebook " .
    $ebook['judul']
);

                mysqli_query($koneksi, "
                    INSERT INTO notifikasi
                    (
                        user_id,
                        pesan,
                        tipe,
                        dibaca
                    )
                    VALUES
                    (
                        {$admin['id']},
                        '$pesan',
                        'success',
                        0
                    )
                ");
            }
        }

// =======================
// 🔥 NOTIF SISWA
// =======================

$judulNotif = mysqli_real_escape_string(
    $koneksi,
    $ebook['judul']
);

$pesanNotif =
    "Berhasil membeli ebook " .
    $judulNotif;

mysqli_query($koneksi, "
    INSERT INTO notifikasi
    (
        user_id,
        pesan,
        tipe,
        dibaca,
        created_at
    )
    VALUES
    (
        '$user_id',
        '$pesanNotif',
        'info',
        0,
        NOW()
    )
");

        echo "
        <script>
            alert('Pembelian berhasil!');
            window.location.href='index.php?success=purchased';
        </script>
        ";

        exit();

    } else {

        echo "
        <script>
            alert('Gagal transaksi!');
            window.history.back();
        </script>
        ";

        exit();
    }
}

// ==========================
// TAMPILKAN HALAMAN
// ==========================
include __DIR__ . '/../../includes/header.php';
?>

<div class="max-w-4xl mx-auto fade-in">

    <!-- BACK -->
    <a href="index.php"
       class="text-primary hover:underline mb-6 inline-block">

        <i class="fas fa-arrow-left mr-1"></i>
        Kembali ke Katalog

    </a>

    <!-- CARD -->
    <div class="bg-white rounded-2xl shadow-xl overflow-visible border border-slate-100"></div>

        <div class="flex flex-col md:flex-row">

<!-- COVER -->
<div class="md:w-2/5 bg-slate-100 flex items-center justify-center p-8 relative">
<?php
$cover = !empty($ebook['cover'])
    ? BASE_URL . 'assets/img/' . $ebook['cover']
    : 'https://placehold.co/300x400?text=No+Cover';
?>

<img
    src="<?php echo htmlspecialchars($cover); ?>"
    alt="Cover Ebook"
    class="w-52 h-72 object-cover rounded-xl shadow-lg"
>

    <!-- BUTTON SINOPSIS -->
    <button
        type="button"
        onclick="toggleSinopsis()"
        class="absolute bottom-24 bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-xl font-semibold transition"
    >

        Baca Sinopsis

    </button>
    <!-- MODAL SINOPSIS -->
<div
    id="sinopsisBox"
    class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-5"
>

    <!-- CARD -->
    <div
        class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden"
    >

        <!-- HEADER -->
        <div class="flex items-center justify-between px-6 py-5 border-b">

            <div>

                <h2 class="text-2xl font-bold text-slate-800">

                    Sinopsis Ebook

                </h2>

                <p class="text-slate-400 text-sm mt-1">

                    Preview cerita sebelum membeli ebook

                </p>

            </div>

            <!-- CLOSE -->
            <button
                type="button"
                onclick="toggleSinopsis()"
                class="text-4xl text-slate-400 hover:text-red-500 transition"
            >

                &times;

            </button>

        </div>

        <!-- CONTENT -->
        <div class="p-6 overflow-y-auto max-h-[500px]">

            <div class="flex flex-col md:flex-row gap-6">

                <!-- COVER -->
                <div class="flex justify-center">

                    <img
                        src="<?php echo htmlspecialchars($cover); ?>"
                        class="w-48 h-64 object-cover rounded-2xl shadow-lg"
                    >

                </div>

                <!-- DETAIL -->
                <div class="flex-1">

                    <h1 class="text-3xl font-bold text-slate-800 mb-3">

                        <?php echo htmlspecialchars($ebook['judul']); ?>

                    </h1>

                    <p class="text-slate-500 mb-5">

                        Oleh:
                        <span class="font-semibold text-slate-700">

                            <?php echo htmlspecialchars($ebook['penulis']); ?>

                        </span>

                    </p>

                    <!-- SINOPSIS -->
                    <div class="text-slate-600 leading-8 text-justify">

                        <?php
                        echo !empty($ebook['sinopsis'])
                            ? nl2br(htmlspecialchars($ebook['sinopsis']))
                            : 'Sinopsis belum tersedia.';
                        ?>

                    </div>

                </div>

            </div>

        </div>

        <!-- FOOTER -->
        <div class="border-t px-6 py-4 flex justify-end">

            <button
                type="button"
                onclick="toggleSinopsis()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl font-semibold transition"
            >

                Tutup

            </button>

        </div>

    </div>

</div>

</div>


            <!-- DETAIL -->
            <div class="md:w-3/5 p-8 flex flex-col justify-between">

                <div>

                    <!-- BADGE -->
                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold uppercase">

                        Ebook Digital

                    </span>

                    <!-- JUDUL -->
                    <h1 class="text-4xl font-bold text-slate-800 mt-5 mb-3 leading-tight">

                        <?php echo htmlspecialchars($ebook['judul']); ?>

                    </h1>

                    <!-- PENULIS -->
                    <p class="text-slate-500 mb-6">

                        Oleh:
                        <span class="text-slate-700 font-semibold">

                            <?php echo htmlspecialchars($ebook['penulis']); ?>

                        </span>

                    </p>

                    <!-- DETAIL -->
                    <div class="border-t border-slate-200 pt-5 mb-6">

                        <div class="space-y-4 mb-6">

                            <!-- KODE BUKU -->
                            <div class="flex justify-between items-center">

                                <span class="text-slate-500">
                                    Kode Buku
                                </span>

                                <span class="font-semibold text-slate-700">

                                    <?php echo htmlspecialchars($kode_buku); ?>

                                </span>

                            </div>

                            <!-- STOK -->
                            <div class="flex justify-between items-center">

                                <span class="text-slate-500">
                                    Stok Tersedia
                                </span>

                                <?php if($stok > 0): ?>

                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">

                                        <?php echo $stok; ?> Ebook

                                    </span>

                                <?php else: ?>

                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold">

                                        Habis

                                    </span>

                                <?php endif; ?>

                            </div>

                            <!-- STATUS -->
                            <div class="flex justify-between items-center">

                                <span class="text-slate-500">
                                    Status
                                </span>

                                <?php if($stok > 0): ?>

                                    <span class="text-green-600 font-bold">
                                        Tersedia
                                    </span>

                                <?php else: ?>

                                    <span class="text-red-600 font-bold">
                                        Tidak Tersedia
                                    </span>

                                <?php endif; ?>

                            </div>

                        </div>

                        <!-- HARGA -->
                        <p class="text-slate-400 text-sm mb-1">
                            Harga
                        </p>

                        <p class="text-5xl font-bold text-primary">

                            Rp <?php echo number_format($ebook['harga'], 0, ',', '.'); ?>

                        </p>

                    </div>

                </div>

                <!-- BUTTON -->
                <form method="POST">

                    <?php if($stok > 0): ?>

                        <button
                            type="submit"
                            name="confirm_buy"
                            class="w-full bg-primary hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-lg transition duration-300 flex items-center justify-center gap-3 text-lg"
                        >

                            <i class="fas fa-shopping-cart"></i>
                            Confirm & Pay

                        </button>

                    <?php else: ?>

                        <button
                            type="button"
                            class="w-full bg-slate-400 text-white font-bold py-4 rounded-xl cursor-not-allowed text-lg"
                        >

                            Stok Habis

                        </button>

                    <?php endif; ?>

                    <p class="text-xs text-center text-slate-400 mt-4">

                        * Transactions are automatically recorded in the admin report

                    </p>

                </form>

            </div>

        </div>

    </div>

<script>

function toggleSinopsis() {

    let box = document.getElementById('sinopsisBox');

    if (box.classList.contains('hidden')) {

        box.classList.remove('hidden');

    } else {

        box.classList.add('hidden');

    }

}

</script>
<?php include __DIR__ . '/../../includes/footer.php'; ?>