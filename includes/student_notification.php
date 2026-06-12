<?php

require_once __DIR__ . '/notification_helper.php';

if ($_SESSION['role'] == 'siswa') {

    $user_id = $_SESSION['user_id'];
    $today = date('Y-m-d');

    // ==========================
    // AMBIL DATA PEMINJAMAN
    // ==========================
    $borrowQuery = mysqli_query($koneksi, "
        SELECT 
            peminjaman.*,
            buku.judul,
            DATEDIFF(tanggal_jatuh_tempo, '$today') AS sisa_hari
        FROM peminjaman
        JOIN buku 
            ON peminjaman.buku_id = buku.id
        WHERE peminjaman.user_id = '$user_id'
        AND peminjaman.status = 'dipinjam'
    ");

    // ==========================
    // CEK DATA
    // ==========================
    if ($borrowQuery) {

        while ($row = mysqli_fetch_assoc($borrowQuery)) {

            $judul_buku = mysqli_real_escape_string(
                $koneksi,
                $row['judul']
            );

            // ==========================
            // H-1 PENGEMBALIAN
            // ==========================
            if ($row['sisa_hari'] == 1) {

                $pesan =
                    "Buku " .
                    $judul_buku .
                    " harus dikembalikan besok.";

                $cek = mysqli_query($koneksi, "
                    SELECT id
                    FROM notifikasi
                    WHERE user_id = '$user_id'
                    AND pesan = '$pesan'
                ");

                if ($cek && mysqli_num_rows($cek) == 0) {

                    smartLibraryAddNotification($koneksi, $user_id, $pesan, 'warning');

                }

            }

            // ==========================
            // TERLAMBAT
            // ==========================
            if ($row['sisa_hari'] < 0) {

                $hari_telat = abs($row['sisa_hari']);

                $pesan =
                    "Buku " .
                    $judul_buku .
                    " terlambat " .
                    $hari_telat .
                    " hari.";

                $cek = mysqli_query($koneksi, "
                    SELECT id
                    FROM notifikasi
                    WHERE user_id = '$user_id'
                    AND pesan = '$pesan'
                ");

                if ($cek && mysqli_num_rows($cek) == 0) {

                    smartLibraryAddNotification($koneksi, $user_id, $pesan, 'warning');

                }

            }

        }

    }

    // ==========================
    // TOTAL NOTIF SISWA
    // ==========================
    $totalNotif = smartLibraryGetUnreadNotificationCount($koneksi, $user_id);

    // ==========================
    // AMBIL DATA NOTIF SISWA
    // ==========================
    $notifQuery = smartLibraryGetNotificationQuery($koneksi, $user_id);

}

?>
