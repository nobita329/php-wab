<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';

use App\Core\View;

View::render('pages.projects', [
    'pageTitle' => 'Engineering Archives & Projects | ' . setting('site_name', 'Alex Vance'),
    'metaDescription' => 'Explore full-stack, WebGL 3D, and distributed systems case studies.',
], 'main');

