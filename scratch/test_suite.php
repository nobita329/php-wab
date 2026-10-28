<?php

require_once dirname(__DIR__) . '/app/bootstrap.php';

use App\Core\Auth;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;
use App\Core\Storage;
use App\Models\ActivityLog;
use App\Models\ContactMessage;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Media;
use App\Models\Message;
use App\Models\Navigation;
use App\Models\Project;
use App\Models\Service;
use App\Models\Skill;
use App\Models\User;

$passed = 0;
$failed = 0;

function assert_test(string $name, bool $condition, string $details = ''): void {
    global $passed, $failed;
    if ($condition) {
        $passed++;
        echo "  [PASS] {$name}\n";
    } else {
        $failed++;
        echo "  [FAIL] {$name} - {$details}\n";
    }
}

echo "========================================================\n";
echo "CYBERPULSE 3D PORTFOLIO & CMS — AUTOMATED TEST SUITE\n";
echo "========================================================\n\n";

// TEST 1: PHP Syntax Check Across All PHP Files
echo "1. Checking PHP Syntax Across Entire Codebase...\n";
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(dirname(__DIR__)));
$syntaxErrors = 0;
foreach ($files as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $output = [];
        $returnVar = 0;
        exec("php -l " . escapeshellarg($file->getPathname()) . " 2>&1", $output, $returnVar);
        if ($returnVar !== 0) {
            $syntaxErrors++;
            echo "Syntax error in: " . $file->getPathname() . " -> " . implode("\n", $output) . "\n";
        }
    }
}
assert_test("PHP Linting / Syntax", $syntaxErrors === 0, "Encountered {$syntaxErrors} syntax errors");

// TEST 2: Database Connection & Tables Check
echo "\n2. Verifying Database Connection and Schema Integrity...\n";
$pdo = Database::connect();
assert_test("Database PDO Connection", $pdo instanceof PDO);
assert_test("Active Database Driver", in_array(Database::getDriver(), ['mysql', 'sqlite']));

$requiredTables = [
    'users', 'site_settings', 'projects', 'project_images', 'skill_categories',
    'skills', 'experiences', 'education', 'services', 'contact_messages',
    'navigation_items', 'media_files', 'activity_logs', 'password_reset_tokens',
    'login_attempts'
];

foreach ($requiredTables as $tbl) {
    $count = $pdo->query("SELECT COUNT(*) FROM `{$tbl}`")->fetchColumn();
    assert_test("Table `{$tbl}` exists and queryable", $count !== false, "Table {$tbl} not accessible");
}

// TEST 3: Admin & User Authentication
echo "\n3. Testing Authentication & Password Security...\n";
$admin = User::findByEmail('admin@cyberpulse.dev');
assert_test("Admin user found in database", $admin !== null);
assert_test("Admin role is 'admin'", $admin && $admin['role'] === 'admin');

// Test Failed Login
$badLogin = Auth::attempt('admin@cyberpulse.dev', 'WrongPassword123!', '127.0.0.1');
assert_test("Failed login rejected", $badLogin['success'] === false);

// Test Successful Login
$goodLogin = Auth::attempt('admin@cyberpulse.dev', 'Admin@Portfolio2026!', '127.0.0.1');
assert_test("Valid admin credentials accepted", $goodLogin['success'] === true);
assert_test("Session authenticated as Admin", Auth::isAdmin() === true);

// TEST 4: CSRF Token Generation & Validation
echo "\n4. Testing CSRF Token Security...\n";
$token = Session::generateCsrfToken();
assert_test("CSRF Token generated", !empty($token) && strlen($token) === 64);
assert_test("Valid CSRF token passes verification", Session::validateCsrfToken($token) === true);
assert_test("Forged CSRF token rejected", Session::validateCsrfToken('invalid_token_12345') === false);

// TEST 5: Projects CRUD Operations
echo "\n5. Testing Projects CRUD Operations...\n";
$initialProjCount = Project::count();
$testProjId = Project::create([
    'title' => 'Test Autonomous WebGL Drone',
    'slug' => 'test-autonomous-webgl-drone-' . time(),
    'excerpt' => 'Test drone visualizer in Three.js',
    'description' => 'Comprehensive case study description for testing.',
    'category' => '3D & Creative Tech',
    'technologies' => 'Three.js, WebGL, PHP 8.2',
    'thumbnail' => '/assets/images/project-nexus.jpg',
    'live_url' => 'https://example.com/drone',
    'github_url' => 'https://github.com/example/drone',
    'client_name' => 'AeroDynamics Lab',
    'project_date' => '2026',
    'is_featured' => 1,
    'is_published' => 1,
    'sort_order' => 99,
]);
assert_test("Project Created", $testProjId > 0);

$fetched = Project::find($testProjId);
assert_test("Project Read / Retrieved", $fetched !== null && $fetched['title'] === 'Test Autonomous WebGL Drone');

$updated = Project::update($testProjId, ['title' => 'Updated Autonomous Drone']);
assert_test("Project Updated", $updated === true);

$fetchedUpdated = Project::find($testProjId);
assert_test("Project Update Verified", $fetchedUpdated['title'] === 'Updated Autonomous Drone');

// Test Project Gallery addition
$imgId = Project::addImage($testProjId, '/assets/images/project-nexus.jpg', 'Cockpit View', 1);
assert_test("Project Gallery Image Added", $imgId > 0);
assert_test("Project Gallery Retrieved", count(Project::getImages($testProjId)) >= 1);
Project::deleteImage($imgId);

// Delete test project
Project::delete($testProjId);
assert_test("Project Deleted Cleanly", Project::find($testProjId) === null);

// TEST 6: Skills CRUD
echo "\n6. Testing Skills CRUD Operations...\n";
$skillId = Skill::create([
    'category_id' => 1,
    'name' => 'GLSL Compute Shaders',
    'proficiency' => 91,
    'icon' => 'sparkles',
    'is_featured' => 1,
    'sort_order' => 10,
]);
assert_test("Skill Created", $skillId > 0);
$skill = Skill::find($skillId);
assert_test("Skill Retrieved", $skill['name'] === 'GLSL Compute Shaders');
Skill::update($skillId, ['proficiency' => 94]);
assert_test("Skill Updated", Skill::find($skillId)['proficiency'] == 94);
Skill::delete($skillId);
assert_test("Skill Deleted", Skill::find($skillId) === null);

// TEST 7: Experience & Education CRUD
echo "\n7. Testing Experience & Education CRUD...\n";
$expId = Experience::create([
    'company' => 'Quantum Interactive Systems',
    'position' => 'Staff 3D Software Engineer',
    'employment_type' => 'Full-time',
    'location' => 'Tokyo, Japan',
    'start_date' => '2024',
    'end_date' => 'Present',
    'is_current' => 1,
    'description' => 'Architected WebGL simulators for aerospace missions.',
    'sort_order' => 1,
]);
assert_test("Experience Created", $expId > 0);
Experience::delete($expId);
assert_test("Experience Deleted", Experience::find($expId) === null);

$eduId = Education::create([
    'institution' => 'MIT Media Lab',
    'degree' => 'Master of Science (M.S.)',
    'field_of_study' => 'Computational Graphics',
    'start_date' => '2017',
    'end_date' => '2019',
    'grade' => 'High Honors',
    'description' => 'Advanced shader optimization.',
    'sort_order' => 1,
]);
assert_test("Education Created", $eduId > 0);
Education::delete($eduId);
assert_test("Education Deleted", Education::find($eduId) === null);

// TEST 8: Services CRUD
echo "\n8. Testing Services CRUD...\n";
$srvId = Service::create([
    'title' => 'Custom GLSL Shader Pipelines',
    'icon' => 'sparkles',
    'summary' => 'Ultra-realistic custom lighting shaders and particle simulations.',
    'features' => "PBR Materials\nReal-time reflections\nRaymarching",
    'sort_order' => 1,
    'is_active' => 1,
]);
assert_test("Service Created", $srvId > 0);
Service::delete($srvId);
assert_test("Service Deleted", Service::find($srvId) === null);

// TEST 9: Contact Messages & Validation
echo "\n9. Testing Contact Message Pipeline...\n";
$msgId = Message::create([
    'name' => 'Sophia Turner',
    'email' => 'sophia@techinnovations.io',
    'subject' => 'Three.js Enterprise Contract',
    'message' => 'We would love to discuss retaining your services for our Q4 product announcement.',
    'ip_address' => '127.0.0.1',
    'user_agent' => 'PHPUnit/TestRunner',
]);
assert_test("Contact Message Logged to DB", $msgId > 0);
$msg = Message::find($msgId);
assert_test("Message Content Verified", $msg['name'] === 'Sophia Turner');
Message::markRead($msgId, true);
assert_test("Message Marked as Read", Message::find($msgId)['is_read'] == 1);
Message::markReplied($msgId, true);
assert_test("Message Marked as Replied", Message::find($msgId)['replied'] == 1);
Message::delete($msgId);
assert_test("Message Deleted Cleanly", Message::find($msgId) === null);

// TEST 10: Site Settings Persistence & Updates
echo "\n10. Testing Site Settings Storage & Dynamic Retrieval...\n";
$origTitle = setting('site_name');
update_setting('site_name', 'Test Vance 3D Lab', 'branding');
// Re-check
$updatedTitle = $pdo->query("SELECT setting_value FROM site_settings WHERE setting_key = 'site_name'")->fetchColumn();
assert_test("Setting Value Updated in Database", $updatedTitle === 'Test Vance 3D Lab');
update_setting('site_name', $origTitle, 'branding');

// TEST 11: SQL Backup Engine
echo "\n11. Testing SQL Backup Generator...\n";
$backupSql = Database::generateSqlBackup();
assert_test("SQL Backup Output Generated", !empty($backupSql));
assert_test("SQL Backup Contains CREATE TABLE statements", str_contains($backupSql, 'CREATE TABLE'));
assert_test("SQL Backup Contains Users Table Data", str_contains($backupSql, 'admin@cyberpulse.dev'));

// Summary
echo "\n========================================================\n";
echo "TEST RESULTS: {$passed} Passed, {$failed} Failed\n";
echo "========================================================\n";

exit($failed > 0 ? 1 : 0);
