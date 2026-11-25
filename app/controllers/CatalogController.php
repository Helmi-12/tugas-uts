<?php
/**
 * Controller Katalog Produk & Mebel
 */

class CatalogController {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function index(): void {
        $kategoriModel = new Kategori($this->pdo);
        $produkModel = new Produk($this->pdo);

        $kategoriId = isset($_GET['kategori_id']) ? (int)$_GET['kategori_id'] : 0;
        $categories = $kategoriModel->getAll();
        $products = $produkModel->getAll($kategoriId > 0 ? $kategoriId : null);

        require_once dirname(__DIR__) . '/views/public/catalog.php';
    }
}

