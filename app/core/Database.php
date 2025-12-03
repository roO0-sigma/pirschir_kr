<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $host = getenv('DB_HOST') ?: 'db';
            $db   = getenv('DB_NAME') ?: 'blog_aggregator';
            $user = getenv('DB_USER') ?: 'blog_user';
            $pass = getenv('DB_PASSWORD') ?: 'blog_password';

            $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

            try {
                self::$instance = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                die('Database connection error: ' . $e->getMessage());
            }
        }

        return self::$instance;
    }
}


