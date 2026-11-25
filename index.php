<?php
/**
 * Front Controller & Router
 * Sistem Pendukung Keputusan Pemilihan Mebel Aluminium (Metode SAW)
 * Dosen Pembimbing: Pak Rofi
 */

// Error reporting for smooth development
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Ensure local session storage exists and is writable (avoids permission denied in C:/laragon/tmp)
$sessionDir = __DIR__ . '/database/sessions';
if (!is_dir($sessionDir)) {
    @mkdir($sessionDir, 0777, true);
}
ini_set('session.save_path', $sessionDir);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autoload core configs & models & helpers
require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/models/Kategori.php';
require_once __DIR__ . '/app/models/Kriteria.php';
require_once __DIR__ . '/app/models/Produk.php';
require_once __DIR__ . '/app/models/NilaiAlternatif.php';
require_once __DIR__ . '/app/models/Order.php';
require_once __DIR__ . '/app/models/User.php';
require_once __DIR__ . '/app/models/PesananKustom.php';
require_once __DIR__ . '/app/helpers/SawEngine.php';
require_once __DIR__ . '/app/helpers/VolumetricCargo.php';

// Controllers
require_once __DIR__ . '/app/controllers/HomeController.php';
require_once __DIR__ . '/app/controllers/CatalogController.php';
require_once __DIR__ . '/app/controllers/SawController.php';
require_once __DIR__ . '/app/controllers/ProductController.php';
require_once __DIR__ . '/app/controllers/OrderController.php';
require_once __DIR__ . '/app/controllers/AdminController.php';
require_once __DIR__ . '/app/controllers/AuthController.php';

// Initialize Database PDO
try {
    $pdo = Database::getConnection();
} catch (PDOException $e) {
    die("Koneksi Database Gagal: " . htmlspecialchars($e->getMessage()));
}

// Router Action: Default langsung mengarah ke 'home' (Beranda)
$action = $_GET['action'] ?? 'home';

// Proteksi Autentikasi: Hanya area admin yang mewajibkan login role administrator
if (strpos($action, 'admin_') === 0) {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        $_SESSION['flash_error'] = 'Akses khusus Administrator. Silakan login terlebih dahulu.';
        header('Location: index.php?action=login&tab=admin');
        exit;
    }
}

switch ($action) {
    case 'home':
        (new HomeController($pdo))->index();
        break;

    case 'katalog':
        (new CatalogController($pdo))->index();
        break;

    case 'pesanan':
        (new OrderController($pdo))->pesananForm();
        break;

    case 'pesanan_simpan':
        (new OrderController($pdo))->pesananSimpan();
        break;

    case 'akun':
        (new AuthController($pdo))->akun();
        break;

    case 'rekomendasi':
        (new SawController($pdo))->recommendation();
        break;

    case 'produk_detail':
        $id = (int)($_GET['id'] ?? 1);
        (new ProductController($pdo))->detail($id);
        break;

    case 'kalkulator_kargo':
        (new ProductController($pdo))->cargoCalculator();
        break;

    case 'portofolio':
        (new ProductController($pdo))->portfolio();
        break;

    case 'tracking':
        (new OrderController($pdo))->tracking();
        break;

    // API Routes
    case 'api_create_order':
        (new OrderController($pdo))->apiCreateOrder();
        break;

    case 'api_midtrans_webhook':
        (new OrderController($pdo))->apiMidtransWebhook();
        break;

    // Auth Routes
    case 'login':
        (new AuthController($pdo))->login();
        break;

    case 'login_post':
        (new AuthController($pdo))->loginPost();
        break;

    case 'register_post':
        (new AuthController($pdo))->registerPost();
        break;

    case 'logout':
        (new AuthController($pdo))->logout();
        break;

    // Admin Routes
    case 'admin_dashboard':
        (new AdminController($pdo))->dashboard();
        break;

    case 'admin_kategori':
        (new AdminController($pdo))->kategori();
        break;

    case 'admin_kategori_save':
        (new AdminController($pdo))->kategoriSave();
        break;

    case 'admin_kategori_delete':
        $id = (int)($_GET['id'] ?? 0);
        (new AdminController($pdo))->kategoriDelete($id);
        break;

    case 'admin_kriteria':
        (new AdminController($pdo))->kriteria();
        break;

    case 'admin_kriteria_save':
        (new AdminController($pdo))->kriteriaSave();
        break;

    case 'admin_produk':
        (new AdminController($pdo))->produk();
        break;

    case 'admin_produk_tambah':
        (new AdminController($pdo))->produkTambah();
        break;

    case 'admin_produk_edit':
        $id = (int)($_GET['id'] ?? 0);
        (new AdminController($pdo))->produkEdit($id);
        break;

    case 'admin_produk_save':
        (new AdminController($pdo))->produkSave();
        break;

    case 'admin_produk_delete':
        $id = (int)($_GET['id'] ?? 0);
        (new AdminController($pdo))->produkDelete($id);
        break;

    case 'admin_saw_matrix':
        (new AdminController($pdo))->sawMatrix();
        break;

    case 'admin_orders':
        (new AdminController($pdo))->orders();
        break;

    case 'admin_orders_update':
        (new AdminController($pdo))->ordersUpdate();
        break;

    default:
        (new HomeController($pdo))->index();
        break;
}

