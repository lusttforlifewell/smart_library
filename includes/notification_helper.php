<?php

function smartLibraryNotificationTableExists($koneksi)
{
    static $exists = null;

    if ($exists !== null) {
        return $exists;
    }

    $result = mysqli_query($koneksi, "SHOW TABLES LIKE 'notifikasi'");
    $exists = $result && mysqli_num_rows($result) > 0;

    return $exists;
}

function smartLibraryAddNotification($koneksi, $userId, $message, $type = 'info', $unique = false)
{
    if (!smartLibraryNotificationTableExists($koneksi)) {
        return false;
    }

    $allowedTypes = ['info', 'warning', 'error', 'success'];
    $type = in_array($type, $allowedTypes, true) ? $type : 'info';

    $userId = intval($userId);
    $messageSql = mysqli_real_escape_string($koneksi, $message);
    $typeSql = mysqli_real_escape_string($koneksi, $type);

    if ($unique) {
        $check = mysqli_query($koneksi, "
            SELECT id
            FROM notifikasi
            WHERE user_id = '$userId'
            AND pesan = '$messageSql'
            LIMIT 1
        ");

        if ($check && mysqli_num_rows($check) > 0) {
            return false;
        }
    }

    return mysqli_query($koneksi, "
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
            '$userId',
            '$messageSql',
            '$typeSql',
            0,
            NOW()
        )
    ");
}

function smartLibraryNotifyAdmins($koneksi, $message, $type = 'info', $unique = false)
{
    $adminQuery = mysqli_query($koneksi, "
        SELECT id
        FROM users
        WHERE role = 'admin'
        OR role = 'super_admin'
    ");

    if (!$adminQuery) {
        return;
    }

    while ($admin = mysqli_fetch_assoc($adminQuery)) {
        smartLibraryAddNotification($koneksi, $admin['id'], $message, $type, $unique);
    }
}

function smartLibraryGetUnreadNotificationCount($koneksi, $userId)
{
    if (!smartLibraryNotificationTableExists($koneksi)) {
        return 0;
    }

    $userId = intval($userId);
    $query = mysqli_query($koneksi, "
        SELECT id
        FROM notifikasi
        WHERE user_id = '$userId'
        AND dibaca = 0
    ");

    return $query ? mysqli_num_rows($query) : 0;
}

function smartLibraryGetNotificationQuery($koneksi, $userId, $limit = 15)
{
    if (!smartLibraryNotificationTableExists($koneksi)) {
        return false;
    }

    $userId = intval($userId);
    $limit = intval($limit);

    return mysqli_query($koneksi, "
        SELECT *
        FROM notifikasi
        WHERE user_id = '$userId'
        ORDER BY created_at DESC
        LIMIT $limit
    ");
}

function smartLibraryNotificationTypeClass($type)
{
    if ($type === 'success') {
        return 'text-green-600';
    }

    if ($type === 'warning') {
        return 'text-yellow-600';
    }

    if ($type === 'error') {
        return 'text-red-600';
    }

    return 'text-blue-600';
}

function smartLibraryRenderNotificationList($notifQuery)
{
    if ($notifQuery && mysqli_num_rows($notifQuery) > 0) {
        while ($notif = mysqli_fetch_assoc($notifQuery)) {
            $type = htmlspecialchars(ucfirst($notif['tipe']));
            $message = htmlspecialchars($notif['pesan']);
            $typeClass = smartLibraryNotificationTypeClass($notif['tipe']);
            $date = date('d M Y H:i', strtotime($notif['created_at']));
            $unreadClass = empty($notif['dibaca']) ? 'bg-blue-50/70' : '';

            echo '
                <div class="p-4 border-b hover:bg-slate-50 transition ' . $unreadClass . '">
                    <div class="text-sm font-bold mb-1 ' . $typeClass . '">' . $type . '</div>
                    <div class="text-sm text-slate-700 leading-relaxed">' . $message . '</div>
                    <div class="text-xs text-slate-400 mt-2">' . $date . '</div>
                </div>
            ';
        }

        return;
    }

    echo '
        <div class="p-6 text-center text-slate-400">
            Tidak ada notifikasi
        </div>
    ';
}

?>
