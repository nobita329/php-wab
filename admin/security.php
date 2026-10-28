<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';
\App\Core\Auth::requireAdmin();

use App\Core\Request;
use App\Core\View;

if (Request::isPost()) {
    if (!Request::checkCsrf()) {
        set_flash('error', 'CSRF verification failed.');
        header('Location: /admin/security.php');
        exit;
    }

    $fields = [
        'max_login_attempts', 'login_lockout_minutes',
        'session_lifetime', 'allow_user_registration'
    ];

    foreach ($fields as $field) {
        $val = Request::post($field);
        update_setting($field, (string)$val, 'security');
    }

    log_activity('UPDATE_SECURITY', 'Updated session lifetimes, rate limit throttling, and user registration flags.');
    set_flash('success', 'Security parameters saved successfully.');
    header('Location: /admin/security.php');
    exit;
}

View::render('admin.security', [
    'pageTitle' => 'Security Guard & Rate Limiting',
], 'admin');

