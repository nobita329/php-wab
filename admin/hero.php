<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';
\App\Core\Auth::requireAdmin();

use App\Core\Request;
use App\Core\Storage;
use App\Core\View;

if (Request::isPost()) {
    if (!Request::checkCsrf()) {
        set_flash('error', 'CSRF verification failed.');
        header('Location: /admin/hero.php');
        exit;
    }

    $fields = [
        'hero_badge', 'hero_heading', 'hero_subtitle', 'hero_description',
        'hero_btn_primary_label', 'hero_btn_primary_link',
        'hero_btn_secondary_label', 'hero_btn_secondary_link',
        'hero_alignment'
    ];

    foreach ($fields as $field) {
        $val = Request::post($field);
        update_setting($field, $val, 'hero');
    }

    // Hero background/art upload if provided
    if (!empty($_FILES['hero_image']['name'])) {
        $upload = Storage::upload($_FILES['hero_image']);
        if ($upload['success']) {
            update_setting('hero_image', $upload['url'], 'hero');
        } else {
            set_flash('error', 'Hero image upload error: ' . $upload['error']);
        }
    }

    log_activity('UPDATE_HERO', 'Updated 3D hero textual and button configurations.');
    set_flash('success', 'Hero settings updated successfully.');
    header('Location: /admin/hero.php');
    exit;
}

View::render('admin.hero', [
    'pageTitle' => 'Hero Stage Settings'
], 'admin');

