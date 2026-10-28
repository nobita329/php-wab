<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Project
{
    public static function all(bool $publishedOnly = true, ?string $category = null, int $limit = 50, int $offset = 0): array
    {
        $pdo = Database::connect();
        $conditions = [];
        $params = [];

        if ($publishedOnly) {
            $conditions[] = "is_published = 1";
        }

        if ($category && $category !== 'all') {
            $conditions[] = "category = ?";
            $params[] = $category;
        }

        $where = !empty($conditions) ? "WHERE " . implode(' AND ', $conditions) : "";
        $sql = "SELECT * FROM projects {$where} ORDER BY sort_order ASC, id DESC LIMIT {$limit} OFFSET {$offset}";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function featured(int $limit = 6): array
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM projects WHERE is_published = 1 AND is_featured = 1 ORDER BY sort_order ASC, id DESC LIMIT ?");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $project = $stmt->fetch();
        if ($project) {
            $project['images'] = self::getImages($id);
        }
        return $project ?: null;
    }

    public static function findBySlug(string $slug): ?array
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM projects WHERE slug = ? LIMIT 1");
        $stmt->execute([$slug]);
        $project = $stmt->fetch();
        if ($project) {
            $project['images'] = self::getImages((int)$project['id']);
        }
        return $project ?: null;
    }

    public static function count(bool $publishedOnly = false): int
    {
        $pdo = Database::connect();
        $sql = $publishedOnly ? "SELECT COUNT(*) FROM projects WHERE is_published = 1" : "SELECT COUNT(*) FROM projects";
        return (int) $pdo->query($sql)->fetchColumn();
    }

    public static function search(string $query, int $limit = 20): array
    {
        $pdo = Database::connect();
        $term = "%{$query}%";
        $stmt = $pdo->prepare("SELECT * FROM projects WHERE is_published = 1 AND (title LIKE ? OR description LIKE ? OR technologies LIKE ? OR category LIKE ?) ORDER BY sort_order ASC LIMIT ?");
        $stmt->bindValue(1, $term, PDO::PARAM_STR);
        $stmt->bindValue(2, $term, PDO::PARAM_STR);
        $stmt->bindValue(3, $term, PDO::PARAM_STR);
        $stmt->bindValue(4, $term, PDO::PARAM_STR);
        $stmt->bindValue(5, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getCategories(): array
    {
        $pdo = Database::connect();
        return $pdo->query("SELECT DISTINCT category FROM projects WHERE category IS NOT NULL AND category != '' ORDER BY category ASC")->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function incrementViews(int $id): void
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("UPDATE projects SET views_count = views_count + 1 WHERE id = ?");
        $stmt->execute([$id]);
    }

    public static function create(array $data): int
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("INSERT INTO projects (title, slug, excerpt, description, category, technologies, thumbnail, live_url, github_url, client_name, project_date, is_featured, is_published, sort_order, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
        $stmt->execute([
            $data['title'],
            $data['slug'],
            $data['excerpt'] ?? '',
            $data['description'] ?? '',
            $data['category'] ?? 'General',
            $data['technologies'] ?? '',
            $data['thumbnail'] ?? '',
            $data['live_url'] ?? '',
            $data['github_url'] ?? '',
            $data['client_name'] ?? '',
            $data['project_date'] ?? date('Y'),
            (int)($data['is_featured'] ?? 0),
            (int)($data['is_published'] ?? 1),
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
        $sql = "UPDATE projects SET " . implode(', ', $fields) . ", updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        return $pdo->prepare($sql)->execute($values);
    }

    public static function delete(int $id): bool
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Gallery Images
    public static function getImages(int $projectId): array
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM project_images WHERE project_id = ? ORDER BY sort_order ASC, id ASC");
        $stmt->execute([$projectId]);
        return $stmt->fetchAll();
    }

    public static function addImage(int $projectId, string $imagePath, string $caption = '', int $sortOrder = 0): int
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("INSERT INTO project_images (project_id, image_path, caption, sort_order, created_at) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)");
        $stmt->execute([$projectId, $imagePath, $caption, $sortOrder]);
        return (int) $pdo->lastInsertId();
    }

    public static function deleteImage(int $imageId): bool
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("DELETE FROM project_images WHERE id = ?");
        return $stmt->execute([$imageId]);
    }
}

