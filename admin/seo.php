<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';
\App\Core\Auth::requireAdmin();

use App\Models\Project;
use App\Core\Request;
use App\Core\Storage;
use App\Core\View;

// Sitemap generation trigger
if (Request::get('action') === 'generate_sitemap') {
    if (Request::checkCsrf()) {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

        $baseUrl = rtrim(\App\Core\Env::get('APP_URL', 'http://localhost:8000'), '/');

        // Static routes
        $routes = ['/', '/#about', '/#skills', '/projects.php', '/#experience', '/#services', '/contact.php', '/privacy.php', '/terms.php'];
        foreach ($routes as $route) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($baseUrl . $route) . "</loc>\n";
            $xml .= "    <lastmod>" . date('Y-m-d') . "</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>" . ($route === '/' ? '1.0' : '0.8') . "</priority>\n";
            $xml .= "  </url>\n";
        }

        // Project routes
        $projects = Project::all(true);
        foreach ($projects as $p) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($baseUrl . '/project.php?slug=' . $p['slug']) . "</loc>\n";
            $xml .= "    <lastmod>" . date('Y-m-d', strtotime($p['updated_at'] ?? $p['created_at'])) . "</lastmod>\n";
            $xml .= "    <changefreq>monthly</changefreq>\n";
            $xml .= "    <priority>0.9</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= "</urlset>";

        file_put_contents(dirname(__DIR__) . '/public/sitemap.xml', $xml);
        log_activity('GENERATE_SITEMAP', 'Generated fresh XML sitemap with all active projects.');
        set_flash('success', 'sitemap.xml generated and published successfully.');
    }
    header('Location: /admin/seo.php');
    exit;
}

// POST Save SEO Settings
if (Request::isPost()) {
    if (!Request::checkCsrf()) {
        set_flash('error', 'CSRF verification failed.');
        header('Location: /admin/seo.php');
        exit;
    }

    $fields = ['seo_meta_keywords', 'robots_txt_content'];
    foreach ($fields as $f) {
        update_setting($f, Request::post($f), 'seo');
    }

    // Save robots.txt directly to public folder
    $robotsTxt = Request::post('robots_txt_content');
    if ($robotsTxt !== null) {
        file_put_contents(dirname(__DIR__) . '/public/robots.txt', $robotsTxt);
    }

    // OG Image upload
    if (!empty($_FILES['seo_og_image']['name'])) {
        $upload = Storage::upload($_FILES['seo_og_image']);
        if ($upload['success']) {
            update_setting('seo_og_image', $upload['url'], 'seo');
        } else {
            set_flash('error', 'OG image upload error: ' . $upload['error']);
        }
    }

    log_activity('UPDATE_SEO', 'Updated search engine optimization tags, OG parameters, and robots.txt.');
    set_flash('success', 'SEO settings saved successfully.');
    header('Location: /admin/seo.php');
    exit;
}

$robotsPath = dirname(__DIR__) . '/public/robots.txt';
$robotsContent = file_exists($robotsPath) ? file_get_contents($robotsPath) : "User-agent: *\nAllow: /\nDisallow: /admin/\nSitemap: " . url('/sitemap.xml');

View::render('admin.seo', [
    'pageTitle' => 'SEO, OpenGraph & Crawlers',
    'robotsContent' => $robotsContent,
], 'admin');

