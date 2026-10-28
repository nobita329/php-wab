<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Experience
{
    public static function all(): array
    {
        $pdo = Database::connect();
        return $pdo->query("SELECT * FROM experiences ORDER BY sort_order ASC, id DESC")->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM experiences WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function create(array $data): int
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("INSERT INTO experiences (company, position, employment_type, location, start_date, end_date, is_current, description, sort_order, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
        $stmt->execute([
            $data['company'],
            $data['position'],
            $data['employment_type'] ?? 'Full-time',
            $data['location'] ?? '',
            $data['start_date'],
            $data['end_date'] ?? 'Present',
            (int)($data['is_current'] ?? 0),
            $data['description'] ?? '',
            (int)($data['sort_order'] ?? 0),
        ]);
        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $pdo = Database::connect();
        $fields = [];
        $values = [];

        foreach ($data as $k => $v) {
            $fields[] = "`{$k}` = ?";
            $values[] = $v;
        }

        if (empty($fields)) return false;

        $values[] = $id;
        $sql = "UPDATE experiences SET " . implode(', ', $fields) . " WHERE id = ?";
        return $pdo->prepare($sql)->execute($values);
    }

    public static function delete(int $id): bool
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("DELETE FROM experiences WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

