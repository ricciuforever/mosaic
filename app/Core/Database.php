<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $pdo = null;
    private static string $activeDriver = 'mysql';
    private static ?string $forcedSqlitePath = null;

    public static function useSqlite(string $path): void
    {
        self::$forcedSqlitePath = $path;
        self::$pdo = null;
    }

    public static function resetConnection(): void
    {
        self::$pdo = null;
        self::$forcedSqlitePath = null;
    }

    public static function pdo(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        if (self::$forcedSqlitePath !== null) {
            return self::connectSqlite(self::$forcedSqlitePath);
        }

        $default = config('database.default', 'mysql');
        if ($default === 'sqlite') {
            $path = config('database.connections.sqlite.database', database_path('database.sqlite'));
            return self::connectSqlite($path);
        }

        $cfg = config('database.connections.mysql', []);
        $host = $cfg['host'] ?? '127.0.0.1';
        $port = $cfg['port'] ?? '3306';
        $dbname = $cfg['database'] ?? 'mosaic_concierge';
        $user = $cfg['username'] ?? 'root';
        $pass = $cfg['password'] ?? '';
        $charset = $cfg['charset'] ?? 'utf8mb4';

        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_TIMEOUT            => 2,
            ]);
            self::$pdo = $pdo;
            self::$activeDriver = 'mysql';
            return self::$pdo;
        } catch (PDOException $e) {
            try {
                $dsn = "mysql:host={$host};port={$port};charset={$charset}";
                $pdo = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                    PDO::ATTR_TIMEOUT            => 2,
                ]);
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $pdo->exec("USE `{$dbname}`");
                self::$pdo = $pdo;
                self::$activeDriver = 'mysql';
                return self::$pdo;
            } catch (PDOException $e2) {
                // Fallback trasparente su SQLite se il demone MySQL locale non è attivo
                $sqlitePath = config('database.connections.sqlite.database', database_path('database.sqlite'));
                return self::connectSqlite($sqlitePath);
            }
        }
    }

    private static function connectSqlite(string $path): PDO
    {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $pdo = new PDO('sqlite:' . $path, null, null, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $pdo->exec('PRAGMA foreign_keys = ON;');
        self::$pdo = $pdo;
        self::$activeDriver = 'sqlite';
        return self::$pdo;
    }

    public static function driver(): string
    {
        self::pdo();
        return self::$activeDriver;
    }

    public static function select(string $sql, array $params = []): array
    {
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function selectOne(string $sql, array $params = []): ?array
    {
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row !== false ? $row : null;
    }

    public static function execute(string $sql, array $params = []): int
    {
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    public static function insert(string $table, array $data): int|string
    {
        $cols = array_keys($data);
        $placeholders = array_map(fn($c) => ':' . $c, $cols);
        $quotedCols = array_map(fn($c) => "`{$c}`", $cols);

        $sql = sprintf(
            'INSERT INTO `%s` (%s) VALUES (%s)',
            $table,
            implode(', ', $quotedCols),
            implode(', ', $placeholders)
        );

        $params = [];
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $v = json_encode($v, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            } elseif (is_bool($v)) {
                $v = $v ? 1 : 0;
            }
            $params[':' . $k] = $v;
        }

        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($params);
        return self::pdo()->lastInsertId();
    }

    public static function update(string $table, array $data, string $whereSql, array $whereParams = []): int
    {
        $sets = [];
        $params = [];
        foreach ($data as $k => $v) {
            $sets[] = "`{$k}` = :set_{$k}";
            if (is_array($v)) {
                $v = json_encode($v, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            } elseif (is_bool($v)) {
                $v = $v ? 1 : 0;
            }
            $params[":set_{$k}"] = $v;
        }
        foreach ($whereParams as $wk => $wv) {
            $key = str_starts_with((string) $wk, ':') ? $wk : ':' . $wk;
            $params[$key] = $wv;
        }

        $sql = sprintf('UPDATE `%s` SET %s WHERE %s', $table, implode(', ', $sets), $whereSql);
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    private static bool $isBootstrapped = false;

    public static function tableExists(string $table): bool
    {
        try {
            if (self::driver() === 'sqlite') {
                $row = self::selectOne("SELECT name FROM sqlite_master WHERE type='table' AND name = :t", [':t' => $table]);
                return $row !== null;
            }
            $row = self::selectOne(
                "SELECT TABLE_NAME FROM information_schema.tables WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :t LIMIT 1",
                [':t' => $table]
            );
            return $row !== null;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public static function ensureBootstrapped(): void
    {
        if (self::$isBootstrapped) {
            return;
        }
        if (!self::tableExists('products') || !self::tableExists('roles')) {
            Migrator::migrateAndSeed(false);
        } else {
            $count = self::selectOne('SELECT COUNT(*) AS cnt FROM `products`');
            if (((int) ($count['cnt'] ?? 0)) === 0) {
                Migrator::seed(false);
            }
        }
        self::$isBootstrapped = true;
    }
}
