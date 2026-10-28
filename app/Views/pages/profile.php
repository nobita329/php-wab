<?php
$user = auth_user();
?>
<div class="section-padding" style="padding-top: 140px;">
  <div class="container" style="max-width: 680px;">
    <div class="section-header">
      <span class="section-tag">Account</span>
      <h1 class="section-title">User Profile</h1>
      <p class="section-desc">Manage your profile details and security credentials.</p>
    </div>

    <div class="glass-panel" style="padding: 2.5rem;">
      <?php if (has_flash('success')): ?>
        <?php foreach (flash('success') as $s): ?>
          <div class="alert alert-success"><?= e($s) ?></div>
        <?php endforeach; ?>
      <?php endif; ?>

      <?php if (has_flash('error')): ?>
        <?php foreach (flash('error') as $err): ?>
          <div class="alert alert-error"><?= e($err) ?></div>
        <?php endforeach; ?>
      <?php endif; ?>

      <form action="/profile.php" method="POST">
        <?= csrf_field() ?>

        <div class="form-group">
          <label class="form-label" for="prof_name">Full Name</label>
          <input type="text" id="prof_name" name="name" value="<?= e($user['name']) ?>" class="form-control" required>
        </div>

        <div class="form-group">
          <label class="form-label" for="prof_email">Email Address</label>
          <input type="email" id="prof_email" name="email" value="<?= e($user['email']) ?>" class="form-control" required>
        </div>

        <div class="form-group">
          <label class="form-label" for="prof_bio">Personal Bio</label>
          <textarea id="prof_bio" name="bio" class="form-control" style="min-height: 90px;"><?= e($user['bio'] ?? '') ?></textarea>
        </div>

        <hr style="border: 0; border-top: 1px solid var(--border-glass); margin: 2rem 0;">

        <h3 style="font-size: 1.15rem; margin-bottom: 1.25rem;">Change Password (leave blank to keep current)</h3>

        <div class="form-group">
          <label class="form-label" for="prof_curr_pwd">Current Password</label>
          <input type="password" id="prof_curr_pwd" name="current_password" class="form-control" placeholder="Required only if changing password">
        </div>

        <div class="form-group">
          <label class="form-label" for="prof_new_pwd">New Password</label>
          <input type="password" id="prof_new_pwd" name="new_password" class="form-control" placeholder="Minimum 8 characters">
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top: 1rem; width: 100%;">
          <span>Save Changes</span>
        </button>
      </form>
    </div>
  </div>
</div>

