-- FIX PAYMENTS
ALTER TABLE `payments` ADD COLUMN `metadata` TEXT NULL;
ALTER TABLE `payments` ADD COLUMN `payment_method` VARCHAR(50) NULL;
ALTER TABLE `payments` ADD COLUMN `amount_paid` DECIMAL(15,2) DEFAULT 0.00;
ALTER TABLE `payments` ADD COLUMN `currency` VARCHAR(10) DEFAULT 'NGN';
ALTER TABLE `payments` ADD COLUMN `paid_at` DATETIME NULL;
ALTER TABLE `payments` ADD COLUMN `employer_id` INT UNSIGNED NULL;

-- FIX STATES
ALTER TABLE `states` ADD COLUMN `slug` VARCHAR(150) NULL;
ALTER TABLE `states` ADD COLUMN `capital` VARCHAR(100) NULL;
ALTER TABLE `states` ADD COLUMN `region` VARCHAR(100) NULL;
ALTER TABLE `states` ADD COLUMN `is_active` TINYINT(1) DEFAULT 1;
ALTER TABLE `states` ADD COLUMN `description` TEXT NULL;
ALTER TABLE `states` ADD COLUMN `meta_description` VARCHAR(255) NULL;
ALTER TABLE `states` ADD COLUMN `seo_h1` VARCHAR(255) NULL;

-- FIX OTHER TABLES
ALTER TABLE `webinars` ADD COLUMN `flyer_image` VARCHAR(255) NULL;
ALTER TABLE `blogs` ADD COLUMN `tags` VARCHAR(255) NULL;
ALTER TABLE `job_seekers` ADD COLUMN `share_contact` TINYINT(1) NOT NULL DEFAULT 1;
ALTER TABLE `job_seekers` ADD COLUMN `is_visible` TINYINT(1) NOT NULL DEFAULT 1;
ALTER TABLE `users` ADD COLUMN `is_archived` TINYINT(1) NOT NULL DEFAULT 0;

-- UPDATE QUALIFICATIONS
INSERT INTO `qualifications` (`name`, `order_index`, `is_active`, `created_at`, `updated_at`) 
VALUES 
('Secondary School/High School', 1, 1, NOW(), NOW()),
('OND', 2, 1, NOW(), NOW()),
('HND', 3, 1, NOW(), NOW()),
('Bachelor\'s Degree', 4, 1, NOW(), NOW()),
('Master\'s Degree', 5, 1, NOW(), NOW()),
('MBA', 6, 1, NOW(), NOW()),
('PhD/Doctorate', 7, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE `order_index` = VALUES(`order_index`), `is_active` = 1, `updated_at` = NOW();
