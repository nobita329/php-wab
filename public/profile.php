<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';

use App\Models\User;
use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;
use App\Core\View;

Auth::requireAuth();
$user = Auth::user();

if (Request::isPost()) {
    if (!Request::checkCsrf()) {
        Session::flash('error', 'CSRF verification failed.');
        header('Location: /profile.php');
        exit;
    }

    $name = Request::post('name');
    $email = Request::post('email');
    $bio = Request::post('bio');
    $currPwd = Request::post('current_password');
    $newPwd = Request::post('new_password');

    $updateData = [
        'name' => $name,
        'email' => $email,
        'bio' => $bio,
    ];

    if (!empty($newPwd)) {
        $pdo = \App\Core\Database::connect();
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$user['id']]);
        $hash = $stmt->fetchColumn();

        if (!password_verify($currPwd, $hash)) {
            Session::flash('error', 'Current password was incorrect. Profile not updated.');
            header('Location: /profile.php');
            exit;
        }

        if (strlen($newPwd) < 8) {
            Session::flash('error', 'New password must be at least 8 characters.');
            header('Location: /profile.php');
            exit;
        }

        $updateData['password'] = $newPwd;
    }

    User::update($user['id'], $updateData);
    Session::flash('success', 'Profile updated successfully.');
    header('Location: /profile.php');
    exit;
}

View::render('pages.profile', [
    'pageTitle' => 'My Profile | ' . setting('site_name', 'Alex Vance'),
], 'main');

