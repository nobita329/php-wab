<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';
\App\Core\Auth::requireAdmin();

use App\Models\Project;
use App\Models\User;
use App\Models\Message;
use App\Models\ActivityLog;
use App\Core\View;

$stats = [
    'total_projects' => Project::count(),
    'total_users' => User::count(),
    'total_messages' => Message::count(),
    'unread_messages' => Message::count('unread'),
];

$recentMessages = Message::all(5);
$recentProjects = Project::all(false, null, 5);
$recentLogs = ActivityLog::recent(8);

View::render('admin.dashboard', [
    'pageTitle' => 'Command Dashboard',
    'stats' => $stats,
    'recentMessages' => $recentMessages,
    'recentProjects' => $recentProjects,
    'recentLogs' => $recentLogs,
], 'admin');

