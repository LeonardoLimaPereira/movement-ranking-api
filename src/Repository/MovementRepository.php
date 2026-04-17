<?php

namespace App\Repository;

use PDO;

class MovementRepository
{
    public function __construct(private PDO $conn) {}

    public function findByNameOrId(string $movement): ?array
    {
        if (is_numeric($movement)) {
            $stmt = $this->conn->prepare(
                "SELECT * FROM movement WHERE id = :movement"
            );
        } else {
            $stmt = $this->conn->prepare(
                "SELECT * FROM movement WHERE LOWER(name) = LOWER(:movement) LIMIT 1"
            );
        }

        $stmt->bindValue(':movement', $movement);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }
}