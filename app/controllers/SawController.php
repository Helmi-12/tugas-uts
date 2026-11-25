<?php
/**
 * Controller SPK Simple Additive Weighting (SAW)
 */

class SawController {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function recommendation(): void {
        $kategoriModel = new Kategori($this->pdo);
        $produkModel = new Produk($this->pdo);
        $sawEngine = new SawEngine($this->pdo);

        $kategoriId = isset($_GET['kategori_id']) ? (int)$_GET['kategori_id'] : 1;
        $minBudget = isset($_GET['min_budget']) ? (float)$_GET['min_budget'] : 2000000;
        $maxBudget = isset($_GET['max_budget']) ? (float)$_GET['max_budget'] : 9000000;

        $categories = $kategoriModel->getAll();

        // Jalankan perhitungan SAW
        $sawData = $sawEngine->calculate(
            $kategoriId > 0 ? $kategoriId : null,
            $minBudget > 0 ? $minBudget : null,
            $maxBudget > 0 ? $maxBudget : null
        );

        // Simpan log pencarian budget
        $produkModel->logSearch($kategoriId > 0 ? $kategoriId : null, $minBudget, $maxBudget);

        require_once dirname(__DIR__) . '/views/public/recommendation.php';
    }
}

