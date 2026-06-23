-- MariaDB Migration for JobberRecruit Phase 3 Updates
-- Generated on 2026-06-02

-- 1. Updates to newsletters table for A/B testing support
ALTER TABLE `newsletters` 
    ADD COLUMN `subject_b` VARCHAR(255) NULL AFTER `subject`,
    ADD COLUMN `content_b` TEXT NULL AFTER `content`,
    ADD COLUMN `test_split_percent` INT DEFAULT 0 AFTER `target_industries`,
    ADD COLUMN `test_status` VARCHAR(50) DEFAULT 'none' AFTER `test_split_percent`,
    ADD COLUMN `winning_variation` VARCHAR(1) NULL AFTER `test_status`,
    ADD COLUMN `open_count_b` INT DEFAULT 0 AFTER `open_count`,
    ADD COLUMN `click_count_b` INT DEFAULT 0 AFTER `click_count`;

-- 2. Ensure existing data is consistent
UPDATE `newsletters` SET `test_status` = 'none' WHERE `test_status` IS NULL;
