<?php
/**
 * Controller Admin Panel, Kategori, Stok Barang & Pesanan
 */

class AdminController {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->ensureAdmin();
    }

    private function ensureAdmin(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $user = $_SESSION['user'] ?? null;
        if (!$user || ($user['role'] ?? '') !== 'admin') {
            $_SESSION['flash_error'] = 'Akses ditolak. Silakan login sebagai Administrator.';
            header('Location: index.php?action=login');
            exit;
        }
    }

    private function resolveViewPath(string $relativeView): string {
        $candidates = [
            dirname(__DIR__) . '/views/' . ltrim($relativeView, '/'),
            dirname(__DIR__) . '/app/views/' . ltrim($relativeView, '/'),
            dirname(__DIR__, 2) . '/views/' . ltrim($relativeView, '/'),
            dirname(__DIR__, 2) . '/app/views/' . ltrim($relativeView, '/'),
        ];

        foreach ($candidates as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        return $candidates[0];
    }

    public function dashboard(): void {
        $this->ensureStokTables();

        $kategoriModel = new Kategori($this->pdo);
        $orderModel = new Order($this->pdo);

        $categories = $kategoriModel->getAll();
        $orders = $orderModel->getAll();

        $totalCategories = count($categories);
        $totalOrders = count($orders);

        // Hitung jumlah barang di stok
        $totalStokBarang = 0;
        try {
            $stmtStok = $this->pdo->query("SELECT COALESCE(SUM(jumlah_stok), 0) FROM stok_barang");
            $totalStokBarang = (int)$stmtStok->fetchColumn();
        } catch (PDOException $e) {
            $totalStokBarang = 0;
        }

        // Hitung jumlah pesanan pelanggan dari tabel pesanan_kustom
        $totalPesananKustom = 0;
        try {
            $stmtKustom = $this->pdo->query("SELECT COUNT(*) FROM pesanan_kustom");
            $totalPesananKustom = (int)$stmtKustom->fetchColumn();
        } catch (PDOException $e) {
            $totalPesananKustom = 0;
        }
        $totalSemuaPesanan = $totalOrders + $totalPesananKustom;

        $categoryStats = $categories;
        $recentOrders = array_slice($orders, 0, 5);

        require_once $this->resolveViewPath('admin/dashboard.php');
    }

    // --- Kategori ---
    public function kategori(): void {
        $kategoriModel = new Kategori($this->pdo);
        $categories = $kategoriModel->getAll();
        require_once $this->resolveViewPath('admin/kategori/index.php');
    }

    public function kategoriSave(): void {
        $kategoriModel = new Kategori($this->pdo);
        $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
        $nama = trim($_POST['nama_kategori'] ?? '');
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $nama)));
        $foto = trim($_POST['foto'] ?? 'assets/images/cat-kitchen.svg');
        $deskripsi = trim($_POST['deskripsi'] ?? '');

        if ($id) {
            $kategoriModel->update($id, $nama, $slug, $foto, $deskripsi);
            $_SESSION['flash_success'] = "Kategori '$nama' berhasil diperbarui.";
        } else {
            $kategoriModel->create($nama, $slug, $foto, $deskripsi);
            $_SESSION['flash_success'] = "Kategori baru '$nama' berhasil ditambahkan.";
        }
        header('Location: index.php?action=admin_kategori');
        exit;
    }

    public function kategoriDelete(int $id): void {
        $kategoriModel = new Kategori($this->pdo);
        $kategoriModel->delete($id);
        $_SESSION['flash_success'] = 'Kategori berhasil dihapus.';
        header('Location: index.php?action=admin_kategori');
        exit;
    }

    // --- Stok Barang & Catatan Stok ---
    private function ensureStokTables(): void {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS stok_barang (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nama_barang TEXT NOT NULL,
                satuan TEXT NOT NULL DEFAULT 'unit',
                jumlah_stok INTEGER NOT NULL DEFAULT 0,
                updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
            );
        ");

        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS riwayat_stok (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                stok_barang_id INTEGER NOT NULL,
                tipe_aksi TEXT NOT NULL,
                perubahan INTEGER NOT NULL,
                stok_sesudah INTEGER NOT NULL,
                catatan TEXT,
                created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (stok_barang_id) REFERENCES stok_barang(id) ON DELETE CASCADE
            );
        ");
    }

    public function stok(): void {
        $this->ensureStokTables();

        $barangList = $this->pdo->query("SELECT * FROM stok_barang ORDER BY nama_barang ASC")->fetchAll();
        $riwayatStok = $this->pdo->query("
            SELECT r.*, b.nama_barang, b.satuan
            FROM riwayat_stok r
            JOIN stok_barang b ON b.id = r.stok_barang_id
            ORDER BY r.id DESC
            LIMIT 50
        ")->fetchAll();

        require_once $this->resolveViewPath('admin/stok.php');
    }

    public function stokSave(): void {
        $this->ensureStokTables();

        $mode = $_POST['mode'] ?? 'sesuaikan'; // 'sesuaikan' atau 'tambah_barang'
        $catatan = trim($_POST['catatan'] ?? '');
        $now = date('Y-m-d H:i:s');

        try {
            $this->pdo->beginTransaction();

            if ($mode === 'tambah_barang') {
                $namaBarang = trim($_POST['nama_barang_baru'] ?? '');
                $satuan = trim($_POST['satuan_baru'] ?? 'unit');
                $stokAwal = max(0, (int)($_POST['stok_awal'] ?? 0));

                if ($namaBarang === '') {
                    throw new RuntimeException('Nama barang baru wajib diisi.');
                }

                $stmtIns = $this->pdo->prepare("
                    INSERT INTO stok_barang (nama_barang, satuan, jumlah_stok, updated_at)
                    VALUES (?, ?, ?, ?)
                ");
                $stmtIns->execute([$namaBarang, $satuan ?: 'unit', $stokAwal, $now]);
                $barangId = (int)$this->pdo->lastInsertId();

                $stmtLog = $this->pdo->prepare("
                    INSERT INTO riwayat_stok (stok_barang_id, tipe_aksi, perubahan, stok_sesudah, catatan, created_at)
                    VALUES (?, 'awal', ?, ?, ?, ?)
                ");
                $stmtLog->execute([
                    $barangId,
                    $stokAwal,
                    $stokAwal,
                    $catatan !== '' ? $catatan : 'Stok awal barang',
                    $now
                ]);

                $_SESSION['flash_success'] = "Barang '$namaBarang' berhasil ditambahkan dengan stok {$stokAwal}.";
            } else {
                $barangId = (int)($_POST['stok_barang_id'] ?? 0);
                $tipeAksi = $_POST['tipe_aksi'] ?? 'tambah'; // 'tambah', 'kurang', 'set_sisa'
                $jumlah = max(0, (int)($_POST['jumlah'] ?? 0));

                $stmtGet = $this->pdo->prepare("SELECT * FROM stok_barang WHERE id = ?");
                $stmtGet->execute([$barangId]);
                $barang = $stmtGet->fetch();

                if (!$barang) {
                    throw new RuntimeException('Barang tidak ditemukan.');
                }

                $stokLama = (int)$barang['jumlah_stok'];

                if ($tipeAksi === 'kurang') {
                    if ($jumlah > $stokLama) {
                        throw new RuntimeException("Jumlah pengurangan ({$jumlah}) melebihi sisa stok ({$stokLama}).");
                    }
                    $stokSesudah = $stokLama - $jumlah;
                    $perubahan = -$jumlah;
                } elseif ($tipeAksi === 'set_sisa') {
                    $stokSesudah = $jumlah;
                    $perubahan = $stokSesudah - $stokLama;
                } else {
                    $stokSesudah = $stokLama + $jumlah;
                    $perubahan = $jumlah;
                }

                $stmtUpd = $this->pdo->prepare("UPDATE stok_barang SET jumlah_stok = ?, updated_at = ? WHERE id = ?");
                $stmtUpd->execute([$stokSesudah, $now, $barangId]);

                $stmtLog = $this->pdo->prepare("
                    INSERT INTO riwayat_stok (stok_barang_id, tipe_aksi, perubahan, stok_sesudah, catatan, created_at)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmtLog->execute([
                    $barangId,
                    $tipeAksi,
                    $perubahan,
                    $stokSesudah,
                    $catatan !== '' ? $catatan : 'Penyesuaian stok',
                    $now
                ]);

                $_SESSION['flash_success'] = "Stok '{$barang['nama_barang']}' diperbarui menjadi {$stokSesudah} {$barang['satuan']}.";
            }

            $this->pdo->commit();
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            $_SESSION['flash_error'] = $e->getMessage();
        }

        header('Location: index.php?action=admin_stok');
        exit;
    }

    // --- Pesanan Kargo & DP ---
    public function orders(): void {
        $orderModel = new Order($this->pdo);
        $orders = $orderModel->getAll();
        require_once $this->resolveViewPath('admin/orders.php');
    }

    public function ordersUpdate(): void {
        $orderModel = new Order($this->pdo);
        $id = (int)$_POST['id'];
        $payStatus = $_POST['payment_status'];
        $orderStatus = $_POST['order_status'];
        $trackingNumber = trim($_POST['tracking_number'] ?? '');

        $orderModel->updateStatus($id, $payStatus, $orderStatus, $trackingNumber ?: null);
        $_SESSION['flash_success'] = 'Status pesanan berhasil diperbarui.';
        header('Location: index.php?action=admin_orders');
        exit;
    }
}