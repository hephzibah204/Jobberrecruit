-- Salary Negotiation Sessions table
-- Stores history of practice negotiation sessions for each candidate

CREATE TABLE IF NOT EXISTS `salary_negotiation_sessions` (
    `id`                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id`             INT UNSIGNED NOT NULL,
    `job_title`           VARCHAR(255) NOT NULL DEFAULT '',
    `base_salary_offered` VARCHAR(50)  NOT NULL DEFAULT '',
    `target_salary`       VARCHAR(50)  NOT NULL DEFAULT '',
    `final_salary`        VARCHAR(50)  NOT NULL DEFAULT '',
    `recruiter_style`     VARCHAR(50)  NOT NULL DEFAULT 'corporate',
    `difficulty`          VARCHAR(20)  NOT NULL DEFAULT 'medium',
    `rounds_completed`    INT UNSIGNED NOT NULL DEFAULT 0,
    `confidence_score`    INT UNSIGNED NOT NULL DEFAULT 0,
    `persuasion_score`    INT UNSIGNED NOT NULL DEFAULT 0,
    `overall_score`       INT UNSIGNED NOT NULL DEFAULT 0,
    `outcome`             VARCHAR(50)  NOT NULL DEFAULT '',
    `transcript_json`     TEXT         NULL,
    `evaluation_json`     TEXT         NULL,
    `created_at`          DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,

    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
