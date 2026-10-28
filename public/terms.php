<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';

use App\Core\View;

View::render('pages.terms', [
    'pageTitle' => 'Terms of Service | ' . setting('site_name', 'Alex Vance'),
], 'main');

