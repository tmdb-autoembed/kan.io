<?php
namespace App\Core;

use PDO;

final class Database
{
    private static ?PDO $pdo = null;

    public static function pdo(): PDO
    {
        if (!self::$pdo) {
            $config = config('database');
            $path = $config['sqlite']['path'];
            if (!str_starts_with($path, '/') && !preg_match('/^[A-Z]:\\/i', $path)) {
                $path = dirname(__DIR__, 2) . '/' . $path;
            }
            if (!is_dir(dirname($path))) {
                mkdir(dirname($path), 0775, true);
            }
            self::$pdo = new PDO('sqlite:' . $path, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            self::$pdo->exec('PRAGMA foreign_keys = ON');
        }

        return self::$pdo;
    }
}
