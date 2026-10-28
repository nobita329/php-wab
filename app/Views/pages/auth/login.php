<div style="text-align: center; margin-bottom: 2rem;">
  <div style="display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 800; font-size: 1.25rem; color: #fff; margin-bottom: 0.5rem;">
    <span class="logo-dot"></span>
    <span><?= e(setting('logo_text', 'ALEX.VANCE')) ?></span>
  </div>
  <h2 style="font-size: 1.5rem; margin-top: 0.5rem; font-weight: 700;">Quantum Access</h2>
  <p style="font-size: 0.9rem; color: var(--text-muted); margin-top: 0.25rem;">Sign in to access dashboard & profile controls</p>
</div>

<?php if (has_flash('error')): ?>
  <?php foreach (flash('error') as $err): ?>
    <div class="alert alert-error"><?= e($err) ?></div>
  <?php endforeach; ?>
<?php endif; ?>

<?php if (has_flash('success')): ?>
  <?php foreach (flash('success') as $succ): ?>
    <div class="alert alert-success"><?= e($succ) ?></div>
  <?php endforeach; ?>
<?php endif; ?>

<form action="/login.php" method="POST">
  <?= csrf_field() ?>

  <div class="form-group">
    <label class="form-label" for="login_email">Email Address</label>
    <input type="email" id="login_email" name="email" value="<?= e(old('email')) ?>" class="form-control" placeholder="name@company.com" required autofocus>
  </div>

  <div class="form-group">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
      <label class="form-label" for="login_password" style="margin-bottom: 0;">Password</label>
    </div>
    <input type="password" id="login_password" name="password" class="form-control" placeholder="••••••••••••" required>
  </div>

  <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem; padding: 0.85rem;">
    <span>Sign In to System</span>
    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
  </button>
</form>

<?php if (setting('allow_user_registration', '1') === '1'): ?>
  <div style="text-align: center; margin-top: 1.75rem; font-size: 0.88rem; color: var(--text-muted);">
    Don't have an account? <a href="/register.php" style="color: var(--primary); font-weight: 600;">Create One</a>
  </div>
<?php endif; ?>

<div style="text-align: center; margin-top: 1rem;">
  <a href="/" style="font-size: 0.85rem; color: var(--text-dim); text-decoration: underline;">← Return to Public Portfolio</a>
</div>

