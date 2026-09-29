-- =====================================================
-- Advanced Newsletter HTML Customization
-- =====================================================

-- 1. Add HTML Mode columns to newsletters table
ALTER TABLE `newsletters` 
    ADD COLUMN `template_mode` ENUM('standard', 'html') DEFAULT 'standard' AFTER `content`,
    ADD COLUMN `custom_html` LONGTEXT NULL AFTER `template_mode`;

-- 2. Add same for Version B (if A/B testing is enabled)
ALTER TABLE `newsletters`
    ADD COLUMN `template_mode_b` ENUM('standard', 'html') DEFAULT 'standard' AFTER `content_b`,
    ADD COLUMN `custom_html_b` LONGTEXT NULL AFTER `template_mode_b`;
