<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';
\App\Core\Auth::requireAdmin();

use App\Models\Skill;
use App\Core\Request;
use App\Core\View;

$action = Request::get('action', 'list');
$id = (int) Request::get('id', 0);

// Process Deletion
if ($action === 'delete' && $id > 0) {
    if (Request::checkCsrf()) {
        Skill::delete($id);
        log_activity('DELETE_SKILL', "Deleted skill ID #{$id}");
        set_flash('success', 'Skill removed successfully.');
    } else {
        set_flash('error', 'CSRF verification failed.');
    }
    header('Location: /admin/skills.php');
    exit;
}

// Process Add/Edit POST
if (Request::isPost()) {
    if (!Request::checkCsrf()) {
        set_flash('error', 'CSRF verification failed.');
        header('Location: /admin/skills.php');
        exit;
    }

    $subAction = Request::post('sub_action');

    // Handle adding new Category
    if ($subAction === 'create_category') {
        $name = Request::post('category_name');
        if (!empty($name)) {
            Skill::createCategory($name, slugify($name), 10);
            log_activity('CREATE_SKILL_CATEGORY', "Created skill category: {$name}");
            set_flash('success', "Category '{$name}' created.");
        }
        header('Location: /admin/skills.php');
        exit;
    }

    // Handle Skill Save (Create or Update)
    $data = [
        'category_id' => (int) Request::post('category_id'),
        'name' => Request::post('name'),
        'proficiency' => (int) Request::post('proficiency', 80),
        'icon' => Request::post('icon', 'code'),
        'is_featured' => Request::post('is_featured') ? 1 : 0,
        'sort_order' => (int) Request::post('sort_order', 0),
    ];

    $editId = (int) Request::post('skill_id', 0);

    if ($editId > 0) {
        Skill::update($editId, $data);
        log_activity('UPDATE_SKILL', "Updated skill: {$data['name']} (ID #{$editId})");
        set_flash('success', "Skill '{$data['name']}' updated.");
    } else {
        Skill::create($data);
        log_activity('CREATE_SKILL', "Created skill: {$data['name']}");
        set_flash('success', "Skill '{$data['name']}' created successfully.");
    }

    header('Location: /admin/skills.php');
    exit;
}

$skills = Skill::all();
$categories = Skill::getCategories();
$editSkill = ($action === 'edit' && $id > 0) ? Skill::find($id) : null;

View::render('admin.skills', [
    'pageTitle' => 'Skills & Technical Competencies',
    'skills' => $skills,
    'categories' => $categories,
    'editSkill' => $editSkill,
], 'admin');

