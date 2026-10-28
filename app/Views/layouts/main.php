<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle ?? setting('site_title', 'Alex Vance — Creative Technologist & Full-Stack Architect')) ?></title>
  <meta name="description" content="<?= e($metaDescription ?? setting('site_description', 'High-performance interactive 3D portfolio and creative engineering lab.')) ?>">
  <meta name="keywords" content="<?= e(setting('seo_meta_keywords', 'full-stack, three.js, webgl, php, mysql')) ?>">
  
  <!-- Open Graph / Social Sharing -->
  <meta property="og:type" content="website">
  <meta property="og:title" content="<?= e($pageTitle ?? setting('site_title')) ?>">
  <meta property="og:description" content="<?= e($metaDescription ?? setting('site_description')) ?>">
  <meta property="og:url" content="<?= e(url()) ?>">
  <meta property="og:image" content="<?= e(url(setting('seo_og_image', '/assets/images/og-preview.jpg'))) ?>">
  <meta name="twitter:card" content="summary_large_image">

  <!-- Favicon -->
  <?php if ($fav = setting('favicon_image')): ?>
    <link rel="icon" href="<?= e($fav) ?>">
  <?php else: ?>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>⚡</text></svg>">
  <?php endif; ?>

  <!-- Google Fonts: Outfit & JetBrains Mono -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <!-- Theme Stylesheet -->
  <link rel="stylesheet" href="<?= asset('/assets/css/style.css') ?>">

  <!-- Dynamic Database Theme Overrides -->
  <style>
    :root {
      --primary: <?= e(setting('theme_primary_color', '#00f0ff')) ?>;
      --accent: <?= e(setting('theme_accent_color', '#7928ca')) ?>;
      --bg-base: <?= e(setting('theme_bg_color', '#07080d')) ?>;
      --radius-lg: <?= e(setting('theme_card_border_radius', '20')) ?>px;
      --font-family: <?= e(setting('theme_font_family', "'Outfit', sans-serif")) ?>;
    }
  </style>
</head>
<body>
  <!-- Ambient Cosmic Glows -->
  <div class="ambient-glow-1"></div>
  <div class="ambient-glow-2"></div>

  <!-- Navbar Partial -->
  <?php require dirname(__DIR__) . '/partials/navbar.php'; ?>

  <!-- Main Content Slot -->
  <main>
    <?= $content ?>
  </main>

  <!-- Footer Partial -->
  <?php require dirname(__DIR__) . '/partials/footer.php'; ?>

  <!-- Vendor Scripts (CDN with graceful local fallback) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

  <!-- Interactive Three.js Scene & Application Logic -->
  <script src="<?= asset('/assets/js/three-scene.js') ?>"></script>
  <script src="<?= asset('/assets/js/main.js') ?>"></script>
</body>
</html>

