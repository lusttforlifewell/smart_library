<?php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../dashboard/");
    exit();
}

$peminjaman_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// =======================
// AMBIL DATA PEMINJAMAN + BUKU
// =======================
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT p.*, b.tipe 
    FROM peminjaman p
    JOIN buku b ON p.buku_id = b.id
    WHERE p.id='$peminjaman_id'
"));

if (!$data) {
    die("Data peminjaman tidak ditemukan!");
}

$buku_id   = $data['buku_id'];
$kode_buku = $data['kode_buku'];
$tipe      = strtolower($data['tipe']);

// =======================
// HITUNG DENDA
// =======================
$today = date('Y-m-d');
$denda = 0;

if ($today > $data['tanggal_jatuh_tempo']) {
    $telat = (strtotime($today) - strtotime($data['tanggal_jatuh_tempo'])) / (60*60*24);
    $denda = $telat * 2000; // Rp2000/hari
}

// =======================
// UPDATE STATUS PEMINJAMAN
// =======================
mysqli_query($koneksi, "
    UPDATE peminjaman 
    SET status='dikembalikan', tanggal_kembali='$today', denda='$denda'
    WHERE id='$peminjaman_id'
");


// =======================
// JIKA FISIK → BALIKIN ITEM + STOK
// =======================
if ($tipe == 'fisik') {

    // kembalikan status buku_item
    mysqli_query($koneksi, "
        UPDATE buku_item 
        SET status='available' 
        WHERE kode_buku='$kode_buku'
    ");

    // tambah stok buku
    mysqli_query($koneksi, "
        UPDATE buku 
        SET stok = stok + 1 
        WHERE id='$buku_id'
    ");
}

// =======================
// NOTIF ADMIN
// =======================
$adminQuery = mysqli_query($koneksi, "
    SELECT id
    FROM users
    WHERE role='admin'
    OR role='super_admin'
");

while($admin = mysqli_fetch_assoc($adminQuery)){

    $pesan =
        "Buku dengan kode " .
        $kode_buku .
        " telah dikembalikan.";

    $pesan = mysqli_real_escape_string($koneksi, $pesan);

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
            '{$admin['id']}',
            '$pesan',
            'success',
            0,
            NOW()
        )
    ");

}

// =======================
// OUTPUT
// =======================
if ($denda > 0) {
    echo "<script>
    alert('Buku dikembalikan!\\nDenda: Rp $denda');
    window.location.href='index.php';
    </script>";
} else {
    echo "<script>
    alert('Buku berhasil dikembalikan!');
    window.location.href='index.php';
    </script>";
}
?>