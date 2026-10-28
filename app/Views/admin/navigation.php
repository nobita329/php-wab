<div class="page-header">
  <div>
    <h1 class="page-title">Navigation Architecture</h1>
    <p class="page-subtitle">Configure header navbar routes, footer legal anchors, open targets, and ordering.</p>
  </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1.6fr; gap: 2rem;">
  <!-- Form -->
  <div>
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title"><?= $editItem ? 'Edit Link: ' . e($editItem['title']) : '+ Add Navigation Link' ?></h3>
        <?php if ($editItem): ?>
          <a href="/admin/navigation.php" class="btn btn-outline btn-sm">Cancel</a>
        <?php endif; ?>
      </div>
      <div class="admin-card-body">
        <form action="/admin/navigation.php" method="POST">
          <?= csrf_field() ?>
          <input type="hidden" name="nav_id" value="<?= $editItem ? (int)$editItem['id'] : 0 ?>">

          <div class="admin-form-group">
            <label class="admin-form-label" for="nav_title">Label / Title *</label>
            <input type="text" id="nav_title" name="title" class="admin-input" value="<?= e($editItem['title'] ?? '') ?>" placeholder="e.g. Projects" required>
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="nav_url">Target URL / Hash *</label>
            <input type="text" id="nav_url" name="url" class="admin-input" value="<?= e($editItem['url'] ?? '') ?>" placeholder="/projects.php or /#about" required>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="admin-form-group">
              <label class="admin-form-label" for="nav_location">Placement</label>
              <select id="nav_location" name="location" class="admin-select">
                <option value="header" <?= ($editItem && $editItem['location'] === 'header') ? 'selected' : '' ?>>Header Navbar</option>
                <option value="footer" <?= ($editItem && $editItem['location'] === 'footer') ? 'selected' : '' ?>>Footer Only</option>
                <option value="both" <?= ($editItem && $editItem['location'] === 'both') ? 'selected' : '' ?>>Both Header & Footer</option>
              </select>
            </div>

            <div class="admin-form-group">
              <label class="admin-form-label" for="nav_target">Window Target</label>
              <select id="nav_target" name="target" class="admin-select">
                <option value="_self" <?= ($editItem && $editItem['target'] === '_self') ? 'selected' : '' ?>>Same Window (_self)</option>
                <option value="_blank" <?= ($editItem && $editItem['target'] === '_blank') ? 'selected' : '' ?>>New Tab (_blank)</option>
              </select>
            </div>
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="nav_sort">Sort Order Index</label>
            <input type="number" id="nav_sort" name="sort_order" class="admin-input" value="<?= e($editItem['sort_order'] ?? '0') ?>">
          </div>

          <div class="admin-form-group">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
              <input type="checkbox" name="is_active" value="1" <?= (!$editItem || $editItem['is_active']) ? 'checked' : '' ?>>
              <span>Active</span>
            </label>
          </div>

          <button type="submit" class="btn btn-primary" style="width: 100%;">
            <span><?= $editItem ? 'Save Navigation Link' : '+ Add Link' ?></span>
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Table -->
  <div>
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title">Configured Menu Links (<?= count($navItems) ?>)</h3>
      </div>
      <div class="table-responsive">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Title</th>
              <th>Target URL</th>
              <th>Placement</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($navItems as $item): ?>
              <tr>
                <td><strong><?= e($item['title']) ?></strong></td>
                <td><span style="font-family: var(--font-mono); font-size: 0.82rem; color: var(--admin-muted);"><?= e($item['url']) ?></span></td>
                <td><span class="badge badge-info"><?= e($item['location']) ?></span></td>
                <td><?= $item['is_active'] ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Hidden</span>' ?></td>
                <td>
                  <div style="display: flex; gap: 0.5rem;">
                    <a href="/admin/navigation.php?action=edit&id=<?= $item['id'] ?>" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;">Edit</a>
                    <a href="/admin/navigation.php?action=delete&id=<?= $item['id'] ?>&csrf_token=<?= csrf_token() ?>" class="btn btn-outline btn-sm confirm-delete" style="padding: 0.25rem 0.6rem; font-size: 0.75rem; border-color: rgba(255,0,85,0.3); color: #ff0055;" data-confirm="Delete link '<?= e($item['title']) ?>'?">Delete</a>
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

