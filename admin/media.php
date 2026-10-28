<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';
\App\Core\Auth::requireAdmin();

use App\Models\Media;
use App\Core\Request;
use App\Core\Storage;
use App\Core\View;

$action = Request::get('action', 'list');
$id = (int) Request::get('id', 0);

// Delete Media File
if ($action === 'delete' && $id > 0) {
    if (Request::checkCsrf()) {
        $media = Media::find($id);
        if ($media) {
            Storage::delete($media['file_path']);
            Media::delete($id);
            log_activity('DELETE_MEDIA', "Deleted media file: {$media['original_name']}");
            set_flash('success', "Media file '{$media['original_name']}' deleted.");
        }
    } else {
        set_flash('error', 'CSRF verification failed.');
    }
    header('Location: /admin/media.php');
    exit;
}

// Upload Media
if (Request::isPost()) {
    if (!Request::checkCsrf()) {
        set_flash('error', 'CSRF verification failed.');
        header('Location: /admin/media.php');
        exit;
    }

    if (!empty($_FILES['file']['name'])) {
        $upload = Storage::upload($_FILES['file'], true);
        if ($upload['success']) {
            log_activity('UPLOAD_MEDIA', "Uploaded asset: {$upload['filename']}");
            set_flash('success', 'Asset successfully uploaded to Media Library.');
        } else {
            set_flash('error', 'Upload failed: ' . $upload['error']);
        }
    }

    header('Location: /admin/media.php');
    exit;
}

$mediaFiles = Media::all(60);

View::render('admin.media', [
    'pageTitle' => 'Media & Digital Asset Library',
    'mediaFiles' => $mediaFiles,
], 'admin');

