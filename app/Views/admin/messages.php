<?php
use App\Models\Message;
?>
<div class="page-header">
  <div>
    <h1 class="page-title">Client Messages & Inquiries</h1>
    <p class="page-subtitle">Review incoming contact submissions, IP addresses, client requests, and direct mail channels.</p>
  </div>
  <div style="display: flex; gap: 0.5rem;">
    <a href="/admin/messages.php" class="btn btn-sm <?= empty($filter) ? 'btn-primary' : 'btn-outline' ?>">All (<?= Message::count() ?>)</a>
    <a href="/admin/messages.php?filter=unread" class="btn btn-sm <?= $filter === 'unread' ? 'btn-primary' : 'btn-outline' ?>">Unread (<?= Message::count('unread') ?>)</a>
    <a href="/admin/messages.php?filter=replied" class="btn btn-sm <?= $filter === 'replied' ? 'btn-primary' : 'btn-outline' ?>">Replied (<?= Message::count('replied') ?>)</a>
  </div>
</div>

<?php if ($viewMessage): ?>
  <!-- View Single Message Overlay Card -->
  <div class="admin-card" style="border-color: var(--admin-primary); margin-bottom: 2rem;">
    <div class="admin-card-header" style="background: rgba(0, 240, 255, 0.05);">
      <div>
        <h3 class="admin-card-title" style="color: #fff;"><?= e($viewMessage['subject']) ?></h3>
        <div style="font-size: 0.85rem; color: var(--admin-muted); margin-top: 0.25rem;">
          From: <strong><?= e($viewMessage['name']) ?></strong> &lt;<?= e($viewMessage['email']) ?>&gt; • IP: <?= e($viewMessage['ip_address']) ?> • Date: <?= format_date($viewMessage['created_at'], 'M d, Y H:i:s') ?>
        </div>
      </div>
      <a href="/admin/messages.php" class="btn btn-outline btn-sm">Close</a>
    </div>
    <div class="admin-card-body">
      <div style="background: #090a10; padding: 1.5rem; border-radius: 8px; border: 1px solid var(--admin-border); line-height: 1.8; font-size: 1rem; color: #fff; white-space: pre-line; margin-bottom: 1.5rem;">
        <?= e($viewMessage['message']) ?>
      </div>

      <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <a href="mailto:<?= e($viewMessage['email']) ?>?subject=Re: <?= urlencode($viewMessage['subject']) ?>" class="btn btn-primary btn-sm">
          <span>Reply via Email</span>
          <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </a>

        <?php if (!$viewMessage['replied']): ?>
          <a href="/admin/messages.php?action=mark_replied&id=<?= $viewMessage['id'] ?>&state=1&csrf_token=<?= csrf_token() ?>" class="btn btn-outline btn-sm">Mark as Replied</a>
        <?php else: ?>
          <a href="/admin/messages.php?action=mark_replied&id=<?= $viewMessage['id'] ?>&state=0&csrf_token=<?= csrf_token() ?>" class="btn btn-outline btn-sm">Mark as Unreplied</a>
        <?php endif; ?>

        <a href="/admin/messages.php?action=delete&id=<?= $viewMessage['id'] ?>&csrf_token=<?= csrf_token() ?>" class="btn btn-outline btn-sm confirm-delete" style="border-color: rgba(255,0,85,0.3); color: #ff0055;" data-confirm="Permanently delete this message?">Delete</a>
      </div>
    </div>
  </div>
<?php endif; ?>

<div class="admin-card">
  <div class="admin-card-header">
    <h3 class="admin-card-title">Messages Inbox</h3>
  </div>
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Sender</th>
          <th>Subject</th>
          <th>Status</th>
          <th>Received</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($messages)): ?>
          <tr>
            <td colspan="5" style="text-align: center; color: var(--admin-muted); padding: 3rem;">No contact messages in this folder.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($messages as $msg): ?>
            <tr style="<?= !$msg['is_read'] ? 'background: rgba(0,240,255,0.03);' : '' ?>">
              <td>
                <strong style="color: #fff;"><?= e($msg['name']) ?></strong>
                <div style="font-size: 0.8rem; color: var(--admin-muted); font-family: var(--font-mono);"><?= e($msg['email']) ?></div>
              </td>
              <td>
                <a href="/admin/messages.php?action=view&id=<?= $msg['id'] ?>" style="color: #fff; font-weight: 500;">
                  <?= e($msg['subject']) ?>
                </a>
                <div style="font-size: 0.8rem; color: var(--admin-muted); max-width: 320px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                  <?= e(substr($msg['message'], 0, 75)) ?>...
                </div>
              </td>
              <td>
                <?php if (!$msg['is_read']): ?>
                  <span class="badge badge-danger">Unread</span>
                <?php elseif ($msg['replied']): ?>
                  <span class="badge badge-success">Replied</span>
                <?php else: ?>
                  <span class="badge badge-info">Read</span>
                <?php endif; ?>
              </td>
              <td style="font-family: var(--font-mono); font-size: 0.82rem; color: var(--admin-muted);">
                <?= format_date($msg['created_at'], 'M d, H:i') ?>
              </td>
              <td>
                <div style="display: flex; gap: 0.5rem;">
                  <a href="/admin/messages.php?action=view&id=<?= $msg['id'] ?>" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;">Open</a>
                  <a href="/admin/messages.php?action=delete&id=<?= $msg['id'] ?>&csrf_token=<?= csrf_token() ?>" class="btn btn-outline btn-sm confirm-delete" style="padding: 0.25rem 0.6rem; font-size: 0.75rem; border-color: rgba(255,0,85,0.3); color: #ff0055;" data-confirm="Permanently delete message from <?= e($msg['name']) ?>?">Delete</a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
