-- MariaDB / MySQL migration script
-- Adds the referral_terms_accepted column to the users table
-- Used to track if a user has accepted the new 10% referral program terms and conditions

ALTER TABLE `users` 
ADD COLUMN `referral_terms_accepted` TINYINT(1) NOT NULL DEFAULT 0;
