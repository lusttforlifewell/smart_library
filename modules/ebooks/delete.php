<?php

require_once '../../includes/auth_check.php';
require_once __DIR__ . '/../../config/database.php';

// VALIDASI ROLE
$role = strtolower(trim($_SESSION['role']));

if ($role != 'admin' && $role != 'super_admin') {

    header("Location: ../dashboard/");
    exit();
}

// AMBIL ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// CEK DATA EBOOK
$ebook = mysqli_fetch_assoc(
    mysqli_query(
        $koneksi,
        "SELECT * FROM ebook WHERE id='$id'"
    )
);

if (!$ebook) {

    echo "
    <script>
        alert('Ebook tidak ditemukan!');
        window.location.href='index.php';
    </script>
    ";

    exit();
}

// ===============================
// HAPUS COVER
// ===============================
$coverPath =
    '../../assets/ebook_cover/' .
    $ebook['cover'];

if (
    !empty($ebook['cover']) &&
    file_exists($coverPath)
) {

    unlink($coverPath);
}

// ===============================
// HAPUS PDF
// ===============================
$pdfPath =
    '../../assets/ebooks/' .
    $ebook['file_url'];

if (
    !empty($ebook['file_url']) &&
    file_exists($pdfPath)
) {

    unlink($pdfPath);
}

// ===============================
// HAPUS DATABASE
// ===============================
mysqli_query(
    $koneksi,
    "DELETE FROM ebook WHERE id='$id'"
);

// ===============================
// REDIRECT
// ===============================
echo "
<script>
    alert('Ebook berhasil dihapus!');
    window.location.href='index.php';
</script>
";
?>