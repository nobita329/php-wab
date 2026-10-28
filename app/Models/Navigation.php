<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Navigation
{
    public static function all(): array
    {
        $pdo = Database::connect();
        return $pdo->query("SELECT * FROM navigation_items ORDER BY sort_order ASC, id ASC")->fetchAll();
    }

    public static function getByLocation(string $location = 'header'): array
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM navigation_items WHERE is_active = 1 AND (location = ? OR location = 'both') ORDER BY sort_order ASC, id ASC");
        $stmt->execute([$location]);
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM navigation_items WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function create(array $data): int
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("INSERT INTO navigation_items (title, url, target, location, sort_order, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
        $stmt->execute([
            $data['title'],
            $data['url'],
            $data['target'] ?? '_self',
            $data['location'] ?? 'header',
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
        $sql = "UPDATE navigation_items SET " . implode(', ', $fields) . " WHERE id = ?";
        return $pdo->prepare($sql)->execute($values);
    }

    public static function delete(int $id): bool
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("DELETE FROM navigation_items WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

