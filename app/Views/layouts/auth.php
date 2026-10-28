<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle ?? 'Authentication Portal') ?> | <?= e(setting('site_name', 'Alex Vance')) ?></title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="<?= asset('/assets/css/style.css') ?>">
  
  <style>
    body {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 2rem 1rem;
      background: radial-gradient(circle at 50% 30%, #161a2b 0%, #07080d 70%);
    }
    .auth-card {
      width: 100%;
      max-width: 440px;
      padding: 2.75rem 2.25rem;
      position: relative;
      z-index: 10;
    }
  </style>
</head>
<body>
  <div class="ambient-glow-1"></div>
  <div class="ambient-glow-2"></div>

  <div class="glass-panel auth-card">
    <?= $content ?>
  </div>
</body>
</html>

