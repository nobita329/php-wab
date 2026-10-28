<div class="page-header">
  <div>
    <h1 class="page-title">Security Guard & Authentication Throttling</h1>
    <p class="page-subtitle">Configure brute-force defense limits, session timeout thresholds, and user registration flags.</p>
  </div>
</div>

<form action="/admin/security.php" method="POST">
  <?= csrf_field() ?>

  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">Brute-Force Rate Limiting</h3>
    </div>
    <div class="admin-card-body">
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <div class="admin-form-group">
          <label class="admin-form-label" for="max_login_attempts">Max Failed Attempts Before Lockout</label>
          <input type="number" id="max_login_attempts" name="max_login_attempts" class="admin-input" value="<?= e(setting('max_login_attempts', '5')) ?>" min="1" max="20" required>
          <div class="admin-form-hint">Number of incorrect credentials allowed per IP before temporary block.</div>
        </div>

        <div class="admin-form-group">
          <label class="admin-form-label" for="login_lockout_minutes">Lockout Duration (Minutes)</label>
          <input type="number" id="login_lockout_minutes" name="login_lockout_minutes" class="admin-input" value="<?= e(setting('login_lockout_minutes', '15')) ?>" min="1" max="1440" required>
          <div class="admin-form-hint">Duration an IP or email remains locked out after exceeding threshold.</div>
        </div>
      </div>
    </div>
  </div>

  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">Session Life & Public Registration</h3>
    </div>
    <div class="admin-card-body">
      <div class="admin-form-group">
        <label class="admin-form-label" for="session_lifetime">Session Inactivity Timeout (Seconds)</label>
        <input type="number" id="session_lifetime" name="session_lifetime" class="admin-input" value="<?= e(setting('session_lifetime', '7200')) ?>" min="300" max="86400" required>
        <div class="admin-form-hint">7200 = 2 hours. Automatically expires inactive administrator sessions.</div>
      </div>

      <div class="admin-form-group" style="margin-top: 1.5rem;">
        <label class="admin-form-label" for="allow_user_registration">Public Member Registration</label>
        <select id="allow_user_registration" name="allow_user_registration" class="admin-select">
          <option value="1" <?= setting('allow_user_registration', '1') === '1' ? 'selected' : '' ?>>Enabled (Public visitors can create user accounts)</option>
          <option value="0" <?= setting('allow_user_registration', '1') === '0' ? 'selected' : '' ?>>Disabled (Admin only; invite/create manually)</option>
        </select>
      </div>

      <div style="background: rgba(0, 240, 255, 0.05); border: 1px solid rgba(0, 240, 255, 0.2); border-radius: 8px; padding: 1.25rem; margin-top: 1.5rem;">
        <div style="font-weight: 700; color: var(--admin-primary); margin-bottom: 0.25rem;">Active Defense Protocols</div>
        <p style="font-size: 0.85rem; color: var(--admin-muted); margin: 0;">
          All forms enforce cryptographic CSRF validation tokens. Passwords use modern bcrypt/argon2 hashing. File uploads verify MIME signatures on disk and restrict extensions.
        </p>
      </div>
    </div>
  </div>

  <div style="display: flex; justify-content: flex-end; margin-bottom: 3rem;">
    <button type="submit" class="btn btn-primary">
      <span>Save Security Settings</span>
    </button>
  </div>
</form>

