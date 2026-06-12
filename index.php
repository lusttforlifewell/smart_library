<?php
session_start();

$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/';
if ($basePath === '//') {
    $basePath = '/';
}
if (!defined('BASE_URL')) {
    define('BASE_URL', $basePath);
}

if (isset($_SESSION['role'])) {
    header("Location: modules/dashboard/");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartLibrary - Perpustakaan Digital</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body{
            font-family:'Inter',sans-serif;
        }

        .hero-gradient{
            background: linear-gradient(135deg, #1E3A8A 0%, #2563EB 100%);
        }

        .feature-card{
            transition:0.3s;
        }

        .feature-card:hover{
            transform:translateY(-5px);
            box-shadow:0 20px 40px rgba(0,0,0,0.1);
        }

        #splash{
            position:fixed;
            inset:0;
            background:linear-gradient(135deg,#1E3A8A,#2563EB);
            z-index:9999;
            display:flex;
            justify-content:center;
            align-items:center;
            transition:0.5s;
        }

        #splash.hidden{
            opacity:0;
            visibility:hidden;
        }

        .loader{
            width:50px;
            height:50px;
            border:4px solid rgba(255,255,255,.3);
            border-top-color:#fff;
            border-radius:50%;
            animation:spin 1s linear infinite;
            margin:auto;
        }

        @keyframes spin{
            to{
                transform:rotate(360deg);
            }
        }
    </style>
</head>

<body class="bg-white text-slate-800">

<!-- SPLASH -->
<div id="splash">

    <div class="text-center">

        <img 
            src="<?= BASE_URL ?>assets/img/smart_library.png"
            alt="Logo"
            style="
                width:180px;
                margin:auto;
                margin-bottom:20px;
                border-radius:20px;
                background:white;
                padding:10px;
            ">

        <h1 class="text-4xl font-bold text-white mb-3">
            SmartLibrary
        </h1>

        <p class="text-blue-100 mb-6">
            Perpustakaan Digital Modern
        </p>

        <div class="loader"></div>

    </div>

</div>

<!-- LANDING -->
<div class="min-h-screen">

    <!-- NAVBAR -->
    <nav class="fixed top-0 left-0 w-full bg-white shadow-sm z-40">

        <div class="max-w-7xl mx-auto px-6">

            <div class="flex items-center justify-between h-16">

                <a href="#" class="flex items-center gap-3">

                    <img 
                        src="<?= BASE_URL ?>assets/img/smart_library.png"
                        alt="Logo"
                        style="
                            width:50px;
                            height:50px;
                            object-fit:contain;
                        ">

                    <span class="text-2xl font-bold text-blue-900">
                        SmartLibrary
                    </span>

                </a>

                <div class="flex gap-3">

                    <a href="modules/auth/login.php"
                       class="px-4 py-2 text-slate-600 hover:text-blue-700">
                        Login
                    </a>

                    <a href="modules/auth/register.php"
                       class="bg-blue-900 text-white px-5 py-2 rounded-lg">
                        Daftar
                    </a>

                </div>

            </div>

        </div>

    </nav>

    <!-- HERO -->
    <section class="hero-gradient pt-32 pb-20 px-6">

        <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-12 items-center">

            <div>

                <span class="bg-white/20 text-white px-4 py-2 rounded-full text-sm">
                    Sistem Perpustakaan Digital
                </span>

                <h1 class="text-5xl font-extrabold text-white leading-tight mt-6 mb-6">
                    Kelola Perpustakaan <br>
                    Lebih Mudah & Modern
                </h1>

                <p class="text-blue-100 text-lg leading-relaxed mb-10">
                    Solusi digital untuk pengelolaan buku, peminjaman,
                    pengembalian, dan ebook secara modern.
                </p>

                <div class="flex gap-4">

                    <a href="modules/auth/login.php"
                       class="bg-white text-blue-900 px-8 py-4 rounded-xl font-bold">
                        Masuk Sekarang
                    </a>

                    <a href="#features"
                       class="border border-white text-white px-8 py-4 rounded-xl font-bold">
                        Pelajari Fitur
                    </a>

                </div>

            </div>

            <div class="text-center">

                <img 
                    src="<?= BASE_URL ?>assets/img/logo.png"
                    alt="Hero Logo"
                    style="
                        width:400px;
                        margin:auto;
                        background:white;
                        padding:20px;
                        border-radius:30px;
                    ">

            </div>

        </div>

    </section>

    <!-- FEATURES -->
    <section id="features" class="py-20 px-6 bg-slate-50">

        <div class="max-w-7xl mx-auto">

            <div class="text-center mb-16">

                <h2 class="text-4xl font-bold mb-4">
                    Fitur Unggulan
                </h2>

                <p class="text-slate-500">
                    Sistem perpustakaan modern dan terintegrasi.
                </p>

            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="feature-card bg-white p-6 rounded-xl shadow">

                    <i class="fas fa-search text-3xl text-blue-700 mb-4"></i>

                    <h3 class="font-bold text-xl mb-2">
                        Pencarian Cepat
                    </h3>

                    <p class="text-slate-500 text-sm">
                        Cari buku dengan cepat dan mudah.
                    </p>

                </div>

                <div class="feature-card bg-white p-6 rounded-xl shadow">

                    <i class="fas fa-book text-3xl text-green-600 mb-4"></i>

                    <h3 class="font-bold text-xl mb-2">
                        Peminjaman Buku
                    </h3>

                    <p class="text-slate-500 text-sm">
                        Kelola peminjaman otomatis.
                    </p>

                </div>

                <div class="feature-card bg-white p-6 rounded-xl shadow">

                    <i class="fas fa-tablet-alt text-3xl text-purple-600 mb-4"></i>

                    <h3 class="font-bold text-xl mb-2">
                        Ebook Store
                    </h3>

                    <p class="text-slate-500 text-sm">
                        Akses ebook kapan saja.
                    </p>

                </div>

                <div class="feature-card bg-white p-6 rounded-xl shadow">

                    <i class="fas fa-chart-line text-3xl text-orange-600 mb-4"></i>

                    <h3 class="font-bold text-xl mb-2">
                        Laporan Realtime
                    </h3>

                    <p class="text-slate-500 text-sm">
                        Statistik dan laporan realtime.
                    </p>

                </div>

            </div>

        </div>

    </section>

    <!-- FOOTER -->
    <footer class="bg-slate-900 text-white py-12 text-center">

        <img 
            src="<?= BASE_URL ?>assets/img/logo.png"
            alt="Logo"
            style="
                width:90px;
                margin:auto;
                margin-bottom:15px;
                background:white;
                padding:8px;
                border-radius:15px;
            ">

        <h3 class="text-2xl font-bold mb-3">
            SmartLibrary
        </h3>

        <p class="text-slate-400">
            Perpustakaan Digital Modern
        </p>

    </footer>

</div>

<script>

window.addEventListener('load', ()=>{

    setTimeout(()=>{
        document.getElementById('splash').classList.add('hidden');
    },2000);

});

</script>

</body>
</html>