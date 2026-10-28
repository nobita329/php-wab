<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';
\App\Core\Auth::requireAdmin();

use App\Models\Project;
use App\Core\Request;
use App\Core\Storage;
use App\Core\View;

$action = Request::get('action', 'list');
$id = (int) Request::get('id', 0);

// Delete Project
if ($action === 'delete' && $id > 0) {
    if (Request::checkCsrf()) {
        $proj = Project::find($id);
        if ($proj) {
            Project::delete($id);
            log_activity('DELETE_PROJECT', "Deleted project: {$proj['title']} (ID #{$id})");
            set_flash('success', "Project '{$proj['title']}' deleted.");
        }
    } else {
        set_flash('error', 'CSRF verification failed.');
    }
    header('Location: /admin/projects.php');
    exit;
}

// Delete Gallery Image
if ($action === 'delete_image') {
    $imgId = (int) Request::get('image_id', 0);
    $projId = (int) Request::get('project_id', 0);
    if ($imgId > 0 && Request::checkCsrf()) {
        Project::deleteImage($imgId);
        set_flash('success', 'Gallery image deleted.');
    }
    header("Location: /admin/projects.php?action=edit&id={$projId}");
    exit;
}

// Handle Form Submission (Create or Edit)
if (Request::isPost()) {
    if (!Request::checkCsrf()) {
        set_flash('error', 'CSRF verification failed.');
        header('Location: /admin/projects.php');
        exit;
    }

    $editId = (int) Request::post('project_id', 0);
    $title = Request::post('title');
    $slug = Request::post('slug');
    if (empty($slug)) {
        $slug = slugify($title);
    }

    $data = [
        'title' => $title,
        'slug' => $slug,
        'excerpt' => Request::post('excerpt'),
        'description' => Request::post('description'),
        'category' => Request::post('category'),
        'technologies' => Request::post('technologies'),
        'live_url' => Request::post('live_url'),
        'github_url' => Request::post('github_url'),
        'client_name' => Request::post('client_name'),
        'project_date' => Request::post('project_date'),
        'is_featured' => Request::post('is_featured') ? 1 : 0,
        'is_published' => Request::post('is_published') ? 1 : 0,
        'sort_order' => (int) Request::post('sort_order', 0),
    ];

    // Thumbnail upload if provided
    if (!empty($_FILES['thumbnail']['name'])) {
        $upload = Storage::upload($_FILES['thumbnail']);
        if ($upload['success']) {
            $data['thumbnail'] = $upload['url'];
        } else {
            set_flash('error', 'Thumbnail upload failed: ' . $upload['error']);
        }
    }

    if ($editId > 0) {
        Project::update($editId, $data);
        $targetId = $editId;
        log_activity('UPDATE_PROJECT', "Updated project: {$title} (ID #{$editId})");
        set_flash('success', "Project '{$title}' updated successfully.");
    } else {
        $targetId = Project::create($data);
        log_activity('CREATE_PROJECT', "Created project: {$title} (ID #{$targetId})");
        set_flash('success', "Project '{$title}' created successfully.");
    }

    // Process Gallery Image Uploads if provided
    if (!empty($_FILES['gallery_images']['name'][0])) {
        $files = $_FILES['gallery_images'];
        $count = count($files['name']);
        for ($i = 0; $i < $count; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $single = [
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size' => $files['size'][$i],
                ];
                $up = Storage::upload($single);
                if ($up['success']) {
                    Project::addImage($targetId, $up['url'], '', $i);
                }
            }
        }
    }

    header('Location: /admin/projects.php');
    exit;
}

// Render Create/Edit form or Projects List
if ($action === 'create' || ($action === 'edit' && $id > 0)) {
    $project = ($action === 'edit') ? Project::find($id) : null;
    $categories = Project::getCategories();
    View::render('admin.project-form', [
        'pageTitle' => $project ? 'Edit Project: ' . e($project['title']) : 'Create New Project',
        'project' => $project,
        'categories' => $categories,
    ], 'admin');
} else {
    $projects = Project::all(false, null, 100);
    View::render('admin.projects', [
        'pageTitle' => 'Projects Portfolio Management',
        'projects' => $projects,
    ], 'admin');
}

