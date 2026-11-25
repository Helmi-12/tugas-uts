<?php
/**
 * Controller Detail Produk & 3D WebAR / Kargo Volumetrik
 */

class ProductController {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function detail(int $id): void {
        $produkModel = new Produk($this->pdo);
        $product = $produkModel->getById($id);

        if (!$product) {
            header('Location: index.php?action=katalog');
            exit;
        }

        $cityName = $_GET['city'] ?? 'Surabaya / Sidoarjo / Gresik (Lokal Jatim)';
        $cargoData = VolumetricCargo::calculateShippingOptions(
            (float)$product['panjang_cm'],
            (float)$product['lebar_cm'],
            (float)$product['tinggi_cm'],
            (float)$product['berat_aktual_kg'],
            $cityName
        );

        $cargoData['cities'] = VolumetricCargo::getDestinationCities();

        require_once dirname(__DIR__) . '/views/public/product_detail.php';
    }

    public function cargoCalculator(): void {
        require_once dirname(__DIR__) . '/views/public/cargo_calculator.php';
    }

    public function portfolio(): void {
        require_once dirname(__DIR__) . '/views/public/portfolio.php';
    }
}

