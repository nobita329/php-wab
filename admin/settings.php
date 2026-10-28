<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';
\App\Core\Auth::requireAdmin();

use App\Core\Request;
use App\Core\View;

if (Request::isPost()) {
    if (!Request::checkCsrf()) {
        set_flash('error', 'CSRF verification failed.');
        header('Location: /admin/settings.php');
        exit;
    }

    $fields = ['maintenance_mode', 'maintenance_message', 'date_format', 'app_timezone'];
    foreach ($fields as $field) {
        $val = Request::post($field, '0');
        update_setting($field, (string)$val, 'general');
    }

    log_activity('UPDATE_GENERAL_SETTINGS', 'Updated general system configuration, timezone, and maintenance state.');
    set_flash('success', 'General settings saved successfully.');
    header('Location: /admin/settings.php');
    exit;
}

View::render('admin.settings', [
    'pageTitle' => 'General System Settings',
], 'admin');

