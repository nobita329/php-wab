<?php

namespace App\Core;

class Session
{
    private static bool $started = false;

    public static function start(): void
    {
        if (self::$started || session_status() === PHP_SESSION_ACTIVE) {
            self::$started = true;
            return;
        }

        $security = require dirname(__DIR__, 2) . '/config/security.php';
        $sessionConfig = $security['session'] ?? [];

        $cookieParams = [
            'lifetime' => $sessionConfig['lifetime'] ?? 7200,
            'path' => '/',
            'domain' => '',
            'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($sessionConfig['secure'] ?? false),
            'httponly' => $sessionConfig['httponly'] ?? true,
            'samesite' => $sessionConfig['samesite'] ?? 'Lax',
        ];

        session_name($sessionConfig['name'] ?? 'PORTFOLIO_SESSION');
        session_set_cookie_params($cookieParams);

        session_start();
        self::$started = true;

        // Auto-expire session if lifetime exceeded
        $now = time();
        if (isset($_SESSION['_last_activity']) && ($now - $_SESSION['_last_activity'] > ($sessionConfig['lifetime'] ?? 7200))) {
            self::destroy();
            session_start();
        }
        $_SESSION['_last_activity'] = $now;
    }

    public static function regenerate(): void
    {
        self::start();
        if (!headers_sent()) {
            session_regenerate_id(true);
        }
    }

    public static function set(string $key, mixed $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }
            session_destroy();
            self::$started = false;
        }
    }

    // CSRF Protection
    public static function generateCsrfToken(): string
    {
        self::start();
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }

    public static function validateCsrfToken(?string $token): bool
    {
        self::start();
        if (empty($token) || empty($_SESSION['_csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['_csrf_token'], $token);
    }

    // Flash Messages
    public static function flash(string $type, string $message): void
    {
        self::start();
        $_SESSION['_flash'][$type][] = $message;
    }

    public static function getFlash(string $type): array
    {
        self::start();
        $messages = $_SESSION['_flash'][$type] ?? [];
        unset($_SESSION['_flash'][$type]);
        return $messages;
    }

    public static function hasFlash(string $type): bool
    {
        self::start();
        return !empty($_SESSION['_flash'][$type]);
    }
}
