<?php
ob_start();

require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

// VALIDASI ROLE ADMIN
$role = strtolower(trim($_SESSION['role']));

if ($role != 'admin' && $role != 'super_admin') {
    header("Location: ../dashboard/");
    exit();
}

// PROSES TAMBAH EBOOK
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $judul   = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $penulis = mysqli_real_escape_string($koneksi, $_POST['penulis']);
    $harga   = mysqli_real_escape_string($koneksi, $_POST['harga']);

    // ===============================
    // UPLOAD PDF
    // ===============================

    $pdfName = $_FILES['ebook_file']['name'];
    $pdfTmp  = $_FILES['ebook_file']['tmp_name'];

    // FOLDER PDF
    $pdfDir = '../../assets/ebooks/';

    // CEK FOLDER
    if (!is_dir($pdfDir)) {
        mkdir($pdfDir, 0777, true);
    }

    // RENAME PDF
    $newPdf = time() . '_' . $pdfName;

    // UPLOAD PDF
    move_uploaded_file($pdfTmp, $pdfDir . $newPdf);

    // ===============================
    // UPLOAD COVER
    // ===============================

    $coverName = $_FILES['cover']['name'];
    $coverTmp  = $_FILES['cover']['tmp_name'];

    // FOLDER COVER
    $uploadDir = '../../assets/ebook_cover/';

    // CEK FOLDER
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // RENAME COVER
    $newCover = time() . '_' . $coverName;

    // UPLOAD COVER
    move_uploaded_file($coverTmp, $uploadDir . $newCover);

    // ===============================
    // INSERT DATABASE
    // ===============================

    $query = "INSERT INTO ebook 
            (judul, penulis, harga, file_url, cover)
            VALUES
            ('$judul', '$penulis', '$harga', '$newPdf', '$newCover')";

    mysqli_query($koneksi, $query);

    header("Location: index.php");
    exit();
}

include '../../includes/header.php';
?>

<div class="w-full p-4 md:p-6 lg:p-8 fade-in">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-8">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Add Ebook
            </h1>

            <p class="text-slate-500 mt-1">
                Tambahkan ebook baru ke katalog siswa
            </p>
        </div>

        <a href="index.php"
           class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg transition">

            Back
        </a>

    </div>

    <!-- FORM -->
    <div class="bg-white rounded-2xl shadow p-6">

        <form method="POST"
              enctype="multipart/form-data"
              class="space-y-5">

            <!-- JUDUL -->
            <div>

                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Judul Ebook
                </label>

                <input type="text"
                       name="judul"
                       required
                       class="w-full border border-slate-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">

            </div>

            <!-- PENULIS -->
            <div>

                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Penulis
                </label>

                <input type="text"
                       name="penulis"
                       required
                       class="w-full border border-slate-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">

            </div>

            <!-- HARGA -->
            <div>

                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Harga
                </label>

                <input type="number"
                       name="harga"
                       required
                       class="w-full border border-slate-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">

            </div>

            <!-- PDF EBOOK -->
            <div>

                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Upload PDF Ebook
                </label>

                <input type="file"
                       name="ebook_file"
                       accept="application/pdf"
                       required
                       class="w-full border border-slate-300 rounded-lg p-3 bg-white">

            </div>

            <!-- COVER -->
            <div>

                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Cover Ebook
                </label>

                <input type="file"
                       name="cover"
                       accept="image/*"
                       required
                       class="w-full border border-slate-300 rounded-lg p-3 bg-white">

            </div>

            <!-- BUTTON -->
            <div class="pt-4">

                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition">

                    Save Ebook

                </button>

            </div>

        </form>

    </div>

</div>

<?php include '../../includes/footer.php'; ?>