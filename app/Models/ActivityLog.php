<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class ActivityLog
{
    public static function recent(int $limit = 10): array
    {
        $pdo = Database::connect();
        $sql = "SELECT a.*, u.name as user_name, u.email as user_email 
                FROM activity_logs a 
                LEFT JOIN users u ON a.user_id = u.id 
                ORDER BY a.id DESC LIMIT ?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function count(): int
    {
        $pdo = Database::connect();
        return (int) $pdo->query("SELECT COUNT(*) FROM activity_logs")->fetchColumn();
    }

    public static function clear(): bool
    {
        $pdo = Database::connect();
        return (bool) $pdo->exec("DELETE FROM activity_logs");
    }
}

