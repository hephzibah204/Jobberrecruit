<?php
define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
chdir(FCPATH);
require FCPATH . 'app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';
CodeIgniter\Boot::bootWorker($paths);

$db = db_connect();

// 1. Create job_seeker_certifications table if not exists
$db->query("CREATE TABLE IF NOT EXISTS `job_seeker_certifications` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `job_seeker_id` INT(11) UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `issuing_organization` VARCHAR(255) NOT NULL,
    `issue_month` VARCHAR(20) NULL,
    `issue_year` INT(4) NULL,
    `expiry_month` VARCHAR(20) NULL,
    `expiry_year` INT(4) NULL,
    `credential_id` VARCHAR(100) NULL,
    `credential_url` VARCHAR(255) NULL,
    `sort_order` INT(3) DEFAULT 0,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    INDEX `idx_job_seeker` (`job_seeker_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
echo "job_seeker_certifications table created or verified.\n";

// 2. Create aptitude_test_invitations table if not exists
$db->query("CREATE TABLE IF NOT EXISTS `aptitude_test_invitations` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `employer_id` INT(11) UNSIGNED NOT NULL,
    `job_id` INT(11) UNSIGNED NULL,
    `test_id` INT(11) UNSIGNED NOT NULL,
    `candidate_id` INT(11) UNSIGNED NULL,
    `email` VARCHAR(255) NOT NULL,
    `code` VARCHAR(64) NOT NULL UNIQUE,
    `status` ENUM('pending', 'in_progress', 'completed', 'expired') DEFAULT 'pending',
    `due_date` DATETIME NULL,
    `reminder_count` INT(3) DEFAULT 0,
    `last_reminder_at` DATETIME NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    INDEX `idx_employer` (`employer_id`),
    INDEX `idx_candidate` (`candidate_id`),
    INDEX `idx_test` (`test_id`),
    INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
echo "aptitude_test_invitations table created or verified.\n";

// 3. Create salary_negotiation_sessions table if not exists
$db->query("CREATE TABLE IF NOT EXISTS `salary_negotiation_sessions` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `job_title` VARCHAR(255) NOT NULL,
    `base_salary_offered` DECIMAL(12,2) DEFAULT 0.00,
    `target_salary` DECIMAL(12,2) DEFAULT 0.00,
    `final_salary` DECIMAL(12,2) DEFAULT 0.00,
    `recruiter_style` VARCHAR(50) DEFAULT 'balanced',
    `difficulty` VARCHAR(50) DEFAULT 'medium',
    `rounds_completed` INT(3) DEFAULT 0,
    `confidence_score` INT(3) DEFAULT 0,
    `persuasion_score` INT(3) DEFAULT 0,
    `overall_score` INT(3) DEFAULT 0,
    `outcome` VARCHAR(50) NULL,
    `transcript_json` LONGTEXT NULL,
    `evaluation_json` LONGTEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
echo "salary_negotiation_sessions table created or verified.\n";

echo "ALL TARGET TABLES CREATED SUCCESSFULLY.\n";
