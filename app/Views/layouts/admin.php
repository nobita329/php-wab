<?php
use App\Models\Message;
$unreadCount = Message::count('unread');
$user = auth_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle ?? 'Admin Control Hub') ?> | <?= e(setting('site_name', 'Alex Vance')) ?></title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="<?= asset('/assets/css/style.css') ?>">
  <link rel="stylesheet" href="<?= asset('/assets/css/admin.css') ?>">
</head>
<body class="admin-body">
  <div class="admin-wrapper">
    <!-- Admin Sidebar (21 Menu Items) -->
    <?php require dirname(__DIR__) . '/partials/admin-sidebar.php'; ?>

    <!-- Main Command Deck -->
    <div class="admin-main">
      <!-- Topbar -->
      <header class="admin-topbar">
        <div class="topbar-left">
          <button class="sidebar-toggle-btn" aria-label="Toggle Navigation">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
          </button>
          <a href="/" target="_blank" class="btn btn-outline btn-sm" style="font-size: 0.8rem;">
            <span>View Public Site</span>
            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
          </a>
        </div>

        <div class="topbar-right">
          <?php if ($unreadCount > 0): ?>
            <a href="/admin/messages.php" class="btn btn-outline btn-sm" style="border-color: rgba(255, 0, 85, 0.4); color: #ff0055;">
              <span>✉ <?= $unreadCount ?> New Inquiry</span>
            </a>
          <?php endif; ?>

          <div class="admin-user-pill">
            <div class="admin-avatar" style="display:flex; align-items:center; justify-content:center; font-weight:700; color:#fff; font-size: 0.85rem;">
              <?= strtoupper(substr($user['name'] ?? 'A', 0, 1)) ?>
            </div>
            <div class="admin-user-info">
              <span class="admin-user-name"><?= e($user['name'] ?? 'Admin') ?></span>
              <span class="admin-user-role"><?= e($user['role'] ?? 'admin') ?></span>
            </div>
          </div>

          <a href="/admin/logout.php" class="btn btn-outline btn-sm" title="Sign Out">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
          </a>
        </div>
      </header>

      <!-- Content Area -->
      <main class="admin-content">
        <!-- System Flash Messages -->
        <?php if (has_flash('success')): ?>
          <?php foreach (flash('success') as $succ): ?>
            <div class="alert alert-success">
              <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
              <span><?= e($succ) ?></span>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>

        <?php if (has_flash('error')): ?>
          <?php foreach (flash('error') as $err): ?>
            <div class="alert alert-error">
              <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
              <span><?= e($err) ?></span>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>

        <?= $content ?>
      </main>
    </div>
  </div>

  <!-- Three.js for 3D live setting preview -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
  <script src="<?= asset('/assets/js/admin.js') ?>"></script>
</body>
</html>

