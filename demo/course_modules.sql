-- Adds the item_type column to the courses table (for eBook support)
ALTER TABLE `courses` ADD COLUMN `item_type` VARCHAR(50) DEFAULT 'course';

-- Creates the course_modules table for multi-video/multi-lesson courses
CREATE TABLE IF NOT EXISTS `course_modules` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `course_id` INT(11) UNSIGNED NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `content_source` VARCHAR(50) DEFAULT 'none',
    `youtube_url` VARCHAR(255),
    `content_file` VARCHAR(255),
    `order_index` INT(11) DEFAULT 0,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    KEY `idx_course_modules_course_id` (`course_id`),
    CONSTRAINT `fk_course_modules_courses` FOREIGN KEY(`course_id`) REFERENCES `courses`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
