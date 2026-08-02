-- ============================================================================
-- Salary Negotiation Sessions
-- Stores the history of practice negotiation sessions for each candidate,
-- used by CareerToolsController::salaryNegotiation() and
-- App\Models\SalaryNegotiationSessionModel.
--
-- This is the ONLY new table introduced by this session's fixes. Every other
-- table referenced anywhere in app/Models and app/Controllers was confirmed
-- to already exist (cross-checked every Model's $table property and every
-- raw ->table() call against SHOW TABLES).
--
-- Run once against the target database:
--   mysql -u <user> -p <database> < database/salary_negotiation_sessions.sql
-- ============================================================================

CREATE TABLE IF NOT EXISTS `salary_negotiation_sessions` (
    `id`                  INT UNSIGNED NOT NULL AUTO_INCREMENT,
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
    PRIMARY KEY (`id`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_created_at` (`created_at`),
    CONSTRAINT `salary_negotiation_sessions_user_id_foreign`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
