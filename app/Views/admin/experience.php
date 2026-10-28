<div class="page-header">
  <div>
    <h1 class="page-title">Work Experience Timeline</h1>
    <p class="page-subtitle">Manage company roles, tenures, achievements, and career trajectory milestones.</p>
  </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1.6fr; gap: 2rem;">
  <!-- Experience Form -->
  <div>
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title"><?= $editItem ? 'Edit Role: ' . e($editItem['company']) : '+ Add Experience Record' ?></h3>
        <?php if ($editItem): ?>
          <a href="/admin/experience.php" class="btn btn-outline btn-sm">Cancel</a>
        <?php endif; ?>
      </div>
      <div class="admin-card-body">
        <form action="/admin/experience.php" method="POST">
          <?= csrf_field() ?>
          <input type="hidden" name="experience_id" value="<?= $editItem ? (int)$editItem['id'] : 0 ?>">

          <div class="admin-form-group">
            <label class="admin-form-label" for="exp_company">Company / Organization *</label>
            <input type="text" id="exp_company" name="company" class="admin-input" value="<?= e($editItem['company'] ?? '') ?>" placeholder="e.g. Vortex Labs Inc." required>
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="exp_position">Job Title / Position *</label>
            <input type="text" id="exp_position" name="position" class="admin-input" value="<?= e($editItem['position'] ?? '') ?>" placeholder="e.g. Lead Creative Technologist" required>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="admin-form-group">
              <label class="admin-form-label" for="exp_type">Type</label>
              <input type="text" id="exp_type" name="employment_type" class="admin-input" value="<?= e($editItem['employment_type'] ?? 'Full-time') ?>" placeholder="Full-time, Contract, etc.">
            </div>
            <div class="admin-form-group">
              <label class="admin-form-label" for="exp_location">Location</label>
              <input type="text" id="exp_location" name="location" class="admin-input" value="<?= e($editItem['location'] ?? '') ?>" placeholder="San Francisco / Remote">
            </div>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="admin-form-group">
              <label class="admin-form-label" for="exp_start">Start Date *</label>
              <input type="text" id="exp_start" name="start_date" class="admin-input" value="<?= e($editItem['start_date'] ?? '') ?>" placeholder="2023 or Jan 2023" required>
            </div>
            <div class="admin-form-group">
              <label class="admin-form-label" for="exp_end">End Date</label>
              <input type="text" id="exp_end" name="end_date" class="admin-input" value="<?= e($editItem['end_date'] ?? '') ?>" placeholder="Present or Dec 2024">
            </div>
          </div>

          <div class="admin-form-group">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
              <input type="checkbox" name="is_current" value="1" <?= ($editItem && $editItem['is_current']) ? 'checked' : '' ?>>
              <span>I currently work here</span>
            </label>
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="exp_desc">Description / Achievements</label>
            <textarea id="exp_desc" name="description" class="admin-textarea" rows="4" placeholder="Key responsibilities and engineering accomplishments..."><?= e($editItem['description'] ?? '') ?></textarea>
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="exp_sort">Display Sort Order</label>
            <input type="number" id="exp_sort" name="sort_order" class="admin-input" value="<?= e($editItem['sort_order'] ?? '0') ?>">
          </div>

          <button type="submit" class="btn btn-primary" style="width: 100%;">
            <span><?= $editItem ? 'Save Experience' : '+ Add Record' ?></span>
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Experience Table -->
  <div>
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title">Recorded Experience History (<?= count($experiences) ?>)</h3>
      </div>
      <div class="table-responsive">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Company & Role</th>
              <th>Period</th>
              <th>Location</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($experiences)): ?>
              <tr>
                <td colspan="4" style="text-align: center; color: var(--admin-muted);">No experience entries found.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($experiences as $item): ?>
                <tr>
                  <td>
                    <strong><?= e($item['company']) ?></strong>
                    <div style="font-size: 0.85rem; color: var(--admin-primary);"><?= e($item['position']) ?></div>
                  </td>
                  <td style="font-family: var(--font-mono); font-size: 0.82rem; color: var(--admin-muted);">
                    <?= e($item['start_date']) ?> — <?= e($item['end_date']) ?>
                  </td>
                  <td><?= e($item['location']) ?></td>
                  <td>
                    <div style="display: flex; gap: 0.5rem;">
                      <a href="/admin/experience.php?action=edit&id=<?= $item['id'] ?>" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;">Edit</a>
                      <a href="/admin/experience.php?action=delete&id=<?= $item['id'] ?>&csrf_token=<?= csrf_token() ?>" class="btn btn-outline btn-sm confirm-delete" style="padding: 0.25rem 0.6rem; font-size: 0.75rem; border-color: rgba(255,0,85,0.3); color: #ff0055;" data-confirm="Delete experience at '<?= e($item['company']) ?>'?">Delete</a>
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

