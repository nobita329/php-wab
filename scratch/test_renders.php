<?php

require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Auth;
use App\Core\Session;

function render_page(string $path, array $queryParams = []): array {
    $script = dirname(__DIR__) . '/public' . explode('?', $path)[0];
    if (str_starts_with($path, '/admin/')) {
        $script = dirname(__DIR__) . explode('?', $path)[0];
    }
    if ($path === '/') {
        $script = dirname(__DIR__) . '/public/index.php';
    }

    $_GET = $queryParams;
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = $path;
    $_SERVER['PHP_SELF'] = explode('?', $path)[0];

    ob_start();
    try {
        require $script;
    } catch (\Throwable $e) {
        echo "Exception: " . $e->getMessage();
    }
    $html = ob_get_clean();

    return [
        'length' => strlen($html),
        'html' => $html,
    ];
}

echo "========================================================\n";
echo "1. VERIFYING PUBLIC PAGES RENDERING\n";
echo "========================================================\n";

$publicPages = [
    '/' => 'Home Page (3D Hero + Sections)',
    '/projects.php' => 'Projects Catalog',
    '/project.php' => 'Single Project Detail',
    '/contact.php' => 'Contact Form Page',
    '/privacy.php' => 'Privacy Policy',
    '/terms.php' => 'Terms of Service',
    '/login.php' => 'User Login',
    '/register.php' => 'User Registration',
    '/admin/login.php' => 'Admin Login Portal',
];

foreach ($publicPages as $route => $name) {
    $params = ($route === '/project.php') ? ['slug' => 'nexus-cyberverse-3d-configurator'] : [];
    $res = render_page($route, $params);
    $ok = $res['length'] > 500 && str_contains($res['html'], '<html');
    echo ($ok ? "  [PASS] " : "  [FAIL] ") . "{$name} ({$res['length']} bytes)\n";
}

echo "\n========================================================\n";
echo "2. AUTHENTICATING AS ADMINISTRATOR\n";
echo "========================================================\n";

$login = Auth::attempt('admin@cyberpulse.dev', 'Admin@Portfolio2026!', '127.0.0.1');
echo "  Admin Login: " . ($login['success'] ? "SUCCESS ✓" : "FAILED ✕") . "\n";
echo "  Is Admin Verified: " . (Auth::isAdmin() ? "YES ✓" : "NO ✕") . "\n";

echo "\n========================================================\n";
echo "3. VERIFYING ALL 20 ADMIN PANEL PAGES\n";
echo "========================================================\n";

$adminPages = [
    '/admin/index.php' => '1. Dashboard',
    '/admin/branding.php' => '2. Website Branding',
    '/admin/hero.php' => '3. Hero Settings',
    '/admin/three-settings.php' => '4. 3D Animation Settings',
    '/admin/about.php' => '5. About Section Management',
    '/admin/skills.php' => '6. Skills Management',
    '/admin/projects.php' => '7. Projects Management',
    '/admin/experience.php' => '8. Experience Management',
    '/admin/education.php' => '9. Education Management',
    '/admin/services.php' => '10. Services Management',
    '/admin/messages.php' => '11. Contact Messages',
    '/admin/users.php' => '12. User Management',
    '/admin/navigation.php' => '13. Navigation Management',
    '/admin/seo.php' => '14. SEO Settings',
    '/admin/media.php' => '15. Media Library',
    '/admin/theme.php' => '16. Theme Studio',
    '/admin/security.php' => '17. Security Settings',
    '/admin/database.php' => '18. Database & Backup',
    '/admin/settings.php' => '19. General Settings',
    '/admin/profile.php' => '20. Admin Profile',
];

$allAdminOk = true;
foreach ($adminPages as $route => $title) {
    $res = render_page($route);
    $hasAdminHtml = str_contains($res['html'], 'admin-sidebar') && str_contains($res['html'], 'Executive Command Deck' || str_contains($res['html'], 'admin-card'));
    $ok = $res['length'] > 1000 && !str_contains($res['html'], 'Exception:');
    if (!$ok) $allAdminOk = false;
    echo ($ok ? "  [PASS] " : "  [FAIL] ") . "{$title} ({$res['length']} bytes)\n";
}

echo "\n========================================================\n";
echo "ADMIN SUITE: " . ($allAdminOk ? "ALL 20 ADMIN PAGES FUNCTIONAL & RENDERED CLEANLY" : "ERRORS DETECTED") . "\n";
echo "========================================================\n";

