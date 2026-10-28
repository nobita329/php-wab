<?php

namespace App\Core;

use PDO;
use PDOException;
use Exception;
use Database\Seeder;

class Database
{
    private static ?PDO $instance = null;
    private static ?string $activeDriver = null;

    public static function connect(): PDO
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        $config = require dirname(__DIR__, 2) . '/config/database.php';
        $driver = $config['default'] ?? 'mysql';

        // Check if pdo_mysql extension is available when mysql driver is requested
        if ($driver === 'mysql' && !extension_loaded('pdo_mysql')) {
            if (extension_loaded('pdo_sqlite')) {
                $driver = 'sqlite';
            } else {
                throw new Exception("Neither PDO MySQL nor PDO SQLite extensions are available in this PHP installation.");
            }
        }

        self::$activeDriver = $driver;
        $connConfig = $config['connections'][$driver] ?? null;

        if (!$connConfig) {
            throw new Exception("Database connection configuration for [{$driver}] not found.");
        }

        try {
            if ($driver === 'sqlite') {
                $dbPath = $connConfig['database'];
                $dir = dirname($dbPath);
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }

                $dsn = "sqlite:{$dbPath}";
                self::$instance = new PDO($dsn, null, null, $connConfig['options'] ?? []);

                // Check if tables exist
                $check = self::$instance->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'")->fetch();
                if (!$check) {
                    $schemaFile = dirname(__DIR__, 2) . '/database/schema.sqlite.sql';
                    if (file_exists($schemaFile)) {
                        self::$instance->exec(file_get_contents($schemaFile));
                    }
                    Seeder::run(self::$instance, 'sqlite');
                }
            } else {
                $dsn = "mysql:host={$connConfig['host']};port={$connConfig['port']};dbname={$connConfig['database']};charset={$connConfig['charset']}";
                self::$instance = new PDO($dsn, $connConfig['username'], $connConfig['password'], $connConfig['options'] ?? []);
            }
        } catch (PDOException $e) {
            $isDev = (bool) Env::get('APP_DEBUG', false);
            if ($isDev) {
                throw new Exception("Database Connection Failed [{$driver}]: " . $e->getMessage(), (int) $e->getCode(), $e);
            }
            error_log("Database Connection Error: " . $e->getMessage());
            die("Database connection failed. Please verify your database configuration or contact administrator.");
        }

        return self::$instance;
    }

    public static function getDriver(): string
    {
        if (self::$activeDriver === null) {
            self::connect();
        }
        return self::$activeDriver ?? 'mysql';
    }

    /**
     * Generate an SQL backup dump of the database
     */
    public static function generateSqlBackup(): string
    {
        $pdo = self::connect();
        $driver = self::getDriver();

        $output = "-- ===================================================\n";
        $output .= "-- 3D Portfolio Database Backup\n";
        $output .= "-- Date: " . date('Y-m-d H:i:s') . "\n";
        $output .= "-- Driver: " . $driver . "\n";
        $output .= "-- ===================================================\n\n";

        if ($driver === 'sqlite') {
            $tablesQuery = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
            $tables = $tablesQuery->fetchAll(PDO::FETCH_COLUMN);
        } else {
            $tablesQuery = $pdo->query("SHOW TABLES");
            $tables = $tablesQuery->fetchAll(PDO::FETCH_COLUMN);
        }

        foreach ($tables as $table) {
            $output .= "\n-- Table structure for table `{$table}`\n";
            $output .= "DROP TABLE IF EXISTS `{$table}`;\n";

            if ($driver === 'sqlite') {
                $createStmt = $pdo->query("SELECT sql FROM sqlite_master WHERE type='table' AND name='{$table}'")->fetchColumn();
                $output .= $createStmt . ";\n\n";
            } else {
                $createStmt = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch(PDO::FETCH_NUM);
                $output .= $createStmt[1] . ";\n\n";
            }

            // Dump Data
            $rows = $pdo->query("SELECT * FROM `{$table}`")->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($rows)) {
                $output .= "-- Dumping data for table `{$table}`\n";
                foreach ($rows as $row) {
                    $columns = array_map(fn($col) => "`{$col}`", array_keys($row));
                    $values = array_map(function ($val) use ($pdo) {
                        if ($val === null) return 'NULL';
                        return $pdo->quote($val);
                    }, array_values($row));

                    $output .= "INSERT INTO `{$table}` (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ");\n";
                }
                $output .= "\n";
            }
        }

        return $output;
    }
}

