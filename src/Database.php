<?php

namespace App;

use App\Core\Response;
use PDO;

class Database
{
    public static function getConnection(): PDO
    {
        $host = 'db';
        $db   = 'ranking';
        $user = 'root';
        $pass = 'root';

        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

        try {
            return new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        } catch (\PDOException $e) {
            Response::json([
                'error' => $e->getMessage()
            ], 500);
            exit;
        }
    }
}