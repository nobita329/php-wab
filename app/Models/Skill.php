<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Skill
{
    public static function all(): array
    {
        $pdo = Database::connect();
        $sql = "SELECT s.*, c.name as category_name, c.slug as category_slug 
                FROM skills s 
                LEFT JOIN skill_categories c ON s.category_id = c.id 
                ORDER BY c.sort_order ASC, s.sort_order ASC, s.id ASC";
        return $pdo->query($sql)->fetchAll();
    }

    public static function getGroupedByCategory(): array
    {
        $all = self::all();
        $grouped = [];
        foreach ($all as $item) {
            $cat = $item['category_name'] ?? 'Other Skills';
            $grouped[$cat][] = $item;
        }
        return $grouped;
    }

    public static function featured(int $limit = 8): array
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT s.*, c.name as category_name 
                               FROM skills s 
                               LEFT JOIN skill_categories c ON s.category_id = c.id 
                               WHERE s.is_featured = 1 
                               ORDER BY s.sort_order ASC, s.id ASC LIMIT ?");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM skills WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function create(array $data): int
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("INSERT INTO skills (category_id, name, proficiency, icon, is_featured, sort_order, created_at) VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
        $stmt->execute([
            (int)($data['category_id'] ?? 1),
            $data['name'],
            (int)($data['proficiency'] ?? 80),
            $data['icon'] ?? 'code',
            (int)($data['is_featured'] ?? 1),
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
        $sql = "UPDATE skills SET " . implode(', ', $fields) . " WHERE id = ?";
        return $pdo->prepare($sql)->execute($values);
    }

    public static function delete(int $id): bool
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("DELETE FROM skills WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Categories
    public static function getCategories(): array
    {
        $pdo = Database::connect();
        return $pdo->query("SELECT * FROM skill_categories ORDER BY sort_order ASC, id ASC")->fetchAll();
    }

    public static function createCategory(string $name, string $slug, int $sortOrder = 0): int
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("INSERT INTO skill_categories (name, slug, sort_order, created_at) VALUES (?, ?, ?, CURRENT_TIMESTAMP)");
        $stmt->execute([$name, $slug, $sortOrder]);
        return (int) $pdo->lastInsertId();
    }

    public static function deleteCategory(int $id): bool
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("DELETE FROM skill_categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

