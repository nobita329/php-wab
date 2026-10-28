<div class="page-header">
  <div>
    <h1 class="page-title">Digital Asset & Media Library</h1>
    <p class="page-subtitle">Upload, inspect, and copy permanent URLs for assets, 3D textures, and image banners.</p>
  </div>
</div>

<!-- Upload Box -->
<div class="admin-card">
  <div class="admin-card-header">
    <h3 class="admin-card-title">+ Upload New Asset</h3>
  </div>
  <div class="admin-card-body">
    <form action="/admin/media.php" method="POST" enctype="multipart/form-data" style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
      <?= csrf_field() ?>
      <input type="file" name="file" class="admin-input" style="max-width: 360px;" required>
      <button type="submit" class="btn btn-primary">
        <span>Upload Asset</span>
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
      </button>
      <div style="font-size: 0.8rem; color: var(--admin-muted);">Supported formats: JPG, PNG, WEBP, GIF, SVG, PDF. Max: 10MB.</div>
    </form>
  </div>
</div>

<!-- Media Grid -->
<div class="admin-card">
  <div class="admin-card-header">
    <h3 class="admin-card-title">Stored Media Files (<?= count($mediaFiles) ?>)</h3>
  </div>
  <div class="admin-card-body">
    <?php if (empty($mediaFiles)): ?>
      <div style="text-align: center; padding: 4rem 1rem; color: var(--admin-muted);">
        No media assets uploaded yet. Use the upload field above to add project screenshots or documents.
      </div>
    <?php else: ?>
      <div class="media-grid">
        <?php foreach ($mediaFiles as $m): ?>
          <div class="media-item">
            <?php if (str_starts_with($m['mime_type'], 'image/')): ?>
              <img src="<?= e($m['file_path']) ?>" alt="<?= e($m['original_name']) ?>" class="media-thumbnail" loading="lazy">
            <?php else: ?>
              <div class="media-thumbnail" style="display: flex; align-items: center; justify-content: center; color: var(--admin-primary); font-family: var(--font-mono); font-size: 0.85rem; font-weight: 700;">
                PDF
              </div>
            <?php endif; ?>

            <div class="media-meta">
              <div class="media-name" title="<?= e($m['original_name']) ?>"><?= e($m['original_name']) ?></div>
              <div style="color: var(--admin-dim); font-family: var(--font-mono); font-size: 0.7rem;">
                <?= round($m['file_size'] / 1024, 1) ?> KB • <?= e($m['mime_type']) ?>
              </div>

              <div style="display: flex; gap: 0.4rem; margin-top: 0.5rem;">
                <button type="button" class="btn btn-outline btn-sm copy-btn" data-url="<?= e($m['file_path']) ?>" style="padding: 0.2rem 0.5rem; font-size: 0.7rem; flex-grow: 1;">Copy URL</button>
                <a href="/admin/media.php?action=delete&id=<?= $m['id'] ?>&csrf_token=<?= csrf_token() ?>" class="btn btn-outline btn-sm confirm-delete" style="padding: 0.2rem 0.4rem; font-size: 0.7rem; border-color: rgba(255,0,85,0.3); color: #ff0055;" title="Delete" data-confirm="Delete '<?= e($m['original_name']) ?>'?">✕</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

