<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';

use App\Models\User;
use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;
use App\Core\View;

if (setting('allow_user_registration', '1') !== '1') {
    Session::flash('error', 'Public registration is currently disabled by administrator.');
    header('Location: /login.php');
    exit;
}

if (Auth::check()) {
    header('Location: /profile.php');
    exit;
}

if (Request::isPost()) {
    if (!Request::checkCsrf()) {
        Session::flash('error', 'Security token mismatch. Please try again.');
        header('Location: /register.php');
        exit;
    }

    $name = Request::post('name');
    $email = Request::post('email');
    $password = Request::post('password');
    $confirmPassword = Request::post('password_confirmation');

    if (empty($name) || empty($email) || empty($password)) {
        Session::flash('error', 'All fields marked with an asterisk are required.');
        header('Location: /register.php');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        Session::flash('error', 'Please enter a valid email address.');
        header('Location: /register.php');
        exit;
    }

    if (strlen($password) < 8) {
        Session::flash('error', 'Password must be at least 8 characters.');
        header('Location: /register.php');
        exit;
    }

    if ($password !== $confirmPassword) {
        Session::flash('error', 'Passwords do not match.');
        header('Location: /register.php');
        exit;
    }

    $existing = User::findByEmail($email);
    if ($existing) {
        Session::flash('error', 'An account with this email address already exists.');
        header('Location: /register.php');
        exit;
    }

    $newId = User::create([
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'role' => 'user',
        'status' => 'active',
    ]);

    log_activity('USER_REGISTER', "New user registered: {$email}", $newId);
    Session::flash('success', 'Your account has been created successfully! Please sign in.');
    header('Location: /login.php');
    exit;
}

View::render('pages.auth.register', [
    'pageTitle' => 'Member Registration',
], 'auth');

