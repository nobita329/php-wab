<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class User
{
    public static function all(): array
    {
        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT id, name, email, role, status, avatar, bio, created_at, updated_at FROM users ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT id, name, email, role, status, avatar, bio, created_at, updated_at FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function findByEmail(string $email): ?array
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public static function count(): int
    {
        $pdo = Database::connect();
        return (int) $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    }

    public static function create(array $data): int
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, status, avatar, bio, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
        $stmt->execute([
            $data['name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['role'] ?? 'user',
            $data['status'] ?? 'active',
            $data['avatar'] ?? null,
            $data['bio'] ?? null,
        ]);
        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $pdo = Database::connect();
        $fields = [];
        $values = [];

        foreach ($data as $key => $val) {
            if ($key === 'password') {
                if (!empty($val)) {
                    $fields[] = "`password` = ?";
                    $values[] = password_hash($val, PASSWORD_DEFAULT);
                }
            } else {
                $fields[] = "`{$key}` = ?";
                $values[] = $val;
            }
        }

        if (empty($fields)) {
            return false;
        }

        $values[] = $id;
        $sql = "UPDATE users SET " . implode(', ', $fields) . ", updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($values);
    }

    public static function delete(int $id): bool
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

