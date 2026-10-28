<?php

namespace Database;

use PDO;
use App\Core\Database;

class Seeder
{
    public static function run(PDO $pdo, string $driver = 'mysql'): void
    {
        // First check if already seeded
        $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM site_settings");
        $count = $stmt->fetchColumn();
        if ($count > 0) {
            return; // Already seeded
        }

        $adminHash = password_hash('Admin@Portfolio2026!', PASSWORD_DEFAULT);

        // 1. Admin User
        $userStmt = $pdo->prepare("INSERT INTO users (name, email, password, role, status, avatar, bio, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $userStmt->execute([
            'Alex Vance',
            'admin@cyberpulse.dev',
            $adminHash,
            'admin',
            'active',
            '/assets/images/avatar.jpg',
            'Principal Creative Technologist and Full-Stack Architect specialized in high-performance web applications, Three.js 3D real-time experiences, and scalable cloud architectures.',
            date('Y-m-d H:i:s')
        ]);

        // 2. Site Settings
        $settings = [
            // Branding
            ['site_name', 'Alex Vance | 3D Portfolio', 'branding'],
            ['site_title', 'Alex Vance — Creative Technologist & Full-Stack Architect', 'branding'],
            ['site_description', 'Interactive 3D portfolio and creative laboratory of Alex Vance, featuring real-time WebGL experiences, full-stack systems, and cutting-edge software architecture.', 'branding'],
            ['logo_text', 'ALEX.VANCE', 'branding'],
            ['logo_image', '', 'branding'],
            ['favicon_image', '', 'branding'],
            ['contact_email', 'alex@cyberpulse.dev', 'branding'],
            ['contact_phone', '+1 (555) 948-2026', 'branding'],
            ['contact_location', 'San Francisco, CA / Remote Global', 'branding'],
            ['footer_copyright', '© 2026 Alex Vance. All rights reserved. Crafted with Three.js & Modern PHP.', 'branding'],
            ['social_github', 'https://github.com', 'branding'],
            ['social_linkedin', 'https://linkedin.com', 'branding'],
            ['social_twitter', 'https://x.com', 'branding'],
            ['social_dribbble', 'https://dribbble.com', 'branding'],
            ['social_youtube', '', 'branding'],

            // Hero Section
            ['hero_badge', 'Available for High-Impact Projects', 'hero'],
            ['hero_heading', 'Architecting Next-Gen Web & 3D Realities', 'hero'],
            ['hero_subtitle', 'Creative Technologist & Senior Full-Stack Engineer', 'hero'],
            ['hero_description', 'I merge precision backend engineering with immersive real-time WebGL graphics, building digital experiences that feel alive, performant, and unforgettable.', 'hero'],
            ['hero_btn_primary_label', 'Explore Projects', 'hero'],
            ['hero_btn_primary_link', '#projects', 'hero'],
            ['hero_btn_secondary_label', 'Initiate Contact', 'hero'],
            ['hero_btn_secondary_link', '#contact', 'hero'],
            ['hero_image', '/assets/images/hero-art.jpg', 'hero'],
            ['hero_alignment', 'left', 'hero'],

            // 3D Settings
            ['three_enabled', '1', 'three'],
            ['three_geometry', 'torus_knot', 'three'],
            ['three_rotation_speed_x', '0.003', 'three'],
            ['three_rotation_speed_y', '0.005', 'three'],
            ['three_camera_sensitivity', '0.05', 'three'],
            ['three_particle_count', '850', 'three'],
            ['three_light_intensity', '1.8', 'three'],
            ['three_accent_color_1', '#00f0ff', 'three'],
            ['three_accent_color_2', '#7928ca', 'three'],
            ['three_bg_color', '#07080d', 'three'],
            ['three_wireframe', '1', 'three'],
            ['three_mobile_perf', '1', 'three'],

            // About Section
            ['about_heading', 'Behind the Code & Dimensions', 'about'],
            ['about_bio_p1', 'With over 9 years designing and scaling digital platforms, I bridge the worlds between resilient backend infrastructure and award-winning frontend design. My work centers on high-load distributed systems, WebGL interactive 3D visualizations, and bespoke enterprise solutions.', 'about'],
            ['about_bio_p2', 'Trained both as a computer scientist and visual designer, I treat every millisecond of latency and every pixel of animation with obsessive care. When not shipping code, I experiment with generative shaders, contribute to open-source graphics tooling, and mentor upcoming engineers.', 'about'],
            ['about_years_exp', '9+', 'about'],
            ['about_projects_completed', '48+', 'about'],
            ['about_clients_satisfied', '35+', 'about'],
            ['about_code_commits', '3.4k+', 'about'],
            ['about_resume_url', '/uploads/alex-vance-resume.pdf', 'about'],

            // Theme Settings
            ['theme_primary_color', '#00f0ff', 'theme'],
            ['theme_accent_color', '#7928ca', 'theme'],
            ['theme_bg_color', '#07080d', 'theme'],
            ['theme_card_bg', 'rgba(16, 18, 27, 0.75)', 'theme'],
            ['theme_card_border_radius', '16', 'theme'],
            ['theme_font_family', 'Outfit, sans-serif', 'theme'],
            ['theme_button_style', 'neon-glow', 'theme'],
            ['theme_section_visibility_services', '1', 'theme'],
            ['theme_section_visibility_experience', '1', 'theme'],
            ['theme_section_visibility_skills', '1', 'theme'],

            // Security & General
            ['maintenance_mode', '0', 'general'],
            ['maintenance_message', 'Under scheduled quantum maintenance. Back online shortly.', 'general'],
            ['allow_user_registration', '1', 'security'],
            ['max_login_attempts', '5', 'security'],
            ['login_lockout_minutes', '15', 'security'],
            ['session_lifetime', '7200', 'security'],
            ['seo_meta_keywords', 'full-stack developer, three.js developer, webgl, creative technologist, php 8, mysql', 'seo'],
            ['seo_og_image', '/assets/images/og-preview.jpg', 'seo'],
        ];

        $setStmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value, setting_group, created_at) VALUES (?, ?, ?, ?)");
        foreach ($settings as $s) {
            $setStmt->execute([$s[0], $s[1], $s[2], date('Y-m-d H:i:s')]);
        }

        // 3. Skill Categories
        $catStmt = $pdo->prepare("INSERT INTO skill_categories (name, slug, sort_order) VALUES (?, ?, ?)");
        $categories = [
            ['3D & Creative Tech', 'creative-tech', 1],
            ['Backend & Architecture', 'backend', 2],
            ['Frontend Engineering', 'frontend', 3],
            ['DevOps & Cloud Systems', 'devops-cloud', 4],
        ];
        foreach ($categories as $cat) {
            $catStmt->execute($cat);
        }

        // 4. Skills
        $skillStmt = $pdo->prepare("INSERT INTO skills (category_id, name, proficiency, icon, is_featured, sort_order) VALUES (?, ?, ?, ?, ?, ?)");
        $skills = [
            [1, 'Three.js & WebGL', 95, 'box', 1, 1],
            [1, 'GLSL Shaders & Post-Processing', 90, 'sparkles', 1, 2],
            [1, 'GSAP & Motion Design', 96, 'activity', 1, 3],
            [1, 'Blender 3D Modeling & UV', 82, 'layers', 0, 4],
            [2, 'PHP 8.2+ & Modern Architecture', 98, 'server', 1, 1],
            [2, 'MySQL / MariaDB & PDO', 94, 'database', 1, 2],
            [2, 'RESTful APIs & Microservices', 92, 'cpu', 1, 3],
            [2, 'Redis & Caching Protocols', 88, 'zap', 0, 4],
            [3, 'TypeScript & Modern JavaScript', 96, 'code', 1, 1],
            [3, 'Semantic HTML5 & Modern CSS3', 98, 'layout', 1, 2],
            [3, 'Responsive Design & Glassmorphism', 95, 'monitor', 1, 3],
            [3, 'Performance & Core Web Vitals', 94, 'gauge', 0, 4],
            [4, 'Docker & Linux Server Admin', 88, 'terminal', 1, 1],
            [4, 'cPanel, Apache & Nginx Config', 95, 'hard-drive', 1, 2],
            [4, 'CI/CD Pipelines & Git Workflows', 92, 'git-branch', 0, 3],
            [4, 'Web Security & OWASP Standards', 94, 'shield', 1, 4],
        ];
        foreach ($skills as $sk) {
            $skillStmt->execute($sk);
        }

        // 5. Projects
        $projStmt = $pdo->prepare("INSERT INTO projects (title, slug, excerpt, description, category, technologies, thumbnail, live_url, github_url, client_name, project_date, is_featured, is_published, sort_order, views_count) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $projects = [
            [
                'Nexus Cyberverse — Real-Time 3D Product Configurator',
                'nexus-cyberverse-3d-configurator',
                'Interactive WebGL automotive visualizer with custom PBR materials, dynamic lighting presets, and real-time raytraced reflections.',
                "An enterprise-grade 3D product visualizer engineered for high-end luxury electric hypercars. Built from the ground up using Three.js with custom GLSL shader passes, allowing customers to customize paint finishes, carbon fiber weaves, interior leather stitching, and aero-kit trims in real-time at 60 FPS.\n\nBackend features include server-side configuration persistence with PHP/MySQL, PDF spec-sheet generation, and integration with enterprise dealership CRM systems.",
                '3D & Creative Tech',
                'Three.js, WebGL, GLSL, PHP 8, MySQL, GSAP',
                '/assets/images/project-nexus.jpg',
                'https://example.com/nexus',
                'https://github.com/example/nexus-3d',
                'Aura Dynamics Corp',
                '2025 - 2026',
                1, 1, 1, 1420
            ],
            [
                'AetherPulse — Decentralized Observability Platform',
                'aetherpulse-observability-platform',
                'High-throughput telemetry dashboard processing 150k metrics/sec with custom time-series visualizations and automated anomaly alerts.',
                "AetherPulse is a distributed systems monitoring and anomaly detection engine designed for microservices and cloud workloads. Engineered the real-time event pipeline and interactive analytical dashboard capable of rendering millions of data points smoothly using GPU-accelerated canvas charts.\n\nKey technical highlights include optimized PDO database queries with partitioned time-series storage, automated log pruning, and role-based access control with granular API token permissioning.",
                'Backend & Cloud',
                'PHP 8.2, MariaDB, WebSockets, TypeScript, TailwindCSS',
                '/assets/images/project-aether.jpg',
                'https://example.com/aetherpulse',
                'https://github.com/example/aetherpulse',
                'Novastack Cloud Solutions',
                '2025',
                1, 1, 2, 980
            ],
            [
                'Kroma Studio — Generative Audio-Visual Synth',
                'kroma-studio-generative-synth',
                'Browser-based generative frequency synthesizer and Web Audio particle reactive visualizer with MIDI controller integration.',
                "Kroma Studio synthesizes sound waves into generative 3D visual geometry using the Web Audio API and Three.js instanced meshes. Users can play via on-screen keys, physical USB-MIDI controllers, or upload audio files to watch custom procedural particle vortexes react to low/mid/high frequencies in real-time.\n\nIncludes project preset saving, collaborative session sharing via unique hash URLs, and lossless WAV exporting.",
                '3D & Creative Tech',
                'Web Audio API, Three.js, GSAP, PHP Backend, Canvas',
                '/assets/images/project-kroma.jpg',
                'https://example.com/kroma',
                'https://github.com/example/kroma-synth',
                'Independent Creative Grant',
                '2024 - 2025',
                1, 1, 3, 2150
            ],
            [
                'Spectra CMS — Headless Content & Asset Engine',
                'spectra-cms-headless-engine',
                'Ultra-fast headless content management engine featuring zero-latency caching, dynamic schema builder, and automated media compression.',
                "Engineered a specialized high-performance content hub built specifically for digital agencies handling enterprise brands. Features an intuitive glassmorphic administration panel, automatic WebP image conversion upon upload, revision history diffing, and fine-grained role-based permission control.",
                'Frontend & Full Stack',
                'PHP 8.2, MySQL, REST API, HTML5/CSS3, Alpine.js',
                '/assets/images/project-spectra.jpg',
                'https://example.com/spectra',
                'https://github.com/example/spectra-cms',
                'Vanguard Media Group',
                '2024',
                0, 1, 4, 840
            ],
        ];

        foreach ($projects as $p) {
            $projStmt->execute($p);
        }

        // 6. Experiences
        $expStmt = $pdo->prepare("INSERT INTO experiences (company, position, employment_type, location, start_date, end_date, is_current, description, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $experiences = [
            ['Vortex Labs Inc.', 'Lead Creative Technologist & Architect', 'Full-time', 'San Francisco, CA (Remote)', '2023', 'Present', 1, 'Leading architectural design of immersive WebGL/Three.js interactive web applications, high-performance PHP backend micro-services, and enterprise CMS platforms for Fortune 500 tech clients.', 1],
            ['Hyperion Interactive', 'Senior Full-Stack Engineer', 'Full-time', 'New York, NY', '2020', '2023', 0, 'Spearheaded frontend and backend development across 30+ custom client applications. Mentored a team of 8 engineers, reduced average page latency by 45%, and established secure CI/CD and deployment standards.', 2],
            ['Apex Digital Studio', 'Interactive Web Developer', 'Contract', 'Austin, TX', '2017', '2020', 0, 'Built award-winning digital campaign experiences, custom WordPress/PHP plugins, interactive animations with GSAP, and integrated REST APIs.', 3],
        ];
        foreach ($experiences as $exp) {
            $expStmt->execute($exp);
        }

        // 7. Education
        $eduStmt = $pdo->prepare("INSERT INTO education (institution, degree, field_of_study, start_date, end_date, grade, description, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $education = [
            ['University of California, Berkeley', 'Bachelor of Science (B.S.)', 'Computer Science & Human-Computer Interaction', '2013', '2017', 'Magna Cum Laude (3.92 GPA)', 'Focus on Computer Graphics, Distributed Systems, Algorithms, and UI/UX Design Engineering.', 1],
            ['AWS & Linux Foundation', 'Certified Solutions Architect - Associate', 'Cloud Infrastructure & Security', '2021', '2024', 'Credential ID: AWS-849201', 'Specialized in high-availability web architectures, database replication, and zero-trust security postures.', 2],
        ];
        foreach ($education as $edu) {
            $eduStmt->execute($edu);
        }

        // 8. Services
        $servStmt = $pdo->prepare("INSERT INTO services (title, icon, summary, features, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?)");
        $services = [
            ['Interactive 3D Web & WebGL', 'box', 'Transform your digital presence with custom real-time 3D experiences, interactive product configurators, and GLSL visual effects that captivate visitors.', "Custom Three.js & WebGL scenes\nProcedural shaders & particle systems\nMobile-optimized 60 FPS performance\nAccessible fallback & reduced-motion support", 1, 1],
            ['Full-Stack Web Development', 'server', 'Bespoke, high-performance web applications built on modern PHP 8.2+, MySQL, and responsive HTML5/CSS3 architectures designed for speed and reliability.', "Modern clean architecture (MVC/Modular)\nZero external framework bloat\nRobust RESTful APIs & database optimization\nStrict OWASP security standards", 2, 1],
            ['Custom CMS & Admin Dashboards', 'layout', 'Intuitive, secure administrative backends tailored specifically to your content workflow, media library, and operational data tracking.', "Full CRUD control over all site elements\nGranular role permissions & activity logging\nAutomated database backup generation\nSeamless media library & image processing", 3, 1],
            ['Performance Optimization & SEO', 'zap', 'Deep performance auditing, Core Web Vitals optimization, asset pipeline tuning, and structured data implementation to dominate search rankings.', "Sub-second load times on shared cPanel hosting\nSemantic HTML & dynamic OpenGraph tags\nAutomated XML sitemap & robots.txt\nAsset minification & browser cache tuning", 4, 1],
        ];
        foreach ($services as $srv) {
            $servStmt->execute($srv);
        }

        // 9. Navigation Items
        $navStmt = $pdo->prepare("INSERT INTO navigation_items (title, url, target, location, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?)");
        $navItems = [
            ['Home', '/#home', '_self', 'header', 1, 1],
            ['About', '/#about', '_self', 'header', 2, 1],
            ['Skills', '/#skills', '_self', 'header', 3, 1],
            ['Projects', '/projects.php', '_self', 'header', 4, 1],
            ['Experience', '/#experience', '_self', 'header', 5, 1],
            ['Services', '/#services', '_self', 'header', 6, 1],
            ['Contact', '/contact.php', '_self', 'both', 7, 1],
            ['Privacy Policy', '/privacy.php', '_self', 'footer', 8, 1],
            ['Terms of Service', '/terms.php', '_self', 'footer', 9, 1],
        ];
        foreach ($navItems as $nav) {
            $navStmt->execute($nav);
        }

        // 10. Initial Messages
        $msgStmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message, ip_address, is_read, replied, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $messages = [
            ['Elena Rostova', 'elena.rostova@cyberdynesystems.com', 'Interactive 3D Showroom Inquiry', 'Hello Alex, we were blown away by your 3D product configurator work. Our team is developing an interactive showcase for our upcoming hardware launch and would love to discuss your availability for a contract project starting next month.', '192.168.1.101', 0, 0, date('Y-m-d H:i:s')],
            ['Marcus Chen', 'mchen@vanguardventures.io', 'Keynote Speaker & Workshop', 'Hi Alex, following up on your recent open-source WebGL shader project. Would you be interested in presenting a 45-minute technical session on optimizing Three.js for shared hosting architectures at our upcoming web tech symposium?', '192.168.1.102', 1, 1, date('Y-m-d H:i:s', strtotime('-2 days'))],
        ];
        foreach ($messages as $msg) {
            $msgStmt->execute($msg);
        }

        // 11. Initial Activity Log
        $logStmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, description, ip_address, created_at) VALUES (?, ?, ?, ?, ?)");
        $logStmt->execute([1, 'SYSTEM_INIT', 'System initialized with default database seeds.', '127.0.0.1', date('Y-m-d H:i:s')]);
    }
}

