<?php
/**
 * Database Connection & Auto-Migration/Seeder
 * Sistem Pendukung Keputusan Mebel Aluminium (Metode SAW)
 */

class Database {
    private static ?PDO $instance = null;

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $dbDriver = getenv('DB_CONNECTION') ?: 'sqlite';

            if ($dbDriver === 'mysql') {
                $host = getenv('DB_HOST') ?: '127.0.0.1';
                $port = getenv('DB_PORT') ?: '3306';
                $dbname = getenv('DB_DATABASE') ?: 'spk_mebel_saw';
                $user = getenv('DB_USERNAME') ?: 'root';
                $pass = getenv('DB_PASSWORD') ?: '';

                $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
                self::$instance = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } else {
                // SQLite default
                $dbDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'database';
                if (!is_dir($dbDir)) {
                    mkdir($dbDir, 0777, true);
                }
                $sqliteFile = $dbDir . DIRECTORY_SEPARATOR . 'database.sqlite';
                $isNew = !file_exists($sqliteFile) || filesize($sqliteFile) === 0;

                self::$instance = new PDO("sqlite:" . $sqliteFile, null, null, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);

                // Enable foreign keys
                self::$instance->exec("PRAGMA foreign_keys = ON;");

                if ($isNew) {
                    self::initSqliteDatabase(self::$instance, $dbDir);
                }
            }
        }
        return self::$instance;
    }

    private static function initSqliteDatabase(PDO $pdo, string $dbDir): void {
        $schemaFile = $dbDir . DIRECTORY_SEPARATOR . 'schema.sql';
        $seedFile = $dbDir . DIRECTORY_SEPARATOR . 'seed.sql';

        if (file_exists($schemaFile)) {
            $schemaSql = file_get_contents($schemaFile);
            $pdo->exec($schemaSql);
        }

        if (file_exists($seedFile)) {
            $seedSql = file_get_contents($seedFile);
            $pdo->exec($seedSql);

           // Update user passwords with fresh bcrypt hashes
            $passAdmin = password_hash('nj_jaya', PASSWORD_BCRYPT);
            $passBudi = password_hash('budi123', PASSWORD_BCRYPT);

            $stmt = $pdo->prepare("UPDATE users SET username = ?, password = ? WHERE username = ?");
            $stmt->execute(['nj aluminium', $passAdmin, 'admin']);

            $stmtBudi = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
            $stmtBudi->execute([$passBudi, 'budi']);
        }
    }
}

