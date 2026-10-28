<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';
\App\Core\Auth::requireAdmin();

use App\Models\User;
use App\Core\Auth;
use App\Core\Request;
use App\Core\View;

$action = Request::get('action', 'list');
$id = (int) Request::get('id', 0);

// Delete User
if ($action === 'delete' && $id > 0) {
    if (Request::checkCsrf()) {
        if ($id === Auth::id()) {
            set_flash('error', 'Security Violation: You cannot delete your own active administrator account.');
        } else {
            $u = User::find($id);
            if ($u) {
                User::delete($id);
                log_activity('DELETE_USER', "Deleted user: {$u['email']} (ID #{$id})");
                set_flash('success', "User {$u['name']} removed.");
            }
        }
    } else {
        set_flash('error', 'CSRF verification failed.');
    }
    header('Location: /admin/users.php');
    exit;
}

// POST Create or Update
if (Request::isPost()) {
    if (!Request::checkCsrf()) {
        set_flash('error', 'CSRF verification failed.');
        header('Location: /admin/users.php');
        exit;
    }

    $editId = (int) Request::post('user_id', 0);
    $email = Request::post('email');
    $name = Request::post('name');
    $role = Request::post('role', 'user');
    $status = Request::post('status', 'active');
    $pwd = Request::post('password');

    if ($editId > 0) {
        $data = [
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'status' => $status,
        ];
        if (!empty($pwd)) {
            $data['password'] = $pwd;
        }
        User::update($editId, $data);
        log_activity('UPDATE_USER', "Updated user account: {$email} (ID #{$editId})");
        set_flash('success', "User {$name} updated.");
    } else {
        if (empty($pwd)) {
            set_flash('error', 'Password is required when creating a new user.');
            header('Location: /admin/users.php');
            exit;
        }

        $existing = User::findByEmail($email);
        if ($existing) {
            set_flash('error', "A user with email {$email} already exists.");
            header('Location: /admin/users.php');
            exit;
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => $pwd,
            'role' => $role,
            'status' => $status,
        ]);
        log_activity('CREATE_USER', "Created user account: {$email}");
        set_flash('success', "User account {$name} created successfully.");
    }

    header('Location: /admin/users.php');
    exit;
}

$users = User::all();
$editItem = ($action === 'edit' && $id > 0) ? User::find($id) : null;

View::render('admin.users', [
    'pageTitle' => 'User & Role Management',
    'users' => $users,
    'editItem' => $editItem,
], 'admin');

