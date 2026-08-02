-- =====================================================
-- Advanced Certificate Customization System (Simplified)
-- =====================================================

-- 1. Drop if exists to ensure a clean slate
DROP TABLE IF EXISTS `certificate_templates`;

-- 2. Create Certificate Templates Table 
-- WE REMOVED THE FOREIGN KEY TO PREVENT ERRNO 150
-- The application will handle the connection via the course_id column
CREATE TABLE `certificate_templates` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `course_id` INT UNSIGNED NULL, -- NULL means Global Default
    `template_name` VARCHAR(100) DEFAULT 'Default Template',
    `background_image` VARCHAR(255) NULL,
    `primary_color` VARCHAR(20) DEFAULT '#0364b3',
    `secondary_color` VARCHAR(20) DEFAULT '#F5A623',
    `text_color` VARCHAR(20) DEFAULT '#333333',
    `show_qr_code` TINYINT(1) DEFAULT 1,
    `show_signature` TINYINT(1) DEFAULT 1,
    `show_logo` TINYINT(1) DEFAULT 1,
    `template_mode` ENUM('builder', 'image', 'html') DEFAULT 'builder',
    `custom_html` LONGTEXT NULL,
    `layout_json` JSON NOT NULL,
    `additional_text` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (`course_id`) -- Adding an index instead of a hard constraint
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Seed Default Global Template
INSERT INTO `certificate_templates` (`course_id`, `template_name`, `layout_json`)
VALUES (NULL, 'Global Default', '{
    "logo": {"top": "80px", "left": "50%", "transform": "translateX(-50%)", "width": "120px", "visible": true},
    "title": {"top": "160px", "left": "50%", "transform": "translateX(-50%)", "font_size": "48px", "visible": true},
    "subtitle": {"top": "230px", "left": "50%", "transform": "translateX(-50%)", "font_size": "20px", "visible": true},
    "recipient_name": {"top": "320px", "left": "50%", "transform": "translateX(-50%)", "font_size": "42px", "visible": true},
    "course_title": {"top": "440px", "left": "50%", "transform": "translateX(-50%)", "font_size": "24px", "visible": true},
    "date_issued": {"bottom": "100px", "left": "100px", "font_size": "16px", "visible": true},
    "signature": {"bottom": "100px", "right": "100px", "width": "150px", "visible": true},
    "qr_code": {"bottom": "100px", "left": "50%", "transform": "translateX(-50%)", "width": "80px", "visible": true},
    "certificate_code": {"bottom": "50px", "left": "50%", "transform": "translateX(-50%)", "font_size": "12px", "visible": true}
}');
