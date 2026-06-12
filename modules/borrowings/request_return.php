<?php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';
require_once '../../includes/notification_helper.php';

$peminjaman_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// =======================
// VALIDASI DATA
// =======================
if ($role == 'siswa') {

    $data = mysqli_fetch_assoc(mysqli_query($koneksi, "
        SELECT * 
        FROM peminjaman
        WHERE id='$peminjaman_id'
        AND user_id='$user_id'
    "));

} else {

    $data = mysqli_fetch_assoc(mysqli_query($koneksi, "
        SELECT *
        FROM peminjaman
        WHERE id='$peminjaman_id'
    "));

}

if (!$data) {

    die("Data tidak valid!");

}

$buku_id   = $data['buku_id'];
$kode_buku = $data['kode_buku'];

$detail = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT
        p.*,
        u.nama,
        b.judul
    FROM peminjaman p
    JOIN users u
        ON p.user_id = u.id
    JOIN buku b
        ON p.buku_id = b.id
    WHERE p.id = '$peminjaman_id'
"));

// =======================
// SISWA REQUEST RETURN
// =======================
if ($role == 'siswa') {

    mysqli_query($koneksi, "
        UPDATE peminjaman
        SET status='waiting'
        WHERE id='$peminjaman_id'
    ");

    if ($detail) {

        $pesan =
            $detail['nama'] .
            ' meminta konfirmasi pengembalian buku ' .
            $detail['judul'] .
            ' (Kode: ' .
            $kode_buku .
            ').';

        smartLibraryNotifyAdmins($koneksi, $pesan, 'warning', true);

        smartLibraryAddNotification(
            $koneksi,
            $user_id,
            'Request pengembalian buku ' . $detail['judul'] . ' sudah dikirim.',
            'info'
        );

    }

    echo "
    <script>

        alert('Request return berhasil dikirim!');

        window.location.href='index.php';

    </script>
    ";

    exit;

}

// =======================
// ADMIN ACCEPT RETURN
// =======================
if (
    $role == 'admin' ||
    $role == 'super_admin'
) {

    mysqli_query($koneksi, "
        UPDATE peminjaman
        SET status='dikembalikan'
        WHERE id='$peminjaman_id'
    ");

    mysqli_query($koneksi, "
        UPDATE buku_item
        SET status='available'
        WHERE kode_buku='$kode_buku'
    ");

    mysqli_query($koneksi, "
        UPDATE buku
        SET stok = stok + 1
        WHERE id='$buku_id'
    ");

    if ($detail) {

        smartLibraryAddNotification(
            $koneksi,
            $detail['user_id'],
            'Pengembalian buku ' . $detail['judul'] . ' sudah diterima admin.',
            'success'
        );

    }

    echo "
    <script>

        alert('Pengembalian diterima!');

        window.location.href='index.php';

    </script>
    ";

}
?>
