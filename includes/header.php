<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/notification_helper.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$totalNotif = 0;

if (isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'siswa') {
        include __DIR__ . '/student_notification.php';
    }

    if ($_SESSION['role'] == 'admin') {
        include __DIR__ . '/admin_notification.php';
    }

    if ($_SESSION['role'] == 'super_admin') {
        include __DIR__ . '/superadmin_notification.php';
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>SmartLibrary - SMK PGRI 1</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <script>

        tailwind.config = {

            theme: {

                extend: {

                    colors: {

                        primary: '#2563EB',
                        secondary: '#10B981',
                        dark: '#1E293B'

                    },

                    fontFamily: {

                        sans: ['Inter', 'sans-serif']

                    }

                }

            }

        }

    </script>

</head>

<body class="bg-slate-50 text-slate-800 font-sans">

<div class="flex min-h-screen">

    <?php include __DIR__ . '/sidebar.php'; ?>

    <main class="flex-1 md:ml-64 p-4 md:p-8 min-h-screen">

        <!-- TOPBAR -->
        <div class="flex justify-end mb-6">

            <?php

            $cartCount = 0;

            if(isset($_SESSION['cart'])){

                foreach($_SESSION['cart'] as $item){

                    $cartCount += isset($item['qty'])
                        ? $item['qty']
                        : 1;

                }

            }

            ?>

            <!-- CART -->
            <div class="relative mr-4">

                <a
                    href="<?= BASE_URL ?>modules/ebooks/cart.php"
                    class="relative bg-white p-3 rounded-full shadow hover:bg-slate-100 transition flex items-center justify-center"
                >

                    <i class="fas fa-shopping-cart text-xl text-slate-700"></i>

                    <?php if($cartCount > 0): ?>

                        <span
                            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full"
                        >

                            <?php echo $cartCount; ?>

                        </span>

                    <?php endif; ?>

                </a>

            </div>

            <!-- NOTIFICATION -->
            <div class="relative">

                <button
                    onclick="toggleNotif(event)"
                    class="relative bg-white p-3 rounded-full shadow hover:bg-slate-100 transition"
                >

                    <i class="fas fa-bell text-xl text-slate-700"></i>

                    <?php if($totalNotif > 0): ?>

                        <span
                            id="notifBadge"
                            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full"
                        >

                            <?php echo $totalNotif; ?>

                        </span>

                    <?php endif; ?>

                </button>

                <!-- DROPDOWN -->
                <div
                    id="notifDropdown"
                    class="hidden absolute right-0 mt-3 w-96 bg-white rounded-2xl shadow-2xl border border-slate-200 z-50 overflow-hidden"
                >

                    <div class="px-5 py-4 border-b bg-slate-50">

                        <h2 class="font-bold text-slate-700 text-lg">
                            Notifications
                        </h2>

                    </div>

                    <div
                        id="notifList"
                        class="max-h-96 overflow-y-auto"
                    >

                        <?php
                        smartLibraryRenderNotificationList(
                            isset($notifQuery) ? $notifQuery : false
                        );
                        ?>

                    </div>

                </div>

            </div>

        </div>

<script>

function toggleNotif(event){

    event.stopPropagation();

    const dropdown =
        document.getElementById('notifDropdown');

    dropdown.classList.toggle('hidden');

    if(!dropdown.classList.contains('hidden')){

        fetch('<?= BASE_URL ?>includes/mark_notifications_read.php')
            .then(response => response.json())
            .then(data => {

                const badge =
                    document.getElementById('notifBadge');

                if(data.success && badge){

                    badge.remove();

                }

            })
            .catch(() => {});

    }

}

window.addEventListener('click', function(e){

    const dropdown =
        document.getElementById('notifDropdown');

    if(
        dropdown &&
        !e.target.closest('#notifDropdown')
    ){

        dropdown.classList.add('hidden');

    }

});

// SUCCESS
function successNotif(message){

    Swal.fire({

        icon: 'success',
        title: 'SUCCESS!',
        text: message,
        confirmButtonColor: '#6C63FF'

    });

}

// ERROR
function errorNotif(message){

    Swal.fire({

        icon: 'error',
        title: 'Oops...',
        text: message,
        confirmButtonColor: '#d33'

    });

}

// WARNING
function warningNotif(message){

    Swal.fire({

        icon: 'warning',
        title: 'Warning!',
        text: message,
        confirmButtonColor: '#f59e0b'

    });

}

</script>
