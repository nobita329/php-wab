<div class="page-header">
  <div>
    <h1 class="page-title">Projects Portfolio Directory</h1>
    <p class="page-subtitle">Full lifecycle management for client case studies, live demonstrations, and GitHub repositories.</p>
  </div>
  <a href="/admin/projects.php?action=create" class="btn btn-primary btn-sm">+ Create Project</a>
</div>

<div class="admin-card">
  <div class="admin-card-header">
    <h3 class="admin-card-title">All Projects (<?= count($projects) ?>)</h3>
  </div>
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Thumbnail</th>
          <th>Project Title</th>
          <th>Category</th>
          <th>Featured</th>
          <th>Status</th>
          <th>Views</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($projects)): ?>
          <tr>
            <td colspan="7" style="text-align: center; color: var(--admin-muted);">No projects created yet.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($projects as $p): ?>
            <tr>
              <td style="width: 70px;">
                <div style="width: 50px; height: 36px; border-radius: 6px; overflow: hidden; background: #111422;">
                  <?php if (!empty($p['thumbnail'])): ?>
                    <img src="<?= e($p['thumbnail']) ?>" alt="thumb" style="width: 100%; height: 100%; object-fit: cover;">
                  <?php else: ?>
                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--admin-muted); font-size: 0.7rem;">None</div>
                  <?php endif; ?>
                </div>
              </td>
              <td>
                <strong style="color: #fff;"><?= e($p['title']) ?></strong>
                <div style="font-size: 0.8rem; color: var(--admin-muted); font-family: var(--font-mono);">/project.php?slug=<?= e($p['slug']) ?></div>
              </td>
              <td><span class="badge badge-info"><?= e($p['category']) ?></span></td>
              <td>
                <?= $p['is_featured'] ? '<span class="badge badge-success">Featured</span>' : '<span class="badge badge-warning">Standard</span>' ?>
              </td>
              <td>
                <?= $p['is_published'] ? '<span class="badge badge-success">Live</span>' : '<span class="badge badge-danger">Draft</span>' ?>
              </td>
              <td style="font-family: var(--font-mono); font-size: 0.85rem; color: var(--admin-primary);">
                <?= number_format((int)$p['views_count']) ?>
              </td>
              <td>
                <div style="display: flex; gap: 0.5rem;">
                  <a href="/project.php?slug=<?= e($p['slug']) ?>" target="_blank" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;" title="View Live">View</a>
                  <a href="/admin/projects.php?action=edit&id=<?= $p['id'] ?>" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;">Edit</a>
                  <a href="/admin/projects.php?action=delete&id=<?= $p['id'] ?>&csrf_token=<?= csrf_token() ?>" class="btn btn-outline btn-sm confirm-delete" style="padding: 0.25rem 0.6rem; font-size: 0.75rem; border-color: rgba(255,0,85,0.3); color: #ff0055;" data-confirm="Are you sure you want to permanently delete '<?= e($p['title']) ?>'?">Delete</a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

