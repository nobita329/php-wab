<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';

use App\Core\Auth;
use App\Core\Session;

Auth::logout();
Session::flash('success', 'You have been logged out.');
header('Location: /login.php');
exit;

