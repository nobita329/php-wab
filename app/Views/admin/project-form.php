<div class="page-header">
  <div>
    <h1 class="page-title"><?= $project ? 'Edit Project: ' . e($project['title']) : 'Create New Project' ?></h1>
    <p class="page-subtitle">Configure project metadata, technological stack, galleries, and source links.</p>
  </div>
  <a href="/admin/projects.php" class="btn btn-outline btn-sm">← Back to Directory</a>
</div>

<form action="/admin/projects.php" method="POST" enctype="multipart/form-data">
  <?= csrf_field() ?>
  <input type="hidden" name="project_id" value="<?= $project ? (int)$project['id'] : 0 ?>">

  <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <!-- Main Content Fields -->
    <div>
      <div class="admin-card">
        <div class="admin-card-header">
          <h3 class="admin-card-title">Project Details</h3>
        </div>
        <div class="admin-card-body">
          <div class="admin-form-group">
            <label class="admin-form-label" for="proj_title">Project Title *</label>
            <input type="text" id="proj_title" name="title" class="admin-input" value="<?= e($project['title'] ?? '') ?>" placeholder="e.g. Nexus Cyberverse Configurator" data-slug-target="#proj_slug" required>
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="proj_slug">URL Slug *</label>
            <input type="text" id="proj_slug" name="slug" class="admin-input" value="<?= e($project['slug'] ?? '') ?>" placeholder="nexus-cyberverse-configurator" required>
            <div class="admin-form-hint">Permalinks will be formed as /project.php?slug=<?= e($project['slug'] ?? 'your-slug') ?></div>
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="proj_excerpt">Card Excerpt / Short Summary *</label>
            <textarea id="proj_excerpt" name="excerpt" class="admin-textarea" rows="3" placeholder="Brief technical summary for portfolio cards..." required><?= e($project['excerpt'] ?? '') ?></textarea>
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="proj_description">Full Architectural Overview & Case Study *</label>
            <textarea id="proj_description" name="description" class="admin-textarea" rows="8" placeholder="Detailed architectural breakdown, challenges, solution, and results..." required><?= e($project['description'] ?? '') ?></textarea>
          </div>
        </div>
      </div>

      <!-- Gallery Images Upload & Display -->
      <div class="admin-card">
        <div class="admin-card-header">
          <h3 class="admin-card-title">Project Gallery Images</h3>
        </div>
        <div class="admin-card-body">
          <?php if (!empty($project['images'])): ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
              <?php foreach ($project['images'] as $img): ?>
                <div style="position: relative; border-radius: 8px; overflow: hidden; border: 1px solid var(--admin-border);">
                  <img src="<?= e($img['image_path']) ?>" alt="gallery" style="width: 100%; height: 90px; object-fit: cover;">
                  <a href="/admin/projects.php?action=delete_image&image_id=<?= $img['id'] ?>&project_id=<?= $project['id'] ?>&csrf_token=<?= csrf_token() ?>" class="confirm-delete" style="position: absolute; top: 4px; right: 4px; background: rgba(255,0,85,0.85); color: #fff; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.7rem;" title="Delete image">✕</a>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <div class="admin-form-group">
            <label class="admin-form-label">Upload Additional Gallery Images</label>
            <input type="file" name="gallery_images[]" accept="image/*" multiple class="admin-input">
            <div class="admin-form-hint">Hold Ctrl/Cmd to select multiple image files.</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Sidebar Meta Fields -->
    <div>
      <div class="admin-card">
        <div class="admin-card-header">
          <h3 class="admin-card-title">Publish & Classification</h3>
        </div>
        <div class="admin-card-body">
          <div class="admin-form-group">
            <label class="admin-form-label" for="proj_category">Category</label>
            <input type="text" id="proj_category" name="category" list="category_suggestions" class="admin-input" value="<?= e($project['category'] ?? '3D & Creative Tech') ?>" required>
            <datalist id="category_suggestions">
              <?php foreach ($categories as $cat): ?>
                <option value="<?= e($cat) ?>"></option>
              <?php endforeach; ?>
            </datalist>
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="proj_technologies">Technologies (comma separated)</label>
            <input type="text" id="proj_technologies" name="technologies" class="admin-input" value="<?= e($project['technologies'] ?? '') ?>" placeholder="Three.js, WebGL, PHP 8, MySQL">
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="proj_client">Client / Entity</label>
            <input type="text" id="proj_client" name="client_name" class="admin-input" value="<?= e($project['client_name'] ?? '') ?>" placeholder="e.g. Aura Dynamics">
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="proj_date">Date / Year</label>
            <input type="text" id="proj_date" name="project_date" class="admin-input" value="<?= e($project['project_date'] ?? date('Y')) ?>" placeholder="2025 - 2026">
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="proj_live_url">Live Demonstration URL</label>
            <input type="url" id="proj_live_url" name="live_url" class="admin-input" value="<?= e($project['live_url'] ?? '') ?>" placeholder="https://example.com/demo">
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="proj_github_url">GitHub Repository URL</label>
            <input type="url" id="proj_github_url" name="github_url" class="admin-input" value="<?= e($project['github_url'] ?? '') ?>" placeholder="https://github.com/org/repo">
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="proj_sort">Display Sort Order</label>
            <input type="number" id="proj_sort" name="sort_order" class="admin-input" value="<?= e($project['sort_order'] ?? '0') ?>">
          </div>

          <div class="admin-form-group">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; margin-bottom: 0.5rem;">
              <input type="checkbox" name="is_published" value="1" <?= (!$project || $project['is_published']) ? 'checked' : '' ?>>
              <span>Publish Publicly</span>
            </label>
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
              <input type="checkbox" name="is_featured" value="1" <?= ($project && $project['is_featured']) ? 'checked' : '' ?>>
              <span>Feature on Homepage</span>
            </label>
          </div>

          <hr style="border: 0; border-top: 1px solid var(--admin-border); margin: 1.5rem 0;">

          <!-- Thumbnail Image Upload -->
          <div class="admin-form-group">
            <label class="admin-form-label">Project Thumbnail</label>
            <div class="image-preview-box" style="width: 100%; height: 140px;">
              <img id="thumb-preview" src="<?= e($project['thumbnail'] ?? '') ?>" alt="Thumb" style="<?= !empty($project['thumbnail']) ? '' : 'display:none;' ?>">
              <?php if (empty($project['thumbnail'])): ?>
                <span style="font-size: 0.8rem; color: var(--admin-dim);">No Image Uploaded</span>
              <?php endif; ?>
            </div>
            <input type="file" name="thumbnail" accept="image/*" class="admin-input" data-preview="#thumb-preview">
          </div>

          <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
            <span><?= $project ? 'Save Project Changes' : 'Publish Project' ?></span>
          </button>
        </div>
      </div>
    </div>
  </div>
</form>

