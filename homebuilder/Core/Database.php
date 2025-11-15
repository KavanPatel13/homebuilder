<?php
namespace Core;

class Database
{
    private static $pdo = null;

    public static function getConnection()
    {
        if (self::$pdo === null) {
            $config = require __DIR__ . '/../Config/config.php';
            $host = $config['DB_HOST'] ?? '127.0.0.1';
            $port = isset($config['DB_PORT']) ? (int)$config['DB_PORT'] : null;
            if ($port) {
                $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $host, $port, $config['DB_NAME']);
            } else {
                $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $host, $config['DB_NAME']);
            }
            try {
                self::$pdo = new \PDO($dsn, $config['DB_USER'], $config['DB_PASS'], [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                ]);
            } catch (\PDOException $e) {
                die('Database connection failed: ' . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}
