<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Media
{
    public static function all(int $limit = 60, int $offset = 0): array
    {
        $pdo = Database::connect();
        return $pdo->query("SELECT * FROM media_files ORDER BY id DESC LIMIT {$limit} OFFSET {$offset}")->fetchAll();
    }

    public static function count(): int
    {
        $pdo = Database::connect();
        return (int) $pdo->query("SELECT COUNT(*) FROM media_files")->fetchColumn();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM media_files WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function delete(int $id): bool
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT file_path FROM media_files WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if ($row) {
            $del = $pdo->prepare("DELETE FROM media_files WHERE id = ?");
            $del->execute([$id]);
            return true;
        }
        return false;
    }
}

