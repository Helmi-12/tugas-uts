<?php
/**
 * Model Kriteria Penilaian SAW
 */

class Kriteria {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM kriteria ORDER BY id ASC");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM kriteria WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function update(int $id, string $nama, string $jenis, float $bobot, string $keterangan): bool {
        $stmt = $this->pdo->prepare("
            UPDATE kriteria 
            SET nama_kriteria = ?, jenis = ?, bobot = ?, keterangan_skala = ?
            WHERE id = ?
        ");
        return $stmt->execute([$nama, $jenis, $bobot, $keterangan, $id]);
    }

    public function getTotalWeight(): float {
        $stmt = $this->pdo->query("SELECT SUM(bobot) as total FROM kriteria");
        $res = $stmt->fetch();
        return (float)($res['total'] ?? 0);
    }
}

