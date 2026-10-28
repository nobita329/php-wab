-- =======================================================
-- 3D PORTFOLIO & CMS COMPLETE MYSQL / MARIADB DATABASE SCHEMA
-- Compatible with cPanel, Apache, MySQL 8.0+ / MariaDB 10.4+
-- Charset: utf8mb4, Collation: utf8mb4_unicode_ci
-- =======================================================

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `login_attempts`;
DROP TABLE IF EXISTS `activity_logs`;
DROP TABLE IF EXISTS `media_files`;
DROP TABLE IF EXISTS `navigation_items`;
DROP TABLE IF EXISTS `contact_messages`;
DROP TABLE IF EXISTS `services`;
DROP TABLE IF EXISTS `education`;
DROP TABLE IF EXISTS `experiences`;
DROP TABLE IF EXISTS `skills`;
DROP TABLE IF EXISTS `skill_categories`;
DROP TABLE IF EXISTS `project_images`;
DROP TABLE IF EXISTS `projects`;
DROP TABLE IF EXISTS `site_settings`;
DROP TABLE IF EXISTS `users`;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. Users Table
CREATE TABLE `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(191) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'user') NOT NULL DEFAULT 'user',
  `status` ENUM('active', 'inactive', 'banned') NOT NULL DEFAULT 'active',
  `avatar` VARCHAR(255) NULL,
  `bio` TEXT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_users_role` (`role`),
  INDEX `idx_users_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Site Settings Table (Key-Value with Grouping)
CREATE TABLE `site_settings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_key` VARCHAR(100) NOT NULL UNIQUE,
  `setting_value` LONGTEXT NULL,
  `setting_group` VARCHAR(50) NOT NULL DEFAULT 'general',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_settings_group` (`setting_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Projects Table
CREATE TABLE `projects` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(200) NOT NULL,
  `slug` VARCHAR(220) NOT NULL UNIQUE,
  `excerpt` VARCHAR(500) NULL,
  `description` LONGTEXT NULL,
  `category` VARCHAR(100) NOT NULL DEFAULT 'Web Development',
  `technologies` VARCHAR(255) NULL,
  `thumbnail` VARCHAR(255) NULL,
  `live_url` VARCHAR(255) NULL,
  `github_url` VARCHAR(255) NULL,
  `client_name` VARCHAR(150) NULL,
  `project_date` VARCHAR(50) NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `is_published` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `views_count` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_projects_category` (`category`),
  INDEX `idx_projects_featured` (`is_featured`),
  INDEX `idx_projects_published` (`is_published`),
  INDEX `idx_projects_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Project Images / Gallery
CREATE TABLE `project_images` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` INT UNSIGNED NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `caption` VARCHAR(255) NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_project_images_pid` (`project_id`),
  CONSTRAINT `fk_project_images_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Skill Categories
CREATE TABLE `skill_categories` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(120) NOT NULL UNIQUE,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_skill_cats_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Skills Table
CREATE TABLE `skills` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `proficiency` TINYINT UNSIGNED NOT NULL DEFAULT 85,
  `icon` VARCHAR(100) NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_skills_cat` (`category_id`),
  INDEX `idx_skills_featured` (`is_featured`),
  CONSTRAINT `fk_skills_category` FOREIGN KEY (`category_id`) REFERENCES `skill_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Experiences Table
CREATE TABLE `experiences` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `company` VARCHAR(150) NOT NULL,
  `position` VARCHAR(150) NOT NULL,
  `employment_type` VARCHAR(80) NULL DEFAULT 'Full-time',
  `location` VARCHAR(150) NULL,
  `start_date` VARCHAR(50) NOT NULL,
  `end_date` VARCHAR(50) NULL DEFAULT 'Present',
  `is_current` TINYINT(1) NOT NULL DEFAULT 0,
  `description` TEXT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_experience_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Education Table
CREATE TABLE `education` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `institution` VARCHAR(180) NOT NULL,
  `degree` VARCHAR(150) NOT NULL,
  `field_of_study` VARCHAR(150) NULL,
  `start_date` VARCHAR(50) NOT NULL,
  `end_date` VARCHAR(50) NULL,
  `grade` VARCHAR(80) NULL,
  `description` TEXT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_education_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Services Table
CREATE TABLE `services` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(150) NOT NULL,
  `icon` VARCHAR(100) NOT NULL DEFAULT 'code',
  `summary` TEXT NOT NULL,
  `features` TEXT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_services_active` (`is_active`),
  INDEX `idx_services_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Contact Messages Table
CREATE TABLE `contact_messages` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(191) NOT NULL,
  `subject` VARCHAR(200) NOT NULL,
  `message` LONGTEXT NOT NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` VARCHAR(255) NULL,
  `is_read` TINYINT(1) NOT NULL DEFAULT 0,
  `replied` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_messages_read` (`is_read`),
  INDEX `idx_messages_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Navigation Items
CREATE TABLE `navigation_items` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  `target` VARCHAR(20) NOT NULL DEFAULT '_self',
  `location` ENUM('header', 'footer', 'both') NOT NULL DEFAULT 'header',
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_nav_location` (`location`),
  INDEX `idx_nav_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Media Files
CREATE TABLE `media_files` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `filename` VARCHAR(255) NOT NULL,
  `original_name` VARCHAR(255) NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `file_size` INT UNSIGNED NOT NULL,
  `mime_type` VARCHAR(100) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. Activity Logs
CREATE TABLE `activity_logs` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NULL,
  `action` VARCHAR(100) NOT NULL,
  `description` TEXT NOT NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` VARCHAR(255) NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_activity_uid` (`user_id`),
  INDEX `idx_activity_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. Password Reset Tokens
CREATE TABLE `password_reset_tokens` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(191) NOT NULL,
  `token` VARCHAR(100) NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_pwd_reset_token` (`token`),
  INDEX `idx_pwd_reset_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. Login Attempts (Rate Limiting)
CREATE TABLE `login_attempts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` VARCHAR(45) NOT NULL,
  `email` VARCHAR(191) NOT NULL,
  `attempts` INT UNSIGNED NOT NULL DEFAULT 1,
  `last_attempt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_login_ip_email` (`ip_address`, `email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================================================
-- INITIAL SEED DATA
-- Default Admin Password: Admin@Portfolio2026!
-- Hash generated via password_hash('Admin@Portfolio2026!', PASSWORD_DEFAULT)
-- =======================================================

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `status`, `avatar`, `bio`, `created_at`) VALUES
(1, 'Alex Vance', 'admin@cyberpulse.dev', '$2y$10$wO3l5839bH3H0mI82nZ90.u1uVp90QfWbZ4s6k.9hRz71K17WlO8G', 'admin', 'active', '/assets/images/avatar.jpg', 'Principal Creative Technologist and Full-Stack Architect specialized in high-performance web applications, Three.js 3D real-time experiences, and scalable cloud architectures.', NOW());

-- Initial Settings
INSERT INTO `site_settings` (`setting_key`, `setting_value`, `setting_group`) VALUES
-- Branding
('site_name', 'Alex Vance | 3D Portfolio', 'branding'),
('site_title', 'Alex Vance — Creative Technologist & Full-Stack Architect', 'branding'),
('site_description', 'Interactive 3D portfolio and creative laboratory of Alex Vance, featuring real-time WebGL experiences, full-stack systems, and cutting-edge software architecture.', 'branding'),
('logo_text', 'ALEX.VANCE', 'branding'),
('logo_image', '', 'branding'),
('favicon_image', '', 'branding'),
('contact_email', 'alex@cyberpulse.dev', 'branding'),
('contact_phone', '+1 (555) 948-2026', 'branding'),
('contact_location', 'San Francisco, CA / Remote Global', 'branding'),
('footer_copyright', '© 2026 Alex Vance. All rights reserved. Crafted with Three.js & Modern PHP.', 'branding'),
('social_github', 'https://github.com', 'branding'),
('social_linkedin', 'https://linkedin.com', 'branding'),
('social_twitter', 'https://x.com', 'branding'),
('social_dribbble', 'https://dribbble.com', 'branding'),
('social_youtube', '', 'branding'),

-- Hero Section
('hero_badge', 'Available for Q4 High-Impact Projects', 'hero'),
('hero_heading', 'Architecting Next-Gen Web & 3D Realities', 'hero'),
('hero_subtitle', 'Creative Technologist & Senior Full-Stack Engineer', 'hero'),
('hero_description', 'I merge precision backend engineering with immersive real-time WebGL graphics, building digital experiences that feel alive, performant, and unforgettable.', 'hero'),
('hero_btn_primary_label', 'Explore Projects', 'hero'),
('hero_btn_primary_link', '#projects', 'hero'),
('hero_btn_secondary_label', 'Initiate Contact', 'hero'),
('hero_btn_secondary_link', '#contact', 'hero'),
('hero_image', '/assets/images/hero-art.jpg', 'hero'),
('hero_alignment', 'left', 'hero'),

-- 3D Settings
('three_enabled', '1', 'three'),
('three_geometry', 'torus_knot', 'three'),
('three_rotation_speed_x', '0.003', 'three'),
('three_rotation_speed_y', '0.005', 'three'),
('three_camera_sensitivity', '0.05', 'three'),
('three_particle_count', '850', 'three'),
('three_light_intensity', '1.8', 'three'),
('three_accent_color_1', '#00f0ff', 'three'),
('three_accent_color_2', '#7928ca', 'three'),
('three_bg_color', '#07080d', 'three'),
('three_wireframe', '1', 'three'),
('three_mobile_perf', '1', 'three'),

-- About Section
('about_heading', 'Behind the Code & Dimensions', 'about'),
('about_bio_p1', 'With over 9 years designing and scaling digital platforms, I bridge the worlds between resilient backend infrastructure and award-winning frontend design. My work centers on high-load distributed systems, WebGL interactive 3D visualizations, and bespoke enterprise solutions.', 'about'),
('about_bio_p2', 'Trained both as a computer scientist and visual designer, I treat every millisecond of latency and every pixel of animation with obsessive care. When not shipping code, I experiment with generative shaders, contribute to open-source graphics tooling, and mentor upcoming engineers.', 'about'),
('about_years_exp', '9+', 'about'),
('about_projects_completed', '48+', 'about'),
('about_clients_satisfied', '35+', 'about'),
('about_code_commits', '3.4k+', 'about'),
('about_resume_url', '/uploads/alex-vance-resume.pdf', 'about'),

-- Theme Settings
('theme_primary_color', '#00f0ff', 'theme'),
('theme_accent_color', '#7928ca', 'theme'),
('theme_bg_color', '#07080d', 'theme'),
('theme_card_bg', 'rgba(16, 18, 27, 0.75)', 'theme'),
('theme_card_border_radius', '16', 'theme'),
('theme_font_family', 'Outfit, sans-serif', 'theme'),
('theme_button_style', 'neon-glow', 'theme'),
('theme_section_visibility_services', '1', 'theme'),
('theme_section_visibility_experience', '1', 'theme'),
('theme_section_visibility_skills', '1', 'theme'),

-- Security & General Settings
('maintenance_mode', '0', 'general'),
('maintenance_message', 'Under scheduled quantum maintenance. Back online shortly.', 'general'),
('allow_user_registration', '1', 'security'),
('max_login_attempts', '5', 'security'),
('login_lockout_minutes', '15', 'security'),
('session_lifetime', '7200', 'security'),
('seo_meta_keywords', 'full-stack developer, three.js developer, webgl, creative technologist, php 8, mysql, web development, frontend engineer', 'seo'),
('seo_og_image', '/assets/images/og-preview.jpg', 'seo');

-- Initial Skill Categories
INSERT INTO `skill_categories` (`id`, `name`, `slug`, `sort_order`) VALUES
(1, '3D & Creative Tech', 'creative-tech', 1),
(2, 'Backend & Architecture', 'backend', 2),
(3, 'Frontend Engineering', 'frontend', 3),
(4, 'DevOps & Cloud Systems', 'devops-cloud', 4);

-- Initial Skills
INSERT INTO `skills` (`category_id`, `name`, `proficiency`, `icon`, `is_featured`, `sort_order`) VALUES
(1, 'Three.js & WebGL', 95, 'box', 1, 1),
(1, 'GLSL Shaders & Post-Processing', 90, 'sparkles', 1, 2),
(1, 'GSAP & Motion Design', 96, 'activity', 1, 3),
(1, 'Blender 3D Modeling & UV', 82, 'layers', 0, 4),
(2, 'PHP 8.2+ & Modern Architecture', 98, 'server', 1, 1),
(2, 'MySQL / MariaDB & PDO', 94, 'database', 1, 2),
(2, 'RESTful APIs & GraphQL', 92, 'cpu', 1, 3),
(2, 'Redis & Caching Protocols', 88, 'zap', 0, 4),
(3, 'TypeScript & Modern JavaScript', 96, 'code', 1, 1),
(3, 'Semantic HTML5 & Modern CSS3', 98, 'layout', 1, 2),
(3, 'Responsive Design & Glassmorphism', 95, 'monitor', 1, 3),
(3, 'Performance & Web Vitals (CWV)', 94, 'gauge', 0, 4),
(4, 'Docker & Linux Server Admin', 88, 'terminal', 1, 1),
(4, 'cPanel, Apache & Nginx Config', 95, 'hard-drive', 1, 2),
(4, 'CI/CD Pipelines & Git Workflows', 92, 'git-branch', 0, 3),
(4, 'Web Security & OWASP Standards', 94, 'shield', 1, 4);

-- Initial Projects
INSERT INTO `projects` (`id`, `title`, `slug`, `excerpt`, `description`, `category`, `technologies`, `thumbnail`, `live_url`, `github_url`, `client_name`, `project_date`, `is_featured`, `is_published`, `sort_order`, `views_count`) VALUES
(1, 'Nexus Cyberverse — Real-Time 3D Product Configurator', 'nexus-cyberverse-3d-configurator', 'Interactive WebGL automotive visualizer with custom PBR materials, dynamic lighting presets, and real-time raytraced reflections.', 'An enterprise-grade 3D product visualizer engineered for high-end luxury electric hypercars. Built from the ground up using Three.js with custom GLSL shader passes, allowing customers to customize paint finishes, carbon fiber weaves, interior leather stitching, and aero-kit trims in real-time at 60 FPS.\n\nBackend features include server-side configuration persistence with PHP/MySQL, PDF spec-sheet generation, and integration with enterprise dealership CRM systems.', '3D & Creative Tech', 'Three.js, WebGL, GLSL, PHP 8, MySQL, GSAP', '/assets/images/project-nexus.jpg', 'https://example.com/nexus', 'https://github.com/example/nexus-3d', 'Aura Dynamics Corp', '2025 - 2026', 1, 1, 1, 1420),

(2, 'AetherPulse — Decentralized Observability Platform', 'aetherpulse-observability-platform', 'High-throughput telemetry dashboard processing 150k metrics/sec with custom time-series visualizations and automated anomaly alerts.', 'AetherPulse is a distributed systems monitoring and anomaly detection engine designed for microservices and cloud workloads. Engineered the real-time event pipeline and interactive analytical dashboard capable of rendering millions of data points smoothly using GPU-accelerated canvas charts.\n\nKey technical highlights include optimized PDO database queries with partitioned time-series storage, automated log pruning, and role-based access control with granular API token permissioning.', 'Backend & Cloud', 'PHP 8.2, MariaDB, WebSockets, TypeScript, TailwindCSS', '/assets/images/project-aether.jpg', 'https://example.com/aetherpulse', 'https://github.com/example/aetherpulse', 'Novastack Cloud Solutions', '2025', 1, 1, 2, 980),

(3, 'Kroma Studio — Generative Audio-Visual Synth', 'kroma-studio-generative-synth', 'Browser-based generative frequency synthesizer and Web Audio particle reactive visualizer with MIDI controller integration.', 'Kroma Studio synthesizes sound waves into generative 3D visual geometry using the Web Audio API and Three.js instanced meshes. Users can play via on-screen keys, physical USB-MIDI controllers, or upload audio files to watch custom procedural particle vortexes react to low/mid/high frequencies in real-time.\n\nIncludes project preset saving, collaborative session sharing via unique hash URLs, and lossless WAV exporting.', '3D & Creative Tech', 'Web Audio API, Three.js, GSAP, PHP Backend, Canvas', '/assets/images/project-kroma.jpg', 'https://example.com/kroma', 'https://github.com/example/kroma-synth', 'Independent Creative Grant', '2024 - 2025', 1, 1, 3, 2150),

(4, 'Spectra CMS — Headless Content & Asset Engine', 'spectra-cms-headless-engine', 'Ultra-fast headless content management engine featuring zero-latency caching, dynamic schema builder, and automated media compression.', 'Engineered a specialized high-performance content hub built specifically for digital agencies handling enterprise brands. Features an intuitive glassmorphic administration panel, automatic WebP image conversion upon upload, revision history diffing, and fine-grained role-based permission control.', 'Full Stack Web', 'PHP 8.2, MySQL, REST API, HTML5/CSS3, Alpine.js', '/assets/images/project-spectra.jpg', 'https://example.com/spectra', 'https://github.com/example/spectra-cms', 'Vanguard Media Group', '2024', 0, 1, 4, 840);

-- Initial Experiences
INSERT INTO `experiences` (`company`, `position`, `employment_type`, `location`, `start_date`, `end_date`, `is_current`, `description`, `sort_order`) VALUES
('Vortex Labs Inc.', 'Lead Creative Technologist & Architect', 'Full-time', 'San Francisco, CA (Remote)', '2023', 'Present', 1, 'Leading architectural design of immersive WebGL/Three.js interactive web applications, high-performance PHP backend micro-services, and enterprise CMS platforms for Fortune 500 tech clients.', 1),
('Hyperion Interactive', 'Senior Full-Stack Engineer', 'Full-time', 'New York, NY', '2020', '2023', 0, 'Spearheaded frontend and backend development across 30+ custom client applications. Mentored a team of 8 engineers, reduced average page latency by 45%, and established secure CI/CD and deployment standards.', 2),
('Apex Digital Studio', 'Interactive Web Developer', 'Contract', 'Austin, TX', '2017', '2020', 0, 'Built award-winning digital campaign experiences, custom WordPress/PHP plugins, interactive animations with GSAP, and integrated REST APIs.', 3);

-- Initial Education
INSERT INTO `education` (`institution`, `degree`, `field_of_study`, `start_date`, `end_date`, `grade`, `description`, `sort_order`) VALUES
('University of California, Berkeley', 'Bachelor of Science (B.S.)', 'Computer Science & Human-Computer Interaction', '2013', '2017', 'Magna Cum Laude (3.92 GPA)', 'Focus on Computer Graphics, Distributed Systems, Algorithms, and UI/UX Design Engineering.', 1),
('AWS & Linux Foundation', 'Certified Solutions Architect - Associate', 'Cloud Infrastructure & Security', '2021', '2024', 'Credential ID: AWS-849201', 'Specialized in high-availability web architectures, database replication, and zero-trust security postures.', 2);

-- Initial Services
INSERT INTO `services` (`title`, `icon`, `summary`, `features`, `sort_order`, `is_active`) VALUES
('Interactive 3D Web & WebGL', 'box', 'Transform your digital presence with custom real-time 3D experiences, interactive product configurators, and GLSL visual effects that captivate visitors.', 'Custom Three.js & WebGL scenes\nProcedural shaders & particle systems\nMobile-optimized 60 FPS performance\nAccessible fallback & reduced-motion support', 1, 1),
('Full-Stack Web Development', 'server', 'Bespoke, high-performance web applications built on modern PHP 8.2+, MySQL, and responsive HTML5/CSS3 architectures designed for speed and reliability.', 'Modern clean architecture (MVC/Modular)\nZero external framework bloat\nRobust RESTful APIs & database optimization\nStrict OWASP security standards', 2, 1),
('Custom CMS & Admin Dashboards', 'layout', 'Intuitive, secure administrative backends tailored specifically to your content workflow, media library, and operational data tracking.', 'Full CRUD control over all site elements\nGranular role permissions & activity logging\nAutomated database backup generation\nSeamless media library & image processing', 3, 1),
('Performance Optimization & SEO', 'zap', 'Deep performance auditing, Core Web Vitals optimization, asset pipeline tuning, and structured data implementation to dominate search rankings.', 'Sub-second load times on shared cPanel hosting\nSemantic HTML & dynamic OpenGraph tags\nAutomated XML sitemap & robots.txt\nAsset minification & browser cache tuning', 4, 1);

-- Initial Navigation Items
INSERT INTO `navigation_items` (`title`, `url`, `target`, `location`, `sort_order`, `is_active`) VALUES
('Home', '/#home', '_self', 'header', 1, 1),
('About', '/#about', '_self', 'header', 2, 1),
('Skills', '/#skills', '_self', 'header', 3, 1),
('Projects', '/projects.php', '_self', 'header', 4, 1),
('Experience', '/#experience', '_self', 'header', 5, 1),
('Services', '/#services', '_self', 'header', 6, 1),
('Contact', '/contact.php', '_self', 'both', 7, 1),
('Privacy Policy', '/privacy.php', '_self', 'footer', 8, 1),
('Terms of Service', '/terms.php', '_self', 'footer', 9, 1);

-- Initial Sample Messages
INSERT INTO `contact_messages` (`name`, `email`, `subject`, `message`, `ip_address`, `is_read`, `replied`, `created_at`) VALUES
('Elena Rostova', 'elena.rostova@cyberdynesystems.com', 'Interactive 3D Showroom Inquiry', 'Hello Alex, we were blown away by your 3D product configurator work. Our team is developing an interactive showcase for our upcoming hardware launch and would love to discuss your availability for a contract project starting next month.', '192.168.1.101', 0, 0, NOW()),
('Marcus Chen', 'mchen@vanguardventures.io', 'Keynote Speaker & Workshop', 'Hi Alex, following up on your recent open-source WebGL shader project. Would you be interested in presenting a 45-minute technical session on optimizing Three.js for shared hosting architectures at our upcoming web tech symposium?', '192.168.1.102', 1, 1, DATE_SUB(NOW(), INTERVAL 2 DAY));

-- Initial Activity Log
INSERT INTO `activity_logs` (`user_id`, `action`, `description`, `ip_address`) VALUES
(1, 'SYSTEM_INSTALL', 'Database schema and initial settings successfully installed.', '127.0.0.1');

