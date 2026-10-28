<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';

use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;
use App\Core\View;

if (Auth::check()) {
    if (Auth::isAdmin()) {
        header('Location: /admin/index.php');
    } else {
        header('Location: /profile.php');
    }
    exit;
}

if (Request::isPost()) {
    if (!Request::checkCsrf()) {
        Session::flash('error', 'Security token mismatch. Please try again.');
        header('Location: /login.php');
        exit;
    }

    $email = Request::post('email');
    $password = Request::post('password');
    $ip = Request::ip();

    $result = Auth::attempt($email, $password, $ip);

    if ($result['success']) {
        Session::flash('success', "Welcome, " . $result['user']['name'] . "!");
        if (Auth::isAdmin()) {
            header('Location: /admin/index.php');
        } else {
            header('Location: /profile.php');
        }
        exit;
    } else {
        Session::flash('error', $result['message']);
        header('Location: /login.php');
        exit;
    }
}

View::render('pages.auth.login', [
    'pageTitle' => 'Member Login',
], 'auth');

