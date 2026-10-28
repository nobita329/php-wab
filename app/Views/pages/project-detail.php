<?php
use App\Models\Project;

$slug = $_GET['slug'] ?? '';
$id = (int)($_GET['id'] ?? 0);

$project = null;
if (!empty($slug)) {
    $project = Project::findBySlug($slug);
} elseif ($id > 0) {
    $project = Project::find($id);
}

if (!$project || !$project['is_published']) {
    http_response_code(404);
    echo "<div class='container section-padding' style='padding-top:140px; text-align:center;'><h2>Project Not Found</h2><p style='margin:1.5rem 0;'>The requested project does not exist or has been archived.</p><a href='/projects.php' class='btn btn-primary'>Back to Projects</a></div>";
    return;
}

// Increment views
Project::incrementViews((int)$project['id']);
$images = $project['images'] ?? [];
?>

<div class="section-padding" style="padding-top: 130px;">
  <div class="container">
    <div style="margin-bottom: 2rem;">
      <a href="/projects.php" class="btn btn-outline btn-sm" style="margin-bottom: 1.5rem;">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        <span>Back to All Projects</span>
      </a>
      <span class="section-tag"><?= e($project['category']) ?></span>
      <h1 class="hero-title" style="margin-top: 0.5rem; margin-bottom: 1.5rem;"><?= e($project['title']) ?></h1>
    </div>

    <!-- Main Hero Image Banner -->
    <?php if (!empty($project['thumbnail'])): ?>
      <div class="glass-panel" style="overflow: hidden; margin-bottom: 3.5rem; max-height: 540px;">
        <img src="<?= e($project['thumbnail']) ?>" alt="<?= e($project['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
      </div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 4rem;">
      <!-- Main Content -->
      <div>
        <h2 style="font-size: 1.75rem; margin-bottom: 1.5rem;">Overview & Architecture</h2>
        <div style="color: var(--text-muted); font-size: 1.1rem; line-height: 1.8; white-space: pre-line; margin-bottom: 3rem;">
          <?= e($project['description']) ?>
        </div>

        <!-- Project Gallery Images -->
        <?php if (!empty($images)): ?>
          <h3 style="font-size: 1.4rem; margin-bottom: 1.5rem;">Gallery & Screenshots</h3>
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
            <?php foreach ($images as $img): ?>
              <div class="glass-panel" style="overflow: hidden; border-radius: var(--radius-md);">
                <img src="<?= e($img['image_path']) ?>" alt="<?= e($img['caption'] ?? 'Project visual') ?>" style="width: 100%; height: 220px; object-fit: cover;">
                <?php if (!empty($img['caption'])): ?>
                  <div style="padding: 0.75rem 1rem; font-size: 0.85rem; color: var(--text-muted); background: var(--bg-surface);">
                    <?= e($img['caption']) ?>
                  </div>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- Sidebar Specs -->
      <div>
        <div class="glass-panel" style="padding: 2.25rem; display: flex; flex-direction: column; gap: 1.75rem; position: sticky; top: 100px;">
          <h3 style="font-size: 1.25rem; border-bottom: 1px solid var(--border-glass); padding-bottom: 1rem;">Project Metadata</h3>

          <?php if (!empty($project['client_name'])): ?>
            <div>
              <div style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Client / Sponsor</div>
              <div style="font-size: 1rem; color: #fff; margin-top: 0.25rem; font-weight: 600;"><?= e($project['client_name']) ?></div>
            </div>
          <?php endif; ?>

          <?php if (!empty($project['project_date'])): ?>
            <div>
              <div style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Timeline / Date</div>
              <div style="font-size: 1rem; color: #fff; margin-top: 0.25rem; font-family: var(--font-mono);"><?= e($project['project_date']) ?></div>
            </div>
          <?php endif; ?>

          <?php if (!empty($project['technologies'])): ?>
            <div>
              <div style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700; margin-bottom: 0.5rem;">Tech Stack</div>
              <div style="display: flex; flex-wrap: wrap; gap: 0.4rem;">
                <?php foreach (explode(',', $project['technologies']) as $tag): ?>
                  <span class="tech-tag"><?= e(trim($tag)) ?></span>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>

          <div>
            <div style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Metrics</div>
            <div style="font-size: 0.95rem; color: var(--primary); font-family: var(--font-mono); margin-top: 0.25rem;">
              👁 <?= number_format((int)$project['views_count']) ?> Views
            </div>
          </div>

          <!-- CTAs -->
          <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-top: 0.5rem;">
            <?php if (!empty($project['live_url'])): ?>
              <a href="<?= e($project['live_url']) ?>" target="_blank" rel="noopener" class="btn btn-primary" style="width: 100%;">
                <span>Launch Live Demo</span>
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
              </a>
            <?php endif; ?>

            <?php if (!empty($project['github_url'])): ?>
              <a href="<?= e($project['github_url']) ?>" target="_blank" rel="noopener" class="btn btn-outline" style="width: 100%;">
                <span>Inspect Repository</span>
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

