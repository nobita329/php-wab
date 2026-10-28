<?php
use App\Models\Project;
use App\Models\Skill;
use App\Models\Experience;
use App\Models\Education;
use App\Models\Service;

// Load real database records
$featuredProjects = Project::featured(3);
$groupedSkills = Skill::getGroupedByCategory();
$experiences = Experience::all();
$education = Education::all();
$services = Service::all(true);
$showServices = setting('theme_section_visibility_services', '1') === '1';
$showExperience = setting('theme_section_visibility_experience', '1') === '1';
$showSkills = setting('theme_section_visibility_skills', '1') === '1';
?>

<!-- 1. Full 3D Interactive Hero -->
<?php require dirname(__DIR__) . '/partials/3d-hero.php'; ?>

<!-- 2. About & Core Stats Section -->
<section id="about" class="section-padding">
  <div class="container">
    <div class="section-header">
      <span class="section-tag">Identity</span>
      <h2 class="section-title"><?= e(setting('about_heading', 'Behind the Code & Dimensions')) ?></h2>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 3.5rem; align-items: center; margin-bottom: 4rem;">
      <div>
        <p style="margin-bottom: 1.5rem; font-size: 1.15rem; color: #fff; line-height: 1.8;">
          <?= e(setting('about_bio_p1')) ?>
        </p>
        <p style="margin-bottom: 2rem;">
          <?= e(setting('about_bio_p2')) ?>
        </p>
        <?php if ($resume = setting('about_resume_url')): ?>
          <a href="<?= e($resume) ?>" target="_blank" class="btn btn-outline">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>Download Curriculum Vitae</span>
          </a>
        <?php endif; ?>
      </div>

      <!-- Live Statistics Grid -->
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <div class="glass-panel" style="padding: 2rem; text-align: center;">
          <div style="font-size: 2.75rem; font-weight: 800; font-family: var(--font-mono); color: var(--primary); margin-bottom: 0.5rem;"><?= e(setting('about_years_exp', '9+')) ?></div>
          <div style="font-size: 0.9rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Years Experience</div>
        </div>
        <div class="glass-panel" style="padding: 2rem; text-align: center;">
          <div style="font-size: 2.75rem; font-weight: 800; font-family: var(--font-mono); color: var(--accent); margin-bottom: 0.5rem;"><?= e(setting('about_projects_completed', '48+')) ?></div>
          <div style="font-size: 0.9rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Shipped Projects</div>
        </div>
        <div class="glass-panel" style="padding: 2rem; text-align: center;">
          <div style="font-size: 2.75rem; font-weight: 800; font-family: var(--font-mono); color: var(--success); margin-bottom: 0.5rem;"><?= e(setting('about_clients_satisfied', '35+')) ?></div>
          <div style="font-size: 0.9rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Enterprise Clients</div>
        </div>
        <div class="glass-panel" style="padding: 2rem; text-align: center;">
          <div style="font-size: 2.75rem; font-weight: 800; font-family: var(--font-mono); color: var(--warning); margin-bottom: 0.5rem;"><?= e(setting('about_code_commits', '3.4k+')) ?></div>
          <div style="font-size: 0.9rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">GitHub Commits</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 3. Featured Projects Showcase -->
<section id="projects" class="section-padding" style="background: rgba(15, 18, 29, 0.4);">
  <div class="container">
    <div class="section-header">
      <span class="section-tag">Portfolio</span>
      <h2 class="section-title">Selected Works & Engineering Lab</h2>
      <p class="section-desc">A curated collection of real-time 3D web applications, scalable backends, and creative computing experiments.</p>
    </div>

    <div class="projects-grid">
      <?php foreach ($featuredProjects as $p): ?>
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
                  <a href="<?= e($p['live_url']) ?>" target="_blank" rel="noopener" class="social-btn" style="width: 32px; height: 32px;" title="Live Demonstration">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                  </a>
                <?php endif; ?>
                <?php if (!empty($p['github_url'])): ?>
                  <a href="<?= e($p['github_url']) ?>" target="_blank" rel="noopener" class="social-btn" style="width: 32px; height: 32px;" title="Source Repository">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                  </a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>

    <div style="text-align: center; margin-top: 3.5rem;">
      <a href="/projects.php" class="btn btn-primary">
        <span>Explore Complete Catalog</span>
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
      </a>
    </div>
  </div>
</section>

<!-- 4. Technical Skills Section -->
<?php if ($showSkills): ?>
<section id="skills" class="section-padding">
  <div class="container">
    <div class="section-header">
      <span class="section-tag">Competencies</span>
      <h2 class="section-title">Technical Mastery & Stack</h2>
      <p class="section-desc">From low-level GLSL shaders to robust distributed micro-service backends.</p>
    </div>

    <?php foreach ($groupedSkills as $categoryName => $skills): ?>
      <div style="margin-bottom: 3rem;">
        <h3 style="font-size: 1.35rem; margin-bottom: 1.5rem; color: var(--primary); display: flex; align-items: center; gap: 0.5rem;">
          <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: var(--primary);"></span>
          <?= e($categoryName) ?>
        </h3>
        <div class="skills-grid">
          <?php foreach ($skills as $s): ?>
            <div class="skill-card">
              <div class="skill-header">
                <div class="skill-icon-wrap">
                  <?= render_icon($s['icon'] ?? 'code') ?>
                </div>
                <span class="skill-pct"><?= (int)$s['proficiency'] ?>%</span>
              </div>
              <div class="skill-name"><?= e($s['name']) ?></div>
              <div class="skill-bar-track">
                <div class="skill-bar-fill" style="width: <?= (int)$s['proficiency'] ?>%;"></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<!-- 5. Experience & Education Timeline -->
<?php if ($showExperience): ?>
<section id="experience" class="section-padding" style="background: rgba(15, 18, 29, 0.4);">
  <div class="container">
    <div class="section-header">
      <span class="section-tag">Trajectory</span>
      <h2 class="section-title">Career Milestones & Pedigree</h2>
      <p class="section-desc">Proven track record of engineering leadership, scalable systems, and continuous academic excellence.</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 4rem;">
      <!-- Professional Experience -->
      <div>
        <h3 style="margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem;">
          <?= render_icon('server', 'w-6 h-6 text-cyan') ?>
          <span>Work Experience</span>
        </h3>
        <div class="timeline">
          <?php foreach ($experiences as $exp): ?>
            <div class="timeline-item">
              <div class="timeline-dot"></div>
              <div class="timeline-content">
                <div class="timeline-header">
                  <div class="timeline-company"><?= e($exp['company']) ?></div>
                  <div class="timeline-date"><?= e($exp['start_date']) ?> — <?= e($exp['end_date']) ?></div>
                </div>
                <div class="timeline-role"><?= e($exp['position']) ?> • <?= e($exp['location']) ?></div>
                <p style="font-size: 0.95rem;"><?= nl2br(e($exp['description'])) ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Education & Certifications -->
      <div>
        <h3 style="margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem;">
          <?= render_icon('shield', 'w-6 h-6 text-accent') ?>
          <span>Education & Credentials</span>
        </h3>
        <div class="timeline">
          <?php foreach ($education as $edu): ?>
            <div class="timeline-item">
              <div class="timeline-dot" style="border-color: var(--accent); box-shadow: 0 0 12px var(--accent);"></div>
              <div class="timeline-content">
                <div class="timeline-header">
                  <div class="timeline-company"><?= e($edu['institution']) ?></div>
                  <div class="timeline-date" style="color: var(--accent);"><?= e($edu['start_date']) ?> — <?= e($edu['end_date']) ?></div>
                </div>
                <div class="timeline-role"><?= e($edu['degree']) ?> <?php if ($edu['field_of_study']): ?>• <?= e($edu['field_of_study']) ?><?php endif; ?></div>
                <?php if ($edu['grade']): ?>
                  <div style="font-size: 0.85rem; color: var(--success); font-family: var(--font-mono); margin-bottom: 0.5rem;"><?= e($edu['grade']) ?></div>
                <?php endif; ?>
                <p style="font-size: 0.95rem;"><?= nl2br(e($edu['description'])) ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- 6. Services & Solutions -->
<?php if ($showServices && !empty($services)): ?>
<section id="services" class="section-padding">
  <div class="container">
    <div class="section-header">
      <span class="section-tag">Solutions</span>
      <h2 class="section-title">Capabilities & Engagements</h2>
      <p class="section-desc">Tailored technical partnerships for visionary startups, agencies, and enterprise brands.</p>
    </div>

    <div class="services-grid">
      <?php foreach ($services as $srv): ?>
        <div class="service-card">
          <div class="service-icon">
            <?= render_icon($srv['icon'] ?? 'code', 'w-7 h-7') ?>
          </div>
          <h3 style="font-size: 1.35rem; margin-bottom: 0.75rem;"><?= e($srv['title']) ?></h3>
          <p style="font-size: 0.95rem;"><?= e($srv['summary']) ?></p>

          <?php if (!empty($srv['features'])): ?>
            <ul class="service-features">
              <?php foreach (explode("\n", $srv['features']) as $feat): ?>
                <?php if (trim($feat)): ?>
                  <li><?= e(trim($feat)) ?></li>
                <?php endif; ?>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- 7. Interactive Contact Section -->
<section id="contact" class="section-padding" style="background: rgba(15, 18, 29, 0.4);">
  <div class="container">
    <div class="section-header">
      <span class="section-tag">Connection</span>
      <h2 class="section-title">Initiate Transmission</h2>
      <p class="section-desc">Have an upcoming high-scale project, 3D visualization need, or engineering inquiry? Let's connect.</p>
    </div>

    <div class="contact-layout">
      <!-- Contact Details -->
      <div class="contact-info-card">
        <div>
          <h3 style="font-size: 1.5rem; margin-bottom: 0.75rem;">Direct Coordinates</h3>
          <p style="font-size: 0.95rem;">Always open for architectural consulting, contract engagements, and keynote tech presentations.</p>
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
        <div id="contact-form-status"></div>

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
              <input type="text" id="contact_name" name="name" class="form-control" placeholder="e.g. John Doe" required>
            </div>
            <div class="form-group">
              <label class="form-label" for="contact_email">Email Address *</label>
              <input type="email" id="contact_email" name="email" class="form-control" placeholder="john@company.com" required>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="contact_subject">Inquiry Subject *</label>
            <input type="text" id="contact_subject" name="subject" class="form-control" placeholder="Project inquiry, consulting, etc." required>
          </div>

          <div class="form-group">
            <label class="form-label" for="contact_message">Project Scope & Message *</label>
            <textarea id="contact_message" name="message" class="form-control" placeholder="Detail your timeline, tech stack, and goals..." required></textarea>
          </div>

          <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem;">
            <span>Transmit Message</span>
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
          </button>
        </form>
      </div>
    </div>
  </div>
</section>

