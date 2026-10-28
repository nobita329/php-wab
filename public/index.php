<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';

use App\Core\View;

// Check maintenance mode
if (setting('maintenance_mode', '0') === '1' && !\App\Core\Auth::isAdmin()) {
    http_response_code(503);
    echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Maintenance Mode</title><style>body{background:#07080d;color:#fff;font-family:sans-serif;display:flex;align-items:center;justify-content:center;height:100vh;margin:0;text-align:center;}h1{color:#00f0ff;}p{color:#94a3b8;}</style></head><body><div><h1>Maintenance Mode</h1><p>' . e(setting('maintenance_message')) . '</p></div></body></html>';
    exit;
}

View::render('pages.home', [
    'pageTitle' => setting('site_title', 'Alex Vance — Creative Technologist & Full-Stack Architect'),
    'metaDescription' => setting('site_description'),
], 'main');

