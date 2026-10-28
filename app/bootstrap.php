<?php

// Ensure PHP 8.2+
if (version_compare(PHP_VERSION, '8.2.0', '<')) {
    die("This application requires PHP 8.2.0 or higher. Current version: " . PHP_VERSION);
}

// Load Environment
require_once __DIR__ . '/Core/Env.php';
\App\Core\Env::load(dirname(__DIR__) . '/.env');

// Register Autoloader
require_once __DIR__ . '/Core/Autoloader.php';
\App\Core\Autoloader::register();

// Load Helpers
require_once __DIR__ . '/Helpers/helpers.php';

// Timezone
$tz = \App\Core\Env::get('APP_TIMEZONE', 'UTC');
date_default_timezone_set($tz);

// Error Handling
$debug = (bool) \App\Core\Env::get('APP_DEBUG', false);
if ($debug) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(0);
}

// Start Session
\App\Core\Session::start();

