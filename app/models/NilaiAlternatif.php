<?php
/**
 * Model Nilai Alternatif (Matriks Keputusan X)
 */

class NilaiAlternatif {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getByProduct(int $productId): array {
        $stmt = $this->pdo->prepare("
            SELECT n.*, c.kode_kriteria, c.nama_kriteria, c.jenis, c.bobot
            FROM nilai_alternatif n
            JOIN kriteria c ON n.kriteria_id = c.id
            WHERE n.produk_id = ?
            ORDER BY c.id ASC
        ");
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    public function setValues(int $productId, array $kriteriaValues): void {
        $stmt = $this->pdo->prepare("
            INSERT INTO nilai_alternatif (produk_id, kriteria_id, nilai)
            VALUES (?, ?, ?)
            ON CONFLICT(produk_id, kriteria_id) DO UPDATE SET nilai = excluded.nilai
        ");

        foreach ($kriteriaValues as $kriteriaId => $nilai) {
            $stmt->execute([$productId, (int)$kriteriaId, (float)$nilai]);
        }
    }
}

