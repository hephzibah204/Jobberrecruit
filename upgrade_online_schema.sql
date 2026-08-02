-- ================================================================
-- JobberRecruit Consolidated Database Upgrade Script
-- Generated: 2026-07-20 (Fixed & Idempotent)
--
-- SAFE TO RUN MULTIPLE TIMES:
--   - CREATE TABLE uses IF NOT EXISTS
--   - ALTER TABLE columns are guarded by IF NOT EXISTS checks
--   - FOREIGN KEY constraints removed (supports MyISAM tables)
-- ================================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = '';

-- ================================================================
-- HELPER PROCEDURE: safely adds a column if it doesn't exist
-- ================================================================
DROP PROCEDURE IF EXISTS `safe_add_column`;

DELIMITER //
CREATE PROCEDURE `safe_add_column`(
    IN p_table  VARCHAR(255),
    IN p_column VARCHAR(255),
    IN p_def    TEXT
)
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME   = p_table
          AND COLUMN_NAME  = p_column
    ) THEN
        SET @sql = CONCAT('ALTER TABLE `', p_table, '` ADD COLUMN `', p_column, '` ', p_def);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END //
DELIMITER ;

-- ================================================================
-- 1. CREATE NEW TABLES (IF NOT EXISTS)
-- ================================================================

CREATE TABLE IF NOT EXISTS `audience_segments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('dynamic','static') NOT NULL DEFAULT 'dynamic',
  `criteria_json` text DEFAULT NULL,
  `user_count` int(11) NOT NULL DEFAULT 0,
  `last_synced_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `resource` varchar(255) DEFAULT NULL,
  `resource_id` int(11) unsigned DEFAULT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `automation_steps` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `automation_id` int(11) unsigned NOT NULL,
  `step_order` int(11) NOT NULL,
  `template_id` int(11) unsigned DEFAULT NULL,
  `delay_minutes` int(11) NOT NULL DEFAULT 0,
  `condition_json` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `automation_id` (`automation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `automation_subscribers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `automation_id` int(11) unsigned NOT NULL,
  `subscriber_id` int(11) unsigned NOT NULL,
  `current_step_id` int(11) unsigned DEFAULT NULL,
  `status` enum('in_progress','completed','cancelled') NOT NULL DEFAULT 'in_progress',
  `enrolled_at` datetime DEFAULT NULL,
  `next_step_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `automation_id_subscriber_id` (`automation_id`,`subscriber_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `automations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `trigger_event` varchar(100) NOT NULL,
  `status` enum('draft','active','paused') NOT NULL DEFAULT 'draft',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `campaign_stats` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `campaign_id` int(10) unsigned NOT NULL,
  `total_recipients` int(11) NOT NULL DEFAULT 0,
  `delivered` int(11) NOT NULL DEFAULT 0,
  `bounced` int(11) NOT NULL DEFAULT 0,
  `complained` int(11) NOT NULL DEFAULT 0,
  `opens_unique` int(11) NOT NULL DEFAULT 0,
  `opens_total` int(11) NOT NULL DEFAULT 0,
  `clicks_unique` int(11) NOT NULL DEFAULT 0,
  `clicks_total` int(11) NOT NULL DEFAULT 0,
  `unsubscribes` int(11) NOT NULL DEFAULT 0,
  `device_breakdown` text DEFAULT NULL,
  `client_breakdown` text DEFAULT NULL,
  `geo_breakdown` text DEFAULT NULL,
  `hourly_open_heatmap` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `campaign_id` (`campaign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- NOTE: FOREIGN KEY constraints removed to support MyISAM/mixed engine environments.
-- Plain KEY indexes are used instead for query performance.
CREATE TABLE IF NOT EXISTS `candidate_notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `candidate_id` int(10) unsigned NOT NULL,
  `application_id` int(10) unsigned NOT NULL,
  `type` varchar(40) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `candidate_notifications_application_id` (`application_id`),
  KEY `candidate_id_is_read_created_at` (`candidate_id`,`is_read`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `email_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `campaign_id` int(10) unsigned NOT NULL,
  `subscriber_id` int(10) unsigned NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `sent_at` datetime DEFAULT NULL,
  `delivered_at` datetime DEFAULT NULL,
  `opened_at` datetime DEFAULT NULL,
  `open_count` int(11) NOT NULL DEFAULT 0,
  `last_opened_at` datetime DEFAULT NULL,
  `clicked_at` datetime DEFAULT NULL,
  `click_count` int(11) NOT NULL DEFAULT 0,
  `last_clicked_at` datetime DEFAULT NULL,
  `links_clicked` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `device_type` varchar(50) DEFAULT NULL,
  `unsubscribe_at` datetime DEFAULT NULL,
  `bounce_reason` text DEFAULT NULL,
  `complaint_type` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `campaign_id` (`campaign_id`),
  KEY `subscriber_id` (`subscriber_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `job_application_status_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `application_id` int(10) unsigned NOT NULL,
  `old_status` varchar(20) DEFAULT NULL,
  `new_status` varchar(20) NOT NULL,
  `changed_by_user_id` int(10) unsigned DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `application_id_created_at` (`application_id`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `newsletter_templates` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `html_content` longtext DEFAULT NULL,
  `thumbnail_url` varchar(500) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ================================================================
-- 2. ALTER EXISTING TABLES (ADD MISSING COLUMNS — SAFE/IDEMPOTENT)
-- ================================================================

CALL safe_add_column('application_references', 'created_at', 'DATETIME NULL');
CALL safe_add_column('application_references', 'updated_at', 'DATETIME NULL');

CALL safe_add_column('certificate_templates', 'is_full_image_mode', 'TINYINT(1) DEFAULT 0');

CALL safe_add_column('course_enrollments', 'updated_at', 'DATETIME NULL');

CALL safe_add_column('employers', 'industry_id', 'INT(11) NULL');
CALL safe_add_column('employers', 'tin_number', 'VARCHAR(100) NULL');

CALL safe_add_column('jobs', 'is_verified', 'TINYINT(1) DEFAULT 0');

CALL safe_add_column('job_applications', 'is_guest', 'TINYINT(1) DEFAULT 0');
CALL safe_add_column('job_applications', 'guest_email_sent', 'TINYINT(1) DEFAULT 0');

CALL safe_add_column('job_application_answers', 'updated_at', 'DATETIME NULL');

CALL safe_add_column('job_credit_transactions', 'updated_at', 'DATETIME NULL');

CALL safe_add_column('referrals', 'reward_paid', 'TINYINT(1) DEFAULT 0');

CALL safe_add_column('webinars', 'speaker_bio', 'TEXT NULL');
CALL safe_add_column('webinars', 'duration', 'INT(11) NULL');

CALL safe_add_column('newsletters', 'preheader_text', 'VARCHAR(255) NULL');
CALL safe_add_column('newsletters', 'content_text', 'TEXT NULL');
CALL safe_add_column('newsletters', 'template_id', 'INT(11) NULL');
CALL safe_add_column('newsletters', 'brand_id', 'INT(11) NULL');
CALL safe_add_column('newsletters', 'completed_at', 'DATETIME NULL');
CALL safe_add_column('newsletters', 'created_by', 'INT(11) NULL');
CALL safe_add_column('newsletters', 'utm_campaign', 'VARCHAR(100) NULL');
CALL safe_add_column('newsletters', 'utm_source', 'VARCHAR(100) NULL');
CALL safe_add_column('newsletters', 'utm_medium', 'VARCHAR(100) NULL');
CALL safe_add_column('newsletters', 'ab_test_enabled', 'TINYINT(1) DEFAULT 0');
CALL safe_add_column('newsletters', 'ab_test_variant_a', 'VARCHAR(255) NULL');
CALL safe_add_column('newsletters', 'ab_test_variant_b', 'VARCHAR(255) NULL');
CALL safe_add_column('newsletters', 'winner_criteria', 'VARCHAR(50) NULL');
CALL safe_add_column('newsletters', 'winner_percentage', 'INT(11) NULL');

CALL safe_add_column('newsletter_subscribers', 'phone', 'VARCHAR(30) NULL');
CALL safe_add_column('newsletter_subscribers', 'type', "VARCHAR(50) NULL DEFAULT 'subscriber'");
CALL safe_add_column('newsletter_subscribers', 'status', "VARCHAR(50) NULL DEFAULT 'active'");
CALL safe_add_column('newsletter_subscribers', 'tags', 'VARCHAR(255) NULL');
CALL safe_add_column('newsletter_subscribers', 'custom_fields', 'TEXT NULL');
CALL safe_add_column('newsletter_subscribers', 'engagement_score', 'INT(11) DEFAULT 0');
CALL safe_add_column('newsletter_subscribers', 'last_opened_at', 'DATETIME NULL');
CALL safe_add_column('newsletter_subscribers', 'last_clicked_at', 'DATETIME NULL');
CALL safe_add_column('newsletter_subscribers', 'signup_source', 'VARCHAR(100) NULL');
CALL safe_add_column('newsletter_subscribers', 'timezone', 'VARCHAR(100) NULL');
CALL safe_add_column('newsletter_subscribers', 'language_preference', "VARCHAR(10) NULL DEFAULT 'en'");
CALL safe_add_column('newsletter_subscribers', 'gdpr_consent', 'TINYINT(1) DEFAULT 0');
CALL safe_add_column('newsletter_subscribers', 'consent_date', 'DATETIME NULL');
CALL safe_add_column('newsletter_subscribers', 'ip_address', 'VARCHAR(45) NULL');
CALL safe_add_column('newsletter_subscribers', 'updated_at', 'DATETIME NULL');

-- ================================================================
-- 3. CLEANUP
-- ================================================================

DROP PROCEDURE IF EXISTS `safe_add_column`;

SET FOREIGN_KEY_CHECKS = 1;

-- ================================================================
-- Done. All changes applied safely.
-- ================================================================
