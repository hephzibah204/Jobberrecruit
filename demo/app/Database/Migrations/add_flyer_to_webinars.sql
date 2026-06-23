-- MariaDB / MySQL migration script
-- Adds the flyer_path column to the webinars table

ALTER TABLE `webinars` 
ADD COLUMN `flyer_path` VARCHAR(255) NULL DEFAULT NULL AFTER `meeting_link`;
