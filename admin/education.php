<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';
\App\Core\Auth::requireAdmin();

use App\Models\Education;
use App\Core\Request;
use App\Core\View;

$action = Request::get('action', 'list');
$id = (int) Request::get('id', 0);

// Delete
if ($action === 'delete' && $id > 0) {
    if (Request::checkCsrf()) {
        Education::delete($id);
        log_activity('DELETE_EDUCATION', "Deleted education record ID #{$id}");
        set_flash('success', 'Education record deleted.');
    } else {
        set_flash('error', 'CSRF verification failed.');
    }
    header('Location: /admin/education.php');
    exit;
}

// Create or Update POST
if (Request::isPost()) {
    if (!Request::checkCsrf()) {
        set_flash('error', 'CSRF verification failed.');
        header('Location: /admin/education.php');
        exit;
    }

    $editId = (int) Request::post('education_id', 0);
    $data = [
        'institution' => Request::post('institution'),
        'degree' => Request::post('degree'),
        'field_of_study' => Request::post('field_of_study'),
        'start_date' => Request::post('start_date'),
        'end_date' => Request::post('end_date'),
        'grade' => Request::post('grade'),
        'description' => Request::post('description'),
        'sort_order' => (int) Request::post('sort_order', 0),
    ];

    if ($editId > 0) {
        Education::update($editId, $data);
        log_activity('UPDATE_EDUCATION', "Updated education: {$data['institution']} (ID #{$editId})");
        set_flash('success', "Education at {$data['institution']} updated.");
    } else {
        Education::create($data);
        log_activity('CREATE_EDUCATION', "Created education: {$data['institution']}");
        set_flash('success', "Education at {$data['institution']} created successfully.");
    }

    header('Location: /admin/education.php');
    exit;
}

$education = Education::all();
$editItem = ($action === 'edit' && $id > 0) ? Education::find($id) : null;

View::render('admin.education', [
    'pageTitle' => 'Education & Credentials Management',
    'education' => $education,
    'editItem' => $editItem,
], 'admin');

