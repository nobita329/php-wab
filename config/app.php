<?php

use App\Core\Env;

return [
    'name' => Env::get('APP_NAME', 'CyberPulse | 3D Portfolio & CMS'),
    'env' => Env::get('APP_ENV', 'production'),
    'debug' => (bool) Env::get('APP_DEBUG', false),
    'url' => Env::get('APP_URL', 'http://localhost:8000'),
    'timezone' => Env::get('APP_TIMEZONE', 'UTC'),
    'locale' => 'en',
];

