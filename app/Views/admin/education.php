<div class="page-header">
  <div>
    <h1 class="page-title">Education & Certifications</h1>
    <p class="page-subtitle">Manage university degrees, professional certifications, and academic achievements.</p>
  </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1.6fr; gap: 2rem;">
  <!-- Form -->
  <div>
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title"><?= $editItem ? 'Edit Credential: ' . e($editItem['institution']) : '+ Add Credential' ?></h3>
        <?php if ($editItem): ?>
          <a href="/admin/education.php" class="btn btn-outline btn-sm">Cancel</a>
        <?php endif; ?>
      </div>
      <div class="admin-card-body">
        <form action="/admin/education.php" method="POST">
          <?= csrf_field() ?>
          <input type="hidden" name="education_id" value="<?= $editItem ? (int)$editItem['id'] : 0 ?>">

          <div class="admin-form-group">
            <label class="admin-form-label" for="edu_inst">Institution / Academy *</label>
            <input type="text" id="edu_inst" name="institution" class="admin-input" value="<?= e($editItem['institution'] ?? '') ?>" placeholder="e.g. UC Berkeley" required>
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="edu_degree">Degree or Credential *</label>
            <input type="text" id="edu_degree" name="degree" class="admin-input" value="<?= e($editItem['degree'] ?? '') ?>" placeholder="e.g. Bachelor of Science (B.S.)" required>
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="edu_field">Field of Study / Specialization</label>
            <input type="text" id="edu_field" name="field_of_study" class="admin-input" value="<?= e($editItem['field_of_study'] ?? '') ?>" placeholder="Computer Science & HCI">
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="admin-form-group">
              <label class="admin-form-label" for="edu_start">Start Year *</label>
              <input type="text" id="edu_start" name="start_date" class="admin-input" value="<?= e($editItem['start_date'] ?? '') ?>" placeholder="2013" required>
            </div>
            <div class="admin-form-group">
              <label class="admin-form-label" for="edu_end">Graduation Year</label>
              <input type="text" id="edu_end" name="end_date" class="admin-input" value="<?= e($editItem['end_date'] ?? '') ?>" placeholder="2017">
            </div>
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="edu_grade">GPA / Honors / Credential ID</label>
            <input type="text" id="edu_grade" name="grade" class="admin-input" value="<?= e($editItem['grade'] ?? '') ?>" placeholder="Magna Cum Laude (3.92 GPA)">
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="edu_desc">Notes / Description</label>
            <textarea id="edu_desc" name="description" class="admin-textarea" rows="3"><?= e($editItem['description'] ?? '') ?></textarea>
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="edu_sort">Display Sort Order</label>
            <input type="number" id="edu_sort" name="sort_order" class="admin-input" value="<?= e($editItem['sort_order'] ?? '0') ?>">
          </div>

          <button type="submit" class="btn btn-primary" style="width: 100%;">
            <span><?= $editItem ? 'Save Credential' : '+ Add Credential' ?></span>
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Table -->
  <div>
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title">Educational Pedigree (<?= count($education) ?>)</h3>
      </div>
      <div class="table-responsive">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Institution & Degree</th>
              <th>Years</th>
              <th>Honors</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($education)): ?>
              <tr>
                <td colspan="4" style="text-align: center; color: var(--admin-muted);">No education records found.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($education as $item): ?>
                <tr>
                  <td>
                    <strong><?= e($item['institution']) ?></strong>
                    <div style="font-size: 0.85rem; color: var(--admin-primary);"><?= e($item['degree']) ?></div>
                  </td>
                  <td style="font-family: var(--font-mono); font-size: 0.82rem; color: var(--admin-muted);">
                    <?= e($item['start_date']) ?> — <?= e($item['end_date']) ?>
                  </td>
                  <td style="font-size: 0.82rem; color: var(--admin-success);"><?= e($item['grade']) ?></td>
                  <td>
                    <div style="display: flex; gap: 0.5rem;">
                      <a href="/admin/education.php?action=edit&id=<?= $item['id'] ?>" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;">Edit</a>
                      <a href="/admin/education.php?action=delete&id=<?= $item['id'] ?>&csrf_token=<?= csrf_token() ?>" class="btn btn-outline btn-sm confirm-delete" style="padding: 0.25rem 0.6rem; font-size: 0.75rem; border-color: rgba(255,0,85,0.3); color: #ff0055;" data-confirm="Delete '<?= e($item['institution']) ?>'?">Delete</a>
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

