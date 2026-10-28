<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';
\App\Core\Auth::requireAdmin();

use App\Core\Database;
use App\Models\ActivityLog;
use App\Core\Request;
use App\Core\View;

$action = Request::get('action');

// 1. Download Database Backup
if ($action === 'download_backup') {
    if (!Request::checkCsrf()) {
        die("Security CSRF token mismatch.");
    }

    $backupSql = Database::generateSqlBackup();
    $filename = 'portfolio_db_backup_' . date('Y-m-d_His') . '.sql';

    header('Content-Type: application/sql');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . strlen($backupSql));
    echo $backupSql;
    exit;
}

// 2. Clear Activity Logs
if ($action === 'clear_logs') {
    if (Request::checkCsrf()) {
        ActivityLog::clear();
        log_activity('CLEAR_LOGS', 'Cleared historical administrative audit trail.');
        set_flash('success', 'Activity logs cleared successfully.');
    }
    header('Location: /admin/database.php');
    exit;
}

// 3. Clear Cache
if ($action === 'clear_cache') {
    if (Request::checkCsrf()) {
        $cacheDir = dirname(__DIR__) . '/storage/cache';
        if (is_dir($cacheDir)) {
            $files = glob($cacheDir . '/*');
            foreach ($files as $f) {
                if (is_file($f)) unlink($f);
            }
        }
        log_activity('CLEAR_CACHE', 'Cleared server-side cache files.');
        set_flash('success', 'Server application cache cleared successfully.');
    }
    header('Location: /admin/database.php');
    exit;
}

// Compute Real Database Statistics
$pdo = Database::connect();
$driver = Database::getDriver();

$tableStats = [];
$totalRows = 0;

if ($driver === 'sqlite') {
    $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'")->fetchAll(\PDO::FETCH_COLUMN);
    foreach ($tables as $t) {
        $rows = (int) $pdo->query("SELECT COUNT(*) FROM `{$t}`")->fetchColumn();
        $tableStats[$t] = $rows;
        $totalRows += $rows;
    }
    $dbSize = file_exists(dirname(__DIR__) . '/storage/database.sqlite') ? filesize(dirname(__DIR__) . '/storage/database.sqlite') : 0;
} else {
    $tables = $pdo->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);
    foreach ($tables as $t) {
        $rows = (int) $pdo->query("SELECT COUNT(*) FROM `{$t}`")->fetchColumn();
        $tableStats[$t] = $rows;
        $totalRows += $rows;
    }
    $sizeQuery = $pdo->query("SELECT SUM(data_length + index_length) FROM information_schema.TABLES WHERE table_schema = DATABASE()")->fetchColumn();
    $dbSize = (int)$sizeQuery;
}

View::render('admin.database', [
    'pageTitle' => 'Database Telemetry & Backup Engine',
    'driver' => $driver,
    'tableStats' => $tableStats,
    'totalRows' => $totalRows,
    'dbSize' => $dbSize,
], 'admin');

