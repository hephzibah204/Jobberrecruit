-- MariaDB / MySQL migration script
-- Adds the profile_completion column to the job_seekers table
-- Tracks how complete a candidate's profile is (percentage 0-100)
-- Used to prioritize candidates with >= 90% completion in employer search results

ALTER TABLE `job_seekers` 
ADD COLUMN `profile_completion` TINYINT(3) UNSIGNED NOT NULL DEFAULT 0 AFTER `is_verified`;
