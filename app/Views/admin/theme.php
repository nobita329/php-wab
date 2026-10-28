<div class="page-header">
  <div>
    <h1 class="page-title">Theme Customization Studio</h1>
    <p class="page-subtitle">Personalize cybernetic colors, glassmorphism card curves, typography, and section visibility.</p>
  </div>
  <a href="/admin/theme.php?action=reset_defaults&csrf_token=<?= csrf_token() ?>" class="btn btn-outline btn-sm confirm-delete" data-confirm="Reset all theme variables back to standard cyber neon defaults?">
    <span>↺ Reset to Defaults</span>
  </a>
</div>

<form action="/admin/theme.php" method="POST">
  <?= csrf_field() ?>

  <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 2rem;">
    <div>
      <div class="admin-card">
        <div class="admin-card-header">
          <h3 class="admin-card-title">Color Palette & Contrast</h3>
        </div>
        <div class="admin-card-body">
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="admin-form-group">
              <label class="admin-form-label" for="theme_primary_color">Primary Glow Accent</label>
              <input type="color" id="theme_primary_color" name="theme_primary_color" class="admin-input" value="<?= e(setting('theme_primary_color', '#00f0ff')) ?>" style="height: 44px; padding: 0.2rem;">
              <div class="admin-form-hint">Used for primary buttons, 3D lights, and glowing dots.</div>
            </div>

            <div class="admin-form-group">
              <label class="admin-form-label" for="theme_accent_color">Secondary Accent</label>
              <input type="color" id="theme_accent_color" name="theme_accent_color" class="admin-input" value="<?= e(setting('theme_accent_color', '#7928ca')) ?>" style="height: 44px; padding: 0.2rem;">
              <div class="admin-form-hint">Used for secondary mesh glows and timeline milestones.</div>
            </div>
          </div>

          <div class="admin-form-group" style="margin-top: 1rem;">
            <label class="admin-form-label" for="theme_bg_color">Deep Background Base Color</label>
            <input type="color" id="theme_bg_color" name="theme_bg_color" class="admin-input" value="<?= e(setting('theme_bg_color', '#07080d')) ?>" style="height: 44px; padding: 0.2rem;">
            <div class="admin-form-hint">Pure dark background tone (#07080d recommended).</div>
          </div>
        </div>
      </div>

      <div class="admin-card">
        <div class="admin-card-header">
          <h3 class="admin-card-title">Typography & Geometry Scaling</h3>
        </div>
        <div class="admin-card-body">
          <div class="admin-form-group">
            <label class="admin-form-label" for="theme_font_family">Primary Typography Stack</label>
            <select id="theme_font_family" name="theme_font_family" class="admin-select">
              <option value="'Outfit', sans-serif" <?= setting('theme_font_family') === "'Outfit', sans-serif" ? 'selected' : '' ?>>Outfit (Futuristic & Geometric)</option>
              <option value="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" <?= str_contains(setting('theme_font_family', ''), 'apple-system') ? 'selected' : '' ?>>System Modern Sans</option>
              <option value="'JetBrains Mono', monospace" <?= str_contains(setting('theme_font_family', ''), 'JetBrains') ? 'selected' : '' ?>>JetBrains Mono (Developer Cyberpunk)</option>
            </select>
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="theme_card_border_radius">Glass Card Border Radius: <span id="radius-val" style="color:var(--admin-primary); font-family:var(--font-mono);"><?= e(setting('theme_card_border_radius', '20')) ?>px</span></label>
            <input type="range" min="4" max="32" step="2" id="theme_card_border_radius" name="theme_card_border_radius" value="<?= e(setting('theme_card_border_radius', '20')) ?>" style="width: 100%;" oninput="document.getElementById('radius-val').innerText = this.value + 'px';">
          </div>
        </div>
      </div>

      <div class="admin-card">
        <div class="admin-card-header">
          <h3 class="admin-card-title">Homepage Section Visibility</h3>
        </div>
        <div class="admin-card-body">
          <div class="admin-form-group">
            <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; margin-bottom: 0.75rem;">
              <input type="checkbox" name="theme_section_visibility_skills" value="1" <?= setting('theme_section_visibility_skills', '1') === '1' ? 'checked' : '' ?>>
              <span>Display Technical Competencies & Skills Section</span>
            </label>

            <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; margin-bottom: 0.75rem;">
              <input type="checkbox" name="theme_section_visibility_experience" value="1" <?= setting('theme_section_visibility_experience', '1') === '1' ? 'checked' : '' ?>>
              <span>Display Career Milestones & Education Timeline</span>
            </label>

            <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
              <input type="checkbox" name="theme_section_visibility_services" value="1" <?= setting('theme_section_visibility_services', '1') === '1' ? 'checked' : '' ?>>
              <span>Display Capabilities & Solutions Section</span>
            </label>
          </div>
        </div>
      </div>

      <div style="display: flex; justify-content: flex-end; margin-bottom: 3rem;">
        <button type="submit" class="btn btn-primary">
          <span>Apply Theme Parameters</span>
        </button>
      </div>
    </div>

    <!-- Live Preview Card -->
    <div>
      <div class="admin-card" style="position: sticky; top: 90px;">
        <div class="admin-card-header">
          <h3 class="admin-card-title">Live Component Preview</h3>
        </div>
        <div class="admin-card-body" style="background: <?= e(setting('theme_bg_color', '#07080d')) ?>; padding: 2rem; border-radius: 8px;">
          <!-- Sample Glass Card -->
          <div style="background: rgba(15, 18, 29, 0.75); border: 1px solid rgba(0, 240, 255, 0.3); border-radius: <?= e(setting('theme_card_border_radius', '20')) ?>px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
              <span class="logo-dot" style="background: <?= e(setting('theme_primary_color', '#00f0ff')) ?>; box-shadow: 0 0 10px <?= e(setting('theme_primary_color', '#00f0ff')) ?>;"></span>
              <span style="font-weight: 700; color: #fff; font-size: 1rem;">Glassmorphic Card</span>
            </div>
            <p style="font-size: 0.85rem; color: #94a3b8; margin-bottom: 1rem;">
              Live rendering of your selected background, primary glow, and border curvature settings.
            </p>
            <button type="button" class="btn btn-sm btn-primary" style="background: linear-gradient(135deg, <?= e(setting('theme_primary_color', '#00f0ff')) ?>, <?= e(setting('theme_accent_color', '#7928ca')) ?>); color: #000;">
              Sample Action Trigger
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>

