<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';
\App\Core\Auth::requireAdmin();

use App\Core\Request;
use App\Core\View;

if (Request::isPost()) {
    if (!Request::checkCsrf()) {
        set_flash('error', 'CSRF verification failed.');
        header('Location: /admin/three-settings.php');
        exit;
    }

    $fields = [
        'three_enabled', 'three_geometry', 'three_rotation_speed_x', 'three_rotation_speed_y',
        'three_camera_sensitivity', 'three_particle_count', 'three_light_intensity',
        'three_accent_color_1', 'three_accent_color_2', 'three_bg_color',
        'three_wireframe', 'three_mobile_perf'
    ];

    foreach ($fields as $field) {
        $val = Request::post($field, '0');
        update_setting($field, (string)$val, 'three');
    }

    log_activity('UPDATE_3D_SETTINGS', 'Updated Three.js 3D hero parameters and WebGL shader attributes.');
    set_flash('success', 'Three.js 3D scene parameters updated successfully.');
    header('Location: /admin/three-settings.php');
    exit;
}

View::render('admin.three-settings', [
    'pageTitle' => 'Three.js 3D Engine Settings'
], 'admin');

