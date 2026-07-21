-- ============================================================
-- JobberRecruit — candidate backend gap tables & columns
-- MySQL / MariaDB (InnoDB, utf8mb4)
--
-- These mirror the CodeIgniter migrations:
--   2026-07-21-000001_AddNotificationPrefsToJobSeekers
--   2026-07-21-000002_CreateJobSeekerExperiences
--   2026-07-21-000003_CreateJobSeekerEducation
-- and the pre-existing 2026-06-11-155312_AddIsVisibleToJobSeekers.
--
-- Prefer `php spark migrate` where possible. This file is for fresh
-- environments, manual setup, or reference.
-- ============================================================

-- ------------------------------------------------------------
-- 1. Notification preferences (+ visibility) on job_seekers
--    `is_visible` already exists on databases migrated at or after
--    2026-06-11-155312 — the guarded statement below is a no-op there.
-- ------------------------------------------------------------
ALTER TABLE `job_seekers`
    ADD COLUMN IF NOT EXISTS `is_visible` TINYINT(1) NOT NULL DEFAULT 1 AFTER `is_verified`;

ALTER TABLE `job_seekers`
    ADD COLUMN IF NOT EXISTS `notify_job_alerts`          TINYINT(1) NOT NULL DEFAULT 1 AFTER `is_visible`,
    ADD COLUMN IF NOT EXISTS `notify_application_updates` TINYINT(1) NOT NULL DEFAULT 1 AFTER `notify_job_alerts`,
    ADD COLUMN IF NOT EXISTS `notify_messages`            TINYINT(1) NOT NULL DEFAULT 1 AFTER `notify_application_updates`,
    ADD COLUMN IF NOT EXISTS `notify_marketing`           TINYINT(1) NOT NULL DEFAULT 0 AFTER `notify_messages`;

-- ------------------------------------------------------------
-- 2. Work experience history
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `job_seeker_experiences` (
    `id`            INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `job_seeker_id` INT UNSIGNED    NOT NULL,
    `job_title`     VARCHAR(255)    NOT NULL,
    `company`       VARCHAR(255)    NULL,
    `location`      VARCHAR(255)    NULL,
    `start_date`    DATE            NULL,
    `end_date`      DATE            NULL,
    `is_current`    TINYINT(1)      NOT NULL DEFAULT 0,
    `description`   TEXT            NULL,
    `sort_order`    INT             NOT NULL DEFAULT 0,
    `created_at`    DATETIME        NULL,
    `updated_at`    DATETIME        NULL,
    PRIMARY KEY (`id`),
    KEY `job_seeker_experiences_seeker_sort` (`job_seeker_id`, `sort_order`),
    CONSTRAINT `job_seeker_experiences_seeker_fk`
        FOREIGN KEY (`job_seeker_id`) REFERENCES `job_seekers` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------------------------------------
-- 3. Education history
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `job_seeker_education` (
    `id`             INT UNSIGNED   NOT NULL AUTO_INCREMENT,
    `job_seeker_id`  INT UNSIGNED   NOT NULL,
    `degree`         VARCHAR(255)   NOT NULL,
    `field_of_study` VARCHAR(255)   NULL,
    `school`         VARCHAR(255)   NULL,
    `start_year`     VARCHAR(4)     NULL,
    `end_year`       VARCHAR(4)     NULL,
    `grade`          VARCHAR(100)   NULL,
    `sort_order`     INT            NOT NULL DEFAULT 0,
    `created_at`     DATETIME       NULL,
    `updated_at`     DATETIME       NULL,
    PRIMARY KEY (`id`),
    KEY `job_seeker_education_seeker_sort` (`job_seeker_id`, `sort_order`),
    CONSTRAINT `job_seeker_education_seeker_fk`
        FOREIGN KEY (`job_seeker_id`) REFERENCES `job_seekers` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- Rollback
-- ============================================================
-- DROP TABLE IF EXISTS `job_seeker_education`;
-- DROP TABLE IF EXISTS `job_seeker_experiences`;
-- ALTER TABLE `job_seekers`
--     DROP COLUMN `notify_job_alerts`,
--     DROP COLUMN `notify_application_updates`,
--     DROP COLUMN `notify_messages`,
--     DROP COLUMN `notify_marketing`;
-- -- DROP COLUMN `is_visible`;  -- only if removing visibility too
