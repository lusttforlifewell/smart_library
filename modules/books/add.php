<?php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';
include '../../includes/header.php';
?>

<!-- SWEETALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php

if (isset($_POST['tambah'])) {

    // ✅ TAMBAHAN (tidak mengganggu kode lama)
    $kode_buku   = $_POST['kode_buku'];

    $judul       = $_POST['judul'];
    $penulis     = $_POST['penulis'];
    $stok        = $_POST['stok'];
    $kategori_id = $_POST['kategori'];
    $tipe        = $_POST['tipe'];

    // ======================
    // UPLOAD COVER
    // ======================
    $cover = $_FILES['cover']['name'];
    $tmp   = $_FILES['cover']['tmp_name'];

    $folder = "../../assets/img/";

    $ext = strtolower(pathinfo($cover, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png'];

    $cover_baru = time() . '_' . str_replace(' ', '_', strtolower($cover));

    if (!in_array($ext, $allowed)) {

        echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Format harus JPG/JPEG/PNG'
        });
        </script>";

    } else {

        if (!move_uploaded_file($tmp, $folder . $cover_baru)) {

            die("Upload gagal!");

        }

        // ======================
        // INSERT DATABASE
        // ======================
        $query = mysqli_query($koneksi, "
            INSERT INTO buku 
            (
                kode_buku,
                judul,
                penulis,
                kategori_id,
                stok,
                cover,
                tipe
            ) 
            VALUES 
            (
                '$kode_buku',
                '$judul',
                '$penulis',
                '$kategori_id',
                '$stok',
                '$cover_baru',
                '$tipe'
            )
        ");

        if (!$query) {

            die("Query Error: " . mysqli_error($koneksi));

        }

  // ======================
// AMBIL SUPER ADMIN
// ======================
$superadmin = mysqli_query(
    $koneksi,
    "
    SELECT id FROM users
    WHERE role='super_admin'
    LIMIT 1
    "
);

$dataAdmin = mysqli_fetch_assoc($superadmin);

$superadmin_id = $dataAdmin['id'];

// ======================
// NOTIF SUPER ADMIN
// ======================
$notifPesan =
    "Buku baru berhasil ditambahkan";

mysqli_query(
    $koneksi,
    "
    INSERT INTO notifikasi
    (
        user_id,
        pesan,
        tipe
    )

    VALUES
    (
        '$superadmin_id',
        '$notifPesan',
        'success'
    )
    "
);

        echo "<script>
        Swal.fire({
            title: 'Success!',
            text: 'Book added successfully',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href='index.php';
        });
        </script>";

    }

}

// ======================
// AMBIL KATEGORI
// ======================
$kategori = mysqli_query($koneksi, "SELECT * FROM kategori");

?>

<h1 class="text-2xl font-bold mb-6">

    Add New Book

</h1>

<div class="bg-white p-6 rounded-lg shadow-sm max-w-lg">
    
    <form method="POST"
          enctype="multipart/form-data">

        <!-- ✅ TAMBAHAN KODE BUKU -->
        <div class="mb-4">

            <label class="block text-sm font-medium mb-1">

                Book Code

            </label>

            <input type="text"
                   name="kode_buku" 
                   class="w-full border p-2 rounded" 
                   placeholder="Contoh: BK001"
                   required>

        </div>

        <!-- JUDUL -->
        <div class="mb-4">

            <label class="block text-sm font-medium mb-1">

                Title Book

            </label>

            <input type="text"
                   name="judul"
                   class="w-full border p-2 rounded"
                   required>

        </div>

        <!-- PENULIS -->
        <div class="mb-4">

            <label class="block text-sm font-medium mb-1">

                Writer

            </label>

            <input type="text"
                   name="penulis"
                   class="w-full border p-2 rounded"
                   required>

        </div>

        <!-- KATEGORI -->
        <div class="mb-4">

            <label class="block text-sm font-medium mb-1">

                Category

            </label>

            <select name="kategori"
                    class="w-full border p-2 rounded"
                    required>

                <option value="">

                    -- Choose Category --

                </option>

                <?php while($k = mysqli_fetch_assoc($kategori)): ?>

                    <option value="<?php echo $k['id']; ?>">

                        <?php echo $k['nama_kategori']; ?>

                    </option>

                <?php endwhile; ?>

            </select>

        </div>

        <!-- STOK -->
        <div class="mb-4">

            <label class="block text-sm font-medium mb-1">

                Stock

            </label>

            <input type="number"
                   name="stok"
                   class="w-full border p-2 rounded"
                   required>

        </div>

        <!-- TIPE -->
        <div class="mb-4">

            <label class="block text-sm font-medium mb-1">

                Type

            </label>

            <select name="tipe"
                    class="w-full border p-2 rounded"
                    required>

                <option value="fisik">

                    Physical

                </option>

                <option value="ebook">

                    Ebook

                </option>

            </select>

        </div>

        <!-- COVER -->
        <div class="mb-4">

            <label class="block text-sm font-medium mb-1">

                Book Cover

            </label>

            <input type="file"
                   name="cover"
                   class="w-full border p-2 rounded"
                   required>

        </div>

        <!-- BUTTON -->
        <button type="submit"
                name="tambah" 
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">

            Save

        </button>

        <a href="index.php"
           class="ml-2 text-slate-500 hover:underline">

            Cancel

        </a>

    </form>

</div>

<?php include '../../includes/footer.php'; ?>