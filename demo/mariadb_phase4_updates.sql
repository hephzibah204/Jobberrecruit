-- MariaDB Migration for JobberRecruit Phase 4 Updates
-- Generated on 2026-06-02

-- 1. Analytics & Predictive Schema Enhancements
-- These columns support the advanced Phase 4 analytics identified in the roadmap

-- Track detailed engagement metrics per user (for predictive model)
CREATE TABLE IF NOT EXISTS `user_engagement_logs` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `action` VARCHAR(100) NOT NULL, -- login, search, apply, view_job, profile_update
    `metadata` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_user_action` (`user_id`, `action`)
);

-- Store predictive platform scores (e.g. churn probability, hiring likelihood)
ALTER TABLE `users` 
    ADD COLUMN `engagement_score` INT DEFAULT 0 AFTER `status_message`,
    ADD COLUMN `predictive_status` VARCHAR(50) NULL AFTER `engagement_score`; -- active, at_risk, churned

-- 2. Enhanced Newsletter Link Tracking
-- Added to support more granular click-through analysis
CREATE TABLE IF NOT EXISTS `newsletter_click_logs` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `newsletter_id` INT NOT NULL,
    `user_id` INT NULL,
    `email` VARCHAR(255) NOT NULL,
    `url` TEXT NOT NULL,
    `variation` VARCHAR(1) DEFAULT 'A',
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_newsletter_variation` (`newsletter_id`, `variation`)
);

-- 3. Optimization Tips & Meta for AI
-- Support for storing optimization history and results
ALTER TABLE `resumes` 
    ADD COLUMN `ai_optimization_meta` TEXT NULL AFTER `template_id`, -- Store JSON results of Phase 4 tailoring
    ADD COLUMN `target_job_description` TEXT NULL AFTER `ai_optimization_meta`;
