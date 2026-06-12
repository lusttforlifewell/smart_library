<?php
ob_start();

require_once '../../includes/auth_check.php';
require_once '../../config/database.php';

$role = $_SESSION['role'];

// ================= SECURITY =================
if (!in_array($role, ['super_admin','admin','siswa'])) {
    header("Location: ../dashboard/");
    exit;
}

// ================= SEARCH =================
$search = isset($_GET['search']) 
    ? mysqli_real_escape_string($koneksi, $_GET['search']) 
    : '';

// ================= QUERY =================
$books_fiction = mysqli_query($koneksi, "
    SELECT b.*, k.nama_kategori 
    FROM buku b 
    LEFT JOIN kategori k ON b.kategori_id=k.id
    WHERE k.jenis='Fiction'
    AND (b.judul LIKE '%$search%' OR b.penulis LIKE '%$search%')
");

$books_nonfiction = mysqli_query($koneksi, "
    SELECT b.*, k.nama_kategori 
    FROM buku b 
    LEFT JOIN kategori k ON b.kategori_id=k.id
    WHERE k.jenis='Non-Fiction'
    AND (b.judul LIKE '%$search%' OR b.penulis LIKE '%$search%')
");

// ================= HEADER =================
include '../../includes/header.php';
?>

<div class="w-full px-6 md:px-8 lg:px-10">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">

        <div class="flex flex-col sm:flex-row sm:items-center gap-4 w-full">
            
            <h1 class="text-3xl font-bold text-slate-800 whitespace-nowrap">
                Book Catalog
                <?php if($role === 'super_admin'): ?>
                    <span class="text-purple-500 text-sm">(Super Admin)</span>
                <?php endif; ?>
            </h1>

            <!-- SEARCH -->
            <form method="GET" class="flex-1 w-full">
                <input 
                    type="text" 
                    name="search" 
                    value="<?= htmlspecialchars($search); ?>" 
                    placeholder="Search by title or author..."
                    class="w-full bg-white border border-slate-300 py-3 px-5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm"
                >
            </form>

        </div>

        <!-- ADD BOOK -->
        <?php if (in_array($role, ['admin','super_admin'])): ?>
            <a href="add.php" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium shadow transition">
               + Add Book
            </a>
        <?php endif; ?>

    </div>

    <!-- ================= FICTION ================= -->
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-8">

        <h2 class="text-2xl font-bold text-blue-600 mb-5">
            Fiction Books
        </h2>

        <div class="overflow-x-auto">

            <table class="w-full text-left">

                <thead>
                    <tr class="border-b text-slate-700">
                        <th class="pb-4">Cover</th>
                        <th class="pb-4">Title</th>
                        <th class="pb-4">Category</th>
                        <th class="pb-4">Stock</th>
                        <th class="pb-4">Action</th>
                    </tr>
                </thead>

                <tbody>

                <?php while($b = mysqli_fetch_assoc($books_fiction)): ?>

                    <tr class="border-b hover:bg-slate-50 transition">

                        <!-- COVER -->
                        <td class="py-4">
                            <?php 
                            $img = !empty($b['cover']) 
                                ? '../../assets/img/'.$b['cover'] 
                                : '../../assets/img/no-image.png'; 
                            ?>

                            <img src="<?= $img ?>" 
                                 class="w-16 h-24 object-cover rounded-lg shadow">
                        </td>

                        <!-- TITLE -->
                        <td class="py-4">
                            <p class="font-semibold text-lg text-slate-800">
                                <?= $b['judul'] ?>
                            </p>

                            <p class="text-sm text-slate-500">
                                <?= $b['penulis'] ?>
                            </p>
                        </td>

                        <!-- CATEGORY -->
                        <td class="py-4 text-slate-700">
                            <?= $b['nama_kategori'] ?>
                        </td>

                        <!-- STOCK -->
                        <td class="py-4">
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-sm font-medium">
                                <?= $b['stok'] ?>
                            </span>
                        </td>

                        <!-- ACTION -->
                        <td class="py-4">

                            <?php if ($role === 'siswa'): ?>

                                <?php if ($b['stok'] > 0): ?>

                                    <a href="../borrowings/borrow_form.php?id=<?= $b['id'] ?>" 
                                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition duration-200 shadow-sm">
                                       Borrow
                                    </a>

                                <?php else: ?>

                                    <span class="inline-block bg-red-100 text-red-600 text-sm font-medium px-4 py-2 rounded-lg">
                                        Empty
                                    </span>

                                <?php endif; ?>

                            <?php else: ?>

                                <a href="edit.php?id=<?= $b['id'] ?>" 
                                   class="text-yellow-600 hover:text-yellow-700 font-medium">
                                   Edit
                                </a>

                                |

                                <a href="delete.php?id=<?= $b['id'] ?>" 
                                   class="text-red-600 hover:text-red-700 font-medium"
                                   onclick="return confirm('Delete this book?')">
                                   Delete
                                </a>

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

    <!-- ================= NON FICTION ================= -->
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-8">

        <h2 class="text-2xl font-bold text-green-600 mb-5">
            Non-Fiction Books
        </h2>

        <div class="overflow-x-auto">

            <table class="w-full text-left">

                <thead>
                    <tr class="border-b text-slate-700">
                        <th class="pb-4">Cover</th>
                        <th class="pb-4">Title</th>
                        <th class="pb-4">Category</th>
                        <th class="pb-4">Stock</th>
                        <th class="pb-4">Action</th>
                    </tr>
                </thead>

                <tbody>

                <?php while($b = mysqli_fetch_assoc($books_nonfiction)): ?>

                    <tr class="border-b hover:bg-slate-50 transition">

                        <!-- COVER -->
                        <td class="py-4">
                            <?php 
                            $img = !empty($b['cover']) 
                                ? '../../assets/img/'.$b['cover'] 
                                : '../../assets/img/no-image.png'; 
                            ?>

                            <img src="<?= $img ?>" 
                                 class="w-16 h-24 object-cover rounded-lg shadow">
                        </td>

                        <!-- TITLE -->
                        <td class="py-4">
                            <p class="font-semibold text-lg text-slate-800">
                                <?= $b['judul'] ?>
                            </p>

                            <p class="text-sm text-slate-500">
                                <?= $b['penulis'] ?>
                            </p>
                        </td>

                        <!-- CATEGORY -->
                        <td class="py-4 text-slate-700">
                            <?= $b['nama_kategori'] ?>
                        </td>

                        <!-- STOCK -->
                        <td class="py-4">
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-lg text-sm font-medium">
                                <?= $b['stok'] ?>
                            </span>
                        </td>

                        <!-- ACTION -->
                        <td class="py-4">

                            <?php if ($role === 'siswa'): ?>

                                <?php if ($b['stok'] > 0): ?>

                                    <a href="../borrowings/borrow_form.php?id=<?= $b['id'] ?>" 
                                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition duration-200 shadow-sm">
                                       Borrow
                                    </a>

                                <?php else: ?>

                                    <span class="inline-block bg-red-100 text-red-600 text-sm font-medium px-4 py-2 rounded-lg">
                                        Empty
                                    </span>

                                <?php endif; ?>

                            <?php else: ?>

                                <a href="edit.php?id=<?= $b['id'] ?>" 
                                   class="text-yellow-600 hover:text-yellow-700 font-medium">
                                   Edit
                                </a>

                                |

                                <a href="delete.php?id=<?= $b['id'] ?>" 
                                   class="text-red-600 hover:text-red-700 font-medium"
                                   onclick="return confirm('Delete this book?')">
                                   Delete
                                </a>

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include '../../includes/footer.php'; ?>
<?php ob_end_flush(); ?>