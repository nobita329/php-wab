<?php
$badge = setting('hero_badge', 'Available for High-Impact Projects');
$heading = setting('hero_heading', 'Architecting Next-Gen Web & 3D Realities');
$subtitle = setting('hero_subtitle', 'Creative Technologist & Senior Full-Stack Engineer');
$desc = setting('hero_description', 'I merge precision backend engineering with immersive real-time WebGL graphics, building digital experiences that feel alive, performant, and unforgettable.');
$btn1Label = setting('hero_btn_primary_label', 'Explore Projects');
$btn1Link = setting('hero_btn_primary_link', '#projects');
$btn2Label = setting('hero_btn_secondary_label', 'Initiate Contact');
$btn2Link = setting('hero_btn_secondary_link', '#contact');

$threeEnabled = setting('three_enabled', '1');
$geometry = setting('three_geometry', 'torus_knot');
$speedX = setting('three_rotation_speed_x', '0.003');
$speedY = setting('three_rotation_speed_y', '0.005');
$sensitivity = setting('three_camera_sensitivity', '0.05');
$particleCount = setting('three_particle_count', '850');
$accent1 = setting('three_accent_color_1', '#00f0ff');
$accent2 = setting('three_accent_color_2', '#7928ca');
$bgColor = setting('three_bg_color', '#07080d');
$wireframe = setting('three_wireframe', '1');
$mobilePerf = setting('three_mobile_perf', '1');
?>
<section id="home" class="hero-section">
  <!-- Interactive Three.js 3D Canvas Background -->
  <div id="three-hero-container" class="canvas-container"
       data-enabled="<?= e($threeEnabled) ?>"
       data-geometry="<?= e($geometry) ?>"
       data-speed-x="<?= e($speedX) ?>"
       data-speed-y="<?= e($speedY) ?>"
       data-sensitivity="<?= e($sensitivity) ?>"
       data-particles="<?= e($particleCount) ?>"
       data-accent1="<?= e($accent1) ?>"
       data-accent2="<?= e($accent2) ?>"
       data-bg="<?= e($bgColor) ?>"
       data-wireframe="<?= e($wireframe) ?>"
       data-mobile-perf="<?= e($mobilePerf) ?>">
    
    <!-- 3D Loading Progress Indicator -->
    <div id="three-loader">
      <div class="loader-spinner"></div>
      <div class="loader-text">Initializing 3D Dimensions...</div>
    </div>
  </div>

  <!-- Hero Content Layer -->
  <div class="container">
    <div class="hero-content">
      <?php if (!empty($badge)): ?>
        <div class="hero-badge">
          <span class="logo-dot"></span>
          <span><?= e($badge) ?></span>
        </div>
      <?php endif; ?>

      <h1 class="hero-title text-gradient"><?= e($heading) ?></h1>
      <p class="hero-subtitle"><?= e($subtitle) ?></p>
      <p class="hero-desc"><?= e($desc) ?></p>

      <div class="hero-buttons">
        <a href="<?= e($btn1Link) ?>" class="btn btn-primary">
          <span><?= e($btn1Label) ?></span>
          <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
        <a href="<?= e($btn2Link) ?>" class="btn btn-outline">
          <span><?= e($btn2Label) ?></span>
        </a>
      </div>
    </div>
  </div>
</section>

