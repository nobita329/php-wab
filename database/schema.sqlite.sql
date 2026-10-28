-- SQLite equivalent schema for local development & testing
DROP TABLE IF EXISTS `login_attempts`;
DROP TABLE IF EXISTS `password_reset_tokens`;
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

CREATE TABLE `users` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `name` TEXT NOT NULL,
  `email` TEXT NOT NULL UNIQUE,
  `password` TEXT NOT NULL,
  `role` TEXT NOT NULL DEFAULT 'user',
  `status` TEXT NOT NULL DEFAULT 'active',
  `avatar` TEXT NULL,
  `bio` TEXT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `site_settings` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `setting_key` TEXT NOT NULL UNIQUE,
  `setting_value` TEXT NULL,
  `setting_group` TEXT NOT NULL DEFAULT 'general',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `projects` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `title` TEXT NOT NULL,
  `slug` TEXT NOT NULL UNIQUE,
  `excerpt` TEXT NULL,
  `description` TEXT NULL,
  `category` TEXT NOT NULL DEFAULT 'Web Development',
  `technologies` TEXT NULL,
  `thumbnail` TEXT NULL,
  `live_url` TEXT NULL,
  `github_url` TEXT NULL,
  `client_name` TEXT NULL,
  `project_date` TEXT NULL,
  `is_featured` INTEGER NOT NULL DEFAULT 0,
  `is_published` INTEGER NOT NULL DEFAULT 1,
  `sort_order` INTEGER NOT NULL DEFAULT 0,
  `views_count` INTEGER NOT NULL DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `project_images` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `project_id` INTEGER NOT NULL,
  `image_path` TEXT NOT NULL,
  `caption` TEXT NULL,
  `sort_order` INTEGER NOT NULL DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
);

CREATE TABLE `skill_categories` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `name` TEXT NOT NULL,
  `slug` TEXT NOT NULL UNIQUE,
  `sort_order` INTEGER NOT NULL DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `skills` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `category_id` INTEGER NOT NULL,
  `name` TEXT NOT NULL,
  `proficiency` INTEGER NOT NULL DEFAULT 85,
  `icon` TEXT NULL,
  `is_featured` INTEGER NOT NULL DEFAULT 1,
  `sort_order` INTEGER NOT NULL DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `skill_categories` (`id`) ON DELETE CASCADE
);

CREATE TABLE `experiences` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `company` TEXT NOT NULL,
  `position` TEXT NOT NULL,
  `employment_type` TEXT NULL DEFAULT 'Full-time',
  `location` TEXT NULL,
  `start_date` TEXT NOT NULL,
  `end_date` TEXT NULL DEFAULT 'Present',
  `is_current` INTEGER NOT NULL DEFAULT 0,
  `description` TEXT NULL,
  `sort_order` INTEGER NOT NULL DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `education` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `institution` TEXT NOT NULL,
  `degree` TEXT NOT NULL,
  `field_of_study` TEXT NULL,
  `start_date` TEXT NOT NULL,
  `end_date` TEXT NULL,
  `grade` TEXT NULL,
  `description` TEXT NULL,
  `sort_order` INTEGER NOT NULL DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `services` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `title` TEXT NOT NULL,
  `icon` TEXT NOT NULL DEFAULT 'code',
  `summary` TEXT NOT NULL,
  `features` TEXT NULL,
  `sort_order` INTEGER NOT NULL DEFAULT 0,
  `is_active` INTEGER NOT NULL DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `contact_messages` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `name` TEXT NOT NULL,
  `email` TEXT NOT NULL,
  `subject` TEXT NOT NULL,
  `message` TEXT NOT NULL,
  `ip_address` TEXT NULL,
  `user_agent` TEXT NULL,
  `is_read` INTEGER NOT NULL DEFAULT 0,
  `replied` INTEGER NOT NULL DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `navigation_items` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `title` TEXT NOT NULL,
  `url` TEXT NOT NULL,
  `target` TEXT NOT NULL DEFAULT '_self',
  `location` TEXT NOT NULL DEFAULT 'header',
  `sort_order` INTEGER NOT NULL DEFAULT 0,
  `is_active` INTEGER NOT NULL DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `media_files` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `filename` TEXT NOT NULL,
  `original_name` TEXT NOT NULL,
  `file_path` TEXT NOT NULL,
  `file_size` INTEGER NOT NULL,
  `mime_type` TEXT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `activity_logs` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `user_id` INTEGER NULL,
  `action` TEXT NOT NULL,
  `description` TEXT NOT NULL,
  `ip_address` TEXT NULL,
  `user_agent` TEXT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `password_reset_tokens` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `email` TEXT NOT NULL,
  `token` TEXT NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `login_attempts` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `ip_address` TEXT NOT NULL,
  `email` TEXT NOT NULL,
  `attempts` INTEGER NOT NULL DEFAULT 1,
  `last_attempt` DATETIME DEFAULT CURRENT_TIMESTAMP
);

