<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';
\App\Core\Auth::requireAdmin();

use App\Models\Service;
use App\Core\Request;
use App\Core\View;

$action = Request::get('action', 'list');
$id = (int) Request::get('id', 0);

// Delete
if ($action === 'delete' && $id > 0) {
    if (Request::checkCsrf()) {
        Service::delete($id);
        log_activity('DELETE_SERVICE', "Deleted service ID #{$id}");
        set_flash('success', 'Service deleted successfully.');
    } else {
        set_flash('error', 'CSRF verification failed.');
    }
    header('Location: /admin/services.php');
    exit;
}

// POST Create or Update
if (Request::isPost()) {
    if (!Request::checkCsrf()) {
        set_flash('error', 'CSRF verification failed.');
        header('Location: /admin/services.php');
        exit;
    }

    $editId = (int) Request::post('service_id', 0);
    $data = [
        'title' => Request::post('title'),
        'icon' => Request::post('icon', 'code'),
        'summary' => Request::post('summary'),
        'features' => Request::post('features'),
        'sort_order' => (int) Request::post('sort_order', 0),
        'is_active' => Request::post('is_active') ? 1 : 0,
    ];

    if ($editId > 0) {
        Service::update($editId, $data);
        log_activity('UPDATE_SERVICE', "Updated service: {$data['title']} (ID #{$editId})");
        set_flash('success', "Service '{$data['title']}' updated.");
    } else {
        Service::create($data);
        log_activity('CREATE_SERVICE', "Created service: {$data['title']}");
        set_flash('success', "Service '{$data['title']}' created successfully.");
    }

    header('Location: /admin/services.php');
    exit;
}

$services = Service::all();
$editItem = ($action === 'edit' && $id > 0) ? Service::find($id) : null;

View::render('admin.services', [
    'pageTitle' => 'Services & Capabilities Management',
    'services' => $services,
    'editItem' => $editItem,
], 'admin');

