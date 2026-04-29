-- JobberRecruit live MySQL update for training/course features
-- Safe to run on an existing database before later training/course work.
-- Notes:
-- 1. Run this inside the target JobberRecruit database.
-- 2. Uploaded course files are referenced by path only. Deploy the matching files to:
--    writable/uploads/courses/

SET @db_name = DATABASE();

-- --------------------------------------------------------
-- COURSES TABLE ALIGNMENT
-- --------------------------------------------------------

SET @sql = IF(
    EXISTS (
        SELECT 1 FROM information_schema.TABLES
        WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'courses'
    ),
    'SELECT 1',
    'CREATE TABLE `courses` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `title` VARCHAR(255) NOT NULL,
        `slug` VARCHAR(255) NOT NULL,
        `description` TEXT NOT NULL,
        `price` DECIMAL(15,2) DEFAULT 0.00,
        `instructor` VARCHAR(150) NOT NULL,
        `thumbnail` VARCHAR(255) NULL,
        `is_active` TINYINT(1) DEFAULT 1,
        `created_at` DATETIME NULL,
        `duration` VARCHAR(100) NULL,
        `level` VARCHAR(50) DEFAULT ''beginner'',
        `content_source` VARCHAR(20) DEFAULT ''none'',
        `youtube_url` VARCHAR(255) NULL,
        `content_file` VARCHAR(255) NULL,
        `is_featured` TINYINT(1) DEFAULT 0,
        `updated_at` DATETIME NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `courses_slug_unique` (`slug`),
        KEY `courses_is_active_idx` (`is_active`),
        KEY `courses_is_featured_idx` (`is_featured`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(
    EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'courses' AND COLUMN_NAME = 'slug'
    ),
    'SELECT 1',
    'ALTER TABLE `courses` ADD COLUMN `slug` VARCHAR(255) NULL AFTER `title`'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(
    EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'courses' AND COLUMN_NAME = 'duration'
    ),
    'SELECT 1',
    'ALTER TABLE `courses` ADD COLUMN `duration` VARCHAR(100) NULL AFTER `created_at`'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(
    EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'courses' AND COLUMN_NAME = 'level'
    ),
    'SELECT 1',
    'ALTER TABLE `courses` ADD COLUMN `level` VARCHAR(50) DEFAULT ''beginner'' AFTER `duration`'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(
    EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'courses' AND COLUMN_NAME = 'content_source'
    ),
    'SELECT 1',
    'ALTER TABLE `courses` ADD COLUMN `content_source` VARCHAR(20) DEFAULT ''none'' AFTER `level`'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(
    EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'courses' AND COLUMN_NAME = 'youtube_url'
    ),
    'SELECT 1',
    'ALTER TABLE `courses` ADD COLUMN `youtube_url` VARCHAR(255) NULL AFTER `content_source`'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(
    EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'courses' AND COLUMN_NAME = 'content_file'
    ),
    'SELECT 1',
    'ALTER TABLE `courses` ADD COLUMN `content_file` VARCHAR(255) NULL AFTER `youtube_url`'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(
    EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'courses' AND COLUMN_NAME = 'is_featured'
    ),
    'SELECT 1',
    'ALTER TABLE `courses` ADD COLUMN `is_featured` TINYINT(1) DEFAULT 0 AFTER `content_file`'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(
    EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'courses' AND COLUMN_NAME = 'is_active'
    ),
    'SELECT 1',
    'ALTER TABLE `courses` ADD COLUMN `is_active` TINYINT(1) DEFAULT 1 AFTER `thumbnail`'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(
    EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'courses' AND COLUMN_NAME = 'updated_at'
    ),
    'SELECT 1',
    'ALTER TABLE `courses` ADD COLUMN `updated_at` DATETIME NULL AFTER `is_featured`'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

ALTER TABLE `courses`
    MODIFY COLUMN `description` TEXT NOT NULL,
    MODIFY COLUMN `price` DECIMAL(15,2) DEFAULT 0.00,
    MODIFY COLUMN `instructor` VARCHAR(150) NOT NULL,
    MODIFY COLUMN `duration` VARCHAR(100) NULL,
    MODIFY COLUMN `level` VARCHAR(50) DEFAULT 'beginner',
    MODIFY COLUMN `content_source` VARCHAR(20) DEFAULT 'none';

SET @sql = IF(
    EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'courses' AND COLUMN_NAME = 'status'
    ),
    'UPDATE `courses`
        SET `is_active` = CASE
            WHEN LOWER(COALESCE(`status`, ''active'')) = ''active'' THEN 1
            ELSE 0
        END',
    'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(
    EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'courses' AND COLUMN_NAME = 'external_url'
    ),
    'UPDATE `courses`
        SET `youtube_url` = `external_url`
      WHERE (`youtube_url` IS NULL OR `youtube_url` = '''')
        AND (`external_url` IS NOT NULL AND `external_url` <> '''')',
    'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

UPDATE `courses`
SET `content_source` = CASE
    WHEN `content_file` IS NOT NULL AND `content_file` <> '' THEN 'upload'
    WHEN `youtube_url` IS NOT NULL AND `youtube_url` <> '' THEN 'youtube'
    ELSE 'none'
END
WHERE `content_source` IS NULL OR `content_source` = '';

UPDATE `courses`
SET `slug` = CONCAT(
        LOWER(
            REPLACE(
                REPLACE(
                    REPLACE(TRIM(`title`), ' ', '-'),
                    '--',
                    '-'
                ),
                '--',
                '-'
            )
        ),
        '-',
        `id`
    )
WHERE `slug` IS NULL OR `slug` = '';

ALTER TABLE `courses`
    MODIFY COLUMN `slug` VARCHAR(255) NOT NULL;

SET @sql = IF(
    EXISTS (
        SELECT 1 FROM information_schema.STATISTICS
        WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'courses' AND INDEX_NAME = 'courses_slug_unique'
    ),
    'SELECT 1',
    'ALTER TABLE `courses` ADD UNIQUE KEY `courses_slug_unique` (`slug`)'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(
    EXISTS (
        SELECT 1 FROM information_schema.STATISTICS
        WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'courses' AND INDEX_NAME = 'courses_is_active_idx'
    ),
    'SELECT 1',
    'ALTER TABLE `courses` ADD KEY `courses_is_active_idx` (`is_active`)'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(
    EXISTS (
        SELECT 1 FROM information_schema.STATISTICS
        WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'courses' AND INDEX_NAME = 'courses_is_featured_idx'
    ),
    'SELECT 1',
    'ALTER TABLE `courses` ADD KEY `courses_is_featured_idx` (`is_featured`)'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- --------------------------------------------------------
-- COURSE ENROLLMENTS TABLE ALIGNMENT
-- --------------------------------------------------------

SET @sql = IF(
    EXISTS (
        SELECT 1 FROM information_schema.TABLES
        WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'course_enrollments'
    ),
    'SELECT 1',
    'CREATE TABLE `course_enrollments` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `course_id` INT UNSIGNED NOT NULL,
        `user_id` INT UNSIGNED NOT NULL,
        `payment_reference` VARCHAR(100) NULL,
        `status` VARCHAR(20) DEFAULT ''enrolled'',
        `created_at` DATETIME NULL,
        `amount` DECIMAL(15,2) DEFAULT 0.00,
        PRIMARY KEY (`id`),
        KEY `course_enrollments_user_id_idx` (`user_id`),
        KEY `course_enrollments_status_idx` (`status`),
        CONSTRAINT `course_enrollments_course_id_fk` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT `course_enrollments_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(
    EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'course_enrollments' AND COLUMN_NAME = 'amount'
    ),
    'SELECT 1',
    'ALTER TABLE `course_enrollments` ADD COLUMN `amount` DECIMAL(15,2) DEFAULT 0.00 AFTER `created_at`'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

ALTER TABLE `course_enrollments`
    MODIFY COLUMN `payment_reference` VARCHAR(100) NULL,
    MODIFY COLUMN `status` VARCHAR(20) DEFAULT 'enrolled',
    MODIFY COLUMN `amount` DECIMAL(15,2) DEFAULT 0.00;

SET @sql = IF(
    EXISTS (
        SELECT 1 FROM information_schema.STATISTICS
        WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'course_enrollments' AND INDEX_NAME = 'course_enrollments_user_id_idx'
    ),
    'SELECT 1',
    'ALTER TABLE `course_enrollments` ADD KEY `course_enrollments_user_id_idx` (`user_id`)'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(
    EXISTS (
        SELECT 1 FROM information_schema.STATISTICS
        WHERE TABLE_SCHEMA = @db_name AND TABLE_NAME = 'course_enrollments' AND INDEX_NAME = 'course_enrollments_status_idx'
    ),
    'SELECT 1',
    'ALTER TABLE `course_enrollments` ADD KEY `course_enrollments_status_idx` (`status`)'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(
    EXISTS (
        SELECT 1 FROM information_schema.TABLE_CONSTRAINTS
        WHERE TABLE_SCHEMA = @db_name
          AND TABLE_NAME = 'course_enrollments'
          AND CONSTRAINT_NAME = 'course_enrollments_course_id_fk'
          AND CONSTRAINT_TYPE = 'FOREIGN KEY'
    ),
    'SELECT 1',
    'ALTER TABLE `course_enrollments` ADD CONSTRAINT `course_enrollments_course_id_fk` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(
    EXISTS (
        SELECT 1 FROM information_schema.TABLE_CONSTRAINTS
        WHERE TABLE_SCHEMA = @db_name
          AND TABLE_NAME = 'course_enrollments'
          AND CONSTRAINT_NAME = 'course_enrollments_user_id_fk'
          AND CONSTRAINT_TYPE = 'FOREIGN KEY'
    ),
    'SELECT 1',
    'ALTER TABLE `course_enrollments` ADD CONSTRAINT `course_enrollments_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- --------------------------------------------------------
-- DEMO COURSE DATA
-- --------------------------------------------------------

INSERT INTO `courses` (
    `title`,
    `slug`,
    `description`,
    `instructor`,
    `price`,
    `duration`,
    `level`,
    `content_source`,
    `youtube_url`,
    `content_file`,
    `thumbnail`,
    `is_featured`,
    `is_active`,
    `created_at`,
    `updated_at`
) VALUES
(
    'AI Career Launchpad',
    'ai-career-launchpad',
    '<p>Learn how to use AI tools to refine your CV, practice job interviews, and structure a stronger career growth plan. This starter course is perfect for first-time candidates using JobberRecruit.</p>',
    'JobberRecruit Academy',
    0.00,
    '1h 20m',
    'beginner',
    'youtube',
    'https://www.youtube.com/watch?v=ysz5S6PUM-U',
    NULL,
    NULL,
    1,
    1,
    NOW(),
    NOW()
),
(
    'Interview Confidence Toolkit',
    'interview-confidence-toolkit',
    '<p>A practical free toolkit with structured answer templates, confidence-building prompts, and an interview checklist candidates can apply immediately.</p>',
    'Talent Success Team',
    0.00,
    '35m',
    'beginner',
    'upload',
    NULL,
    'courses/interview-confidence-toolkit.html',
    NULL,
    1,
    1,
    NOW(),
    NOW()
),
(
    'CV Rewrite Masterclass',
    'cv-rewrite-masterclass',
    '<p>A premium course for professionals who want to reposition their experience, quantify achievements, and build recruiter-ready CVs for competitive roles.</p>',
    'Career Strategy Desk',
    15000.00,
    '2h 10m',
    'intermediate',
    'youtube',
    'https://www.youtube.com/watch?v=jNQXAC9IVRw',
    NULL,
    NULL,
    0,
    1,
    NOW(),
    NOW()
),
(
    'Employer Hiring Playbook',
    'employer-hiring-playbook',
    '<p>A paid employer-focused course covering job post structure, interview scorecards, and candidate communication workflows for better hiring outcomes.</p>',
    'Recruitment Operations Team',
    25000.00,
    '1h 45m',
    'advanced',
    'upload',
    NULL,
    'courses/employer-hiring-playbook.html',
    NULL,
    1,
    1,
    NOW(),
    NOW()
)
ON DUPLICATE KEY UPDATE
    `title` = VALUES(`title`),
    `description` = VALUES(`description`),
    `instructor` = VALUES(`instructor`),
    `price` = VALUES(`price`),
    `duration` = VALUES(`duration`),
    `level` = VALUES(`level`),
    `content_source` = VALUES(`content_source`),
    `youtube_url` = VALUES(`youtube_url`),
    `content_file` = VALUES(`content_file`),
    `thumbnail` = VALUES(`thumbnail`),
    `is_featured` = VALUES(`is_featured`),
    `is_active` = VALUES(`is_active`),
    `updated_at` = NOW();
