<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentUser = $_SESSION['user'] ?? null;
$isAdmin = $currentUser && ($currentUser['role'] === 'admin');
$action = $_GET['action'] ?? 'home';
?>
<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'NJ Aluminium - Mebel Aluminium Berkualitas') ?></title>
    <link rel="icon" type="image/svg+xml" href="assets/images/icon-192.svg">
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#0284c7">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f9ff', 100: '#e0f2fe', 200: '#bae6fd',
                            500: '#0ea5e9', 600: '#0284c7', 700: '#0369a1',
                            800: '#075985', 900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body class="flex flex-col min-h-screen bg-slate-50 text-slate-800 antialiased">

    <!-- Main Navigation Bar -->
    <nav class="sticky top-0 z-50 bg-white shadow-sm border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Brand Logo -->
                <a href="index.php?action=home" class="flex items-center space-x-2.5 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-700 via-blue-600 to-cyan-500 flex items-center justify-center text-white font-black text-sm tracking-wider shadow group-hover:scale-105 transition-transform">
                        NJ
                    </div>
                    <span class="text-lg font-extrabold tracking-tight text-slate-900 font-['Plus_Jakarta_Sans']">NJ Aluminium</span>
                </a>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="index.php?action=home" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors <?= ($action === 'home') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:text-blue-600 hover:bg-slate-100' ?>">
                        <i class="fa-solid fa-house mr-1.5 opacity-70"></i>Beranda
                    </a>
                    <a href="index.php?action=katalog" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors <?= ($action === 'katalog') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:text-blue-600 hover:bg-slate-100' ?>">
                        <i class="fa-solid fa-boxes-stacked mr-1.5 opacity-70"></i>Katalog Mebel
                    </a>
                    <a href="index.php?action=pesanan" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors <?= in_array($action, ['pesanan','pesanan_simpan']) ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:text-blue-600 hover:bg-slate-100' ?>">
                        <i class="fa-solid fa-clipboard-list mr-1.5 opacity-70"></i>Pesanan
                    </a>
                    <a href="index.php?action=akun" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors <?= ($action === 'akun') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:text-blue-600 hover:bg-slate-100' ?>">
                        <i class="fa-solid fa-circle-user mr-1.5 opacity-70"></i>Akun
                    </a>
                    <?php if ($isAdmin): ?>
                        <a href="index.php?action=admin_dashboard" class="ml-3 px-4 py-2 rounded-lg text-sm font-bold bg-slate-900 text-white hover:bg-slate-700 transition-colors">
                            <i class="fa-solid fa-chart-pie mr-1.5 text-blue-400"></i>Admin
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobileMenuBtn" class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Dropdown -->
        <div id="mobileMenu" class="hidden md:hidden border-t border-slate-200 bg-white px-4 pt-2 pb-4 space-y-1 shadow-lg">
            <a href="index.php?action=home" class="block px-3 py-2.5 rounded-lg text-sm font-semibold <?= ($action === 'home') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-100' ?>">
                <i class="fa-solid fa-house mr-2"></i>Beranda
            </a>
            <a href="index.php?action=katalog" class="block px-3 py-2.5 rounded-lg text-sm font-semibold <?= ($action === 'katalog') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-100' ?>">
                <i class="fa-solid fa-boxes-stacked mr-2"></i>Katalog Mebel
            </a>
            <a href="index.php?action=pesanan" class="block px-3 py-2.5 rounded-lg text-sm font-semibold <?= ($action === 'pesanan') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-100' ?>">
                <i class="fa-solid fa-clipboard-list mr-2"></i>Pesanan
            </a>
            <a href="index.php?action=akun" class="block px-3 py-2.5 rounded-lg text-sm font-semibold <?= ($action === 'akun') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-100' ?>">
                <i class="fa-solid fa-circle-user mr-2"></i>Akun
            </a>
            <?php if ($isAdmin): ?>
                <a href="index.php?action=admin_dashboard" class="block px-3 py-2.5 rounded-lg text-sm font-bold bg-slate-900 text-white text-center mt-1">
                    <i class="fa-solid fa-chart-pie mr-2 text-blue-400"></i>Dashboard Admin
                </a>
            <?php endif; ?>
        </div>
    </nav>
    <main class="flex-grow">
