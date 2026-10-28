<div class="page-header">
  <div>
    <h1 class="page-title">Services & Offerings Management</h1>
    <p class="page-subtitle">Configure engineering service cards, custom iconography, deliverables, and active status.</p>
  </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1.6fr; gap: 2rem;">
  <!-- Form -->
  <div>
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title"><?= $editItem ? 'Edit Service: ' . e($editItem['title']) : '+ Add New Service' ?></h3>
        <?php if ($editItem): ?>
          <a href="/admin/services.php" class="btn btn-outline btn-sm">Cancel</a>
        <?php endif; ?>
      </div>
      <div class="admin-card-body">
        <form action="/admin/services.php" method="POST">
          <?= csrf_field() ?>
          <input type="hidden" name="service_id" value="<?= $editItem ? (int)$editItem['id'] : 0 ?>">

          <div class="admin-form-group">
            <label class="admin-form-label" for="srv_title">Service Title *</label>
            <input type="text" id="srv_title" name="title" class="admin-input" value="<?= e($editItem['title'] ?? '') ?>" placeholder="e.g. Interactive 3D Web & WebGL" required>
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="srv_icon">Icon Key</label>
            <select id="srv_icon" name="icon" class="admin-select">
              <option value="box" <?= ($editItem && $editItem['icon'] === 'box') ? 'selected' : '' ?>>box (3D WebGL)</option>
              <option value="server" <?= ($editItem && $editItem['icon'] === 'server') ? 'selected' : '' ?>>server (Full-Stack Backend)</option>
              <option value="layout" <?= ($editItem && $editItem['icon'] === 'layout') ? 'selected' : '' ?>>layout (CMS & UI)</option>
              <option value="zap" <?= ($editItem && $editItem['icon'] === 'zap') ? 'selected' : '' ?>>zap (Performance & Optimization)</option>
              <option value="code" <?= ($editItem && $editItem['icon'] === 'code') ? 'selected' : '' ?>>code (Software Architecture)</option>
              <option value="shield" <?= ($editItem && $editItem['icon'] === 'shield') ? 'selected' : '' ?>>shield (Security & Audits)</option>
            </select>
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="srv_summary">Service Summary *</label>
            <textarea id="srv_summary" name="summary" class="admin-textarea" rows="3" placeholder="High level value proposition..." required><?= e($editItem['summary'] ?? '') ?></textarea>
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="srv_features">Deliverables Checklist (one item per line)</label>
            <textarea id="srv_features" name="features" class="admin-textarea" rows="4" placeholder="Custom Three.js shaders&#10;Mobile 60 FPS tuning&#10;WebGL fallback"><?= e($editItem['features'] ?? '') ?></textarea>
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="srv_sort">Sort Order</label>
            <input type="number" id="srv_sort" name="sort_order" class="admin-input" value="<?= e($editItem['sort_order'] ?? '0') ?>">
          </div>

          <div class="admin-form-group">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
              <input type="checkbox" name="is_active" value="1" <?= (!$editItem || $editItem['is_active']) ? 'checked' : '' ?>>
              <span>Active (Visible on public portfolio)</span>
            </label>
          </div>

          <button type="submit" class="btn btn-primary" style="width: 100%;">
            <span><?= $editItem ? 'Save Service' : '+ Add Service' ?></span>
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Table -->
  <div>
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title">Configured Services (<?= count($services) ?>)</h3>
      </div>
      <div class="table-responsive">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Service</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($services)): ?>
              <tr>
                <td colspan="3" style="text-align: center; color: var(--admin-muted);">No services created.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($services as $srv): ?>
                <tr>
                  <td>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                      <div style="width: 28px; height: 28px; color: var(--admin-primary);">
                        <?= render_icon($srv['icon'] ?? 'code') ?>
                      </div>
                      <div>
                        <strong><?= e($srv['title']) ?></strong>
                        <div style="font-size: 0.8rem; color: var(--admin-muted); max-width: 280px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                          <?= e($srv['summary']) ?>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <?= $srv['is_active'] ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-warning">Disabled</span>' ?>
                  </td>
                  <td>
                    <div style="display: flex; gap: 0.5rem;">
                      <a href="/admin/services.php?action=edit&id=<?= $srv['id'] ?>" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;">Edit</a>
                      <a href="/admin/services.php?action=delete&id=<?= $srv['id'] ?>&csrf_token=<?= csrf_token() ?>" class="btn btn-outline btn-sm confirm-delete" style="padding: 0.25rem 0.6rem; font-size: 0.75rem; border-color: rgba(255,0,85,0.3); color: #ff0055;" data-confirm="Delete '<?= e($srv['title']) ?>'?">Delete</a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

