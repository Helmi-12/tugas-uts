<?php
/**
 * Model Order & Transaksi Pembayaran DP (Down Payment)
 * Sesuai Dokumen Analisis Rancang Bangun & Blueprint
 */

class Order {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function createOrder(array $data): array {
        $orderCode = 'ALUM-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
        
        $dpAmount = ($data['payment_scheme'] === 'Down Payment (DP 50%)') 
            ? round(($data['total_amount'] * 0.5), 0)
            : $data['total_amount'];
            
        $remainingAmount = $data['total_amount'] - $dpAmount;
        $snapToken = 'SNAP-' . bin2hex(random_bytes(8));

        $stmt = $this->pdo->prepare("
            INSERT INTO orders (
                order_code, customer_id, customer_name, customer_phone, customer_address, city_destination,
                order_type, payment_scheme, courier_name, courier_service, actual_weight_kg,
                volumetric_weight_kg, charged_weight_kg, shipping_cost, product_subtotal,
                total_amount, dp_amount, remaining_amount, payment_status, order_status,
                tracking_number, snap_token, custom_notes
            ) VALUES (
                :order_code, :customer_id, :customer_name, :customer_phone, :customer_address, :city_destination,
                :order_type, :payment_scheme, :courier_name, :courier_service, :actual_weight_kg,
                :volumetric_weight_kg, :charged_weight_kg, :shipping_cost, :product_subtotal,
                :total_amount, :dp_amount, :remaining_amount, :payment_status, :order_status,
                :tracking_number, :snap_token, :custom_notes
            )
        ");

        $stmt->execute([
            ':order_code' => $orderCode,
            ':customer_id' => $data['customer_id'] ?? null,
            ':customer_name' => $data['customer_name'],
            ':customer_phone' => $data['customer_phone'],
            ':customer_address' => $data['customer_address'],
            ':city_destination' => $data['city_destination'],
            ':order_type' => $data['order_type'] ?? 'Pre-Order (PO)',
            ':payment_scheme' => $data['payment_scheme'],
            ':courier_name' => $data['courier_name'],
            ':courier_service' => $data['courier_service'],
            ':actual_weight_kg' => $data['actual_weight_kg'],
            ':volumetric_weight_kg' => $data['volumetric_weight_kg'],
            ':charged_weight_kg' => $data['charged_weight_kg'],
            ':shipping_cost' => $data['shipping_cost'],
            ':product_subtotal' => $data['product_subtotal'],
            ':total_amount' => $data['total_amount'],
            ':dp_amount' => $dpAmount,
            ':remaining_amount' => $remainingAmount,
            ':payment_status' => 'Menunggu Pembayaran DP',
            ':order_status' => 'Pending',
            ':tracking_number' => null,
            ':snap_token' => $snapToken,
            ':custom_notes' => $data['custom_notes'] ?? '',
        ]);

        $orderId = (int)$this->pdo->lastInsertId();

        // Simpan item pesanan
        if (!empty($data['items'])) {
            $stmtItem = $this->pdo->prepare("
                INSERT INTO order_items (order_id, produk_id, nama_produk, harga_satuan, jumlah, subtotal, custom_notes)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            foreach ($data['items'] as $item) {
                $subtotal = $item['harga_satuan'] * $item['jumlah'];
                $stmtItem->execute([
                    $orderId,
                    $item['produk_id'] ?? null,
                    $item['nama_produk'],
                    $item['harga_satuan'],
                    $item['jumlah'],
                    $subtotal,
                    $item['custom_notes'] ?? ''
                ]);
            }
        }

        return [
            'order_id' => $orderId,
            'order_code' => $orderCode,
            'snap_token' => $snapToken,
            'dp_amount' => $dpAmount,
            'remaining_amount' => $remainingAmount,
            'total_amount' => $data['total_amount'],
        ];
    }

    public function getByCode(string $code): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE order_code = ?");
        $stmt->execute([$code]);
        $order = $stmt->fetch();
        if (!$order) return null;

        $stmtItems = $this->pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmtItems->execute([$order['id']]);
        $order['items'] = $stmtItems->fetchAll();

        $stmtPay = $this->pdo->prepare("SELECT * FROM payments WHERE order_id = ? ORDER BY id DESC");
        $stmtPay->execute([$order['id']]);
        $order['payments'] = $stmtPay->fetchAll();

        return $order;
    }

    public function getAll(?string $status = null): array {
        $sql = "SELECT * FROM orders";
        $params = [];
        if ($status) {
            $sql .= " WHERE order_status = ?";
            $params[] = $status;
        }
        $sql .= " ORDER BY id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function updateStatus(int $id, string $paymentStatus, string $orderStatus, ?string $trackingNumber = null): bool {
        $sql = "UPDATE orders SET payment_status = ?, order_status = ?";
        $params = [$paymentStatus, $orderStatus];
        if ($trackingNumber !== null) {
            $sql .= ", tracking_number = ?";
            $params[] = $trackingNumber;
        }
        $sql .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function recordPayment(int $orderId, string $type, float $amount, string $method, string $status): int {
        $txCode = 'PAY-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        $stmt = $this->pdo->prepare("
            INSERT INTO payments (order_id, transaction_code, transaction_type, gross_amount, payment_method, payment_gateway_status)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$orderId, $txCode, $type, $amount, $method, $status]);
        return (int)$this->pdo->lastInsertId();
    }
}

