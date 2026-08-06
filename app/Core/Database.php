<?php
declare(strict_types=1);

namespace ThemeHub\Core;

use PDO;
use PDOException;

/**
 * Database Connection Manager
 * Supports SQLite (default) and MySQL
 */
final class Database
{
    private static ?PDO $pdo = null;
    private static ?string $driver = null;

    public static function connection(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $driver = env('DB_CONNECTION', 'sqlite');
        self::$driver = $driver;

        try {
            switch ($driver) {
                case 'mysql':
                    $host = env('DB_HOST', 'localhost');
                    $port = env('DB_PORT', '3306');
                    $database = env('DB_DATABASE', 'themehub');
                    $username = env('DB_USERNAME', 'root');
                    $password = env('DB_PASSWORD', '');
                    
                    $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";
                    self::$pdo = new PDO($dsn, $username, $password, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        PDO::MYSQL_ATTR_FOUND_ROWS => true,
                    ]);
                    break;

                case 'sqlite':
                default:
                    $dbPath = env('DB_DATABASE', APP_ROOT . '/database/themehub.sqlite');
                    
                    if (!str_starts_with($dbPath, '/') && !str_starts_with($dbPath, '.')) {
                        $dbPath = APP_ROOT . '/' . $dbPath;
                    }
                    
                    $dbDir = dirname($dbPath);
                    if (!is_dir($dbDir)) {
                        mkdir($dbDir, 0755, true);
                    }
                    
                    self::$pdo = new PDO('sqlite:' . $dbPath, null, null, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]);
                    
                    self::$pdo->exec('PRAGMA foreign_keys = ON');
                    self::$pdo->exec('PRAGMA journal_mode = WAL');
                    break;
            }
        } catch (PDOException $e) {
            log_message('error', 'Database connection failed: ' . $e->getMessage());
            throw new \RuntimeException('Database connection failed: ' . $e->getMessage());
        }

        return self::$pdo;
    }

    public static function driver(): string
    {
        return self::$driver ?? env('DB_CONNECTION', 'sqlite');
    }

    public static function transaction(callable $callback): mixed
    {
        $pdo = self::connection();
        $pdo->beginTransaction();
        
        try {
            $result = $callback($pdo);
            $pdo->commit();
            return $result;
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public static function migrate(string $sql): void
    {
        self::connection()->exec($sql);
    }

    public static function tableExists(string $table): bool
    {
        $driver = self::driver();
        $sql = match ($driver) {
            'mysql' => "SHOW TABLES LIKE ?",
            default => "SELECT name FROM sqlite_master WHERE type='table' AND name = ?"
        };
        
        $stmt = self::connection()->prepare($sql);
        $stmt->execute([$table]);
        return (bool)$stmt->fetch();
    }
}
