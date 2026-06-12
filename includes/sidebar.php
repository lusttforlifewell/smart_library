<!-- Mobile Overlay -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-20 hidden md:hidden" onclick="toggleSidebar()"></div>

<aside id="sidebar" class="fixed top-0 left-0 z-30 w-64 h-screen bg-slate-900 text-white transform -translate-x-full md:translate-x-0 transition-transform duration-300 flex flex-col shadow-xl">
    
   <!-- LOGO -->
<div class="p-5 border-b border-slate-700 flex items-center justify-between">
    <a href="../dashboard/" class="flex items-center gap-3">

   <img src="<?= BASE_URL ?>assets/img/smart_library.png"
     alt="Logo"
     class="h-10 w-auto">
    <h1 class="text-2xl font-bold text-white tracking-wide">
        SmartLibrary
    </h1>

</a>
    <button onclick="toggleSidebar()" 
            class="md:hidden text-slate-400 hover:text-white">
        <i class="fas fa-times text-lg"></i>
    </button>
</div>
    <!-- NAVIGATION -->
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

        <!-- DASHBOARD -->
        <a href="../dashboard/" 
        class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 
        <?php echo strpos($_SERVER['REQUEST_URI'], 'dashboard') ? 'active' : ''; ?>">
            <i class="fas fa-home w-5 text-center"></i> Dashboard
        </a>

        <!-- ================= SUPERADMIN ================= -->
        <?php if ($_SESSION['role'] === 'super_admin'): ?>
        <div class="pt-4 pb-1 text-xs font-semibold text-purple-400 uppercase tracking-wider">Super Admin</div>

        <a href="../users/index.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 hover:bg-slate-800">
            <i class="fas fa-users w-5 text-center"></i> Manage
        </a>

        <a href="../books/" class="sidebar-link flex items-center gap-3 px-3 py-2.5 hover:bg-slate-800">
            <i class="fas fa-book w-5 text-center"></i> Book Catalogue
        </a>

        <!-- ✅ FIX DI SINI -->
        <a href="../settings/index.php" 
        class="sidebar-link flex items-center gap-3 px-3 py-2.5 hover:bg-slate-800 
        <?php echo strpos($_SERVER['REQUEST_URI'], 'settings') ? 'active' : ''; ?>">
            <i class="fas fa-cogs w-5 text-center"></i> System Settings
        </a>

        <a href="../borrowings/" class="sidebar-link flex items-center gap-3 px-3 py-2.5 hover:bg-slate-800">
            <i class="fas fa-exchange-alt w-5 text-center"></i> Borrowing
        </a>

        <a href="../reports/" class="sidebar-link flex items-center gap-3 px-3 py-2.5 hover:bg-slate-800">
            <i class="fas fa-chart-line w-5 text-center"></i> Reports
        </a>

        <a href="../reports/activity.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 hover:bg-slate-800">
            <i class="fas fa-history w-5 text-center"></i> Activity Log
        </a>
        <?php endif; ?>

        <!-- ================= ADMIN ================= -->
        <?php if ($_SESSION['role'] === 'admin'): ?>
        <div class="pt-4 pb-1 text-xs font-semibold text-slate-500 uppercase tracking-wider">Menu Admin</div>

        <a href="../books/" class="sidebar-link flex items-center gap-3 px-3 py-2.5 hover:bg-slate-800">
            <i class="fas fa-book w-5 text-center"></i> Book Data
        </a>

        <a href="../borrowings/" class="sidebar-link flex items-center gap-3 px-3 py-2.5 hover:bg-slate-800">
            <i class="fas fa-exchange-alt w-5 text-center"></i> Borrowing
        </a>

        <a href="../reports/" class="sidebar-link flex items-center gap-3 px-3 py-2.5 hover:bg-slate-800">
            <i class="fas fa-chart-line w-5 text-center"></i> Reports
        </a>
        <?php endif; ?>

        <!-- ================= GENERAL ================= -->
        <?php if ($_SESSION['role'] !== 'super_admin'): ?>
        <div class="pt-4 pb-1 text-xs font-semibold text-slate-500 uppercase tracking-wider">General</div>

        <a href="../books/" class="sidebar-link flex items-center gap-3 px-3 py-2.5 hover:bg-slate-800">
            <i class="fas fa-search w-5 text-center"></i> Book Catalogue
        </a>

        <?php if ($_SESSION['role'] === 'siswa'): ?>
        <a href="../borrowings/" class="sidebar-link flex items-center gap-3 px-3 py-2.5 hover:bg-slate-800">
            <i class="fas fa-history w-5 text-center"></i> My Borrowing
        </a>
        <?php endif; ?>

        <a href="../ebooks/" class="sidebar-link flex items-center gap-3 px-3 py-2.5 hover:bg-slate-800">
            <i class="fas fa-tablet-alt w-5 text-center"></i> Ebook Store
        </a>

        <?php endif; ?>

    </nav>
    
    <!-- USER -->
    <div class="p-4 border-t border-slate-700">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center font-bold text-sm shadow-lg">
                <?php echo strtoupper(substr($_SESSION['nama'], 0, 1)); ?>
            </div>

            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium truncate">
                    <?php echo htmlspecialchars($_SESSION['nama']); ?>
                </p>
                <p class="text-xs text-slate-400 capitalize">
                    <?php echo htmlspecialchars($_SESSION['role']); ?>
                </p>
            </div>
        </div>

        <a href="../auth/logout.php" class="flex items-center justify-center gap-2 w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-sm font-medium transition shadow-md">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</aside>
