<?php
/**
 * Controller Autentikasi Pengguna & Sesi
 */

class AuthController {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function login(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!empty($_SESSION['user'])) {
            if ($_SESSION['user']['role'] === 'admin') {
                header('Location: index.php?action=admin_dashboard');
            } else {
                header('Location: index.php?action=home');
            }
            exit;
        }
        $activeTab = $_GET['tab'] ?? 'pelanggan';
        require_once dirname(__DIR__) . '/views/auth/login.php';
    }

    public function loginPost(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $roleLogin = $_POST['role_login'] ?? 'pelanggan';

        $userModel = new User($this->pdo);
        $user = $userModel->findByUsername($username);

        if ($user && $userModel->verifyPassword($password, $user['password'])) {
            // Validasi tab jika login admin
            if ($roleLogin === 'admin' && $user['role'] !== 'admin') {
                $_SESSION['flash_error'] = 'Akun Anda (' . htmlspecialchars($user['username']) . ') terdaftar sebagai Pelanggan, bukan Administrator. Silakan login pada tab Pembeli / Pelanggan.';
                header('Location: index.php?action=login&tab=admin');
                exit;
            }

            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'nama_lengkap' => $user['nama_lengkap'],
                'role' => $user['role'],
                'email' => $user['email'],
                'phone' => $user['phone'] ?? '',
            ];
            $_SESSION['flash_success'] = "Selamat datang, {$user['nama_lengkap']}!";

            if ($user['role'] === 'admin') {
                header('Location: index.php?action=admin_dashboard');
            } else {
                header('Location: index.php?action=home');
            }
            exit;
        }

        $_SESSION['flash_error'] = 'Username atau password yang Anda masukkan salah.';
        $redirectTab = ($roleLogin === 'admin') ? 'admin' : 'pelanggan';
        header("Location: index.php?action=login&tab={$redirectTab}");
        exit;
    }

    public function registerPost(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $nama = trim($_POST['nama_lengkap'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        if (empty($nama) || empty($username) || empty($password)) {
            $_SESSION['flash_error'] = 'Nama lengkap, username, dan password wajib diisi.';
            header('Location: index.php?action=login&tab=register');
            exit;
        }

        $userModel = new User($this->pdo);
        if ($userModel->findByUsername($username)) {
            $_SESSION['flash_error'] = "Username '{$username}' sudah terdaftar. Silakan gunakan username lain.";
            header('Location: index.php?action=login&tab=register');
            exit;
        }

        $userId = $userModel->register($username, $password, $nama, $email, $phone, 'pelanggan');

        // Otomatis login akun pelanggan yang baru dibuat
        $_SESSION['user'] = [
            'id' => $userId,
            'username' => $username,
            'nama_lengkap' => $nama,
            'role' => 'pelanggan',
            'email' => $email,
            'phone' => $phone,
        ];
        $_SESSION['flash_success'] = "Pendaftaran berhasil! Selamat datang di NJ Aluminium, {$nama}.";
        header('Location: index.php?action=home');
        exit;
    }

    public function akun(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $currentUser = $_SESSION['user'] ?? null;
        $isAdmin = $currentUser && ($currentUser['role'] === 'admin');
        $pesananList = [];

        if ($currentUser) {
            require_once dirname(__DIR__) . '/models/PesananKustom.php';
            $model = new PesananKustom($this->pdo);
            $pesananList = $model->getByUserIdOrPhone(
                (int)($currentUser['id'] ?? 0),
                $currentUser['phone'] ?? ''
            );
        }

        require_once dirname(__DIR__) . '/views/public/akun.php';
    }

    public function logout(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['user']);
        $_SESSION['flash_success'] = 'Anda telah berhasil keluar.';
        header('Location: index.php?action=home');
        exit;
    }
}

