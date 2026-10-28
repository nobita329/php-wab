<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Service
{
    public static function all(bool $activeOnly = false): array
    {
        $pdo = Database::connect();
        $sql = $activeOnly ? "SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order ASC, id ASC" : "SELECT * FROM services ORDER BY sort_order ASC, id ASC";
        return $pdo->query($sql)->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function create(array $data): int
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("INSERT INTO services (title, icon, summary, features, sort_order, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
        $stmt->execute([
            $data['title'],
            $data['icon'] ?? 'code',
            $data['summary'],
            $data['features'] ?? '',
            (int)($data['sort_order'] ?? 0),
            (int)($data['is_active'] ?? 1),
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
        $sql = "UPDATE services SET " . implode(', ', $fields) . " WHERE id = ?";
        return $pdo->prepare($sql)->execute($values);
    }

    public static function delete(int $id): bool
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

