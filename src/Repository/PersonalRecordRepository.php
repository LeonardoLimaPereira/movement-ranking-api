<?php

namespace App\Repository;

use PDO;

class PersonalRecordRepository
{
    public function __construct(private PDO $conn) {}

    public function getBestRecordsByMovementId(int $movementId): array
    {
        $sql = "
            WITH ranked AS (
                SELECT
                    U.name,
                    PR.value,
                    PR.date,
                    ROW_NUMBER() OVER (PARTITION BY PR.user_id ORDER BY PR.value DESC) AS rn
                FROM personal_record PR
                JOIN user U ON U.id = PR.user_id
                WHERE PR.movement_id = :movement_id
            )
            SELECT
                name,
                value AS record,
                DENSE_RANK() OVER (ORDER BY value DESC) AS position,
                date
            FROM ranked
            WHERE rn = 1
            ORDER BY value DESC;
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':movement_id', $movementId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}