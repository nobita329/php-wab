<div class="page-header">
  <div>
    <h1 class="page-title">Hero Stage Configuration</h1>
    <p class="page-subtitle">Customize the primary headline, subtitles, call-to-action triggers, and badges.</p>
  </div>
</div>

<form action="/admin/hero.php" method="POST" enctype="multipart/form-data">
  <?= csrf_field() ?>

  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">Typography & Copy</h3>
    </div>
    <div class="admin-card-body">
      <div class="admin-form-group">
        <label class="admin-form-label" for="hero_badge">Status Pill Badge</label>
        <input type="text" id="hero_badge" name="hero_badge" class="admin-input" value="<?= e(setting('hero_badge')) ?>" placeholder="Available for High-Impact Projects">
      </div>

      <div class="admin-form-group">
        <label class="admin-form-label" for="hero_heading">Main Headline</label>
        <input type="text" id="hero_heading" name="hero_heading" class="admin-input" value="<?= e(setting('hero_heading')) ?>" required>
      </div>

      <div class="admin-form-group">
        <label class="admin-form-label" for="hero_subtitle">Professional Subtitle / Role</label>
        <input type="text" id="hero_subtitle" name="hero_subtitle" class="admin-input" value="<?= e(setting('hero_subtitle')) ?>" required>
      </div>

      <div class="admin-form-group">
        <label class="admin-form-label" for="hero_description">Introductory Statement</label>
        <textarea id="hero_description" name="hero_description" class="admin-textarea" rows="4"><?= e(setting('hero_description')) ?></textarea>
      </div>

      <div class="admin-form-group">
        <label class="admin-form-label" for="hero_alignment">Content Alignment</label>
        <select id="hero_alignment" name="hero_alignment" class="admin-select">
          <option value="left" <?= setting('hero_alignment') === 'left' ? 'selected' : '' ?>>Left Aligned (Recommended with 3D Center-Right Sculpture)</option>
          <option value="center" <?= setting('hero_alignment') === 'center' ? 'selected' : '' ?>>Center Aligned</option>
        </select>
      </div>
    </div>
  </div>

  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">Call to Action Buttons</h3>
    </div>
    <div class="admin-card-body">
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        <div>
          <h4 style="margin-bottom: 1rem; color: var(--admin-primary); font-size: 1rem;">Primary Action Button</h4>
          <div class="admin-form-group">
            <label class="admin-form-label" for="hero_btn_primary_label">Button Label</label>
            <input type="text" id="hero_btn_primary_label" name="hero_btn_primary_label" class="admin-input" value="<?= e(setting('hero_btn_primary_label')) ?>" required>
          </div>
          <div class="admin-form-group">
            <label class="admin-form-label" for="hero_btn_primary_link">Target URL or Anchor</label>
            <input type="text" id="hero_btn_primary_link" name="hero_btn_primary_link" class="admin-input" value="<?= e(setting('hero_btn_primary_link')) ?>" required>
          </div>
        </div>

        <div>
          <h4 style="margin-bottom: 1rem; color: var(--admin-muted); font-size: 1rem;">Secondary Action Button</h4>
          <div class="admin-form-group">
            <label class="admin-form-label" for="hero_btn_secondary_label">Button Label</label>
            <input type="text" id="hero_btn_secondary_label" name="hero_btn_secondary_label" class="admin-input" value="<?= e(setting('hero_btn_secondary_label')) ?>" required>
          </div>
          <div class="admin-form-group">
            <label class="admin-form-label" for="hero_btn_secondary_link">Target URL or Anchor</label>
            <input type="text" id="hero_btn_secondary_link" name="hero_btn_secondary_link" class="admin-input" value="<?= e(setting('hero_btn_secondary_link')) ?>" required>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div style="display: flex; justify-content: flex-end; margin-bottom: 3rem;">
    <button type="submit" class="btn btn-primary">
      <span>Save Hero Configuration</span>
    </button>
  </div>
</form>

