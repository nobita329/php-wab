<?php

namespace App\Core;

use Exception;
use PDO;

class Storage
{
    public static function getUploadDir(): string
    {
        $dir = dirname(__DIR__, 2) . '/public/uploads';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        return $dir;
    }

    /**
     * Handle single file upload securely
     */
    public static function upload(array $file, bool $recordMedia = true): array
    {
        if (!isset($file['error']) || is_array($file['error'])) {
            return ['success' => false, 'error' => 'Invalid file parameter.'];
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $msg = match ($file['error']) {
                UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'File exceeds maximum upload size limit.',
                UPLOAD_ERR_PARTIAL => 'File was only partially uploaded.',
                UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder on server.',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
                default => 'Unknown file upload error.'
            };
            return ['success' => false, 'error' => $msg];
        }

        $security = require dirname(__DIR__, 2) . '/config/security.php';
        $maxSize = $security['uploads']['max_file_size'] ?? 10485760;

        if ($file['size'] > $maxSize) {
            $mb = round($maxSize / (1024 * 1024));
            return ['success' => false, 'error' => "File size cannot exceed {$mb}MB."];
        }

        // Verify MIME type using finfo
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        $allowedMimes = $security['uploads']['allowed_image_mimes'] ?? [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            'image/svg+xml' => 'svg',
            'application/pdf' => 'pdf',
        ];

        if (!array_key_exists($mime, $allowedMimes)) {
            return ['success' => false, 'error' => 'Unsupported file format. Only JPG, PNG, WEBP, GIF, SVG, and PDF are allowed.'];
        }

        $extension = $allowedMimes[$mime];
        $randomName = bin2hex(random_bytes(16)) . '.' . $extension;
        $uploadDir = self::getUploadDir();
        $targetPath = $uploadDir . '/' . $randomName;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            return ['success' => false, 'error' => 'Failed to move uploaded file. Check directory permissions.'];
        }

        $publicUrl = '/uploads/' . $randomName;

        // Optionally record in media_files
        if ($recordMedia) {
            try {
                $pdo = Database::connect();
                $stmt = $pdo->prepare("INSERT INTO media_files (filename, original_name, file_path, file_size, mime_type, created_at) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
                $stmt->execute([
                    $randomName,
                    basename($file['name']),
                    $publicUrl,
                    $file['size'],
                    $mime
                ]);
            } catch (\Exception $e) {
                // Non-fatal if recording fails
            }
        }

        return [
            'success' => true,
            'filename' => $randomName,
            'url' => $publicUrl,
            'size' => $file['size'],
            'mime' => $mime
        ];
    }

    /**
     * Safely delete an uploaded file
     */
    public static function delete(string $filePath): bool
    {
        // Path traversal protection: only allow deleting files inside public/uploads
        $uploadDir = realpath(self::getUploadDir());
        $fullPath = realpath(dirname(__DIR__, 2) . '/public' . $filePath);

        if ($fullPath && str_starts_with($fullPath, $uploadDir) && file_exists($fullPath)) {
            unlink($fullPath);

            // Also delete from media_files table if present
            try {
                $pdo = Database::connect();
                $stmt = $pdo->prepare("DELETE FROM media_files WHERE file_path = ?");
                $stmt->execute([$filePath]);
            } catch (\Exception $e) {
            }

            return true;
        }

        return false;
    }
}

