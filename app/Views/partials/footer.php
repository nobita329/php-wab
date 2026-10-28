<?php
use App\Models\Navigation;
$footerNav = Navigation::getByLocation('footer');
?>
<footer class="site-footer">
  <div class="container">
    <div class="footer-top">
      <div class="footer-brand">
        <a href="/" class="nav-logo">
          <span class="logo-dot"></span>
          <span><?= e(setting('logo_text', 'ALEX.VANCE')) ?></span>
        </a>
        <p><?= e(setting('site_description', 'High-performance interactive 3D portfolio and creative engineering lab.')) ?></p>
        
        <div class="social-links">
          <?php if ($gh = setting('social_github')): ?>
            <a href="<?= e($gh) ?>" target="_blank" rel="noopener" class="social-btn" aria-label="GitHub">
              <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
            </a>
          <?php endif; ?>
          <?php if ($li = setting('social_linkedin')): ?>
            <a href="<?= e($li) ?>" target="_blank" rel="noopener" class="social-btn" aria-label="LinkedIn">
              <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
            </a>
          <?php endif; ?>
          <?php if ($tw = setting('social_twitter')): ?>
            <a href="<?= e($tw) ?>" target="_blank" rel="noopener" class="social-btn" aria-label="X / Twitter">
              <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </a>
          <?php endif; ?>
        </div>
      </div>

      <div class="footer-col">
        <h4 class="footer-title">Navigation</h4>
        <ul class="footer-links">
          <li><a href="/#home">Home</a></li>
          <li><a href="/#about">About</a></li>
          <li><a href="/#skills">Skills</a></li>
          <li><a href="/projects.php">Projects</a></li>
          <li><a href="/#experience">Experience</a></li>
          <li><a href="/#services">Services</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4 class="footer-title">Legal & Security</h4>
        <ul class="footer-links">
          <li><a href="/privacy.php">Privacy Policy</a></li>
          <li><a href="/terms.php">Terms of Service</a></li>
          <li><a href="/admin/login.php">Admin Portal</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4 class="footer-title">Direct Contact</h4>
        <p style="font-size: 0.9rem; margin-bottom: 0.75rem;"><?= e(setting('contact_location', 'San Francisco, CA / Remote')) ?></p>
        <p style="font-size: 0.9rem; color: var(--primary); font-family: var(--font-mono);"><?= e(setting('contact_email', 'alex@cyberpulse.dev')) ?></p>
      </div>
    </div>

    <div class="footer-bottom">
      <div><?= e(setting('footer_copyright', '© 2026 Alex Vance. All rights reserved.')) ?></div>
      <div>Designed with Precision & Three.js 3D Acceleration</div>
    </div>
  </div>
</footer>

