<?php
/**
 * Controller Beranda (Home)
 */

class HomeController {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function index(): void {
        $kategoriModel = new Kategori($this->pdo);
        $produkModel = new Produk($this->pdo);

        $categories = $kategoriModel->getAll();
        $allProducts = $produkModel->getAll();

        require_once dirname(__DIR__) . '/views/public/home.php';
    }
}

