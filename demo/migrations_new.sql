-- ============================================================
-- JobberRecruit - Additional Migrations (May 2026)
-- Run this after the base schema to add new features.
-- ============================================================

-- 1. Messaging System
-- ============================================================

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
    FOREIGN KEY (`employer_id`) REFERENCES `employers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`job_seeker_id`) REFERENCES `job_seekers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`job_id`) REFERENCES `jobs`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
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
    FOREIGN KEY (`conversation_id`) REFERENCES `conversations`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Course Certificates
-- ============================================================

CREATE TABLE IF NOT EXISTS `course_certificates` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `course_id` INT UNSIGNED NOT NULL,
    `enrollment_id` INT UNSIGNED NOT NULL,
    `certificate_code` VARCHAR(50) NOT NULL UNIQUE,
    `issued_at` DATETIME NOT NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`enrollment_id`) REFERENCES `course_enrollments`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add completion tracking to course_enrollments
ALTER TABLE `course_enrollments`
    ADD COLUMN IF NOT EXISTS `status` VARCHAR(20) DEFAULT 'enrolled',
    ADD COLUMN IF NOT EXISTS `completed_at` DATETIME NULL,
    ADD COLUMN IF NOT EXISTS `progress` INT DEFAULT 0;

-- 3. ATS Pre-screening Options
-- ============================================================

ALTER TABLE `job_questions`
    ADD COLUMN IF NOT EXISTS `options` TEXT NULL AFTER `question_type`;
