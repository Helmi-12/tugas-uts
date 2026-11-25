<?php
/**
 * Controller Transaksi Order, Pembayaran DP & Webhook Midtrans
 * Dokumen 1: Integrasi Webhook Gerbang Pembayaran
 */

class OrderController {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function pesananForm(): void {
        require_once dirname(__DIR__) . '/views/public/pesanan.php';
    }

   public function pesananSimpan(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=pesanan');
            exit;
        }

        $nama = trim($_POST['nama_pelanggan'] ?? '');
        $noTelp = trim($_POST['no_telepon'] ?? '');
        $jenisProduk = trim($_POST['jenis_produk'] ?? '');
        $tinggi = (int)($_POST['tinggi_cm'] ?? 0);
        $lebar = (int)($_POST['lebar_cm'] ?? 0);
        $bahan = trim($_POST['bahan_pilihan'] ?? '');
        $alamat = trim($_POST['alamat'] ?? '');
        $catatan = trim($_POST['catatan_tambahan'] ?? '');

        if (empty($nama) || empty($noTelp) || empty($jenisProduk) || $tinggi <= 0 || $lebar <= 0 || empty($bahan)) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['flash_error'] = 'Mohon lengkapi semua kolom yang wajib diisi (*)';
            header('Location: index.php?action=pesanan');
            exit;
        }

        $model = new PesananKustom($this->pdo);

        $data = [
            'nama_pelanggan'   => $nama,
            'no_telepon'       => $noTelp,
            'alamat'           => $alamat,
            'jenis_produk'     => $jenisProduk,
            'tinggi_cm'        => $tinggi,
            'lebar_cm'         => $lebar,
            'bahan_pilihan'    => $bahan,
            'catatan_tambahan' => $catatan,
        ];

        $kode = $model->create($data);
        $data['kode_pesanan'] = $kode;
        $data['status_pesanan'] = 'Menunggu Konfirmasi';

        $pesanan = $data;
        require_once dirname(__DIR__) . '/views/public/pesanan_sukses.php';
        exit;
    }


    public function tracking(): void {
        $orderModel = new Order($this->pdo);
        $orderCode = trim($_GET['order_code'] ?? '');
        $order = !empty($orderCode) ? $orderModel->getByCode($orderCode) : null;

        require_once dirname(__DIR__) . '/views/public/order_tracking.php';
    }

    public function apiCreateOrder(): void {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        $orderModel = new Order($this->pdo);

        $productPrice = (float)($_POST['product_price'] ?? 0);
        $shippingCost = (float)($_POST['shipping_cost'] ?? 0);
        $totalAmount = $productPrice + $shippingCost;
        $paymentScheme = $_POST['payment_scheme'] ?? 'Down Payment (DP 50%)';

        $orderData = [
            'customer_id' => $_SESSION['user']['id'] ?? null,
            'customer_name' => trim($_POST['customer_name'] ?? 'Pelanggan Mebel'),
            'customer_phone' => trim($_POST['customer_phone'] ?? '08123456789'),
            'customer_address' => trim($_POST['customer_address'] ?? 'Alamat Pemesan'),
            'city_destination' => trim($_POST['city_destination'] ?? 'Surabaya'),
            'order_type' => $_POST['order_type'] ?? 'Pre-Order (PO)',
            'payment_scheme' => $paymentScheme,
            'courier_name' => $_POST['courier_name'] ?? 'JNE Trucking (JTR)',
            'courier_service' => $_POST['courier_service'] ?? 'Kargo Darat Standar',
            'actual_weight_kg' => (float)($_POST['actual_weight_kg'] ?? 40),
            'volumetric_weight_kg' => (float)($_POST['volumetric_weight_kg'] ?? 45),
            'charged_weight_kg' => (float)($_POST['charged_weight_kg'] ?? 45),
            'shipping_cost' => $shippingCost,
            'product_subtotal' => $productPrice,
            'total_amount' => $totalAmount,
            'custom_notes' => $_POST['custom_notes'] ?? '',
            'items' => [
                [
                    'produk_id' => (int)($_POST['product_id'] ?? 0),
                    'nama_produk' => $_POST['product_name'] ?? 'Mebel Aluminium',
                    'harga_satuan' => $productPrice,
                    'jumlah' => 1,
                    'custom_notes' => 'Finishing standar',
                ]
            ]
        ];

        try {
            $result = $orderModel->createOrder($orderData);
            echo json_encode([
                'success' => true,
                'order_id' => $result['order_id'],
                'order_code' => $result['order_code'],
                'snap_token' => $result['snap_token'],
                'dp_amount' => $result['dp_amount'],
                'remaining_amount' => $result['remaining_amount'],
                'total_amount' => $result['total_amount'],
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function apiMidtransWebhook(): void {
        header('Content-Type: application/json');

        $rawBody = file_get_contents('php://input');
        $payload = json_decode($rawBody, true) ?? [];

        $orderCode = $payload['order_id'] ?? ($_POST['order_id'] ?? null);
        $transactionStatus = $payload['transaction_status'] ?? 'settlement';
        $paymentType = $payload['payment_type'] ?? 'QRIS Instant';

        if (!$orderCode) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing order_id']);
            exit;
        }

        $orderModel = new Order($this->pdo);
        $order = $orderModel->getByCode($orderCode);

        if (!$order) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Order not found']);
            exit;
        }

        // Webhook verification logic (Dokumen 1 Section 4)
        if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
            if ($order['payment_status'] === 'Menunggu Pembayaran DP' || $order['payment_status'] === 'Pending') {
                $newPayStatus = ($order['payment_scheme'] === 'Full Payment') ? 'Lunas' : 'DP Terbayar';
                $newOrderStatus = 'Dalam Proses Produksi'; // Otomatis masuk ke jalur produksi furnitur
                $amountPaid = $order['dp_amount'];
                $txType = 'DP';
            } else {
                // Pelunasan akhir sisa tagihan
                $newPayStatus = 'Lunas';
                $newOrderStatus = 'Siap Dikirim';
                $amountPaid = $order['remaining_amount'];
                $txType = 'Settlement';
            }

            // Update database status
            $orderModel->updateStatus($order['id'], $newPayStatus, $newOrderStatus);
            $orderModel->recordPayment($order['id'], $txType, $amountPaid, $paymentType, 'settlement');

            echo json_encode([
                'success' => true,
                'message' => 'Webhook Midtrans berhasil diproses.',
                'order_code' => $orderCode,
                'payment_status' => $newPayStatus,
                'order_status' => $newOrderStatus,
            ]);
        } else {
            echo json_encode(['success' => true, 'message' => 'Status notification recorded: ' . $transactionStatus]);
        }
        exit;
    }
}

