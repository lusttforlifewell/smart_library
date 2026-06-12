<?php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';
include '../../includes/header.php';

// =====================
// AMBIL PARAMETER
// =====================
$buku_id = isset($_GET['id'])
    ? intval($_GET['id'])
    : 0;

$user_id = $_SESSION['user_id'];

// =====================
// AMBIL DATA USER
// =====================
$user = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT *
    FROM users
    WHERE id = '$user_id'
"));

// =====================
// AMBIL DATA BUKU
// =====================
$buku = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT *
    FROM buku
    WHERE id = '$buku_id'
"));

// =====================
// VALIDASI BUKU
// =====================
if (!$buku) {

    echo "
    <script>

        alert('Book not found!');

        window.location.href='../books/';

    </script>
    ";

    exit;

}

// =====================
// DEFAULT
// =====================
$kode_buku   = '-';
$status_buku = 'Tidak Tersedia';

// =====================
// CEK TIPE BUKU
// =====================
if (
    trim(strtolower($buku['tipe'])) == 'fisik'
) {

    // =====================
    // CARI ITEM TERSEDIA
    // =====================
    $query_item = mysqli_query($koneksi, "
        SELECT *
        FROM buku_item
        WHERE buku_id = '$buku_id'
        AND (
            status = 'available'
            OR status = 'tersedia'
        )
        LIMIT 1
    ");

    $item = mysqli_fetch_assoc($query_item);

    // =====================
    // JIKA ADA ITEM
    // =====================
    if ($item) {

        $kode_buku   = $item['kode_buku'];
        $status_buku = 'Tersedia';

    }

} 

// =====================
// JIKA EBOOK
// =====================
else {

    $kode_buku = !empty($buku['kode_buku'])
        ? $buku['kode_buku']
        : 'EBOOK';

    $status_buku = 'Digital';

}
?>

<div class="max-w-3xl mx-auto fade-in">

    <!-- BACK -->
    <a href="../books/"
       class="text-primary hover:underline mb-5 inline-block">

        <i class="fas fa-arrow-left mr-1"></i>
        Kembali ke Katalog

    </a>

    <!-- CARD -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-100">

        <div class="flex flex-col md:flex-row">

            <!-- COVER -->
            <div class="md:w-2/5 bg-slate-100 flex items-center justify-center p-8">

                <img 
                    src="../../assets/img/<?php echo htmlspecialchars($buku['cover']); ?>"
                    alt="Cover Buku"
                    class="w-52 h-72 object-cover rounded-xl shadow-lg"
                >

            </div>

            <!-- DETAIL -->
            <div class="md:w-3/5 p-8 flex flex-col justify-between">

                <div>

                    <!-- BADGE -->
                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold uppercase">

                        <?php echo htmlspecialchars($buku['tipe']); ?>

                    </span>

                    <!-- JUDUL -->
                    <h1 class="text-4xl font-bold text-slate-800 mt-5 mb-3 leading-tight">

                        <?php echo htmlspecialchars($buku['judul']); ?>

                    </h1>

                    <!-- PENULIS -->
                    <p class="text-slate-500 mb-6">

                        Oleh:
                        <span class="text-slate-700 font-semibold">

                            <?php echo htmlspecialchars($buku['penulis']); ?>

                        </span>

                    </p>

                    <!-- DETAIL -->
                    <div class="border-t border-slate-200 pt-5 mb-6">

                        <div class="space-y-4">

                            <!-- USER -->
                            <div class="flex justify-between">

                                <span class="text-slate-500">
                                    Nama
                                </span>

                                <span class="font-semibold text-slate-700">

                                    <?php echo htmlspecialchars($user['nama']); ?>

                                </span>

                            </div>

                            <!-- NIS -->
                            <div class="flex justify-between">

                                <span class="text-slate-500">
                                    NIS
                                </span>

                                <span class="font-semibold text-slate-700">

                                    <?php echo isset($user['nis'])
                                        ? htmlspecialchars($user['nis'])
                                        : '-'; ?>

                                </span>

                            </div>

                            <!-- KODE BUKU -->
                            <div class="flex justify-between">

                                <span class="text-slate-500">
                                    Kode Buku
                                </span>

                                <span class="font-semibold text-slate-700">

                                    <?php echo htmlspecialchars($kode_buku); ?>

                                </span>

                            </div>

                            <!-- STATUS -->
                            <div class="flex justify-between items-center">

                                <span class="text-slate-500">
                                    Status
                                </span>

                                <?php if($kode_buku != '-'): ?>

                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">

                                        <?php echo $status_buku; ?>

                                    </span>

                                <?php else: ?>

                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold">

                                        Tidak Tersedia

                                    </span>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- FORM -->
                <form method="POST" action="borrow.php">

                    <input 
                        type="hidden"
                        name="buku_id"
                        value="<?php echo $buku_id; ?>"
                    >

                    <!-- WARNING -->
                    <?php if($kode_buku == '-'): ?>

                        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">

                            Tidak ada buku tersedia untuk dipinjam.

                        </div>

                    <?php endif; ?>

                    <!-- BUTTON -->
                    <?php if($kode_buku != '-'): ?>

                        <button 
                            type="submit"
                            class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-4 rounded-xl shadow-lg transition duration-300 flex items-center justify-center gap-3 text-lg"
                        >

                            <i class="fas fa-book"></i>
                            Confirm Borrow

                        </button>

                    <?php else: ?>

                        <button 
                            type="button"
                            class="w-full bg-slate-400 text-white font-bold py-4 rounded-xl cursor-not-allowed text-lg"
                        >

                            Buku Tidak Tersedia

                        </button>

                    <?php endif; ?>

                    <!-- CANCEL -->
                    <a 
                        href="../books/"
                        class="block text-center text-slate-500 hover:underline mt-4"
                    >

                        Cancel

                    </a>

                </form>

            </div>

        </div>

    </div>

</div>

<?php include '../../includes/footer.php'; ?>