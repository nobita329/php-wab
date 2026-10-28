<div class="page-header">
  <div>
    <h1 class="page-title">Website Branding & Identity</h1>
    <p class="page-subtitle">Configure website titles, logos, typography tags, coordinates, and social media handles.</p>
  </div>
</div>

<form action="/admin/branding.php" method="POST" enctype="multipart/form-data">
  <?= csrf_field() ?>

  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">Brand Identity</h3>
    </div>
    <div class="admin-card-body">
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <div class="admin-form-group">
          <label class="admin-form-label" for="site_name">Website Name</label>
          <input type="text" id="site_name" name="site_name" class="admin-input" value="<?= e(setting('site_name')) ?>" required>
          <div class="admin-form-hint">Used in browser tabs and meta titles.</div>
        </div>

        <div class="admin-form-group">
          <label class="admin-form-label" for="logo_text">Navbar Logo Text</label>
          <input type="text" id="logo_text" name="logo_text" class="admin-input" value="<?= e(setting('logo_text')) ?>" required>
          <div class="admin-form-hint">Displayed in the futuristic cyber-pill header.</div>
        </div>
      </div>

      <div class="admin-form-group">
        <label class="admin-form-label" for="site_title">Global Browser Title Format</label>
        <input type="text" id="site_title" name="site_title" class="admin-input" value="<?= e(setting('site_title')) ?>" required>
      </div>

      <div class="admin-form-group">
        <label class="admin-form-label" for="site_description">Website Meta Description</label>
        <textarea id="site_description" name="site_description" class="admin-textarea" rows="3"><?= e(setting('site_description')) ?></textarea>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 1.5rem;">
        <!-- Logo Image Upload -->
        <div class="admin-form-group">
          <label class="admin-form-label">Navbar Logo Image (Optional)</label>
          <div class="image-preview-box">
            <img id="logo-preview" src="<?= e(setting('logo_image', '/assets/images/placeholder.png')) ?>" alt="Logo Preview" style="<?= setting('logo_image') ? '' : 'display:none;' ?>">
            <?php if (!setting('logo_image')): ?>
              <span style="font-size: 0.8rem; color: var(--admin-dim);">No Logo Uploaded</span>
            <?php endif; ?>
          </div>
          <input type="file" name="logo_image" accept="image/*" class="admin-input" data-preview="#logo-preview">
        </div>

        <!-- Favicon Image Upload -->
        <div class="admin-form-group">
          <label class="admin-form-label">Favicon (.ico, .png, .svg)</label>
          <div class="image-preview-box" style="width: 80px; height: 80px;">
            <img id="favicon-preview" src="<?= e(setting('favicon_image', '/assets/images/placeholder.png')) ?>" alt="Favicon Preview" style="<?= setting('favicon_image') ? '' : 'display:none;' ?>">
            <?php if (!setting('favicon_image')): ?>
              <span style="font-size: 0.75rem; color: var(--admin-dim);">No Favicon</span>
            <?php endif; ?>
          </div>
          <input type="file" name="favicon_image" accept="image/*" class="admin-input" data-preview="#favicon-preview">
        </div>
      </div>
    </div>
  </div>

  <!-- Contact & Social Coordinates -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">Contact Coordinates & Social Channels</h3>
    </div>
    <div class="admin-card-body">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
        <div class="admin-form-group">
          <label class="admin-form-label" for="contact_email">Public Email Address</label>
          <input type="email" id="contact_email" name="contact_email" class="admin-input" value="<?= e(setting('contact_email')) ?>" required>
        </div>

        <div class="admin-form-group">
          <label class="admin-form-label" for="contact_phone">Telephone / Signal</label>
          <input type="text" id="contact_phone" name="contact_phone" class="admin-input" value="<?= e(setting('contact_phone')) ?>">
        </div>

        <div class="admin-form-group">
          <label class="admin-form-label" for="contact_location">Physical Coordinates / Location</label>
          <input type="text" id="contact_location" name="contact_location" class="admin-input" value="<?= e(setting('contact_location')) ?>">
        </div>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-top: 1rem;">
        <div class="admin-form-group">
          <label class="admin-form-label" for="social_github">GitHub Profile URL</label>
          <input type="url" id="social_github" name="social_github" class="admin-input" value="<?= e(setting('social_github')) ?>">
        </div>

        <div class="admin-form-group">
          <label class="admin-form-label" for="social_linkedin">LinkedIn Profile URL</label>
          <input type="url" id="social_linkedin" name="social_linkedin" class="admin-input" value="<?= e(setting('social_linkedin')) ?>">
        </div>

        <div class="admin-form-group">
          <label class="admin-form-label" for="social_twitter">X / Twitter Profile URL</label>
          <input type="url" id="social_twitter" name="social_twitter" class="admin-input" value="<?= e(setting('social_twitter')) ?>">
        </div>

        <div class="admin-form-group">
          <label class="admin-form-label" for="social_dribbble">Dribbble / Design Portfolio URL</label>
          <input type="url" id="social_dribbble" name="social_dribbble" class="admin-input" value="<?= e(setting('social_dribbble')) ?>">
        </div>
      </div>

      <div class="admin-form-group" style="margin-top: 1rem;">
        <label class="admin-form-label" for="footer_copyright">Footer Copyright Statement</label>
        <input type="text" id="footer_copyright" name="footer_copyright" class="admin-input" value="<?= e(setting('footer_copyright')) ?>">
      </div>
    </div>
  </div>

  <div style="display: flex; justify-content: flex-end; margin-bottom: 3rem;">
    <button type="submit" class="btn btn-primary">
      <span>Save Branding Configuration</span>
    </button>
  </div>
</form>

