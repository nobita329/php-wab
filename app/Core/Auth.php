<?php

namespace App\Core;

use PDO;

class Auth
{
    private static ?array $currentUser = null;

    public static function check(): bool
    {
        return self::id() !== null;
    }

    public static function id(): ?int
    {
        Session::start();
        return Session::get('user_id');
    }

    public static function user(): ?array
    {
        $id = self::id();
        if ($id === null) {
            return null;
        }

        if (self::$currentUser !== null && self::$currentUser['id'] == $id) {
            return self::$currentUser;
        }

        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT id, name, email, role, status, avatar, bio, created_at FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $user = $stmt->fetch();

        if ($user && $user['status'] === 'active') {
            self::$currentUser = $user;
            return self::$currentUser;
        }

        // If inactive or deleted, log out
        self::logout();
        return null;
    }

    public static function isAdmin(): bool
    {
        $user = self::user();
        return $user !== null && $user['role'] === 'admin';
    }

    public static function requireAdmin(): void
    {
        if (!self::isAdmin()) {
            Session::flash('error', 'Administrator access required. Please sign in with an admin account.');
            header("Location: /admin/login.php");
            exit;
        }
    }

    public static function requireAuth(): void
    {
        if (!self::check()) {
            Session::flash('error', 'Please log in to continue.');
            header("Location: /login.php");
            exit;
        }
    }

    public static function isRateLimited(string $ip, string $email): bool
    {
        $pdo = Database::connect();
        $security = require dirname(__DIR__, 2) . '/config/security.php';
        $maxAttempts = $security['rate_limit']['max_login_attempts'] ?? 5;
        $lockoutMinutes = $security['rate_limit']['lockout_time_minutes'] ?? 15;

        // Cleanup old attempts (> lockout minutes)
        if (Database::getDriver() === 'sqlite') {
            $cleanup = $pdo->prepare("DELETE FROM login_attempts WHERE datetime(last_attempt) < datetime('now', '-' || ? || ' minutes')");
        } else {
            $cleanup = $pdo->prepare("DELETE FROM login_attempts WHERE last_attempt < DATE_SUB(NOW(), INTERVAL ? MINUTE)");
        }
        $cleanup->execute([$lockoutMinutes]);

        // Check current attempts
        $stmt = $pdo->prepare("SELECT attempts FROM login_attempts WHERE ip_address = ? OR email = ? ORDER BY attempts DESC LIMIT 1");
        $stmt->execute([$ip, $email]);
        $row = $stmt->fetch();

        if ($row && $row['attempts'] >= $maxAttempts) {
            return true;
        }

        return false;
    }

    public static function recordFailedLogin(string $ip, string $email): void
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT id, attempts FROM login_attempts WHERE ip_address = ? AND email = ? LIMIT 1");
        $stmt->execute([$ip, $email]);
        $row = $stmt->fetch();

        if ($row) {
            $upd = $pdo->prepare("UPDATE login_attempts SET attempts = attempts + 1, last_attempt = CURRENT_TIMESTAMP WHERE id = ?");
            $upd->execute([$row['id']]);
        } else {
            $ins = $pdo->prepare("INSERT INTO login_attempts (ip_address, email, attempts, last_attempt) VALUES (?, ?, 1, CURRENT_TIMESTAMP)");
            $ins->execute([$ip, $email]);
        }
    }

    public static function clearLoginAttempts(string $ip, string $email): void
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("DELETE FROM login_attempts WHERE ip_address = ? OR email = ?");
        $stmt->execute([$ip, $email]);
    }

    public static function attempt(string $email, string $password, string $ip): array
    {
        if (self::isRateLimited($ip, $email)) {
            return [
                'success' => false,
                'message' => 'Too many failed login attempts. Account temporarily locked for security. Please try again later.'
            ];
        }

        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            self::recordFailedLogin($ip, $email);
            return [
                'success' => false,
                'message' => 'Invalid email address or password.'
            ];
        }

        if ($user['status'] !== 'active') {
            return [
                'success' => false,
                'message' => 'This account has been deactivated or suspended.'
            ];
        }

        // Login successful
        self::clearLoginAttempts($ip, $email);
        Session::regenerate();
        Session::set('user_id', (int) $user['id']);
        Session::set('user_role', $user['role']);
        Session::set('user_name', $user['name']);

        self::$currentUser = $user;

        // Log activity
        $logStmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, description, ip_address, created_at) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)");
        $logStmt->execute([$user['id'], 'AUTH_LOGIN', "User {$user['name']} ({$user['role']}) logged in successfully.", $ip]);

        return [
            'success' => true,
            'user' => $user
        ];
    }

    public static function logout(): void
    {
        $id = self::id();
        if ($id !== null) {
            $pdo = Database::connect();
            $logStmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, description, ip_address, created_at) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)");
            $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
            $logStmt->execute([$id, 'AUTH_LOGOUT', "User logged out.", $ip]);
        }

        self::$currentUser = null;
        Session::destroy();
    }
}

