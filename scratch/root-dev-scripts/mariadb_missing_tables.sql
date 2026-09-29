-- =====================================================
-- MariaDB SQL Schema for JobberRecruit Missing Tables
-- Tables: Messaging, Candidate Search, Transactions
-- =====================================================

-- -----------------------------------------------------
-- Table: conversations
-- For: Messaging System
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `conversations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `employer_id` INT UNSIGNED NOT NULL,
  `job_seeker_id` INT UNSIGNED NOT NULL,
  `job_id` INT UNSIGNED NULL DEFAULT NULL,
  `last_message` TEXT NULL DEFAULT NULL,
  `last_message_at` DATETIME NULL DEFAULT NULL,
  `employer_last_read` DATETIME NULL DEFAULT NULL,
  `seeker_last_read` DATETIME NULL DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_employer_id` (`employer_id`),
  INDEX `idx_job_seeker_id` (`job_seeker_id`),
  INDEX `idx_job_id` (`job_id`),
  INDEX `idx_last_message_at` (`last_message_at`),
  INDEX `idx_is_active` (`is_active`),
  CONSTRAINT `fk_conversations_employer`
    FOREIGN KEY (`employer_id`) REFERENCES `employers` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_conversations_job_seeker`
    FOREIGN KEY (`job_seeker_id`) REFERENCES `job_seekers` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_conversations_job`
    FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table: messages
-- For: Messaging System
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `messages` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `conversation_id` INT UNSIGNED NOT NULL,
  `sender_id` INT UNSIGNED NOT NULL,
  `sender_type` VARCHAR(20) NOT NULL COMMENT 'employer or job_seeker',
  `message` TEXT NOT NULL,
  `is_read` TINYINT(1) NOT NULL DEFAULT 0,
  `read_at` DATETIME NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_conversation_id` (`conversation_id`),
  INDEX `idx_sender_id` (`sender_id`),
  INDEX `idx_sender_type` (`sender_type`),
  INDEX `idx_is_read` (`is_read`),
  INDEX `idx_created_at` (`created_at`),
  CONSTRAINT `fk_messages_conversation`
    FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table: candidate_unlocks
-- For: Candidate Search (Employers unlock candidates to message)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `candidate_unlocks` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `employer_id` INT UNSIGNED NOT NULL,
  `job_seeker_id` INT UNSIGNED NOT NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_unlock` (`employer_id`, `job_seeker_id`),
  INDEX `idx_employer_id` (`employer_id`),
  INDEX `idx_job_seeker_id` (`job_seeker_id`),
  CONSTRAINT `fk_candidate_unlocks_employer`
    FOREIGN KEY (`employer_id`) REFERENCES `employers` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_candidate_unlocks_job_seeker`
    FOREIGN KEY (`job_seeker_id`) REFERENCES `job_seekers` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table: payments
-- For: Transactions (Employer payments for subscriptions/bundles)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `payments` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NULL DEFAULT NULL COMMENT 'User who made the payment',
  `employer_id` INT UNSIGNED NULL DEFAULT NULL COMMENT 'Employer account (if applicable)',
  `reference` VARCHAR(255) NOT NULL COMMENT 'Payment reference from gateway',
  `amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `status` VARCHAR(50) NOT NULL DEFAULT 'pending' COMMENT 'pending, paid, failed',
  `payment_method` VARCHAR(50) NULL DEFAULT NULL COMMENT 'card, bank_transfer, etc.',
  `metadata` JSON NULL DEFAULT NULL COMMENT 'Additional payment data',
  `paid_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_reference` (`reference`),
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_employer_id` (`employer_id`),
  INDEX `idx_status` (`status`),
  INDEX `idx_paid_at` (`paid_at`),
  CONSTRAINT `fk_payments_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_payments_employer`
    FOREIGN KEY (`employer_id`) REFERENCES `employers` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table: wallets
-- For: User Wallet/Balance System
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `wallets` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `balance` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `currency` VARCHAR(3) NOT NULL DEFAULT 'NGN',
  `is_locked` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_wallet` (`user_id`),
  INDEX `idx_user_id` (`user_id`),
  CONSTRAINT `fk_wallets_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table: wallet_transactions
-- For: Wallet Transaction History
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `wallet_transactions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `wallet_id` INT UNSIGNED NOT NULL,
  `type` VARCHAR(50) NOT NULL COMMENT 'credit, debit, refund',
  `amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `reference` VARCHAR(255) NULL DEFAULT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_wallet_id` (`wallet_id`),
  INDEX `idx_type` (`type`),
  INDEX `idx_created_at` (`created_at`),
  CONSTRAINT `fk_wallet_transactions_wallet`
    FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table: job_credit_wallets
-- For: Job Posting Credits System
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `job_credit_wallets` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `credits` INT NOT NULL DEFAULT 0 COMMENT 'Number of job posting credits',
  `source` VARCHAR(50) NOT NULL DEFAULT 'starter' COMMENT 'starter, subscription, bundle',
  `reference_id` INT UNSIGNED NULL DEFAULT NULL COMMENT 'plan_id or bundle_id',
  `expires_at` DATETIME NULL DEFAULT NULL COMMENT 'NULL = never expires',
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_source` (`source`),
  INDEX `idx_expires_at` (`expires_at`),
  INDEX `idx_credits` (`credits`),
  CONSTRAINT `fk_job_credit_wallets_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table: job_credit_transactions
-- For: Job Credit Transaction History
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `job_credit_transactions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `type` VARCHAR(20) NOT NULL COMMENT 'credit_in, credit_out, reset',
  `credits` INT NOT NULL DEFAULT 0,
  `reference` VARCHAR(255) NULL DEFAULT NULL COMMENT 'job_id, subscription_id, bundle_id, monthly_reset',
  `description` TEXT NULL DEFAULT NULL,
  `meta` JSON NULL DEFAULT NULL COMMENT 'Extra data like plan features',
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_type` (`type`),
  INDEX `idx_reference` (`reference`),
  INDEX `idx_created_at` (`created_at`),
  CONSTRAINT `fk_job_credit_transactions_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
