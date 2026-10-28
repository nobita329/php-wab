<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';
\App\Core\Auth::requireAdmin();

use App\Models\User;
use App\Core\Auth;
use App\Core\Request;
use App\Core\View;

$user = Auth::user();

if (Request::isPost()) {
    if (!Request::checkCsrf()) {
        set_flash('error', 'CSRF verification failed.');
        header('Location: /admin/profile.php');
        exit;
    }

    $name = Request::post('name');
    $email = Request::post('email');
    $currPwd = Request::post('current_password');
    $newPwd = Request::post('new_password');
    $confPwd = Request::post('confirm_password');

    $updateData = [
        'name' => $name,
        'email' => $email,
    ];

    if (!empty($newPwd)) {
        // Must verify current password
        $pdo = \App\Core\Database::connect();
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$user['id']]);
        $hash = $stmt->fetchColumn();

        if (!password_verify($currPwd, $hash)) {
            set_flash('error', 'Current password verification failed. Password was not changed.');
            header('Location: /admin/profile.php');
            exit;
        }

        if (strlen($newPwd) < 8) {
            set_flash('error', 'New password must be at least 8 characters long.');
            header('Location: /admin/profile.php');
            exit;
        }

        if ($newPwd !== $confPwd) {
            set_flash('error', 'New password and confirmation do not match.');
            header('Location: /admin/profile.php');
            exit;
        }

        $updateData['password'] = $newPwd;
    }

    User::update($user['id'], $updateData);
    log_activity('UPDATE_ADMIN_PROFILE', "Administrator {$name} updated personal credentials.");
    set_flash('success', 'Admin profile and credentials updated successfully.');
    header('Location: /admin/profile.php');
    exit;
}

View::render('admin.profile', [
    'pageTitle' => 'Administrator Profile & Security',
    'user' => $user,
], 'admin');

