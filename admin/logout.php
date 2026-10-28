<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';

use App\Core\Auth;
use App\Core\Session;

Auth::logout();
Session::flash('success', 'You have been securely signed out.');
header('Location: /admin/login.php');
exit;

