<?php

session_start();

require_once '../config/database.php';

// ==========================
// CEK SESSION
// ==========================
if (!isset($_SESSION['user_id'])) {

    echo json_encode([
        'notif' => '
            <div class="p-6 text-center text-red-500">
                Session user tidak ditemukan
            </div>
        ',
        'count' => 0
    ]);

    exit;

}

$user_id = $_SESSION['user_id'];

// ==========================
// AMBIL DATA NOTIF
// ==========================
$query = mysqli_query(
    $koneksi,
    "
    SELECT * FROM notifikasi
    WHERE user_id = '$user_id'
    ORDER BY created_at DESC
    LIMIT 10
    "
);

// ==========================
// HTML OUTPUT
// ==========================
$output = '';

if (mysqli_num_rows($query) > 0) {

    while($notif = mysqli_fetch_assoc($query)) {

        // WARNA TIPE
        $warna = 'text-blue-600';

        if($notif['tipe'] == 'success') {

            $warna = 'text-green-600';

        }
        elseif($notif['tipe'] == 'warning') {

            $warna = 'text-yellow-600';

        }
        elseif($notif['tipe'] == 'error') {

            $warna = 'text-red-600';

        }

        $output .= '

        <div class="p-4 border-b hover:bg-slate-50 transition">

            <div class="text-sm font-bold mb-1 '.$warna.'">

                '.ucfirst($notif['tipe']).'

            </div>

            <div class="text-sm text-slate-700 leading-relaxed">

                '.$notif['pesan'].'

            </div>

            <div class="text-xs text-slate-400 mt-2">

                '.$notif['created_at'].'

            </div>

        </div>

        ';

    }

} else {

    $output = '

    <div class="p-6 text-center text-slate-400">

        Tidak ada notifikasi

    </div>

    ';

}

// ==========================
// HITUNG BADGE
// ==========================
$countQuery = mysqli_query(
    $koneksi,
    "
    SELECT id FROM notifikasi
    WHERE user_id = '$user_id'
    AND dibaca = 0
    "
);

$count = mysqli_num_rows($countQuery);

// ==========================
// RETURN JSON
// ==========================
echo json_encode([

    'notif' => $output,
    'count' => $count

]);