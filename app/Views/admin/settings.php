<div class="page-header">
  <div>
    <h1 class="page-title">General Application Settings</h1>
    <p class="page-subtitle">Configure maintenance mode locks, application timezones, and system formatting.</p>
  </div>
</div>

<form action="/admin/settings.php" method="POST">
  <?= csrf_field() ?>

  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">Maintenance Mode</h3>
    </div>
    <div class="admin-card-body">
      <div class="admin-form-group">
        <label class="admin-form-label" for="maintenance_mode">Public Maintenance Lock</label>
        <select id="maintenance_mode" name="maintenance_mode" class="admin-select">
          <option value="0" <?= setting('maintenance_mode', '0') === '0' ? 'selected' : '' ?>>Disabled (Site is fully live and public)</option>
          <option value="1" <?= setting('maintenance_mode', '0') === '1' ? 'selected' : '' ?>>Enabled (Displays maintenance notice to public visitors; admin can still access)</option>
        </select>
      </div>

      <div class="admin-form-group">
        <label class="admin-form-label" for="maintenance_message">Public Maintenance Notice Message</label>
        <textarea id="maintenance_message" name="maintenance_message" class="admin-textarea" rows="3"><?= e(setting('maintenance_message', 'Under scheduled quantum maintenance. Back online shortly.')) ?></textarea>
      </div>
    </div>
  </div>

  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">Localization & Date Standards</h3>
    </div>
    <div class="admin-card-body">
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <div class="admin-form-group">
          <label class="admin-form-label" for="app_timezone">Server Timezone</label>
          <input type="text" id="app_timezone" name="app_timezone" class="admin-input" value="<?= e(setting('app_timezone', 'UTC')) ?>" placeholder="UTC, America/Los_Angeles, Europe/London">
        </div>

        <div class="admin-form-group">
          <label class="admin-form-label" for="date_format">Date Display Format</label>
          <select id="date_format" name="date_format" class="admin-select">
            <option value="M d, Y" <?= setting('date_format', 'M d, Y') === 'M d, Y' ? 'selected' : '' ?>>Jan 15, 2026 (M d, Y)</option>
            <option value="Y-m-d" <?= setting('date_format') === 'Y-m-d' ? 'selected' : '' ?>>2026-01-15 (Y-m-d)</option>
            <option value="d/m/Y" <?= setting('date_format') === 'd/m/Y' ? 'selected' : '' ?>>15/01/2026 (d/m/Y)</option>
          </select>
        </div>
      </div>
    </div>
  </div>

  <div style="display: flex; justify-content: flex-end; margin-bottom: 3rem;">
    <button type="submit" class="btn btn-primary">
      <span>Save General Settings</span>
    </button>
  </div>
</form>

