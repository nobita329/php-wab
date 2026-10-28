<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';

use App\Core\View;

View::render('pages.privacy', [
    'pageTitle' => 'Privacy Policy | ' . setting('site_name', 'Alex Vance'),
], 'main');

