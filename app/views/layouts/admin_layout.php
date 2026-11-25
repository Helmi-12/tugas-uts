<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentUser = $_SESSION['user'] ?? null;
if (!$currentUser || ($currentUser['role'] ?? '') !== 'admin') {
    $_SESSION['flash_error'] = 'Akses ditolak. Silakan login sebagai Administrator.';
    header('Location: index.php?action=login');
    exit;
}
$adminAction = $_GET['action'] ?? 'admin_dashboard';
?>
<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Dashboard Admin - NJ Aluminium') ?></title>
    <link rel="icon" type="image/svg+xml" href="assets/images/icon-192.svg">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta_Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body class="h-full flex text-slate-100 font-['Inter'] antialiased">

    <!-- Admin Sidebar -->
    <aside class="w-64 bg-slate-950 border-r border-slate-800 flex flex-col flex-shrink-0 z-50 relative pointer-events-auto no-print">
        <!-- Brand Header -->
        <div class="h-20 flex items-center px-6 border-b border-slate-800 space-x-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-600 to-cyan-500 flex items-center justify-center text-white shadow-md font-black text-base tracking-wider">
                NJ
            </div>
            <div>
                <span class="text-lg font-bold tracking-tight text-white font-['Plus_Jakarta_Sans']">NJ Aluminium</span>
                <span class="block text-[11px] text-brand-400 font-semibold tracking-wider uppercase">Admin Panel</span>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto relative z-50 pointer-events-auto">
            <a href="index.php?action=admin_dashboard"
               class="flex items-center px-3.5 py-3 text-sm font-semibold rounded-xl transition-colors cursor-pointer <?= ($adminAction === 'admin_dashboard') ? 'bg-brand-600 text-white shadow-md shadow-brand-500/20' : 'text-slate-400 hover:bg-slate-900 hover:text-white' ?>">
                <i class="fa-solid fa-chart-pie w-5 text-base mr-3 <?= ($adminAction === 'admin_dashboard') ? 'text-white' : 'text-slate-500' ?>"></i>
                <span>Dashboard</span>
            </a>

            <div class="pt-4 pb-2 px-3 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                Kelola Toko &amp; Gudang
            </div>

            <a href="index.php?action=admin_kategori"
               class="flex items-center px-3.5 py-3 text-sm font-semibold rounded-xl transition-colors cursor-pointer <?= ($adminAction === 'admin_kategori') ? 'bg-brand-600 text-white shadow-md shadow-brand-500/20' : 'text-slate-400 hover:bg-slate-900 hover:text-white' ?>">
                <i class="fa-solid fa-tags w-5 text-base mr-3 <?= ($adminAction === 'admin_kategori') ? 'text-white' : 'text-slate-500' ?>"></i>
                <span>Kategori Mebel</span>
            </a>

            <a href="index.php?action=admin_stok"
               class="flex items-center px-3.5 py-3 text-sm font-semibold rounded-xl transition-colors cursor-pointer <?= ($adminAction === 'admin_stok') ? 'bg-brand-600 text-white shadow-md shadow-brand-500/20' : 'text-slate-400 hover:bg-slate-900 hover:text-white' ?>">
                <i class="fa-solid fa-warehouse w-5 text-base mr-3 <?= ($adminAction === 'admin_stok') ? 'text-white' : 'text-slate-500' ?>"></i>
                <span>Stok Barang</span>
            </a>

            <a href="index.php?action=admin_orders"
               class="flex items-center px-3.5 py-3 text-sm font-semibold rounded-xl transition-colors cursor-pointer <?= ($adminAction === 'admin_orders') ? 'bg-brand-600 text-white shadow-md shadow-brand-500/20' : 'text-slate-400 hover:bg-slate-900 hover:text-white' ?>">
                <i class="fa-solid fa-cart-shopping w-5 text-base mr-3 <?= ($adminAction === 'admin_orders') ? 'text-white' : 'text-slate-500' ?>"></i>
                <span>Pesanan &amp; DP Kargo</span>
            </a>

            <div class="pt-6 border-t border-slate-800/80 mt-6">
                <a href="index.php?action=home" target="_blank"
                   class="flex items-center px-3.5 py-2.5 text-sm font-medium text-slate-400 hover:text-cyan-400 hover:bg-slate-900 rounded-xl transition-colors cursor-pointer">
                    <i class="fa-solid fa-arrow-up-right-from-square w-5 mr-3 text-slate-500"></i>
                    <span>Lihat Web Publik</span>
                </a>
            </div>
        </nav>

        <!-- Admin Profile Foot -->
        <div class="p-4 border-t border-slate-800 bg-slate-950/60 flex items-center justify-between">
            <div class="flex items-center space-x-3 overflow-hidden">
                <div class="w-9 h-9 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-brand-400 font-bold">
                    <?= strtoupper(substr($currentUser['username'] ?? 'A', 0, 1)) ?>
                </div>
                <div class="truncate">
                    <p class="text-xs font-bold text-white truncate"><?= htmlspecialchars($currentUser['nama_lengkap']) ?></p>
                    <p class="text-[10px] text-brand-400 font-medium">Administrator</p>
                </div>
            </div>
            <a href="index.php?action=logout" class="p-2 text-slate-500 hover:text-rose-400 transition-colors" title="Logout">
                <i class="fa-solid fa-power-off"></i>
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 bg-slate-900 overflow-y-auto relative z-10">
        <!-- Top Navigation -->
        <header class="h-20 bg-slate-900/80 backdrop-blur-md border-b border-slate-800 px-8 flex items-center justify-between sticky top-0 z-20 no-print">
            <div>
                <h1 class="text-xl font-bold text-white font-['Plus_Jakarta_Sans']"><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></h1>
                <p class="text-xs text-slate-400">Panel Administrasi NJ Aluminium</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="hidden sm:flex items-center text-xs text-slate-400 bg-slate-800/80 px-3 py-1.5 rounded-lg border border-slate-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-pulse"></span> Sistem Aktif
                </div>
            </div>
        </header>

        <!-- Page Inner Content -->
        <main class="flex-1 p-8">
            <?php if (!empty($_SESSION['flash_success'])): ?>
                <div class="mb-6 bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 px-4 py-3 rounded-xl flex items-center justify-between text-sm">
                    <div class="flex items-center">
                        <i class="fa-solid fa-circle-check text-emerald-400 mr-3 text-lg"></i>
                        <span><?= htmlspecialchars($_SESSION['flash_success']) ?></span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-200"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['flash_error'])): ?>
                <div class="mb-6 bg-rose-500/15 border border-rose-500/30 text-rose-300 px-4 py-3 rounded-xl flex items-center justify-between text-sm">
                    <div class="flex items-center">
                        <i class="fa-solid fa-circle-exclamation text-rose-400 mr-3 text-lg"></i>
                        <span><?= htmlspecialchars($_SESSION['flash_error']) ?></span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-rose-400 hover:text-rose-200"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>

            <?= $adminContent ?? '' ?>
        </main>
    </div>
</body>
</html>