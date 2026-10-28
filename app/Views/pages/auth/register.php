<div style="text-align: center; margin-bottom: 2rem;">
  <div style="display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 800; font-size: 1.25rem; color: #fff; margin-bottom: 0.5rem;">
    <span class="logo-dot"></span>
    <span><?= e(setting('logo_text', 'ALEX.VANCE')) ?></span>
  </div>
  <h2 style="font-size: 1.5rem; margin-top: 0.5rem; font-weight: 700;">Account Registration</h2>
  <p style="font-size: 0.9rem; color: var(--text-muted); margin-top: 0.25rem;">Create a member profile to interact with the portfolio</p>
</div>

<?php if (has_flash('error')): ?>
  <?php foreach (flash('error') as $err): ?>
    <div class="alert alert-error"><?= e($err) ?></div>
  <?php endforeach; ?>
<?php endif; ?>

<form action="/register.php" method="POST">
  <?= csrf_field() ?>

  <div class="form-group">
    <label class="form-label" for="reg_name">Full Name *</label>
    <input type="text" id="reg_name" name="name" value="<?= e(old('name')) ?>" class="form-control" placeholder="e.g. Jordan Hayes" required autofocus>
  </div>

  <div class="form-group">
    <label class="form-label" for="reg_email">Email Address *</label>
    <input type="email" id="reg_email" name="email" value="<?= e(old('email')) ?>" class="form-control" placeholder="jordan@company.com" required>
  </div>

  <div class="form-group">
    <label class="form-label" for="reg_password">Password *</label>
    <input type="password" id="reg_password" name="password" class="form-control" placeholder="Minimum 8 characters" required minlength="8">
  </div>

  <div class="form-group">
    <label class="form-label" for="reg_confirm_password">Confirm Password *</label>
    <input type="password" id="reg_confirm_password" name="password_confirmation" class="form-control" placeholder="Re-type password" required minlength="8">
  </div>

  <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem; padding: 0.85rem;">
    <span>Create Account</span>
    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
  </button>
</form>

<div style="text-align: center; margin-top: 1.75rem; font-size: 0.88rem; color: var(--text-muted);">
  Already have an account? <a href="/login.php" style="color: var(--primary); font-weight: 600;">Sign In</a>
</div>

<div style="text-align: center; margin-top: 1rem;">
  <a href="/" style="font-size: 0.85rem; color: var(--text-dim); text-decoration: underline;">← Return to Public Portfolio</a>
</div>

