<?php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';
require_once '../../includes/log.php';

// =======================
// SESSION USER
// =======================
$user_id = isset($_SESSION['user_id'])
    ? intval($_SESSION['user_id'])
    : 0;

// =======================
// DEFAULT USER
// =======================
$userNama = 'Unknown';

// =======================
// AMBIL ROLE USER
// =======================
$userQuery = mysqli_query($koneksi, "
    SELECT role
    FROM users
    WHERE id = '$user_id'
");

if (
    $userQuery &&
    mysqli_num_rows($userQuery) > 0
) {

    $userData = mysqli_fetch_assoc($userQuery);

    if (!empty($userData['role'])) {

        $userNama = ucwords(
            str_replace(
                '_',
                ' ',
                $userData['role']
            )
        );

    }

}

// =======================
// AMBIL DATA FORM
// =======================
$buku_id = isset($_POST['buku_id'])
    ? intval($_POST['buku_id'])
    : 0;

$nis = isset($_POST['nis'])
    ? $_POST['nis']
    : '';

// =======================
// VALIDASI
// =======================
if ($buku_id == 0) {

    die("ERROR: buku_id tidak terbaca!");

}

// =======================
// AMBIL DATA BUKU
// =======================
$bukuQuery = mysqli_query($koneksi, "
    SELECT * 
    FROM buku 
    WHERE id = '$buku_id'
");

$buku = mysqli_fetch_assoc($bukuQuery);

if (!$buku) {

    die("ERROR: Buku tidak ditemukan!");

}

// =======================
// AMBIL JUDUL BUKU
// =======================
$judulBuku = isset($buku['judul'])
    ? $buku['judul']
    : 'Buku';

// =======================
// CEK STOK
// =======================
if (
    strtolower($buku['tipe']) == 'fisik'
    && intval($buku['stok']) <= 0
) {

    echo "
    <script>

        alert('Stock empty!');

        window.location.href='../books/';

    </script>
    ";

    exit;

}

// =======================
// AMBIL KODE BUKU
// =======================
$kode_buku = '';

if (strtolower(trim($buku['tipe'])) == 'fisik') {

    // =======================
    // CARI ITEM TERSEDIA
    // =======================
    $itemQuery = mysqli_query($koneksi, "
        SELECT * 
        FROM buku_item
        WHERE buku_id = '$buku_id'
        AND (
            status = 'available'
            OR status = 'tersedia'
        )
        LIMIT 1
    ");

    $item = mysqli_fetch_assoc($itemQuery);

    // =======================
    // JIKA TIDAK ADA ITEM
    // =======================
    if (!$item) {

        die("ERROR: Tidak ada buku tersedia!");

    }

    $kode_buku = $item['kode_buku'];
    $item_id   = $item['id'];

    // =======================
    // UPDATE STATUS ITEM
    // =======================
    mysqli_query($koneksi, "
        UPDATE buku_item
        SET status = 'borrowed'
        WHERE id = '$item_id'
    ");

    // =======================
    // KURANGI STOK
    // =======================
    mysqli_query($koneksi, "
        UPDATE buku
        SET stok = stok - 1
        WHERE id = '$buku_id'
    ");

} else {

    // =======================
    // EBOOK
    // =======================
    $kode_buku = !empty($buku['kode_buku'])
        ? $buku['kode_buku']
        : 'EBOOK';

}

// =======================
// VALIDASI KODE BUKU
// =======================
if (empty($kode_buku)) {

    die("ERROR: kode buku tidak ditemukan!");

}

// =======================
// SIMPAN PEMINJAMAN
// =======================
$tgl_pinjam = date('Y-m-d');

$tgl_tempo = date(
    'Y-m-d',
    strtotime('+7 days')
);

$query = mysqli_query($koneksi, "
    INSERT INTO peminjaman
    (
        user_id,
        nis,
        buku_id,
        kode_buku,
        tanggal_pinjam,
        tanggal_jatuh_tempo,
        status
    )
    VALUES
    (
        '$user_id',
        '$nis',
        '$buku_id',
        '$kode_buku',
        '$tgl_pinjam',
        '$tgl_tempo',
        'dipinjam'
    )
");

if (!$query) {

    die(
        'ERROR INSERT: ' .
        mysqli_error($koneksi)
    );

}

// =======================
// 🔥 NOTIF SISWA
// =======================
$pesanSiswa =
    'Berhasil meminjam buku ' .
    $judulBuku .
    ' (Kode: ' .
    $kode_buku .
    ')';

$pesanSiswa = mysqli_real_escape_string(
    $koneksi,
    $pesanSiswa
);

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
        '$pesanSiswa',
        'info',
        0,
        NOW()
    )
");

// =======================
// 🔥 LOG AKTIVITAS
// =======================
logAktivitas(
    $koneksi,
    $userNama,
    'Meminjam buku: ' .
    $judulBuku .
    ' (Kode: ' .
    $kode_buku .
    ')'
);

// =======================
// 🔥 NOTIF ADMIN
// =======================
$judulNotif = mysqli_real_escape_string(
    $koneksi,
    $judulBuku
);

$kodeNotif = mysqli_real_escape_string(
    $koneksi,
    $kode_buku
);

$namaNotif = mysqli_real_escape_string(
    $koneksi,
    $userNama
);

$adminQuery = mysqli_query($koneksi, "
    SELECT id
    FROM users
    WHERE role = 'admin'
    OR role = 'super_admin'
");

while ($admin = mysqli_fetch_assoc($adminQuery)) {

    $admin_id = $admin['id'];

    $pesanNotif =
        $namaNotif .
        ' meminjam buku ' .
        $judulNotif .
        ' (Kode: ' .
        $kodeNotif .
        ')';

    $pesanNotif = mysqli_real_escape_string(
        $koneksi,
        $pesanNotif
    );

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
            '$admin_id',
            '$pesanNotif',
            'info',
            0,
            NOW()
        )
    ");

}

?>

<!DOCTYPE html>
<html>
<head>

    <title>Success</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

<script>

document.addEventListener(
    'DOMContentLoaded',
    function() {

        Swal.fire({

            title: 'SUCCESS!',

            html:
                'Book successfully borrowed <br><br>' +
                '<b>Book Code:</b> <?php echo $kode_buku; ?>',

            icon: 'success',

            confirmButtonText: 'OK'

        }).then(() => {

            window.location.href = '../borrowings/';

        });

    }
);

</script>

</body>
</html>

<?php
exit;
?>