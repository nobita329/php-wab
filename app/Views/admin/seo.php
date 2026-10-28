<div class="page-header">
  <div>
    <h1 class="page-title">SEO, OpenGraph & Search Indexing</h1>
    <p class="page-subtitle">Configure search engine crawlers, social share cards, XML sitemaps, and robots.txt.</p>
  </div>
  <a href="/admin/seo.php?action=generate_sitemap&csrf_token=<?= csrf_token() ?>" class="btn btn-primary btn-sm">
    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
    <span>Generate XML Sitemap</span>
  </a>
</div>

<form action="/admin/seo.php" method="POST" enctype="multipart/form-data">
  <?= csrf_field() ?>

  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">Search Keywords & OpenGraph Sharing</h3>
    </div>
    <div class="admin-card-body">
      <div class="admin-form-group">
        <label class="admin-form-label" for="seo_meta_keywords">Global Meta Keywords (comma separated)</label>
        <input type="text" id="seo_meta_keywords" name="seo_meta_keywords" class="admin-input" value="<?= e(setting('seo_meta_keywords')) ?>" placeholder="three.js, webgl, creative developer, php, mysql">
      </div>

      <div class="admin-form-group" style="margin-top: 1.5rem;">
        <label class="admin-form-label">Social Share Card Preview (OpenGraph / Twitter Card Image)</label>
        <div class="image-preview-box" style="width: 280px; height: 146px;">
          <img id="og-preview" src="<?= e(setting('seo_og_image', '/assets/images/og-preview.jpg')) ?>" alt="OG Preview">
        </div>
        <input type="file" name="seo_og_image" accept="image/*" class="admin-input" data-preview="#og-preview">
        <div class="admin-form-hint">Recommended dimensions: 1200 x 630 px. Shown when your portfolio is shared on LinkedIn, Discord, and X.</div>
      </div>
    </div>
  </div>

  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">Robots.txt Editor</h3>
    </div>
    <div class="admin-card-body">
      <div class="admin-form-group">
        <label class="admin-form-label" for="robots_txt_content">Public robots.txt Rules</label>
        <textarea id="robots_txt_content" name="robots_txt_content" class="admin-textarea" rows="6" style="font-family: var(--font-mono); font-size: 0.85rem;"><?= e($robotsContent) ?></textarea>
        <div class="admin-form-hint">Instructs Googlebot and search crawlers which paths are allowed or indexed.</div>
      </div>
    </div>
  </div>

  <div style="display: flex; justify-content: flex-end; margin-bottom: 3rem;">
    <button type="submit" class="btn btn-primary">
      <span>Save SEO Parameters</span>
    </button>
  </div>
</form>

