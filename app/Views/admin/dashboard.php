<div class="page-header">
  <div>
    <h1 class="page-title">Executive Command Deck</h1>
    <p class="page-subtitle">Real-time telemetry, database stats, and operational log stream.</p>
  </div>
  <div style="display: flex; gap: 0.75rem;">
    <a href="/admin/projects.php?action=create" class="btn btn-primary btn-sm">+ New Project</a>
    <a href="/admin/messages.php" class="btn btn-outline btn-sm">Review Inquiries</a>
  </div>
</div>

<!-- Real Database Stat Cards -->
<div class="stats-grid">
  <div class="stat-card">
    <div>
      <div class="stat-label">Published Projects</div>
      <div class="stat-number"><?= $stats['total_projects'] ?></div>
    </div>
    <div class="stat-icon cyan">
      <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
    </div>
  </div>

  <div class="stat-card">
    <div>
      <div class="stat-label">Active Users</div>
      <div class="stat-number"><?= $stats['total_users'] ?></div>
    </div>
    <div class="stat-icon purple">
      <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
    </div>
  </div>

  <div class="stat-card">
    <div>
      <div class="stat-label">Total Inquiries</div>
      <div class="stat-number"><?= $stats['total_messages'] ?></div>
    </div>
    <div class="stat-icon green">
      <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
    </div>
  </div>

  <div class="stat-card">
    <div>
      <div class="stat-label">Unread Messages</div>
      <div class="stat-number" style="color: <?= $stats['unread_messages'] > 0 ? '#ff0055' : 'inherit' ?>;">
        <?= $stats['unread_messages'] ?>
      </div>
    </div>
    <div class="stat-icon yellow">
      <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
    </div>
  </div>
</div>

<div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 2rem; margin-bottom: 2rem;">
  <!-- Recent Contact Inquiries -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">Recent Contact Messages</h3>
      <a href="/admin/messages.php" class="btn btn-outline btn-sm">All Messages</a>
    </div>
    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Sender</th>
            <th>Subject</th>
            <th>Status</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($recentMessages)): ?>
            <tr>
              <td colspan="4" style="text-align: center; color: var(--admin-muted);">No messages logged yet.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($recentMessages as $msg): ?>
              <tr>
                <td>
                  <strong><?= e($msg['name']) ?></strong>
                  <div style="font-size: 0.8rem; color: var(--admin-muted); font-family: var(--font-mono);"><?= e($msg['email']) ?></div>
                </td>
                <td><?= e($msg['subject']) ?></td>
                <td>
                  <?php if ($msg['is_read']): ?>
                    <span class="badge badge-success">Read</span>
                  <?php else: ?>
                    <span class="badge badge-danger">Unread</span>
                  <?php endif; ?>
                </td>
                <td style="font-size: 0.82rem; color: var(--admin-muted);"><?= format_date($msg['created_at'], 'M d, H:i') ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Recently Added Projects -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">Recent Projects</h3>
      <a href="/admin/projects.php" class="btn btn-outline btn-sm">All Projects</a>
    </div>
    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Project</th>
            <th>Category</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($recentProjects)): ?>
            <tr>
              <td colspan="3" style="text-align: center; color: var(--admin-muted);">No projects recorded.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($recentProjects as $proj): ?>
              <tr>
                <td>
                  <strong><?= e($proj['title']) ?></strong>
                  <div style="font-size: 0.8rem; color: var(--admin-primary);"><?= (int)$proj['views_count'] ?> views</div>
                </td>
                <td><span class="badge badge-info"><?= e($proj['category']) ?></span></td>
                <td>
                  <?php if ($proj['is_published']): ?>
                    <span class="badge badge-success">Published</span>
                  <?php else: ?>
                    <span class="badge badge-warning">Draft</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Recent Administrative Activity Stream -->
<div class="admin-card">
  <div class="admin-card-header">
    <h3 class="admin-card-title">Audit Trail & Activity Log</h3>
    <a href="/admin/database.php" class="btn btn-outline btn-sm">Maintenance</a>
  </div>
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Timestamp</th>
          <th>Action</th>
          <th>Description</th>
          <th>IP Address</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($recentLogs)): ?>
          <tr>
            <td colspan="4" style="text-align: center; color: var(--admin-muted);">No activity logged.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($recentLogs as $log): ?>
            <tr>
              <td style="font-family: var(--font-mono); font-size: 0.8rem; color: var(--admin-muted);">
                <?= format_date($log['created_at'], 'Y-m-d H:i:s') ?>
              </td>
              <td>
                <span class="badge badge-info"><?= e($log['action']) ?></span>
              </td>
              <td><?= e($log['description']) ?></td>
              <td style="font-family: var(--font-mono); font-size: 0.82rem; color: var(--admin-muted);"><?= e($log['ip_address']) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

