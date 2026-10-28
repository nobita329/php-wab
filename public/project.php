<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';

use App\Models\Project;
use App\Core\View;

$slug = $_GET['slug'] ?? '';
$id = (int)($_GET['id'] ?? 0);

$proj = !empty($slug) ? Project::findBySlug($slug) : ($id > 0 ? Project::find($id) : null);

$pageTitle = $proj ? e($proj['title']) . ' | ' . setting('site_name') : 'Project Case Study';
$metaDesc = $proj ? e($proj['excerpt']) : setting('site_description');

View::render('pages.project-detail', [
    'pageTitle' => $pageTitle,
    'metaDescription' => $metaDesc,
], 'main');

