<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';
\App\Core\Auth::requireAdmin();

use App\Core\Request;
use App\Core\Storage;
use App\Core\View;

if (Request::isPost()) {
    if (!Request::checkCsrf()) {
        set_flash('error', 'CSRF verification failed.');
        header('Location: /admin/about.php');
        exit;
    }

    $fields = [
        'about_heading', 'about_bio_p1', 'about_bio_p2',
        'about_years_exp', 'about_projects_completed',
        'about_clients_satisfied', 'about_code_commits', 'about_resume_url'
    ];

    foreach ($fields as $field) {
        $val = Request::post($field);
        update_setting($field, $val, 'about');
    }

    // Resume PDF upload if provided
    if (!empty($_FILES['resume_file']['name'])) {
        $upload = Storage::upload($_FILES['resume_file']);
        if ($upload['success']) {
            update_setting('about_resume_url', $upload['url'], 'about');
        } else {
            set_flash('error', 'Resume upload error: ' . $upload['error']);
        }
    }

    log_activity('UPDATE_ABOUT', 'Updated about biography copy, credentials, and career metrics.');
    set_flash('success', 'About section parameters updated successfully.');
    header('Location: /admin/about.php');
    exit;
}

View::render('admin.about', [
    'pageTitle' => 'About Section & Career Metrics'
], 'admin');

