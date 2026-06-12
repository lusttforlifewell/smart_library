<?php
session_start();

// Redirect jika sudah login
if (isset($_SESSION['role'])) {
    header("Location: ../dashboard/");
    exit();
}

require_once '../../config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitasi input
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $nis = trim($_POST['nis']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    // Validasi dasar
    if (empty($nama) || empty($email) || empty($nis) || empty($password)) {

        $error = "Semua field wajib diisi!";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Format email tidak valid!";

    } elseif ($password !== $confirm) {

        $error = "Password dan konfirmasi password tidak cocok!";

    } elseif (strlen($password) < 6) {

        $error = "Password minimal 6 karakter!";

    } else {

        // Escape untuk query SQL
        $nama = mysqli_real_escape_string($koneksi, $nama);
        $email = mysqli_real_escape_string($koneksi, $email);
        $nis = mysqli_real_escape_string($koneksi, $nis);

        // Cek duplikasi Email
        $check_email = mysqli_query(
            $koneksi,
            "SELECT id FROM users WHERE email='$email'"
        );

        if (mysqli_num_rows($check_email) > 0) {

            $error = "Email sudah terdaftar!";

        } else {

            // Cek duplikasi NIS
            $check_nis = mysqli_query(
                $koneksi,
                "SELECT id FROM users WHERE nis='$nis'"
            );

            if (mysqli_num_rows($check_nis) > 0) {

                $error = "NIS sudah terdaftar!";

            } else {

                // ==========================
                // INSERT USER BARU
                // ==========================
                $query = "
                    INSERT INTO users
                    (
                        nama,
                        email,
                        password,
                        role,
                        nis
                    ) 
                    VALUES
                    (
                        '$nama',
                        '$email',
                        '$password',
                        'siswa',
                        '$nis'
                    )
                ";

                if (mysqli_query($koneksi, $query)) {

                
            // ==========================
// AMBIL SUPER ADMIN
// ==========================
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

// ==========================
// NOTIF SUPER ADMIN
// ==========================
$notifPesan =
    "User baru berhasil mendaftar";

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
        'info'
    )
    "
);

                    $success =
                        "Registrasi berhasil! Mengalihkan ke halaman login...";

                    header("refresh:2;url=login.php");

                } else {

                    $error =
                        "Gagal registrasi: "
                        . mysqli_error($koneksi);

                }

            }

        }

    }

}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Daftar Akun - SmartLibrary
    </title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          rel="stylesheet">

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

    <style>

        .fade-in {

            animation: fadeIn 0.3s ease-in-out;

        }

        @keyframes fadeIn {

            from {

                opacity: 0;
                transform: translateY(10px);

            }

            to {

                opacity: 1;
                transform: translateY(0);

            }

        }

    </style>

</head>

<body
style="
background:url('../../assets/img/bg.jpg');
background-size:cover;
background-position:center;
background-repeat:no-repeat;
"
class="min-h-screen flex items-center justify-center">

<!-- Overlay gelap -->
<div class="absolute inset-0 bg-black/50"></div>

<!-- Card Register -->
<div class="
relative
z-10
w-full
max-w-md
p-8
rounded-3xl
bg-white/10
backdrop-blur-xl
border
border-white/20
shadow-[0_8px_32px_rgba(0,0,0,0.35)]
fade-in
">

        <div class="text-center mb-6">

            <div class="
w-16
h-16
rounded-full
bg-white/20
backdrop-blur-md
border
border-white/20
flex
items-center
justify-center
mx-auto
mb-4">

    <i class="fas fa-user-plus text-white text-xl"></i>

</div>
           <h1 class="text-2xl font-bold text-white">

                Daftar Akun Siswa

            </h1>

           <p class="text-white/70 text-sm">

                SMK PGRI 1 Surabaya

            </p>

        </div>

        <?php if ($error): ?>

            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2 text-sm">

                <i class="fas fa-exclamation-circle"></i>

                <?php echo htmlspecialchars($error); ?>

            </div>

        <?php endif; ?>

        <?php if ($success): ?>

            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2 text-sm">

                <i class="fas fa-check-circle"></i>

                <?php echo htmlspecialchars($success); ?>

            </div>

        <?php endif; ?>

        <form method="POST"
              class="space-y-4">

            <div>

               <label class="block text-sm font-medium text-white mb-2">
    Nama Lengkap
</label>
                <input type="text"
                       name="nama"
                       value="<?php echo isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : ''; ?>"
                       class="
w-full
px-4
py-3
bg-white/10
backdrop-blur-md
border
border-white/20
rounded-xl
text-white
placeholder-white/50
focus:outline-none
focus:ring-2
focus:ring-blue-400
transition
"
                       placeholder="* Full name is required"
                       required>

            </div>

            <div>

                <label class="block text-sm font-medium text-white mb-2">

                    Email

                </label>

                <input type="email"
                       name="email"
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                       class="
w-full
px-4
py-3
bg-white/10
backdrop-blur-md
border
border-white/20
rounded-xl
text-white
placeholder-white/50
focus:outline-none
focus:ring-2
focus:ring-blue-400
transition
"
                       placeholder="* Email is required"
                       required>

            </div>

            <div>

                <label class="block text-sm font-medium text-white mb-2">
    NIS (Nomor Induk Siswa)
</label>

                <input type="text"
                       name="nis"
                       value="<?php echo isset($_POST['nis']) ? htmlspecialchars($_POST['nis']) : ''; ?>"
                       class="
w-full
px-4
py-3
bg-white/10
backdrop-blur-md
border
border-white/20
rounded-xl
text-white
placeholder-white/50
focus:outline-none
focus:ring-2
focus:ring-blue-400
transition
"
                        placeholder="* Student ID is required"
                       required>

            </div>

            <div>

                <label class="block text-sm font-medium text-white mb-2">

                    Password

                </label>

                <input type="password"
                       name="password"
                       class="
w-full
px-4
py-3
bg-white/10
backdrop-blur-md
border
border-white/20
rounded-xl
text-white
placeholder-white/50
focus:outline-none
focus:ring-2
focus:ring-blue-400
transition
"
                        placeholder="* Password is required"
                       required>

            </div>

            <div>

               <label class="block text-sm font-medium text-white mb-2">
    Konfirmasi Password
</label>


                <input type="password"
                       name="confirm_password"
                       class="
w-full
px-4
py-3
bg-white/10
backdrop-blur-md
border
border-white/20
rounded-xl
text-white
placeholder-white/50
focus:outline-none
focus:ring-2
focus:ring-blue-400
transition
"
                       placeholder="* Confirm password is required"
                       required>

            </div>

            <button type="submit"
                    class="w-full bg-primary hover:bg-blue-700 text-white py-2.5 rounded-lg font-medium transition flex justify-center items-center gap-2 shadow-md">

                <i class="fas fa-user-plus"></i>

                Daftar Sekarang

            </button>

        </form>

       <p class="text-center text-sm mt-6 text-white/80">

        Sudah punya akun?

    <a href="login.php"
       class="text-blue-500 hover:text-blue-400 font-semibold transition">

        Login di sini

    </a>

</p>

        </p>

    </div>

</body>

</html>