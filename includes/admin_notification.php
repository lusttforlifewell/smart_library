<?php

require_once __DIR__ . '/notification_helper.php';

if (
    $_SESSION['role'] == 'admin'
    ||
    $_SESSION['role'] == 'super_admin'
) {

    $user_id = $_SESSION['user_id'];

    // ==========================
    // NOTIF REQUEST RETURN
    // ==========================
    $returnQuery = mysqli_query($koneksi, "
        SELECT
            p.kode_buku,
            u.nama,
            b.judul
        FROM peminjaman p
        JOIN users u
            ON p.user_id = u.id
        JOIN buku b
            ON p.buku_id = b.id
        WHERE p.status = 'waiting'
    ");

    if ($returnQuery) {

        while ($return = mysqli_fetch_assoc($returnQuery)) {

            $pesan =
                $return['nama'] .
                ' meminta konfirmasi pengembalian buku ' .
                $return['judul'] .
                ' (Kode: ' .
                $return['kode_buku'] .
                ').';

            smartLibraryNotifyAdmins($koneksi, $pesan, 'warning', true);

        }

    }

    // ==========================
    // TOTAL NOTIF ADMIN
    // ==========================
    $totalNotif = smartLibraryGetUnreadNotificationCount($koneksi, $user_id);

    // ==========================
    // AMBIL DATA NOTIF ADMIN
    // ==========================
    $notifQuery = smartLibraryGetNotificationQuery($koneksi, $user_id);

}

?>
