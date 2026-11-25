<?php
/**
 * Model Produk Alternatif Mebel Aluminium
 */

class Produk {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAll(?int $kategoriId = null): array {
        $sql = "
            SELECT p.*, k.nama_kategori 
            FROM produk_alternatif p
            JOIN kategori_mebel k ON p.kategori_id = k.id
        ";
        $params = [];
        if ($kategoriId !== null && $kategoriId > 0) {
            $sql .= " WHERE p.kategori_id = ?";
            $params[] = $kategoriId;
        }
        $sql .= " ORDER BY p.id ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->pdo->prepare("
            SELECT p.*, k.nama_kategori 
            FROM produk_alternatif p
            JOIN kategori_mebel k ON p.kategori_id = k.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) return null;

        // Ambil nilai kriteria
        $stmtVal = $this->pdo->prepare("
            SELECT n.*, c.kode_kriteria, c.nama_kriteria, c.jenis, c.bobot, c.keterangan_skala
            FROM nilai_alternatif n
            JOIN kriteria c ON n.kriteria_id = c.id
            WHERE n.produk_id = ?
            ORDER BY c.id ASC
        ");
        $stmtVal->execute([$id]);
        $row['criteria_values'] = $stmtVal->fetchAll();

        return $row;
    }

    public function create(array $data): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO produk_alternatif (
                kategori_id, sku, nama_produk, harga, ketebalan, merk, garansi, finishing,
                panjang_cm, lebar_cm, tinggi_cm, berat_aktual_kg, foto, model_glb_url,
                deskripsi_spesifikasi, is_po, lead_time_days
            ) VALUES (
                :kategori_id, :sku, :nama_produk, :harga, :ketebalan, :merk, :garansi, :finishing,
                :panjang_cm, :lebar_cm, :tinggi_cm, :berat_aktual_kg, :foto, :model_glb_url,
                :deskripsi_spesifikasi, :is_po, :lead_time_days
            )
        ");
        $stmt->execute([
            ':kategori_id' => $data['kategori_id'],
            ':sku' => $data['sku'],
            ':nama_produk' => $data['nama_produk'],
            ':harga' => $data['harga'],
            ':ketebalan' => $data['ketebalan'],
            ':merk' => $data['merk'],
            ':garansi' => $data['garansi'],
            ':finishing' => $data['finishing'],
            ':panjang_cm' => $data['panjang_cm'],
            ':lebar_cm' => $data['lebar_cm'],
            ':tinggi_cm' => $data['tinggi_cm'],
            ':berat_aktual_kg' => $data['berat_aktual_kg'],
            ':foto' => $data['foto'] ?? '',
            ':model_glb_url' => $data['model_glb_url'] ?? '',
            ':deskripsi_spesifikasi' => $data['deskripsi_spesifikasi'] ?? '',
            ':is_po' => $data['is_po'] ?? 1,
            ':lead_time_days' => $data['lead_time_days'] ?? 14,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->pdo->prepare("
            UPDATE produk_alternatif SET
                kategori_id = :kategori_id,
                sku = :sku,
                nama_produk = :nama_produk,
                harga = :harga,
                ketebalan = :ketebalan,
                merk = :merk,
                garansi = :garansi,
                finishing = :finishing,
                panjang_cm = :panjang_cm,
                lebar_cm = :lebar_cm,
                tinggi_cm = :tinggi_cm,
                berat_aktual_kg = :berat_aktual_kg,
                foto = :foto,
                model_glb_url = :model_glb_url,
                deskripsi_spesifikasi = :deskripsi_spesifikasi,
                is_po = :is_po,
                lead_time_days = :lead_time_days
            WHERE id = :id
        ");
        $data[':id'] = $id;
        return $stmt->execute($data);
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM produk_alternatif WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function logSearch(?int $kategoriId, ?float $minBudget, ?float $maxBudget): void {
        $stmt = $this->pdo->prepare("
            INSERT INTO search_logs (kategori_id, min_budget, max_budget)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$kategoriId, $minBudget, $maxBudget]);
    }
}

