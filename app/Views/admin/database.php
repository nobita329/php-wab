<div class="page-header">
  <div>
    <h1 class="page-title">Database Telemetry & Backup Engine</h1>
    <p class="page-subtitle">Inspect real table row counts, generate full SQL backups, and perform storage maintenance.</p>
  </div>
  <div style="display: flex; gap: 0.75rem;">
    <a href="/admin/database.php?action=download_backup&csrf_token=<?= csrf_token() ?>" class="btn btn-primary btn-sm">
      <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
      <span>Download SQL Backup</span>
    </a>
  </div>
</div>

<div class="stats-grid">
  <div class="stat-card">
    <div>
      <div class="stat-label">Active Database Engine</div>
      <div class="stat-number" style="font-size: 1.5rem; text-transform: uppercase; color: var(--admin-primary);"><?= e($driver) ?></div>
    </div>
    <div class="stat-icon cyan">
      <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
    </div>
  </div>

  <div class="stat-card">
    <div>
      <div class="stat-label">Total Tables</div>
      <div class="stat-number"><?= count($tableStats) ?></div>
    </div>
    <div class="stat-icon purple">
      <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
    </div>
  </div>

  <div class="stat-card">
    <div>
      <div class="stat-label">Total Records</div>
      <div class="stat-number"><?= $totalRows ?></div>
    </div>
    <div class="stat-icon green">
      <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
    </div>
  </div>

  <div class="stat-card">
    <div>
      <div class="stat-label">Estimated DB Size</div>
      <div class="stat-number" style="font-size: 1.5rem;"><?= round($dbSize / 1024, 1) ?> KB</div>
    </div>
    <div class="stat-icon yellow">
      <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
    </div>
  </div>
</div>

<div style="display: grid; grid-template-columns: 1.6fr 1fr; gap: 2rem;">
  <!-- Tables Record Counts -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">Database Tables Breakdown</h3>
    </div>
    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Table Name</th>
            <th>Total Rows</th>
            <th>Storage Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($tableStats as $tbl => $rows): ?>
            <tr>
              <td><code style="color: #fff; font-family: var(--font-mono);"><?= e($tbl) ?></code></td>
              <td style="font-family: var(--font-mono); font-size: 0.9rem; font-weight: 700; color: var(--admin-primary);"><?= $rows ?></td>
              <td><span class="badge badge-success">Healthy</span></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Maintenance Operations -->
  <div>
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title">Maintenance Operations</h3>
      </div>
      <div class="admin-card-body" style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div>
          <div style="font-weight: 700; color: #fff; margin-bottom: 0.25rem;">Download Database Dump</div>
          <p style="font-size: 0.85rem; color: var(--admin-muted); margin-bottom: 0.75rem;">Generates a standalone .sql file containing table structures and all data rows for easy cPanel phpMyAdmin import.</p>
          <a href="/admin/database.php?action=download_backup&csrf_token=<?= csrf_token() ?>" class="btn btn-outline btn-sm">Generate & Download .SQL</a>
        </div>

        <hr style="border: 0; border-top: 1px solid var(--admin-border);">

        <div>
          <div style="font-weight: 700; color: #fff; margin-bottom: 0.25rem;">Clear Application Cache</div>
          <p style="font-size: 0.85rem; color: var(--admin-muted); margin-bottom: 0.75rem;">Clears any cached view fragments or server temporary caches.</p>
          <a href="/admin/database.php?action=clear_cache&csrf_token=<?= csrf_token() ?>" class="btn btn-outline btn-sm confirm-delete" data-confirm="Clear all server-side cache files?">Clear Cache</a>
        </div>

        <hr style="border: 0; border-top: 1px solid var(--admin-border);">

        <div>
          <div style="font-weight: 700; color: var(--admin-danger); margin-bottom: 0.25rem;">Flush Activity Logs</div>
          <p style="font-size: 0.85rem; color: var(--admin-muted); margin-bottom: 0.75rem;">Clears the administrative audit trail table to reclaim disk space.</p>
          <a href="/admin/database.php?action=clear_logs&csrf_token=<?= csrf_token() ?>" class="btn btn-outline btn-sm confirm-delete" style="border-color: rgba(255,0,85,0.3); color: #ff0055;" data-confirm="Purge all historical audit logs?">Clear Audit Logs</a>
        </div>
      </div>
    </div>
  </div>
</div>

