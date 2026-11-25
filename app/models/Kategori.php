<?php
/**
 * Model Kategori Mebel
 */

class Kategori {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAll(): array {
        $stmt = $this->pdo->query("
            SELECT k.*, COUNT(p.id) as total_produk 
            FROM kategori_mebel k
            LEFT JOIN produk_alternatif p ON k.id = p.kategori_id
            GROUP BY k.id
            ORDER BY k.id ASC
        ");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM kategori_mebel WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(string $nama, string $slug, string $foto, string $deskripsi): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO kategori_mebel (nama_kategori, slug, foto, deskripsi)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$nama, $slug, $foto, $deskripsi]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, string $nama, string $slug, string $foto, string $deskripsi): bool {
        $stmt = $this->pdo->prepare("
            UPDATE kategori_mebel 
            SET nama_kategori = ?, slug = ?, foto = ?, deskripsi = ?
            WHERE id = ?
        ");
        return $stmt->execute([$nama, $slug, $foto, $deskripsi, $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM kategori_mebel WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

