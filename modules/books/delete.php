<?php
// 1. Sertakan Proteksi Halaman (Auth Check)
require_once '../../includes/auth_check.php';

// 2. Sertakan Koneksi Database
require_once '../../config/database.php';

// 3. Validasi Role Pengguna
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../dashboard/");
    exit();
}

// 4. Ambil ID Buku dari URL dan amankan dengan intval
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 5. Proses Penghapusan
if ($id > 0) {
    // Cek keberadaan buku
    $check_query = "SELECT id FROM buku WHERE id = $id";
    $result = mysqli_query($koneksi, $check_query);

    if (mysqli_num_rows($result) > 0) {
        
        // PENTING: Menghapus Relasi Data (Foreign Key)
        mysqli_query($koneksi, "DELETE FROM peminjaman WHERE buku_id = $id");

        // Query Hapus Buku
        $delete_query = "DELETE FROM buku WHERE id = $id";

        if (mysqli_query($koneksi, $delete_query)) {
            // 6. Redirect Sukses
            header("Location: index.php?success=deleted");
            exit();
        } else {
            header("Location: index.php?error=Gagal menghapus data");
            exit();
        }
    } else {
        header("Location: index.php?error=Buku tidak ditemukan");
        exit();
    }
} else {
    header("Location: index.php?error=ID tidak valid");
    exit();
}
?>