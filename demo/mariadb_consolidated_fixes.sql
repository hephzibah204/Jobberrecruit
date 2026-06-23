-- ===================================================================
-- MariaDB Database Migrations & DDL Updates (Consolidated Fixes)
-- Optimized for compatibility and idempotency
-- ===================================================================

-- 1. Create qualifications table for dynamic management
CREATE TABLE IF NOT EXISTS `qualifications` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `order_index` INT DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Seed default qualifications (Avoid duplicates)
INSERT INTO `qualifications` (`name`, `order_index`) 
SELECT * FROM (
    SELECT 'BA/BSc/HND' as n, 1 as o UNION ALL
    SELECT 'First School Leaving Certificate', 2 UNION ALL
    SELECT 'MBA/MSc/MA', 3 UNION ALL
    SELECT 'NCE', 4 UNION ALL
    SELECT 'OND', 5 UNION ALL
    SELECT 'Others', 6 UNION ALL
    SELECT 'PhD/Fellowship', 7 UNION ALL
    SELECT 'Professional Certificate', 8 UNION ALL
    SELECT 'Secondary School (SSCE)', 9 UNION ALL
    SELECT 'Vocational', 10
) AS tmp
WHERE NOT EXISTS (
    SELECT name FROM `qualifications` WHERE name = tmp.n
) LIMIT 10;

-- 3. Add profile_completion column to job_seekers table
SET @dbname = DATABASE();
SET @tablename = 'job_seekers';
SET @columnname = 'profile_completion';

-- Check if is_verified exists to use in AFTER clause
SET @afterColumn = (SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = 'is_verified');

SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname, ' TINYINT(3) UNSIGNED NOT NULL DEFAULT 0', 
    IF(@afterColumn IS NOT NULL, ' AFTER `is_verified`', ''))));
PREPARE stmt FROM @preparedStatement; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 4. Ensure existing records have a default completion if null
UPDATE `job_seekers` SET `profile_completion` = 70 WHERE `profile_completion` = 0 AND `job_title` IS NOT NULL;
