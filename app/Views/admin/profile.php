<div class="page-header">
  <div>
    <h1 class="page-title">Administrator Profile & Security</h1>
    <p class="page-subtitle">Manage your personal credentials, administrative contact email, and authentication secrets.</p>
  </div>
</div>

<form action="/admin/profile.php" method="POST">
  <?= csrf_field() ?>

  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">Identity & Comms</h3>
    </div>
    <div class="admin-card-body">
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <div class="admin-form-group">
          <label class="admin-form-label" for="prof_name">Full Name</label>
          <input type="text" id="prof_name" name="name" class="admin-input" value="<?= e($user['name']) ?>" required>
        </div>

        <div class="admin-form-group">
          <label class="admin-form-label" for="prof_email">Primary Administrative Email</label>
          <input type="email" id="prof_email" name="email" class="admin-input" value="<?= e($user['email']) ?>" required>
        </div>
      </div>
    </div>
  </div>

  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">Password Mutation (Leave blank to keep existing password)</h3>
    </div>
    <div class="admin-card-body">
      <div class="admin-form-group">
        <label class="admin-form-label" for="prof_curr">Current Password</label>
        <input type="password" id="prof_curr" name="current_password" class="admin-input" placeholder="Required if specifying a new password">
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <div class="admin-form-group">
          <label class="admin-form-label" for="prof_new">New Password (Min 8 characters)</label>
          <input type="password" id="prof_new" name="new_password" class="admin-input" placeholder="••••••••••••">
        </div>

        <div class="admin-form-group">
          <label class="admin-form-label" for="prof_conf">Confirm New Password</label>
          <input type="password" id="prof_conf" name="confirm_password" class="admin-input" placeholder="••••••••••••">
        </div>
      </div>
    </div>
  </div>

  <div style="display: flex; justify-content: flex-end; margin-bottom: 3rem;">
    <button type="submit" class="btn btn-primary">
      <span>Save Profile Changes</span>
    </button>
  </div>
</form>

