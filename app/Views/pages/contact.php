<div class="section-padding" style="padding-top: 130px;">
  <div class="container">
    <div class="section-header">
      <span class="section-tag">Direct Comms</span>
      <h1 class="section-title">Initiate Contact</h1>
      <p class="section-desc">Submit your project details, schedule an architectural consult, or send an inquiry. All messages are securely routed directly to my workspace.</p>
    </div>

    <div class="contact-layout">
      <!-- Contact Details -->
      <div class="contact-info-card">
        <div>
          <h2 style="font-size: 1.5rem; margin-bottom: 0.75rem;">Office Coordinates</h2>
          <p style="font-size: 0.95rem;">Always open for architectural consulting, high-impact contract engagements, and keynote tech presentations.</p>
        </div>

        <div class="contact-meta-item">
          <div class="contact-icon">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
          </div>
          <div>
            <div style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Email Address</div>
            <div style="font-family: var(--font-mono); font-size: 1rem; color: #fff; margin-top: 0.2rem;"><?= e(setting('contact_email', 'alex@cyberpulse.dev')) ?></div>
          </div>
        </div>

        <div class="contact-meta-item">
          <div class="contact-icon">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
          </div>
          <div>
            <div style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Telephone</div>
            <div style="font-family: var(--font-mono); font-size: 1rem; color: #fff; margin-top: 0.2rem;"><?= e(setting('contact_phone', '+1 (555) 948-2026')) ?></div>
          </div>
        </div>

        <div class="contact-meta-item">
          <div class="contact-icon">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          </div>
          <div>
            <div style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Physical Headquarters</div>
            <div style="font-size: 0.95rem; color: #fff; margin-top: 0.2rem;"><?= e(setting('contact_location', 'San Francisco, CA / Remote Global')) ?></div>
          </div>
        </div>
      </div>

      <!-- Interactive Form -->
      <div class="glass-panel" style="padding: 2.5rem;">
        <div id="contact-form-status">
          <?php if (has_flash('success')): ?>
            <?php foreach (flash('success') as $msg): ?>
              <div class="alert alert-success"><?= e($msg) ?></div>
            <?php endforeach; ?>
          <?php endif; ?>
          <?php if (has_flash('error')): ?>
            <?php foreach (flash('error') as $msg): ?>
              <div class="alert alert-error"><?= e($msg) ?></div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <form id="contact-form" action="/contact.php" method="POST">
          <?= csrf_field() ?>

          <!-- Honeypot anti-spam protection -->
          <div style="display: none !important;">
            <label for="website_hp">Leave empty</label>
            <input type="text" name="website_hp" id="website_hp" autocomplete="off" tabindex="-1">
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
              <label class="form-label" for="contact_name">Your Name *</label>
              <input type="text" id="contact_name" name="name" class="form-control" placeholder="e.g. Elena Rostova" required>
            </div>
            <div class="form-group">
              <label class="form-label" for="contact_email">Email Address *</label>
              <input type="email" id="contact_email" name="email" class="form-control" placeholder="elena@company.com" required>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="contact_subject">Inquiry Subject *</label>
            <input type="text" id="contact_subject" name="subject" class="form-control" placeholder="Project Inquiry / Consulting" required>
          </div>

          <div class="form-group">
            <label class="form-label" for="contact_message">Project Scope & Message *</label>
            <textarea id="contact_message" name="message" class="form-control" placeholder="Describe your project, objectives, and timeline..." required></textarea>
          </div>

          <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem;">
            <span>Transmit Message</span>
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

