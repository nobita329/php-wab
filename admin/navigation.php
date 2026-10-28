<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';
\App\Core\Auth::requireAdmin();

use App\Models\Navigation;
use App\Core\Request;
use App\Core\View;

$action = Request::get('action', 'list');
$id = (int) Request::get('id', 0);

// Delete
if ($action === 'delete' && $id > 0) {
    if (Request::checkCsrf()) {
        Navigation::delete($id);
        log_activity('DELETE_NAV', "Deleted navigation link ID #{$id}");
        set_flash('success', 'Navigation item deleted.');
    } else {
        set_flash('error', 'CSRF verification failed.');
    }
    header('Location: /admin/navigation.php');
    exit;
}

// POST Create or Update
if (Request::isPost()) {
    if (!Request::checkCsrf()) {
        set_flash('error', 'CSRF verification failed.');
        header('Location: /admin/navigation.php');
        exit;
    }

    $editId = (int) Request::post('nav_id', 0);
    $data = [
        'title' => Request::post('title'),
        'url' => Request::post('url'),
        'target' => Request::post('target', '_self'),
        'location' => Request::post('location', 'header'),
        'sort_order' => (int) Request::post('sort_order', 0),
        'is_active' => Request::post('is_active') ? 1 : 0,
    ];

    if ($editId > 0) {
        Navigation::update($editId, $data);
        log_activity('UPDATE_NAV', "Updated navigation item: {$data['title']} (ID #{$editId})");
        set_flash('success', "Navigation item '{$data['title']}' updated.");
    } else {
        Navigation::create($data);
        log_activity('CREATE_NAV', "Created navigation item: {$data['title']}");
        set_flash('success', "Navigation item '{$data['title']}' added.");
    }

    header('Location: /admin/navigation.php');
    exit;
}

$navItems = Navigation::all();
$editItem = ($action === 'edit' && $id > 0) ? Navigation::find($id) : null;

View::render('admin.navigation', [
    'pageTitle' => 'Navigation Architecture',
    'navItems' => $navItems,
    'editItem' => $editItem,
], 'admin');

