<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';
\App\Core\Auth::requireAdmin();

use App\Core\Request;
use App\Core\View;

// Reset to Defaults Trigger
if (Request::get('action') === 'reset_defaults') {
    if (Request::checkCsrf()) {
        update_setting('theme_primary_color', '#00f0ff', 'theme');
        update_setting('theme_accent_color', '#7928ca', 'theme');
        update_setting('theme_bg_color', '#07080d', 'theme');
        update_setting('theme_card_border_radius', '20', 'theme');
        update_setting('theme_font_family', 'Outfit, sans-serif', 'theme');
        update_setting('theme_button_style', 'neon-glow', 'theme');
        update_setting('theme_section_visibility_services', '1', 'theme');
        update_setting('theme_section_visibility_experience', '1', 'theme');
        update_setting('theme_section_visibility_skills', '1', 'theme');

        log_activity('RESET_THEME', 'Reset website theme parameters to defaults.');
        set_flash('success', 'Theme reset to default cyber aesthetic.');
    }
    header('Location: /admin/theme.php');
    exit;
}

// POST Save Theme
if (Request::isPost()) {
    if (!Request::checkCsrf()) {
        set_flash('error', 'CSRF verification failed.');
        header('Location: /admin/theme.php');
        exit;
    }

    $fields = [
        'theme_primary_color', 'theme_accent_color', 'theme_bg_color',
        'theme_card_border_radius', 'theme_font_family', 'theme_button_style',
        'theme_section_visibility_services', 'theme_section_visibility_experience',
        'theme_section_visibility_skills'
    ];

    foreach ($fields as $field) {
        $val = Request::post($field, '0');
        update_setting($field, (string)$val, 'theme');
    }

    log_activity('UPDATE_THEME', 'Updated global CSS theme variables and section visibility.');
    set_flash('success', 'Theme parameters updated successfully.');
    header('Location: /admin/theme.php');
    exit;
}

View::render('admin.theme', [
    'pageTitle' => 'Theme Customization Studio',
], 'admin');

