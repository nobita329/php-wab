<div class="page-header">
  <div>
    <h1 class="page-title">User Accounts & Access Control</h1>
    <p class="page-subtitle">Configure administrator accounts, members, access roles, and suspension states.</p>
  </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1.6fr; gap: 2rem;">
  <!-- User Form -->
  <div>
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title"><?= $editItem ? 'Edit User: ' . e($editItem['name']) : '+ Create User Account' ?></h3>
        <?php if ($editItem): ?>
          <a href="/admin/users.php" class="btn btn-outline btn-sm">Cancel</a>
        <?php endif; ?>
      </div>
      <div class="admin-card-body">
        <form action="/admin/users.php" method="POST">
          <?= csrf_field() ?>
          <input type="hidden" name="user_id" value="<?= $editItem ? (int)$editItem['id'] : 0 ?>">

          <div class="admin-form-group">
            <label class="admin-form-label" for="usr_name">Full Name *</label>
            <input type="text" id="usr_name" name="name" class="admin-input" value="<?= e($editItem['name'] ?? '') ?>" required>
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="usr_email">Email Address *</label>
            <input type="email" id="usr_email" name="email" class="admin-input" value="<?= e($editItem['email'] ?? '') ?>" required>
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="usr_pwd"><?= $editItem ? 'New Password (leave blank to retain current)' : 'Password *' ?></label>
            <input type="password" id="usr_pwd" name="password" class="admin-input" placeholder="••••••••••••" <?= $editItem ? '' : 'required minlength="8"' ?>>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="admin-form-group">
              <label class="admin-form-label" for="usr_role">Role Authority</label>
              <select id="usr_role" name="role" class="admin-select">
                <option value="user" <?= ($editItem && $editItem['role'] === 'user') ? 'selected' : '' ?>>Member / User</option>
                <option value="admin" <?= ($editItem && $editItem['role'] === 'admin') ? 'selected' : '' ?>>Administrator</option>
              </select>
            </div>

            <div class="admin-form-group">
              <label class="admin-form-label" for="usr_status">Account Status</label>
              <select id="usr_status" name="status" class="admin-select">
                <option value="active" <?= ($editItem && $editItem['status'] === 'active') ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= ($editItem && $editItem['status'] === 'inactive') ? 'selected' : '' ?>>Suspended</option>
              </select>
            </div>
          </div>

          <button type="submit" class="btn btn-primary" style="width: 100%;">
            <span><?= $editItem ? 'Update User' : '+ Create User' ?></span>
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Users Table -->
  <div>
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title">Registered Accounts (<?= count($users) ?>)</h3>
      </div>
      <div class="table-responsive">
        <table class="admin-table">
          <thead>
            <tr>
              <th>User</th>
              <th>Role</th>
              <th>Status</th>
              <th>Registered</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $u): ?>
              <tr>
                <td>
                  <strong><?= e($u['name']) ?></strong>
                  <div style="font-size: 0.8rem; color: var(--admin-muted); font-family: var(--font-mono);"><?= e($u['email']) ?></div>
                </td>
                <td>
                  <?= $u['role'] === 'admin' ? '<span class="badge badge-info">Admin</span>' : '<span class="badge badge-warning">User</span>' ?>
                </td>
                <td>
                  <?= $u['status'] === 'active' ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Suspended</span>' ?>
                </td>
                <td style="font-family: var(--font-mono); font-size: 0.8rem; color: var(--admin-muted);">
                  <?= format_date($u['created_at'], 'M d, Y') ?>
                </td>
                <td>
                  <div style="display: flex; gap: 0.5rem;">
                    <a href="/admin/users.php?action=edit&id=<?= $u['id'] ?>" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;">Edit</a>
                    <?php if ($u['id'] != auth_user()['id']): ?>
                      <a href="/admin/users.php?action=delete&id=<?= $u['id'] ?>&csrf_token=<?= csrf_token() ?>" class="btn btn-outline btn-sm confirm-delete" style="padding: 0.25rem 0.6rem; font-size: 0.75rem; border-color: rgba(255,0,85,0.3); color: #ff0055;" data-confirm="Delete user '<?= e($u['email']) ?>'?">Delete</a>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

