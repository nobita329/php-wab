<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';
\App\Core\Auth::requireAdmin();

use App\Models\Message;
use App\Core\Request;
use App\Core\View;

$action = Request::get('action', 'list');
$id = (int) Request::get('id', 0);
$filter = Request::get('filter');

// Delete Message
if ($action === 'delete' && $id > 0) {
    if (Request::checkCsrf()) {
        Message::delete($id);
        log_activity('DELETE_MESSAGE', "Deleted message ID #{$id}");
        set_flash('success', 'Message deleted.');
    } else {
        set_flash('error', 'CSRF verification failed.');
    }
    header('Location: /admin/messages.php');
    exit;
}

// Mark Read / Unread
if ($action === 'mark_read' && $id > 0) {
    if (Request::checkCsrf()) {
        $readState = (bool) Request::get('state', 1);
        Message::markRead($id, $readState);
        set_flash('success', $readState ? 'Message marked as read.' : 'Message marked as unread.');
    }
    header('Location: /admin/messages.php');
    exit;
}

// Mark Replied
if ($action === 'mark_replied' && $id > 0) {
    if (Request::checkCsrf()) {
        $repliedState = (bool) Request::get('state', 1);
        Message::markReplied($id, $repliedState);
        set_flash('success', $repliedState ? 'Marked as replied.' : 'Marked as unreplied.');
    }
    header('Location: /admin/messages.php');
    exit;
}

$page = max(1, (int) Request::get('page', 1));
$limit = 20;
$offset = ($page - 1) * $limit;

$messages = Message::all($limit, $offset, $filter);
$totalMessages = Message::count($filter);
$totalPages = ceil($totalMessages / $limit);

$viewMessage = ($action === 'view' && $id > 0) ? Message::find($id) : null;
if ($viewMessage && !$viewMessage['is_read']) {
    Message::markRead($id, true);
}

View::render('admin.messages', [
    'pageTitle' => 'Contact Inquiries & Communications',
    'messages' => $messages,
    'totalMessages' => $totalMessages,
    'totalPages' => $totalPages,
    'currentPage' => $page,
    'filter' => $filter,
    'viewMessage' => $viewMessage,
], 'admin');

