-- ===================================================================
-- JobberRecruit - Complete MariaDB Migration
-- Generated from PHP CodeIgniter 4 migration files for MariaDB
-- Run this in phpMyAdmin or MariaDB CLI to set up the full schema.
-- ===================================================================

SET FOREIGN_KEY_CHECKS = 0;
SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ===================================================================
-- Migration 1: Shield Auth Tables (CodeIgniter Shield 2020-12-28)
-- Base authentication tables + custom app columns
-- ===================================================================

CREATE TABLE IF NOT EXISTS `users` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `username` varchar(30) DEFAULT NULL,
    `status` varchar(255) DEFAULT NULL,
    `status_message` varchar(255) DEFAULT NULL,
    `active` tinyint(1) NOT NULL DEFAULT 0,
    `last_active` datetime DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    `deleted_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `auth_identities` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(11) unsigned NOT NULL,
    `type` varchar(255) NOT NULL,
    `name` varchar(255) DEFAULT NULL,
    `secret` varchar(255) NOT NULL,
    `secret2` varchar(255) DEFAULT NULL,
    `expires` datetime DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `type_secret` (`type`,`secret`),
    KEY `user_id` (`user_id`),
    CONSTRAINT `auth_identities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `auth_logins` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `ip_address` varchar(255) NOT NULL,
    `user_agent` varchar(255) DEFAULT NULL,
    `id_type` varchar(255) NOT NULL,
    `identifier` varchar(255) NOT NULL,
    `user_id` int(11) unsigned DEFAULT NULL,
    `date` datetime NOT NULL,
    `success` tinyint(1) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `id_type_identifier` (`id_type`,`identifier`),
    KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `auth_token_logins` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `ip_address` varchar(255) NOT NULL,
    `user_agent` varchar(255) DEFAULT NULL,
    `id_type` varchar(255) NOT NULL,
    `identifier` varchar(255) NOT NULL,
    `user_id` int(11) unsigned DEFAULT NULL,
    `date` datetime NOT NULL,
    `success` tinyint(1) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `id_type_identifier` (`id_type`,`identifier`),
    KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `auth_remember_tokens` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `selector` varchar(255) NOT NULL,
    `hashedValidator` varchar(255) NOT NULL,
    `user_id` int(11) unsigned NOT NULL,
    `expires` datetime NOT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `selector` (`selector`),
    KEY `user_id` (`user_id`),
    CONSTRAINT `auth_remember_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `auth_groups_users` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(11) unsigned NOT NULL,
    `group` varchar(255) NOT NULL,
    `created_at` datetime NOT NULL,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    CONSTRAINT `auth_groups_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `auth_permissions_users` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(11) unsigned NOT NULL,
    `permission` varchar(255) NOT NULL,
    `created_at` datetime NOT NULL,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    CONSTRAINT `auth_permissions_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================================
-- Migration 2: 2021-01-01-000000_CreateInitialTables
-- All core application tables
-- ===================================================================

CREATE TABLE IF NOT EXISTS `countries` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL UNIQUE,
    `iso_code` VARCHAR(10) NOT NULL UNIQUE,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `states` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `country_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    CONSTRAINT `states_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `industries` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `parent_id` INT UNSIGNED NULL,
    `name` VARCHAR(150) NOT NULL,
    `slug` VARCHAR(150) NOT NULL UNIQUE,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    CONSTRAINT `industries_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `industries`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_categories` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `parent_id` INT UNSIGNED NULL,
    `name` VARCHAR(150) NOT NULL,
    `slug` VARCHAR(150) NOT NULL UNIQUE,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    CONSTRAINT `job_categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `job_categories`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `subscription_plans` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NULL,
    `price` DECIMAL(15,2) DEFAULT 0.00,
    `duration` INT DEFAULT 30,
    `job_limit` INT DEFAULT 0,
    `featured_limit` INT DEFAULT 0,
    `billing_cycle` VARCHAR(50) DEFAULT 'monthly',
    `credit_allowance` INT DEFAULT 0,
    `discount_percentage` DECIMAL(5,2) DEFAULT 0.00,
    `description` TEXT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `plans` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(50) NULL,
    `name` VARCHAR(100) NOT NULL,
    `base_price` DECIMAL(15,2) DEFAULT 0.00,
    `pricing_tiers` TEXT NULL,
    `billing_type` VARCHAR(20) DEFAULT 'subscription',
    `plan_type` VARCHAR(20) DEFAULT 'subscription',
    `monthly_job_credits` INT DEFAULT 0,
    `features` TEXT NULL,
    `paystack_plan_code` VARCHAR(100) NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `bundle_packages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `price` DECIMAL(15,2) NOT NULL,
    `credits` INT NOT NULL,
    `cost_per_credit` DECIMAL(15,2) NOT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `plan_bundles` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL,
    `job_credits` INT NOT NULL,
    `price` DECIMAL(15,2) NOT NULL,
    `price_per_credit` DECIMAL(15,2) NOT NULL,
    `is_best_value` TINYINT(1) DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `pricing_rules` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `plan_id` INT UNSIGNED NULL,
    `bundle_id` INT UNSIGNED NULL,
    `action` VARCHAR(50) NOT NULL,
    `price` DECIMAL(15,2) NOT NULL,
    `currency` VARCHAR(10) DEFAULT 'NGN',
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `user_subscriptions` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `plan_id` INT UNSIGNED NULL,
    `starts_at` DATETIME NOT NULL,
    `ends_at` DATETIME NOT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `auto_renew` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    CONSTRAINT `user_subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `wallets` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `balance` DECIMAL(15,2) DEFAULT 0.00,
    `currency` VARCHAR(10) DEFAULT 'NGN',
    `is_locked` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    CONSTRAINT `wallets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `wallet_transactions` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `wallet_id` INT UNSIGNED NOT NULL,
    `amount` DECIMAL(15,2) NOT NULL,
    `type` VARCHAR(20) NOT NULL,
    `description` VARCHAR(255) NULL,
    `reference` VARCHAR(100) NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    CONSTRAINT `wallet_transactions_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_credit_wallets` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `credits` INT DEFAULT 0,
    `source` VARCHAR(20) NOT NULL,
    `reference_id` INT UNSIGNED NULL,
    `expires_at` DATETIME NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    CONSTRAINT `job_credit_wallets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_credit_transactions` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `type` VARCHAR(20) NOT NULL,
    `credits` INT NOT NULL,
    `reference` VARCHAR(100) NULL,
    `description` VARCHAR(255) NULL,
    `meta` TEXT NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    CONSTRAINT `job_credit_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `payments` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `employer_id` INT UNSIGNED NULL,
    `reference` VARCHAR(100) NOT NULL UNIQUE,
    `amount` DECIMAL(15,2) NOT NULL,
    `status` VARCHAR(20) DEFAULT 'pending',
    `payment_method` VARCHAR(50) NULL,
    `metadata` TEXT NULL,
    `paid_at` DATETIME NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `employers` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `company_name` VARCHAR(255) NULL,
    `logo` VARCHAR(255) NULL,
    `industry_id` INT UNSIGNED NULL,
    `company_size` VARCHAR(50) NULL,
    `website` VARCHAR(255) NULL,
    `company_address` VARCHAR(255) NULL,
    `state_id` INT UNSIGNED NULL,
    `description` TEXT NULL,
    `contact_name` VARCHAR(150) NULL,
    `contact_email` VARCHAR(150) NULL,
    `contact_phone` VARCHAR(50) NULL,
    `verification_doc` VARCHAR(255) NULL,
    `unlimited_access` TINYINT(1) DEFAULT 0,
    `unlimited_until` DATETIME NULL,
    `tin_number` VARCHAR(50) NULL,
    `is_verified` TINYINT(1) DEFAULT 0,
    `verification_status` VARCHAR(20) DEFAULT 'pending',
    `verification_documents` TEXT NULL,
    `verification_notes` TEXT NULL,
    `verified_at` DATETIME NULL,
    `verified_by` INT UNSIGNED NULL,
    `rejection_reason` TEXT NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    CONSTRAINT `employers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `employers_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `employers_industry_id_foreign` FOREIGN KEY (`industry_id`) REFERENCES `industries`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `employer_documents` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `employer_id` INT UNSIGNED NOT NULL,
    `document_type` VARCHAR(100) NOT NULL,
    `file_path` VARCHAR(255) NOT NULL,
    `file_name` VARCHAR(255) NOT NULL,
    `file_size` INT NOT NULL,
    `mime_type` VARCHAR(100) NOT NULL,
    `status` VARCHAR(20) DEFAULT 'pending',
    `uploaded_at` DATETIME NULL,
    `verified_at` DATETIME NULL,
    `verified_by` INT UNSIGNED NULL,
    CONSTRAINT `employer_documents_employer_id_foreign` FOREIGN KEY (`employer_id`) REFERENCES `employers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `employer_verification_logs` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `employer_id` INT UNSIGNED NOT NULL,
    `admin_id` INT UNSIGNED NOT NULL,
    `action` VARCHAR(50) NOT NULL,
    `notes` TEXT NULL,
    `created_at` DATETIME NULL,
    CONSTRAINT `employer_verification_logs_employer_id_foreign` FOREIGN KEY (`employer_id`) REFERENCES `employers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_seekers` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `full_name` VARCHAR(150) NULL,
    `profile_picture` VARCHAR(255) NULL,
    `dob` DATE NULL,
    `gender` VARCHAR(20) NULL,
    `phone` VARCHAR(50) NULL,
    `location` VARCHAR(255) NULL,
    `bio` TEXT NULL,
    `state_id` INT UNSIGNED NULL,
    `job_title` VARCHAR(150) NULL,
    `employment_type` VARCHAR(100) NULL,
    `skills` TEXT NULL,
    `experience_years` INT NULL,
    `education_level` VARCHAR(100) NULL,
    `languages` TEXT NULL,
    `resume` VARCHAR(255) NULL,
    `cover_letter` VARCHAR(255) NULL,
    `portfolio` VARCHAR(255) NULL,
    `desired_salary` VARCHAR(100) NULL,
    `salary_type` VARCHAR(50) NULL,
    `availability` VARCHAR(100) NULL,
    `is_verified` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    CONSTRAINT `job_seekers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `job_seekers_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `jobs` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `employer_id` INT UNSIGNED NOT NULL,
    `category_id` INT UNSIGNED NOT NULL,
    `state_id` INT UNSIGNED NULL,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `description` TEXT NOT NULL,
    `job_type` VARCHAR(50) NULL,
    `location_type` VARCHAR(50) NULL,
    `salary_type` VARCHAR(50) NULL,
    `salary_period` VARCHAR(50) NULL,
    `salary` VARCHAR(100) NULL,
    `salary_details` VARCHAR(255) NULL,
    `industry_id` INT UNSIGNED NULL,
    `education_level` VARCHAR(100) NULL,
    `experience_level` VARCHAR(100) NULL,
    `experience` TEXT NULL,
    `skills` TEXT NULL,
    `requirements` TEXT NULL,
    `application_method` VARCHAR(50) NULL,
    `application_access` VARCHAR(50) NULL,
    `whatsapp_link` VARCHAR(255) NULL,
    `application_email` VARCHAR(150) NULL,
    `external_url` VARCHAR(255) NULL,
    `application_deadline` DATE NULL,
    `start_date` DATE NULL,
    `contact_email` VARCHAR(150) NULL,
    `contact_phone` VARCHAR(50) NULL,
    `application` TEXT NULL,
    `is_featured` TINYINT(1) DEFAULT 0,
    `featured_until` DATETIME NULL,
    `is_anonymous` TINYINT(1) DEFAULT 0,
    `network_blast` TINYINT(1) DEFAULT 0,
    `views` INT DEFAULT 0,
    `accommodation` VARCHAR(100) NULL,
    `notification_preferences` TEXT NULL,
    `admin_status` VARCHAR(20) DEFAULT 'pending',
    `admin_reviewed_at` DATETIME NULL,
    `admin_notes` TEXT NULL,
    `status` VARCHAR(20) DEFAULT 'open',
    `is_verified` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    CONSTRAINT `jobs_employer_id_foreign` FOREIGN KEY (`employer_id`) REFERENCES `employers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `jobs_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `job_categories`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `jobs_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `jobs_industry_id_foreign` FOREIGN KEY (`industry_id`) REFERENCES `industries`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_applications` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `job_id` INT UNSIGNED NOT NULL,
    `job_seeker_id` INT UNSIGNED NULL,
    `first_name` VARCHAR(100) NULL,
    `last_name` VARCHAR(100) NULL,
    `email` VARCHAR(150) NULL,
    `phone` VARCHAR(50) NULL,
    `cv_path` VARCHAR(255) NULL,
    `cover_letter` TEXT NULL,
    `availability` VARCHAR(100) NULL,
    `salary_expectation` VARCHAR(100) NULL,
    `work_eligibility` VARCHAR(100) NULL,
    `consent` TINYINT(1) DEFAULT 1,
    `status` VARCHAR(20) DEFAULT 'pending',
    `status_message` TEXT NULL,
    `is_guest` TINYINT(1) DEFAULT 0,
    `guest_email_sent` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    CONSTRAINT `job_applications_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `job_applications_job_seeker_id_foreign` FOREIGN KEY (`job_seeker_id`) REFERENCES `job_seekers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_questions` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `job_id` INT UNSIGNED NOT NULL,
    `question_text` TEXT NOT NULL,
    `question_type` VARCHAR(20) DEFAULT 'text',
    `is_required` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME NULL,
    CONSTRAINT `job_questions_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_application_answers` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `application_id` INT UNSIGNED NOT NULL,
    `question_id` INT UNSIGNED NOT NULL,
    `answer_text` TEXT NOT NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    CONSTRAINT `job_application_answers_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `job_applications`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `job_application_answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `job_questions`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `application_notes` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `application_id` INT UNSIGNED NOT NULL,
    `employer_id` INT UNSIGNED NOT NULL,
    `note` TEXT NOT NULL,
    `type` VARCHAR(20) DEFAULT 'internal',
    `created_by` INT UNSIGNED NOT NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    CONSTRAINT `application_notes_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `job_applications`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_notifications` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `employer_id` INT UNSIGNED NOT NULL,
    `job_id` INT UNSIGNED NULL,
    `application_id` INT UNSIGNED NULL,
    `type` VARCHAR(50) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `message` TEXT NOT NULL,
    `is_read` TINYINT(1) DEFAULT 0,
    `read_at` DATETIME NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    CONSTRAINT `job_notifications_employer_id_foreign` FOREIGN KEY (`employer_id`) REFERENCES `employers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_clicks` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `job_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` VARCHAR(255) NULL,
    `created_at` DATETIME NULL,
    CONSTRAINT `job_clicks_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `saved_jobs` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `job_id` INT UNSIGNED NOT NULL,
    `created_at` DATETIME NULL,
    CONSTRAINT `saved_jobs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `saved_jobs_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_alerts` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `job_seeker_id` INT UNSIGNED NOT NULL,
    `keyword` VARCHAR(150) NULL,
    `location_id` INT UNSIGNED NULL,
    `frequency` VARCHAR(20) DEFAULT 'daily',
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    CONSTRAINT `job_alerts_job_seeker_id_foreign` FOREIGN KEY (`job_seeker_id`) REFERENCES `job_seekers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `job_alerts_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `states`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `admins` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `full_name` VARCHAR(150) NULL,
    `role` VARCHAR(100) DEFAULT 'super_admin',
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    CONSTRAINT `admins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `blogs` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `admin_id` INT UNSIGNED NOT NULL,
    `title` VARCHAR(155) NOT NULL,
    `slug` VARCHAR(155) NOT NULL UNIQUE,
    `content` TEXT NOT NULL,
    `thumbnail` VARCHAR(155) NULL,
    `status` VARCHAR(20) DEFAULT 'draft',
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    CONSTRAINT `blogs_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `employer_industries` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `employer_id` INT UNSIGNED NOT NULL,
    `industry_id` INT UNSIGNED NOT NULL,
    CONSTRAINT `employer_industries_employer_id_foreign` FOREIGN KEY (`employer_id`) REFERENCES `employers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `employer_industries_industry_id_foreign` FOREIGN KEY (`industry_id`) REFERENCES `industries`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_seeker_industries` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `job_seeker_id` INT UNSIGNED NOT NULL,
    `industry_id` INT UNSIGNED NOT NULL,
    CONSTRAINT `job_seeker_industries_job_seeker_id_foreign` FOREIGN KEY (`job_seeker_id`) REFERENCES `job_seekers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `job_seeker_industries_industry_id_foreign` FOREIGN KEY (`industry_id`) REFERENCES `industries`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_seeker_categories` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `job_seeker_id` INT UNSIGNED NOT NULL,
    `category_id` INT UNSIGNED NOT NULL,
    CONSTRAINT `job_seeker_categories_job_seeker_id_foreign` FOREIGN KEY (`job_seeker_id`) REFERENCES `job_seekers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `job_seeker_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `job_categories`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `testimonials` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `content` TEXT NOT NULL,
    `rating` TINYINT DEFAULT 5,
    `is_published` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    CONSTRAINT `testimonials_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `password_resets` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `token` VARCHAR(64) NOT NULL,
    `expires_at` DATETIME NOT NULL,
    `created_at` DATETIME NULL,
    CONSTRAINT `password_resets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `resumes` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `summary` TEXT NULL,
    `template_id` VARCHAR(50) DEFAULT 'classic',
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    CONSTRAINT `resumes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `resume_experiences` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `resume_id` INT UNSIGNED NOT NULL,
    `company` VARCHAR(255) NOT NULL,
    `position` VARCHAR(255) NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NULL,
    `description` TEXT NULL,
    `is_current` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME NULL,
    CONSTRAINT `resume_experiences_resume_id_foreign` FOREIGN KEY (`resume_id`) REFERENCES `resumes`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `resume_education` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `resume_id` INT UNSIGNED NOT NULL,
    `institution` VARCHAR(255) NOT NULL,
    `degree` VARCHAR(255) NOT NULL,
    `field_of_study` VARCHAR(255) NULL,
    `graduation_date` DATE NULL,
    `created_at` DATETIME NULL,
    CONSTRAINT `resume_education_resume_id_foreign` FOREIGN KEY (`resume_id`) REFERENCES `resumes`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `resume_skills` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `resume_id` INT UNSIGNED NOT NULL,
    `skill_name` VARCHAR(100) NOT NULL,
    `proficiency_level` VARCHAR(20) DEFAULT 'intermediate',
    `created_at` DATETIME NULL,
    CONSTRAINT `resume_skills_resume_id_foreign` FOREIGN KEY (`resume_id`) REFERENCES `resumes`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `referrals` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `referrer_id` INT UNSIGNED NOT NULL,
    `referred_id` INT UNSIGNED NOT NULL,
    `status` VARCHAR(20) DEFAULT 'pending',
    `reward_amount` DECIMAL(15,2) DEFAULT 0.00,
    `reward_paid` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME NULL,
    CONSTRAINT `referrals_referrer_id_foreign` FOREIGN KEY (`referrer_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `referrals_referred_id_foreign` FOREIGN KEY (`referred_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `newsletters` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `subject` VARCHAR(255) NOT NULL,
    `content` TEXT NOT NULL,
    `status` VARCHAR(20) DEFAULT 'draft',
    `sent_at` DATETIME NULL,
    `created_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `webinars` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `speaker_name` VARCHAR(255) NULL,
    `speaker_bio` TEXT NULL,
    `scheduled_at` DATETIME NOT NULL,
    `duration` INT NULL COMMENT 'minutes',
    `meeting_link` VARCHAR(255) NOT NULL,
    `status` VARCHAR(20) DEFAULT 'upcoming',
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `webinar_registrations` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `webinar_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `created_at` DATETIME NULL,
    CONSTRAINT `webinar_registrations_webinar_id_foreign` FOREIGN KEY (`webinar_id`) REFERENCES `webinars`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `webinar_registrations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_reports` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `job_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NULL,
    `reason` VARCHAR(100) NOT NULL,
    `details` TEXT NULL,
    `status` VARCHAR(20) DEFAULT 'pending',
    `created_at` DATETIME NULL,
    CONSTRAINT `job_reports_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `courses` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `description` TEXT NOT NULL,
    `price` DECIMAL(15,2) DEFAULT 0.00,
    `instructor` VARCHAR(150) NOT NULL,
    `thumbnail` VARCHAR(255) NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `course_enrollments` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `course_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `payment_reference` VARCHAR(100) NULL,
    `status` VARCHAR(20) DEFAULT 'enrolled',
    `created_at` DATETIME NULL,
    CONSTRAINT `course_enrollments_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `course_enrollments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `queue_jobs` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `queue` VARCHAR(100) DEFAULT 'default',
    `payload` LONGTEXT NOT NULL,
    `attempts` TINYINT UNSIGNED DEFAULT 0,
    `status` VARCHAR(20) DEFAULT 'pending',
    `error` TEXT NULL,
    `available_at` DATETIME NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `email_verifications` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `expires_at` DATETIME NOT NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    CONSTRAINT `email_verifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `application_references` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `application_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `title` VARCHAR(255) NULL,
    `email` VARCHAR(150) NOT NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    CONSTRAINT `application_references_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `job_applications`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `application_answers` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `application_id` INT UNSIGNED NOT NULL,
    `question_id` INT UNSIGNED NOT NULL,
    `answer` TEXT NOT NULL,
    `created_at` DATETIME NULL,
    CONSTRAINT `application_answers_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `job_applications`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `application_answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `job_questions`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================================
-- Migration 3: 2025-10-02-143155_AddUserTypeToUsers
-- ===================================================================

ALTER TABLE `users`
    ADD COLUMN IF NOT EXISTS `user_type` ENUM('job_seeker','employer','admin') DEFAULT 'job_seeker' AFTER `active`;

-- ===================================================================
-- Migration 4: 2026-04-24-101007_CreateAffiliateSettings
-- ===================================================================

CREATE TABLE IF NOT EXISTS `affiliate_settings` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(100) NOT NULL UNIQUE,
    `value` TEXT NOT NULL,
    `description` VARCHAR(255) NULL,
    `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `affiliate_settings` (`key`, `value`, `description`, `updated_at`) VALUES
    ('referral_reward_employer', '1000', 'Reward amount for referring a new employer who makes a payment', NOW()),
    ('referral_reward_candidate', '200', 'Reward amount for referring a new candidate who completes their profile', NOW()),
    ('referral_program_active', '1', 'Is the referral program globally enabled?', NOW());

ALTER TABLE `users`
    ADD COLUMN IF NOT EXISTS `referred_by` INT UNSIGNED NULL AFTER `id`,
    ADD COLUMN IF NOT EXISTS `referral_code` VARCHAR(20) NULL UNIQUE AFTER `referred_by`;

ALTER TABLE `users`
    ADD CONSTRAINT `users_referred_by_foreign` FOREIGN KEY (`referred_by`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- ===================================================================
-- Migration 5: 2026-04-28-131500_AlignCoursesSchema
-- ===================================================================

ALTER TABLE `courses`
    ADD COLUMN IF NOT EXISTS `duration` VARCHAR(100) NULL AFTER `is_active`,
    ADD COLUMN IF NOT EXISTS `level` VARCHAR(50) DEFAULT 'beginner' AFTER `duration`,
    ADD COLUMN IF NOT EXISTS `content_source` VARCHAR(20) DEFAULT 'none' AFTER `level`,
    ADD COLUMN IF NOT EXISTS `youtube_url` VARCHAR(255) NULL AFTER `content_source`,
    ADD COLUMN IF NOT EXISTS `content_file` VARCHAR(255) NULL AFTER `youtube_url`,
    ADD COLUMN IF NOT EXISTS `is_featured` TINYINT(1) DEFAULT 0 AFTER `content_file`,
    ADD COLUMN IF NOT EXISTS `updated_at` DATETIME NULL AFTER `created_at`;

ALTER TABLE `course_enrollments`
    ADD COLUMN IF NOT EXISTS `amount` DECIMAL(15,2) DEFAULT 0.00 AFTER `created_at`;

-- ===================================================================
-- Migration 6: 2026-04-28-160500_CreateMockInterviewSessions
-- ===================================================================

CREATE TABLE IF NOT EXISTS `mock_interview_sessions` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `application_id` INT UNSIGNED NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `job_title` VARCHAR(255) NOT NULL,
    `difficulty` VARCHAR(20) DEFAULT 'medium',
    `question_pack` VARCHAR(50) DEFAULT 'general',
    `interview_mode` VARCHAR(20) DEFAULT 'chat',
    `webcam_enabled` TINYINT(1) DEFAULT 0,
    `duration_seconds` INT UNSIGNED DEFAULT 0,
    `overall_score` INT UNSIGNED NULL,
    `star_average` INT UNSIGNED NULL,
    `transcript_json` TEXT NOT NULL,
    `evaluation_json` TEXT NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    INDEX `mock_interview_sessions_application_id_idx` (`application_id`),
    INDEX `mock_interview_sessions_user_id_idx` (`user_id`),
    INDEX `mock_interview_sessions_created_at_idx` (`created_at`),
    CONSTRAINT `mock_interview_sessions_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `job_applications`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `mock_interview_sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================================
-- Migration 7: 2026-04-28-200000_AddApplicationIdToMockInterviewSessions
-- (Already handled above in the CREATE TABLE)
-- ===================================================================

-- ===================================================================
-- Migration 8: 2026-05-19-000000_CreateMessagingTables
-- ===================================================================

CREATE TABLE IF NOT EXISTS `conversations` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `employer_id` INT UNSIGNED NOT NULL,
    `job_seeker_id` INT UNSIGNED NOT NULL,
    `job_id` INT UNSIGNED NULL,
    `last_message` TEXT NULL,
    `last_message_at` DATETIME NULL,
    `employer_last_read` DATETIME NULL,
    `seeker_last_read` DATETIME NULL,
    `is_active` TINYINT DEFAULT 1,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    CONSTRAINT `conversations_employer_id_foreign` FOREIGN KEY (`employer_id`) REFERENCES `employers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `conversations_job_seeker_id_foreign` FOREIGN KEY (`job_seeker_id`) REFERENCES `job_seekers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `conversations_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `messages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `conversation_id` INT UNSIGNED NOT NULL,
    `sender_id` INT UNSIGNED NOT NULL,
    `sender_type` VARCHAR(20) NOT NULL,
    `message` TEXT NOT NULL,
    `is_read` TINYINT DEFAULT 0,
    `read_at` DATETIME NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    CONSTRAINT `messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================================
-- Migration 9: 2026-05-19-000001_CreateCourseCertificates
-- ===================================================================

CREATE TABLE IF NOT EXISTS `course_certificates` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `course_id` INT UNSIGNED NOT NULL,
    `enrollment_id` INT UNSIGNED NOT NULL,
    `certificate_code` VARCHAR(50) NOT NULL UNIQUE,
    `issued_at` DATETIME NOT NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    CONSTRAINT `course_certificates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `course_certificates_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `course_certificates_enrollment_id_foreign` FOREIGN KEY (`enrollment_id`) REFERENCES `course_enrollments`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `course_enrollments`
    ADD COLUMN IF NOT EXISTS `completed_at` DATETIME NULL AFTER `created_at`,
    ADD COLUMN IF NOT EXISTS `progress` INT DEFAULT 0 AFTER `completed_at`;

-- ===================================================================
-- Migration 10: 2026-05-19-000002_AddOptionsToJobQuestions
-- ===================================================================

ALTER TABLE `job_questions`
    ADD COLUMN IF NOT EXISTS `options` TEXT NULL AFTER `question_type`;

-- ===================================================================
-- Migration 11: 2026-05-20-000000_CreateCourseModules
-- ===================================================================

ALTER TABLE `courses`
    ADD COLUMN IF NOT EXISTS `item_type` VARCHAR(50) DEFAULT 'course' NOT NULL AFTER `title`;

CREATE TABLE IF NOT EXISTS `course_modules` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `course_id` INT UNSIGNED NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `content_source` VARCHAR(50) DEFAULT 'none',
    `youtube_url` VARCHAR(255) NULL,
    `content_file` VARCHAR(255) NULL,
    `order_index` INT DEFAULT 0,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    INDEX `course_modules_course_id_idx` (`course_id`),
    CONSTRAINT `course_modules_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================================
-- Migration 12: 2026-05-21-000000_AddLocationSEOColumnsToStates
-- ===================================================================

ALTER TABLE `states`
    ADD COLUMN IF NOT EXISTS `slug` VARCHAR(255) NULL AFTER `name`,
    ADD COLUMN IF NOT EXISTS `capital` VARCHAR(255) NULL AFTER `slug`,
    ADD COLUMN IF NOT EXISTS `region` VARCHAR(255) NULL AFTER `capital`,
    ADD COLUMN IF NOT EXISTS `is_active` TINYINT(1) DEFAULT 1 AFTER `region`,
    ADD COLUMN IF NOT EXISTS `description` TEXT NULL AFTER `is_active`,
    ADD COLUMN IF NOT EXISTS `meta_description` TEXT NULL AFTER `description`,
    ADD COLUMN IF NOT EXISTS `seo_h1` VARCHAR(255) NULL AFTER `meta_description`;

-- ===================================================================
-- Migration 13: 2026-05-21-201500_AlignTestimonialsSchema
-- ===================================================================

DROP TABLE IF EXISTS `testimonials`;

CREATE TABLE IF NOT EXISTS `testimonials` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `role` VARCHAR(100) NULL,
    `company` VARCHAR(100) NULL,
    `message` TEXT NOT NULL,
    `rating` TINYINT DEFAULT 5,
    `avatar` VARCHAR(255) NULL,
    `status` VARCHAR(20) DEFAULT 'active',
    `is_featured` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================================
-- Migration 14: 2026-05-22-120000_AddSEOAndActiveColumnsToIndustriesAndCategories
-- ===================================================================

ALTER TABLE `industries`
    ADD COLUMN IF NOT EXISTS `is_active` TINYINT(1) DEFAULT 1 AFTER `slug`,
    ADD COLUMN IF NOT EXISTS `description` TEXT NULL AFTER `is_active`,
    ADD COLUMN IF NOT EXISTS `meta_description` TEXT NULL AFTER `description`,
    ADD COLUMN IF NOT EXISTS `seo_h1` VARCHAR(255) NULL AFTER `meta_description`;

ALTER TABLE `job_categories`
    ADD COLUMN IF NOT EXISTS `is_active` TINYINT(1) DEFAULT 1 AFTER `slug`;

-- ===================================================================
-- Migration 15: 2026-05-22-180000_CreateCvReviews
-- ===================================================================

CREATE TABLE IF NOT EXISTS `cv_reviews` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `plan` VARCHAR(50) DEFAULT 'basic',
    `amount` DECIMAL(10,2) DEFAULT 0.00,
    `payment_reference` VARCHAR(100) NULL,
    `payment_status` VARCHAR(20) DEFAULT 'pending',
    `file_path` VARCHAR(255) NULL,
    `full_name` VARCHAR(255) NULL,
    `email` VARCHAR(255) NULL,
    `phone` VARCHAR(50) NULL,
    `industry` VARCHAR(100) NULL,
    `target_role` VARCHAR(255) NULL,
    `feedback_request` TEXT NULL,
    `admin_notes` TEXT NULL,
    `status` VARCHAR(20) DEFAULT 'pending',
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    INDEX `cv_reviews_user_id_idx` (`user_id`),
    INDEX `cv_reviews_payment_reference_idx` (`payment_reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================================
-- Re-enable foreign key checks
-- ===================================================================

SET FOREIGN_KEY_CHECKS = 1;
