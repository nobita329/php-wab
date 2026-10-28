<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';

use App\Models\Message;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;

if (Request::isPost()) {
    // 1. Honeypot check for bots
    $hp = Request::post('website_hp');
    if (!empty($hp)) {
        if (Request::isAjax()) {
            Response::json(['success' => true, 'message' => 'Transmission received.']);
        }
        Session::flash('success', 'Your message has been transmitted successfully.');
        header('Location: /contact.php');
        exit;
    }

    // 2. CSRF Token verification
    if (!Request::checkCsrf()) {
        if (Request::isAjax()) {
            Response::json(['success' => false, 'error' => 'Security token invalid. Please refresh the page and retry.'], 403);
        }
        Session::flash('error', 'Security token invalid. Please refresh the page.');
        header('Location: /contact.php');
        exit;
    }

    // 3. Validation
    [$data, $errors] = Request::validate([
        'name' => 'required|min:2|max:100',
        'email' => 'required|email|max:150',
        'subject' => 'required|min:3|max:180',
        'message' => 'required|min:10|max:5000',
    ]);

    if (!empty($errors)) {
        $firstError = reset($errors);
        if (Request::isAjax()) {
            Response::json(['success' => false, 'error' => $firstError], 422);
        }
        Session::flash('error', $firstError);
        header('Location: /contact.php');
        exit;
    }

    // 4. Store in Database
    $msgId = Message::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'subject' => $data['subject'],
        'message' => $data['message'],
        'ip_address' => Request::ip(),
        'user_agent' => Request::userAgent(),
    ]);

    // 5. Optional Email Notification to Admin
    $notifyEmail = setting('contact_email');
    if ($notifyEmail && filter_var($notifyEmail, FILTER_VALIDATE_EMAIL)) {
        $mailSubject = "[Portfolio Inquiry] " . $data['subject'];
        $mailHeaders = "From: " . $data['name'] . " <" . $data['email'] . ">\r\n" .
                       "Reply-To: " . $data['email'] . "\r\n" .
                       "X-Mailer: PHP/" . phpversion();
        @mail($notifyEmail, $mailSubject, $data['message'], $mailHeaders);
    }

    if (Request::isAjax()) {
        Response::json([
            'success' => true,
            'message' => 'Your transmission has been logged successfully. I will review and reply to ' . e($data['email']) . ' shortly.'
        ]);
    }

    Session::flash('success', 'Your message has been transmitted successfully! I will respond shortly.');
    header('Location: /contact.php');
    exit;
}

View::render('pages.contact', [
    'pageTitle' => 'Initiate Contact | ' . setting('site_name', 'Alex Vance'),
    'metaDescription' => 'Get in touch for contract engineering, 3D WebGL development, or consulting inquiries.',
], 'main');

