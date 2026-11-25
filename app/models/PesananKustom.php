<?php
/**
 * Model Pesanan Kustom Aluminium
 * Menangani pesanan kustom (Jenis Produk, Ukuran P x L, Bahan Pilihan)
 */

class PesananKustom {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->ensureTableExists();
    }

    public function ensureTableExists(): void {
        $sql = "
            CREATE TABLE IF NOT EXISTS pesanan_kustom (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                kode_pesanan VARCHAR(50) NOT NULL UNIQUE,
                user_id INTEGER DEFAULT NULL,
                nama_pelanggan VARCHAR(150) NOT NULL,
                no_telepon VARCHAR(50) NOT NULL,
                alamat TEXT,
                jenis_produk VARCHAR(100) NOT NULL,
                lebar_cm DOUBLE NOT NULL,
tinggi_cm DOUBLE NOT NULL,
                bahan_pilihan VARCHAR(200) NOT NULL,
                catatan_tambahan TEXT,
                estimasi_harga DOUBLE DEFAULT NULL,
                status_pesanan VARCHAR(50) DEFAULT 'Menunggu Konfirmasi',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        try {
            $this->pdo->exec($sql);
        } catch (PDOException $e) {
            // Ignore if exists
        }
    }

    public function create(array $data): string {
        $kodePesanan = 'PK-' . date('YmdHis') . rand(10, 99);

        $sql = "INSERT INTO pesanan_kustom (
                    kode_pesanan,
                    nama_pelanggan,
                    no_telepon,
                    alamat,
                    jenis_produk,
                    tinggi_cm,
                    lebar_cm,
                    bahan_pilihan,
                    catatan_tambahan,
                    status_pesanan
                ) VALUES (
                    :kode_pesanan,
                    :nama_pelanggan,
                    :no_telepon,
                    :alamat,
                    :jenis_produk,
                    :tinggi_cm,
                    :lebar_cm,
                    :bahan_pilihan,
                    :catatan_tambahan,
                    :status_pesanan
                )";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':kode_pesanan'     => $kodePesanan,
            ':nama_pelanggan'   => $data['nama_pelanggan'],
            ':no_telepon'       => $data['no_telepon'],
            ':alamat'           => $data['alamat'],
            ':jenis_produk'     => $data['jenis_produk'],
            ':tinggi_cm'        => $data['tinggi_cm'],
            ':lebar_cm'         => $data['lebar_cm'],
            ':bahan_pilihan'    => $data['bahan_pilihan'],
            ':catatan_tambahan' => $data['catatan_tambahan'],
            ':status_pesanan'   => 'Menunggu Konfirmasi',
        ]);

        return $kodePesanan;
    }

    public function getByKode(string $kode): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM pesanan_kustom WHERE kode_pesanan = ?");
        $stmt->execute([$kode]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM pesanan_kustom ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function getByUserIdOrPhone(?int $userId, string $phone = ''): array {
        if ($userId) {
            $stmt = $this->pdo->prepare("SELECT * FROM pesanan_kustom WHERE user_id = ? OR no_telepon = ? ORDER BY id DESC");
            $stmt->execute([$userId, $phone]);
        } else {
            $stmt = $this->pdo->prepare("SELECT * FROM pesanan_kustom WHERE no_telepon = ? ORDER BY id DESC");
            $stmt->execute([$phone]);
        }
        return $stmt->fetchAll();
    }

    public function updateStatus(int $id, string $status): bool {
        $stmt = $this->pdo->prepare("UPDATE pesanan_kustom SET status_pesanan = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
}
