-- ===================================================================
-- JobberRecruit Comprehensive Data Integrity Fixes
-- Optimized for MariaDB/MySQL
-- ===================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------
-- 1. DATA TYPE ALIGNMENT & CLEANUP
-- Ensuring all foreign key columns match their parents
-- --------------------------------------------------------

-- Fix potential INT vs INT UNSIGNED mismatches
ALTER TABLE `user_engagement_logs` MODIFY COLUMN `user_id` INT UNSIGNED NOT NULL;
ALTER TABLE `newsletter_click_logs` MODIFY COLUMN `newsletter_id` INT UNSIGNED NOT NULL;
ALTER TABLE `newsletter_click_logs` MODIFY COLUMN `user_id` INT UNSIGNED NULL;

-- --------------------------------------------------------
-- 2. ORPHAN CLEANUP
-- Removing or nullifying records that violate integrity
-- --------------------------------------------------------

-- Clean up orphans in user_engagement_logs
DELETE FROM `user_engagement_logs` WHERE `user_id` NOT IN (SELECT `id` FROM `users`);

-- Clean up orphans in newsletter_click_logs
DELETE FROM `newsletter_click_logs` WHERE `newsletter_id` NOT IN (SELECT `id` FROM `newsletters`);
UPDATE `newsletter_click_logs` SET `user_id` = NULL WHERE `user_id` IS NOT NULL AND `user_id` NOT IN (SELECT `id` FROM `users`);

-- Clean up orphans in job_reports
DELETE FROM `job_reports` WHERE `job_id` NOT IN (SELECT `id` FROM `jobs`);
UPDATE `job_reports` SET `user_id` = NULL WHERE `user_id` IS NOT NULL AND `user_id` NOT IN (SELECT `id` FROM `users`);

-- Clean up orphans in job_notifications
DELETE FROM `job_notifications` WHERE `employer_id` NOT IN (SELECT `id` FROM `employers`);
UPDATE `job_notifications` SET `job_id` = NULL WHERE `job_id` IS NOT NULL AND `job_id` NOT IN (SELECT `id` FROM `jobs`);
UPDATE `job_notifications` SET `application_id` = NULL WHERE `application_id` IS NOT NULL AND `application_id` NOT IN (SELECT `id` FROM `job_applications`);

-- Clean up orphans in job_clicks
UPDATE `job_clicks` SET `user_id` = NULL WHERE `user_id` IS NOT NULL AND `user_id` NOT IN (SELECT `id` FROM `users`);

-- Clean up orphans in pricing_rules
UPDATE `pricing_rules` SET `plan_id` = NULL WHERE `plan_id` IS NOT NULL AND `plan_id` NOT IN (SELECT `id` FROM `plans`);
UPDATE `pricing_rules` SET `bundle_id` = NULL WHERE `bundle_id` IS NOT NULL AND `bundle_id` NOT IN (SELECT `bundle_packages`.`id` FROM `bundle_packages`);

-- Clean up orphans in cv_reviews
DELETE FROM `cv_reviews` WHERE `user_id` NOT IN (SELECT `id` FROM `users`);

-- --------------------------------------------------------
-- 3. ADDING MISSING FOREIGN KEYS
-- --------------------------------------------------------

-- user_engagement_logs
ALTER TABLE `user_engagement_logs` 
    ADD CONSTRAINT `fk_user_engagement_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE;

-- newsletter_click_logs
ALTER TABLE `newsletter_click_logs` 
    ADD CONSTRAINT `fk_newsletter_click_newsletter` FOREIGN KEY (`newsletter_id`) REFERENCES `newsletters`(`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `fk_newsletter_click_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL;

-- job_reports
ALTER TABLE `job_reports` 
    ADD CONSTRAINT `fk_job_reports_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL;

-- job_notifications
ALTER TABLE `job_notifications` 
    ADD CONSTRAINT `fk_job_notifications_job` FOREIGN KEY (`job_id`) REFERENCES `jobs`(`id`) ON DELETE SET NULL,
    ADD CONSTRAINT `fk_job_notifications_application` FOREIGN KEY (`application_id`) REFERENCES `job_applications`(`id`) ON DELETE SET NULL;

-- job_clicks
ALTER TABLE `job_clicks` 
    ADD CONSTRAINT `fk_job_clicks_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL;

-- cv_reviews
ALTER TABLE `cv_reviews` 
    ADD CONSTRAINT `fk_cv_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE;

-- pricing_rules
ALTER TABLE `pricing_rules` 
    ADD CONSTRAINT `fk_pricing_rules_plan` FOREIGN KEY (`plan_id`) REFERENCES `plans`(`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `fk_pricing_rules_bundle` FOREIGN KEY (`bundle_id`) REFERENCES `bundle_packages`(`id`) ON DELETE CASCADE;

-- employer_verification_logs
ALTER TABLE `employer_verification_logs` 
    ADD CONSTRAINT `fk_employer_verification_admin` FOREIGN KEY (`admin_id`) REFERENCES `admins`(`id`) ON DELETE CASCADE;

-- application_notes (created_by)
ALTER TABLE `application_notes` 
    ADD CONSTRAINT `fk_application_notes_creator` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE CASCADE;

-- --------------------------------------------------------
-- 4. MISSING INDEXES FOR PERFORMANCE
-- --------------------------------------------------------

-- Index all FK columns that might be missing indexes
CREATE INDEX IF NOT EXISTS `idx_job_reports_user_id` ON `job_reports` (`user_id`);
CREATE INDEX IF NOT EXISTS `idx_job_notifications_job_id` ON `job_notifications` (`job_id`);
CREATE INDEX IF NOT EXISTS `idx_job_notifications_application_id` ON `job_notifications` (`application_id`);
CREATE INDEX IF NOT EXISTS `idx_job_clicks_user_id` ON `job_clicks` (`user_id`);
CREATE INDEX IF NOT EXISTS `idx_cv_reviews_user_id` ON `cv_reviews` (`user_id`);
CREATE INDEX IF NOT EXISTS `idx_application_notes_created_by` ON `application_notes` (`created_by`);
CREATE INDEX IF NOT EXISTS `idx_pricing_rules_plan_id` ON `pricing_rules` (`plan_id`);
CREATE INDEX IF NOT EXISTS `idx_pricing_rules_bundle_id` ON `pricing_rules` (`bundle_id`);

-- Indexes for status/featured flags
CREATE INDEX IF NOT EXISTS `idx_jobs_status_featured` ON `jobs` (`status`, `is_featured`);
CREATE INDEX IF NOT EXISTS `idx_employers_verification_status` ON `employers` (`verification_status`);
CREATE INDEX IF NOT EXISTS `idx_job_applications_status` ON `job_applications` (`status`);

-- --------------------------------------------------------
-- 5. DATA CONSISTENCY REPAIR
-- --------------------------------------------------------

-- Ensure newsletter_subscribers.email matches users.email where applicable (soft link)
-- No direct FK but we can normalize
UPDATE `newsletter_subscribers` ns
JOIN `users` u ON ns.`email` = u.`username` -- Assuming username is email or they match
SET ns.`is_active` = 1
WHERE ns.`is_active` = 0 AND u.`active` = 1;

-- Ensure all users have a referral code if they don't
UPDATE `users` SET `referral_code` = CONCAT('JB', id, SUBSTRING(MD5(RAND()), 1, 4)) WHERE `referral_code` IS NULL;

-- --------------------------------------------------------
-- 6. AUDIT LOGGING
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `data_integrity_audit_log` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `action` VARCHAR(255),
    `table_name` VARCHAR(100),
    `result` TEXT,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO `data_integrity_audit_log` (`action`, `table_name`, `result`) 
VALUES ('COMPREHENSIVE_FIX', 'ALL', 'Applied missing foreign keys, indexes, and cleaned orphaned data.');

SET FOREIGN_KEY_CHECKS = 1;
