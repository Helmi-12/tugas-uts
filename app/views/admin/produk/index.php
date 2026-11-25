<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autoload classes dari app/config, app/controllers, app/models, dan app/helpers
spl_autoload_register(function (string $className): void {
    $baseDirs = [
        __DIR__ . '/app/config/',
        __DIR__ . '/app/controllers/',
        __DIR__ . '/app/models/',
        __DIR__ . '/app/helpers/',
    ];

    foreach ($baseDirs as $dir) {
        $file = $dir . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Koneksi database utama
$pdo = Database::getConnection();

$action = $_GET['action'] ?? 'home';

// Jika action diawali 'admin_', jalankan melalui AdminController
if (str_starts_with($action, 'admin_')) {
    $adminController = new AdminController($pdo);

    switch ($action) {
        case 'admin_dashboard':
            $adminController->dashboard();
            break;

        case 'admin_kategori':
            $adminController->kategori();
            break;

        case 'admin_kategori_save':
            $adminController->kategoriSave();
            break;

        case 'admin_kategori_delete':
            $adminController->kategoriDelete((int)($_GET['id'] ?? 0));
            break;

        case 'admin_stok':
            $adminController->stok();
            break;

        case 'admin_stok_save':
            $adminController->stokSave();
            break;

        case 'admin_orders':
            $adminController->orders();
            break;

        case 'admin_orders_update':
            $adminController->ordersUpdate();
            break;

        default:
            $adminController->dashboard();
            break;
    }
    exit;
}

// --- Route Publik / Auth / Pelanggan ---
switch ($action) {
    case 'login':
        $authController = new AuthController($pdo);
        $authController->loginForm();
        break;

    case 'login_post':
        $authController = new AuthController($pdo);
        $authController->loginPost();
        break;

    case 'register_post':
        $authController = new AuthController($pdo);
        $authController->registerPost();
        break;

    case 'logout':
        $authController = new AuthController($pdo);
        $authController->logout();
        break;

    case 'home':
    default:
        if (class_exists('HomeController')) {
            $homeController = new HomeController($pdo);
            $homeController->index();
        } else {
            require_once __DIR__ . '/app/views/public/home.php';
        }
        break;
}