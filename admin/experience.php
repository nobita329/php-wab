<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';
\App\Core\Auth::requireAdmin();

use App\Models\Experience;
use App\Core\Request;
use App\Core\View;

$action = Request::get('action', 'list');
$id = (int) Request::get('id', 0);

// Delete
if ($action === 'delete' && $id > 0) {
    if (Request::checkCsrf()) {
        Experience::delete($id);
        log_activity('DELETE_EXPERIENCE', "Deleted experience ID #{$id}");
        set_flash('success', 'Experience record deleted.');
    } else {
        set_flash('error', 'CSRF verification failed.');
    }
    header('Location: /admin/experience.php');
    exit;
}

// Create or Update POST
if (Request::isPost()) {
    if (!Request::checkCsrf()) {
        set_flash('error', 'CSRF verification failed.');
        header('Location: /admin/experience.php');
        exit;
    }

    $editId = (int) Request::post('experience_id', 0);
    $data = [
        'company' => Request::post('company'),
        'position' => Request::post('position'),
        'employment_type' => Request::post('employment_type', 'Full-time'),
        'location' => Request::post('location'),
        'start_date' => Request::post('start_date'),
        'end_date' => Request::post('is_current') ? 'Present' : Request::post('end_date'),
        'is_current' => Request::post('is_current') ? 1 : 0,
        'description' => Request::post('description'),
        'sort_order' => (int) Request::post('sort_order', 0),
    ];

    if ($editId > 0) {
        Experience::update($editId, $data);
        log_activity('UPDATE_EXPERIENCE', "Updated experience: {$data['company']} (ID #{$editId})");
        set_flash('success', "Experience at {$data['company']} updated.");
    } else {
        Experience::create($data);
        log_activity('CREATE_EXPERIENCE', "Created experience: {$data['company']}");
        set_flash('success', "Experience at {$data['company']} created successfully.");
    }

    header('Location: /admin/experience.php');
    exit;
}

$experiences = Experience::all();
$editItem = ($action === 'edit' && $id > 0) ? Experience::find($id) : null;

View::render('admin.experience', [
    'pageTitle' => 'Work Experience Timeline',
    'experiences' => $experiences,
    'editItem' => $editItem,
], 'admin');

