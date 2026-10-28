<?php

namespace App\Core;

/**
 * PSR-4 compliant autoloader for App and Database namespaces
 */
class Autoloader
{
    public static function register(): void
    {
        spl_autoload_register(function ($class) {
            $root = dirname(__DIR__, 2);

            if (str_starts_with($class, 'App\\')) {
                $relative = substr($class, 4);
                $file = $root . '/app/' . str_replace('\\', '/', $relative) . '.php';
                if (file_exists($file)) {
                    require_once $file;
                    return;
                }
            }

            if (str_starts_with($class, 'Database\\')) {
                $relative = substr($class, 9);
                $file = $root . '/database/' . str_replace('\\', '/', $relative) . '.php';
                if (file_exists($file)) {
                    require_once $file;
                    return;
                }
            }
        });
    }
}

