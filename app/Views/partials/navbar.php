<?php
use App\Models\Navigation;
$navItems = Navigation::getByLocation('header');
$user = auth_user();
?>
<header class="glass-nav">
  <div class="container nav-container">
    <a href="/" class="nav-logo">
      <span class="logo-dot"></span>
      <span><?= e(setting('logo_text', 'ALEX.VANCE')) ?></span>
    </a>

    <!-- Navigation Links -->
    <ul class="nav-links">
      <?php foreach ($navItems as $item): ?>
        <li>
          <a href="<?= e($item['url']) ?>" target="<?= e($item['target']) ?>" class="nav-link">
            <?= e($item['title']) ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>

    <!-- Actions -->
    <div class="nav-actions">
      <?php if ($user): ?>
        <?php if ($user['role'] === 'admin'): ?>
          <a href="/admin/index.php" class="btn btn-outline btn-sm">Dashboard</a>
        <?php else: ?>
          <a href="/profile.php" class="btn btn-outline btn-sm"><?= e($user['name']) ?></a>
        <?php endif; ?>
        <a href="/logout.php" class="btn btn-outline btn-sm" title="Log Out">⎋</a>
      <?php else: ?>
        <a href="/#contact" class="btn btn-primary btn-sm">Let's Talk</a>
      <?php endif; ?>

      <button class="mobile-toggle" aria-label="Toggle Navigation Menu">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="3" y1="12" x2="21" y2="12"></line>
          <line x1="3" y1="6" x2="21" y2="6"></line>
          <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
      </button>
    </div>
  </div>
</header>

