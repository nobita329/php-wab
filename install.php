<?php
/**
 * ========================================================
 * CYBERPULSE — FIRST RUN WEB INSTALLATION WIZARD
 * Automates Database Setup, Schema Import & Admin Creation
 * ========================================================
 */

$lockFile = __DIR__ . '/storage/installed.lock';
if (file_exists($lockFile)) {
    die("Installation has already been completed. For security, remove storage/installed.lock if you genuinely need to re-run the wizard.");
}

require_once __DIR__ . '/app/Core/Env.php';
require_once __DIR__ . '/app/Core/Autoloader.php';
\App\Core\Autoloader::register();

// Run System Checks
$checks = [
    'PHP 8.2+ Version' => version_compare(PHP_VERSION, '8.2.0', '>='),
    'PDO Extension' => extension_loaded('PDO'),
    'PDO MySQL or SQLite Driver' => extension_loaded('pdo_mysql') || extension_loaded('pdo_sqlite'),
    'OpenSSL Extension' => extension_loaded('openssl'),
    'Mbstring Extension' => extension_loaded('mbstring'),
    'Fileinfo Extension' => extension_loaded('fileinfo'),
    'storage/ directory writable' => is_writable(__DIR__ . '/storage'),
    'public/uploads/ writable' => is_writable(__DIR__ . '/public/uploads'),
];

$allChecksPassed = !in_array(false, $checks, true);

$error = null;
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $allChecksPassed) {
    $dbDriver = $_POST['db_driver'] ?? 'mysql';
    $dbHost = $_POST['db_host'] ?? '127.0.0.1';
    $dbPort = (int)($_POST['db_port'] ?? 3306);
    $dbName = $_POST['db_name'] ?? 'portfolio_db';
    $dbUser = $_POST['db_user'] ?? 'root';
    $dbPass = $_POST['db_pass'] ?? '';

    $adminName = trim($_POST['admin_name'] ?? '');
    $adminEmail = trim($_POST['admin_email'] ?? '');
    $adminPassword = $_POST['admin_password'] ?? '';

    if (empty($adminName) || empty($adminEmail) || strlen($adminPassword) < 8) {
        $error = "Please provide a valid Admin Name, Email, and a password of at least 8 characters.";
    } else {
        try {
            if ($dbDriver === 'sqlite') {
                $dbPath = __DIR__ . '/storage/database.sqlite';
                $pdo = new PDO("sqlite:{$dbPath}", null, null, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);

                $schema = file_get_contents(__DIR__ . '/database/schema.sqlite.sql');
                $pdo->exec($schema);
            } else {
                $pdo = new PDO("mysql:host={$dbHost};port={$dbPort};charset=utf8mb4", $dbUser, $dbPass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]);

                // Create database if not exists
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $pdo->exec("USE `{$dbName}`");

                // Import MySQL schema
                $schema = file_get_contents(__DIR__ . '/database/database.sql');
                // Remove existing INSERT INTO users from schema dump so custom admin is created!
                $pdo->exec($schema);
            }

            // Run database seeds if empty
            \Database\Seeder::run($pdo, $dbDriver);

            // Create custom initial administrator
            $hashedPwd = password_hash($adminPassword, PASSWORD_DEFAULT);
            $checkAdmin = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
            $checkAdmin->execute([$adminEmail]);
            $existing = $checkAdmin->fetch();

            if ($existing) {
                $upd = $pdo->prepare("UPDATE users SET name = ?, password = ?, role = 'admin', status = 'active' WHERE id = ?");
                $upd->execute([$adminName, $hashedPwd, $existing['id']]);
            } else {
                $ins = $pdo->prepare("INSERT INTO users (name, email, password, role, status, created_at) VALUES (?, ?, ?, 'admin', 'active', CURRENT_TIMESTAMP)");
                $ins->execute([$adminName, $adminEmail, $hashedPwd]);
            }

            // Write .env configuration file
            $envContent = "APP_NAME=\"{$adminName} | 3D Portfolio\"\n";
            $envContent .= "APP_ENV=production\n";
            $envContent .= "APP_DEBUG=false\n";
            $envContent .= "APP_URL=\"http://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "\"\n";
            $envContent .= "APP_TIMEZONE=\"UTC\"\n\n";

            $envContent .= "DB_CONNECTION={$dbDriver}\n";
            if ($dbDriver === 'sqlite') {
                $envContent .= "DB_DATABASE=\"" . __DIR__ . "/storage/database.sqlite\"\n";
            } else {
                $envContent .= "DB_HOST={$dbHost}\n";
                $envContent .= "DB_PORT={$dbPort}\n";
                $envContent .= "DB_DATABASE={$dbName}\n";
                $envContent .= "DB_USERNAME={$dbUser}\n";
                $envContent .= "DB_PASSWORD={$dbPass}\n";
            }

            $envContent .= "\nSESSION_LIFETIME=7200\nMAX_LOGIN_ATTEMPTS=5\nMAX_FILE_SIZE=10485760\n";
            file_put_contents(__DIR__ . '/.env', $envContent);

            // Lock Installer
            file_put_contents($lockFile, "Installed at: " . date('Y-m-d H:i:s') . "\nAdmin: {$adminEmail}\n");

            $success = true;
        } catch (Exception $e) {
            $error = "Database Initialization Failed: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Installation Wizard | 3D Portfolio CMS</title>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/style.css">
  <style>
    body {
      background: #07080d;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 2rem 1rem;
    }
    .install-box {
      width: 100%;
      max-width: 620px;
      padding: 2.5rem;
    }
    .check-item {
      display: flex;
      justify-content: space-between;
      padding: 0.6rem 0;
      border-bottom: 1px solid rgba(255,255,255,0.06);
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
  <div class="ambient-glow-1"></div>
  <div class="ambient-glow-2"></div>

  <div class="glass-panel install-box">
    <div style="text-align: center; margin-bottom: 2rem;">
      <div style="font-size: 2rem; margin-bottom: 0.5rem;">⚡</div>
      <h1 style="font-size: 1.75rem; font-weight: 800;">3D Portfolio Installation</h1>
      <p style="color: var(--text-muted); font-size: 0.95rem;">Automated Setup Wizard for Shared cPanel & Dedicated Hosting</p>
    </div>

    <?php if ($success): ?>
      <div class="alert alert-success" style="display: block; text-align: center;">
        <h3 style="margin-bottom: 0.5rem;">Installation Complete!</h3>
        <p>The database schema and initial portfolio data have been deployed, and your administrator account is ready.</p>
        <div style="margin-top: 1.5rem;">
          <a href="/admin/login.php" class="btn btn-primary">Proceed to Admin Login →</a>
        </div>
      </div>
    <?php else: ?>
      <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <!-- Pre-flight checks -->
      <div style="margin-bottom: 2rem; background: rgba(0,0,0,0.3); padding: 1.25rem; border-radius: 10px; border: 1px solid var(--border-glass);">
        <h3 style="font-size: 1rem; margin-bottom: 0.75rem; color: var(--primary);">System Environment Checks</h3>
        <?php foreach ($checks as $lbl => $pass): ?>
          <div class="check-item">
            <span><?= $lbl ?></span>
            <strong style="color: <?= $pass ? '#00ff88' : '#ff0055' ?>; font-family: var(--font-mono);"><?= $pass ? 'PASS ✓' : 'FAIL ✕' ?></strong>
          </div>
        <?php endforeach; ?>
      </div>

      <?php if ($allChecksPassed): ?>
        <form method="POST">
          <h3 style="font-size: 1.15rem; margin-bottom: 1rem; color: #fff;">1. Database Connection</h3>
          
          <div class="form-group">
            <label class="form-label">Database Engine</label>
            <select name="db_driver" class="form-control" onchange="toggleDbFields(this.value)">
              <option value="mysql" <?= extension_loaded('pdo_mysql') ? 'selected' : '' ?>>MySQL / MariaDB (Recommended for cPanel)</option>
              <option value="sqlite" <?= !extension_loaded('pdo_mysql') ? 'selected' : '' ?>>SQLite (Local / Zero Configuration)</option>
            </select>
          </div>

          <div id="mysql-fields" style="<?= !extension_loaded('pdo_mysql') ? 'display:none;' : '' ?>">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem;">
              <div class="form-group">
                <label class="form-label">Host</label>
                <input type="text" name="db_host" value="127.0.0.1" class="form-control">
              </div>
              <div class="form-group">
                <label class="form-label">Port</label>
                <input type="number" name="db_port" value="3306" class="form-control">
              </div>
            </div>

            <div class="form-group">
              <label class="form-label">Database Name</label>
              <input type="text" name="db_name" value="portfolio_db" class="form-control">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
              <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="db_user" value="root" class="form-control">
              </div>
              <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="db_pass" class="form-control">
              </div>
            </div>
          </div>

          <hr style="border: 0; border-top: 1px solid var(--border-glass); margin: 2rem 0;">

          <h3 style="font-size: 1.15rem; margin-bottom: 1rem; color: #fff;">2. Initial Administrator Credentials</h3>

          <div class="form-group">
            <label class="form-label">Admin Full Name</label>
            <input type="text" name="admin_name" value="Alex Vance" class="form-control" required>
          </div>

          <div class="form-group">
            <label class="form-label">Admin Email</label>
            <input type="email" name="admin_email" value="admin@cyberpulse.dev" class="form-control" required>
          </div>

          <div class="form-group">
            <label class="form-label">Admin Password (Min 8 characters)</label>
            <input type="password" name="admin_password" class="form-control" placeholder="••••••••••••" required minlength="8">
          </div>

          <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1.5rem; padding: 1rem;">
            <span>Deploy Schema & Initialize Site</span>
          </button>
        </form>
      <?php else: ?>
        <div class="alert alert-error">
          Please resolve the failing system checks above before proceeding with installation.
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>

  <script>
    function toggleDbFields(driver) {
      document.getElementById('mysql-fields').style.display = (driver === 'sqlite') ? 'none' : 'block';
    }
  </script>
</body>
</html>

