<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Message
{
    public static function all(int $limit = 50, int $offset = 0, ?string $filter = null): array
    {
        $pdo = Database::connect();
        $where = "";
        if ($filter === 'unread') {
            $where = "WHERE is_read = 0";
        } elseif ($filter === 'replied') {
            $where = "WHERE replied = 1";
        }

        $sql = "SELECT * FROM contact_messages {$where} ORDER BY id DESC LIMIT {$limit} OFFSET {$offset}";
        return $pdo->query($sql)->fetchAll();
    }

    public static function count(?string $filter = null): int
    {
        $pdo = Database::connect();
        $where = "";
        if ($filter === 'unread') {
            $where = "WHERE is_read = 0";
        } elseif ($filter === 'replied') {
            $where = "WHERE replied = 1";
        }

        return (int) $pdo->query("SELECT COUNT(*) FROM contact_messages {$where}")->fetchColumn();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM contact_messages WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function create(array $data): int
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message, ip_address, user_agent, is_read, replied, created_at) VALUES (?, ?, ?, ?, ?, ?, 0, 0, CURRENT_TIMESTAMP)");
        $stmt->execute([
            $data['name'],
            $data['email'],
            $data['subject'] ?? 'Website Inquiry',
            $data['message'],
            $data['ip_address'] ?? '127.0.0.1',
            $data['user_agent'] ?? '',
        ]);
        return (int) $pdo->lastInsertId();
    }

    public static function markRead(int $id, bool $read = true): bool
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("UPDATE contact_messages SET is_read = ? WHERE id = ?");
        return $stmt->execute([$read ? 1 : 0, $id]);
    }

    public static function markReplied(int $id, bool $replied = true): bool
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("UPDATE contact_messages SET replied = ? WHERE id = ?");
        return $stmt->execute([$replied ? 1 : 0, $id]);
    }

    public static function delete(int $id): bool
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

