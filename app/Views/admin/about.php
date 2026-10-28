<div class="page-header">
  <div>
    <h1 class="page-title">About Section Management</h1>
    <p class="page-subtitle">Refine your biographical introduction, career milestone counters, and downloadable CV.</p>
  </div>
</div>

<form action="/admin/about.php" method="POST" enctype="multipart/form-data">
  <?= csrf_field() ?>

  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">Biographical Narrative</h3>
    </div>
    <div class="admin-card-body">
      <div class="admin-form-group">
        <label class="admin-form-label" for="about_heading">Section Heading</label>
        <input type="text" id="about_heading" name="about_heading" class="admin-input" value="<?= e(setting('about_heading')) ?>" required>
      </div>

      <div class="admin-form-group">
        <label class="admin-form-label" for="about_bio_p1">Lead Bio Paragraph (Hero Typography)</label>
        <textarea id="about_bio_p1" name="about_bio_p1" class="admin-textarea" rows="4"><?= e(setting('about_bio_p1')) ?></textarea>
      </div>

      <div class="admin-form-group">
        <label class="admin-form-label" for="about_bio_p2">Secondary Details Paragraph</label>
        <textarea id="about_bio_p2" name="about_bio_p2" class="admin-textarea" rows="4"><?= e(setting('about_bio_p2')) ?></textarea>
      </div>
    </div>
  </div>

  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">Live Key Performance Metrics</h3>
    </div>
    <div class="admin-card-body">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem;">
        <div class="admin-form-group">
          <label class="admin-form-label" for="about_years_exp">Years of Experience</label>
          <input type="text" id="about_years_exp" name="about_years_exp" class="admin-input" value="<?= e(setting('about_years_exp')) ?>" required>
        </div>

        <div class="admin-form-group">
          <label class="admin-form-label" for="about_projects_completed">Shipped Projects</label>
          <input type="text" id="about_projects_completed" name="about_projects_completed" class="admin-input" value="<?= e(setting('about_projects_completed')) ?>" required>
        </div>

        <div class="admin-form-group">
          <label class="admin-form-label" for="about_clients_satisfied">Satisfied Clients</label>
          <input type="text" id="about_clients_satisfied" name="about_clients_satisfied" class="admin-input" value="<?= e(setting('about_clients_satisfied')) ?>" required>
        </div>

        <div class="admin-form-group">
          <label class="admin-form-label" for="about_code_commits">GitHub Commits</label>
          <input type="text" id="about_code_commits" name="about_code_commits" class="admin-input" value="<?= e(setting('about_code_commits')) ?>" required>
        </div>
      </div>
    </div>
  </div>

  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">Curriculum Vitae / Resume Attachment</h3>
    </div>
    <div class="admin-card-body">
      <div class="admin-form-group">
        <label class="admin-form-label" for="about_resume_url">Direct Resume File URL</label>
        <input type="text" id="about_resume_url" name="about_resume_url" class="admin-input" value="<?= e(setting('about_resume_url')) ?>">
      </div>

      <div class="admin-form-group">
        <label class="admin-form-label">Upload New Resume (PDF format, max 10MB)</label>
        <input type="file" name="resume_file" accept="application/pdf" class="admin-input">
      </div>
    </div>
  </div>

  <div style="display: flex; justify-content: flex-end; margin-bottom: 3rem;">
    <button type="submit" class="btn btn-primary">
      <span>Save About Section</span>
    </button>
  </div>
</form>

