<?php
use App\Models\Project;

$category = $_GET['category'] ?? 'all';
$search = trim($_GET['q'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 9;
$offset = ($page - 1) * $limit;

if (!empty($search)) {
    $projects = Project::search($search, $limit);
    $totalProjects = count($projects);
} else {
    $projects = Project::all(true, $category === 'all' ? null : $category, $limit, $offset);
    $totalProjects = Project::count(true);
}

$categories = Project::getCategories();
$totalPages = ceil($totalProjects / $limit);
?>

<div class="section-padding" style="padding-top: 130px;">
  <div class="container">
    <div class="section-header">
      <span class="section-tag">Engineering Archives</span>
      <h1 class="section-title">Projects & Experiments</h1>
      <p class="section-desc">Interactive WebGL applications, custom shader pipelines, enterprise APIs, and high-performance full-stack architectures.</p>
    </div>

    <!-- Search & Filter Controls -->
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1.5rem; margin-bottom: 3.5rem;">
      <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
        <a href="/projects.php" class="btn btn-sm <?= $category === 'all' && empty($search) ? 'btn-primary' : 'btn-outline' ?>">All</a>
        <?php foreach ($categories as $cat): ?>
          <a href="/projects.php?category=<?= urlencode($cat) ?>" class="btn btn-sm <?= $category === $cat ? 'btn-primary' : 'btn-outline' ?>">
            <?= e($cat) ?>
          </a>
        <?php endforeach; ?>
      </div>

      <!-- Search Box -->
      <form action="/projects.php" method="GET" style="display: flex; gap: 0.5rem; max-width: 320px; width: 100%;">
        <input type="text" name="q" value="<?= e($search) ?>" placeholder="Search projects or tags..." class="form-control" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
        <button type="submit" class="btn btn-primary btn-sm">Search</button>
      </form>
    </div>

    <?php if (empty($projects)): ?>
      <div class="glass-panel" style="padding: 4rem; text-align: center;">
        <h3 style="margin-bottom: 0.75rem;">No projects found</h3>
        <p>No matching projects matched your search criteria or filter. Try another keyword or browse all.</p>
        <div style="margin-top: 1.5rem;">
          <a href="/projects.php" class="btn btn-primary btn-sm">Reset Filter</a>
        </div>
      </div>
    <?php else: ?>
      <div class="projects-grid">
        <?php foreach ($projects as $p): ?>
          <article class="project-card">
            <div class="project-img-wrapper">
              <span class="project-category-badge"><?= e($p['category']) ?></span>
              <?php if (!empty($p['thumbnail'])): ?>
                <img src="<?= e($p['thumbnail']) ?>" alt="<?= e($p['title']) ?>" class="project-img" loading="lazy">
              <?php else: ?>
                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #101424, #1a2038); color: var(--primary);">
                  <?= render_icon('box', 'w-12 h-12') ?>
                </div>
              <?php endif; ?>
            </div>
            <div class="project-content">
              <h3 class="project-title">
                <a href="/project.php?slug=<?= e($p['slug']) ?>"><?= e($p['title']) ?></a>
              </h3>
              <p class="project-excerpt"><?= e($p['excerpt']) ?></p>

              <?php if (!empty($p['technologies'])): ?>
                <div class="project-tags">
                  <?php foreach (explode(',', $p['technologies']) as $tag): ?>
                    <span class="tech-tag"><?= e(trim($tag)) ?></span>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>

              <div class="project-actions">
                <a href="/project.php?slug=<?= e($p['slug']) ?>" class="project-link-btn">
                  <span>View Case Study</span>
                  <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>

                <div style="display: flex; gap: 0.75rem;">
                  <?php if (!empty($p['live_url'])): ?>
                    <a href="<?= e($p['live_url']) ?>" target="_blank" rel="noopener" class="social-btn" style="width: 32px; height: 32px;" title="Live Demo">
                      <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                  <?php endif; ?>
                  <?php if (!empty($p['github_url'])): ?>
                    <a href="<?= e($p['github_url']) ?>" target="_blank" rel="noopener" class="social-btn" style="width: 32px; height: 32px;" title="Source Code">
                      <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                    </a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>

      <!-- Pagination -->
      <?php if ($totalPages > 1): ?>
        <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 4rem;">
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="/projects.php?page=<?= $i ?>&category=<?= urlencode($category) ?>&q=<?= urlencode($search) ?>" class="btn btn-sm <?= $page === $i ? 'btn-primary' : 'btn-outline' ?>" style="min-width: 40px;">
              <?= $i ?>
            </a>
          <?php endfor; ?>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>

