<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';
\App\Core\Auth::requireAdmin();

use App\Core\Request;
use App\Core\Storage;
use App\Core\View;

if (Request::isPost()) {
    if (!Request::checkCsrf()) {
        set_flash('error', 'CSRF verification failed.');
        header('Location: /admin/branding.php');
        exit;
    }

    $fields = [
        'site_name', 'site_title', 'site_description', 'logo_text',
        'contact_email', 'contact_phone', 'contact_location', 'footer_copyright',
        'social_github', 'social_linkedin', 'social_twitter', 'social_dribbble', 'social_youtube'
    ];

    foreach ($fields as $field) {
        $val = Request::post($field);
        update_setting($field, $val, 'branding');
    }

    // Logo image upload
    if (!empty($_FILES['logo_image']['name'])) {
        $upload = Storage::upload($_FILES['logo_image']);
        if ($upload['success']) {
            update_setting('logo_image', $upload['url'], 'branding');
        } else {
            set_flash('error', 'Logo upload error: ' . $upload['error']);
        }
    }

    // Favicon image upload
    if (!empty($_FILES['favicon_image']['name'])) {
        $upload = Storage::upload($_FILES['favicon_image']);
        if ($upload['success']) {
            update_setting('favicon_image', $upload['url'], 'branding');
        } else {
            set_flash('error', 'Favicon upload error: ' . $upload['error']);
        }
    }

    log_activity('UPDATE_BRANDING', 'Updated website branding and identity coordinates.');
    set_flash('success', 'Branding parameters updated successfully.');
    header('Location: /admin/branding.php');
    exit;
}

View::render('admin.branding', [
    'pageTitle' => 'Website Branding & Identity'
], 'admin');

