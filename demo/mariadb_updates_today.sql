-- ===================================================================
-- MariaDB Database Migrations & DDL Updates (Today's Changes)
-- Optimized for compatibility and idempotency
-- ===================================================================

-- 1. Create resume_autosaves table
CREATE TABLE IF NOT EXISTS `resume_autosaves` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `resume_id` INT UNSIGNED NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `metadata` TEXT NULL,
    `created_at` DATETIME NOT NULL,
    INDEX `resume_autosaves_user_id_idx` (`user_id`),
    INDEX `resume_autosaves_resume_id_idx` (`resume_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Create ai_images table
CREATE TABLE IF NOT EXISTS `ai_images` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `origin_url` VARCHAR(2048) NOT NULL,
    `proxied_path` VARCHAR(1024) NULL DEFAULT NULL,
    `checksum` VARCHAR(64) NULL DEFAULT NULL,
    `mime` VARCHAR(64) NULL DEFAULT NULL,
    `size` INT NULL DEFAULT NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'pending',
    `error` VARCHAR(1024) NULL DEFAULT NULL,
    `created_at` DATETIME NOT NULL,
    `processed_at` DATETIME NULL DEFAULT NULL,
    KEY `checksum_idx` (`checksum`),
    KEY `origin_url_idx` (`origin_url`(255)),
    KEY `status_idx` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Add description column to resume_education table
SET @dbname = DATABASE();
SET @tablename = 'resume_education';
SET @columnname = 'description';
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname, ' TEXT NULL AFTER `graduation_date`')));
PREPARE stmt FROM @preparedStatement; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 4. Add profile_completion column to job_seekers table
SET @tablename = 'job_seekers';
SET @columnname = 'profile_completion';

-- Check if is_verified exists to use in AFTER clause, otherwise just ADD
SET @afterColumn = (SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = 'is_verified');

SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname, ' TINYINT(3) UNSIGNED NOT NULL DEFAULT 0', 
    IF(@afterColumn IS NOT NULL, ' AFTER `is_verified`', ''))));
PREPARE stmt FROM @preparedStatement; EXECUTE stmt; DEALLOCATE PREPARE stmt;
