<?php
/**
 * Model User & Autentikasi
 */

class User {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findByUsername(string $username): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function findById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function register(string $username, string $password, string $nama, string $email, string $phone, string $role = 'pelanggan'): int {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare("
            INSERT INTO users (username, password, nama_lengkap, email, phone, role)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$username, $hashed, $nama, $email, $phone, $role]);
        return (int)$this->pdo->lastInsertId();
    }

    public function verifyPassword(string $plainPassword, string $hashedPassword): bool {
        return password_verify($plainPassword, $hashedPassword);
    }
}

