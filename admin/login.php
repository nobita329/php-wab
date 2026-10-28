<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';

use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;
use App\Core\View;

if (Auth::isAdmin()) {
    header('Location: /admin/index.php');
    exit;
}

if (Request::isPost()) {
    if (!Request::checkCsrf()) {
        Session::flash('error', 'Security token mismatch. Please try again.');
        header('Location: /admin/login.php');
        exit;
    }

    $email = Request::post('email');
    $password = Request::post('password');
    $ip = Request::ip();

    $result = Auth::attempt($email, $password, $ip);

    if ($result['success']) {
        if (Auth::isAdmin()) {
            Session::flash('success', "Welcome back, " . $result['user']['name'] . "!");
            header('Location: /admin/index.php');
            exit;
        } else {
            Session::flash('error', 'Access restricted: User account does not hold administrative clearance.');
            Auth::logout();
            header('Location: /admin/login.php');
            exit;
        }
    } else {
        Session::flash('error', $result['message']);
        header('Location: /admin/login.php');
        exit;
    }
}

View::render('pages.auth.login', [
    'pageTitle' => 'Administrator Access Portal',
], 'auth');

