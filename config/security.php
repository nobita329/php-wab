<?php

use App\Core\Env;

return [
    'session' => [
        'name' => 'PORTFOLIO_SESSION_ID',
        'lifetime' => (int) Env::get('SESSION_LIFETIME', 7200), // 2 hours
        'secure' => (bool) Env::get('SESSION_SECURE', false),
        'httponly' => true,
        'samesite' => 'Lax',
    ],
    'csrf' => [
        'token_name' => 'csrf_token',
        'header_name' => 'X-CSRF-TOKEN',
    ],
    'rate_limit' => [
        'max_login_attempts' => (int) Env::get('MAX_LOGIN_ATTEMPTS', 5),
        'lockout_time_minutes' => (int) Env::get('LOGIN_LOCKOUT_MINUTES', 15),
    ],
    'uploads' => [
        'max_file_size' => (int) Env::get('MAX_FILE_SIZE', 10 * 1024 * 1024), // 10MB
        'allowed_image_mimes' => [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/gif'  => 'gif',
            'image/svg+xml' => 'svg',
            'image/x-icon' => 'ico',
            'application/pdf' => 'pdf', // For resumes
        ],
        'upload_dir' => dirname(__DIR__) . '/public/uploads',
        'upload_url' => '/uploads',
    ],
];

