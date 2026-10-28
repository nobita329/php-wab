<?php
/**
 * Root Router / Wrapper for servers pointing directly to project root
 */
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));

// If accessing /admin or /admin/...
if (str_starts_with($uri, '/admin/')) {
    $script = __DIR__ . $uri;
    if (is_file($script)) {
        require_once $script;
        exit;
    }
    if (is_dir($script) && file_exists($script . '/index.php')) {
        require_once $script . '/index.php';
        exit;
    }
}

if ($uri === '/admin') {
    header('Location: /admin/index.php');
    exit;
}

// Check if file exists in public/
$publicFile = __DIR__ . '/public' . $uri;
if ($uri !== '/' && is_file($publicFile)) {
    // Serve static files with proper MIME types if running built-in server
    $ext = pathinfo($publicFile, PATHINFO_EXTENSION);
    $mimes = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'webp' => 'image/webp',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
        'pdf' => 'application/pdf',
        'xml' => 'application/xml',
        'txt' => 'text/plain',
        'json' => 'application/json',
    ];

    if ($ext === 'php') {
        require_once $publicFile;
        exit;
    }

    if (isset($mimes[$ext])) {
        header('Content-Type: ' . $mimes[$ext]);
        readfile($publicFile);
        exit;
    }
}

// Forward to public front controller
require_once __DIR__ . '/public/index.php';

