-- JobberRecruit Complete Database Schema (Including Shield and Migrations)
-- Generate for phpMyAdmin Import

SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------
-- SHIELD AUTH TABLES
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `referred_by` int(11) unsigned DEFAULT NULL,
  `referral_code` varchar(20) DEFAULT NULL UNIQUE,
  `username` varchar(30) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `status_message` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `user_type` enum('job_seeker','employer','admin') DEFAULT 'job_seeker',
  `last_active` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  FOREIGN KEY (`referred_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `auth_identities` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `type` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `secret` varchar(255) NOT NULL,
  `secret2` varchar(255) DEFAULT NULL,
  `expires` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_secret` (`type`,`secret`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `auth_identities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `auth_logins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `id_type` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_type_identifier` (`id_type`,`identifier`),
  KEY `user_id` (`user_id`)
);

CREATE TABLE IF NOT EXISTS `auth_token_logins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `id_type` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_type_identifier` (`id_type`,`identifier`),
  KEY `user_id` (`user_id`)
);

CREATE TABLE IF NOT EXISTS `auth_remember_tokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `selector` varchar(255) NOT NULL,
  `hashedValidator` varchar(255) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `expires` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `selector` (`selector`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `auth_remember_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `auth_groups_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `group` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `auth_groups_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `auth_permissions_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `permission` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `auth_permissions_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- BASE APPLICATION TABLES
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS countries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) UNIQUE,
    iso_code VARCHAR(10) UNIQUE,
    created_at DATETIME,
    updated_at DATETIME
);

CREATE TABLE IF NOT EXISTS states (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    country_id INT UNSIGNED NOT NULL,
    name VARCHAR(150),
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS industries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parent_id INT UNSIGNED NULL,
    name VARCHAR(150),
    slug VARCHAR(150) UNIQUE,
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (parent_id) REFERENCES industries(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS job_categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parent_id INT UNSIGNED NULL,
    name VARCHAR(150),
    slug VARCHAR(150) UNIQUE,
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (parent_id) REFERENCES job_categories(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS employers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    company_name VARCHAR(255),
    logo VARCHAR(255) NULL,
    industry_id INT UNSIGNED NULL,
    company_size VARCHAR(50),
    website VARCHAR(255),
    company_address VARCHAR(255),
    state_id INT UNSIGNED NULL,
    description TEXT,
    contact_name VARCHAR(150),
    contact_email VARCHAR(150),
    contact_phone VARCHAR(50),
    verification_doc VARCHAR(255) NULL,
    unlimited_access TINYINT(1) DEFAULT 0,
    unlimited_until DATETIME NULL,
    tin_number VARCHAR(50) NULL,
    is_verified TINYINT(1) DEFAULT 0,
    verification_status ENUM('pending', 'verified', 'rejected', 'document_required') DEFAULT 'pending',
    verification_documents JSON NULL,
    verification_notes TEXT NULL,
    verified_at DATETIME NULL,
    verified_by INT UNSIGNED NULL,
    rejection_reason TEXT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (state_id) REFERENCES states(id) ON DELETE SET NULL,
    FOREIGN KEY (industry_id) REFERENCES industries(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS job_seekers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    is_verified TINYINT(1) DEFAULT 0,
    full_name VARCHAR(150),
    profile_picture VARCHAR(255) NULL,
    dob DATE NULL,
    gender ENUM('male','female','other') NULL,
    phone VARCHAR(50),
    location VARCHAR(255) NULL,
    bio TEXT NULL,
    job_title VARCHAR(150),
    employment_type VARCHAR(100),
    skills TEXT,
    experience_years INT,
    education_level VARCHAR(100),
    languages TEXT,
    resume VARCHAR(255) NULL,
    cover_letter VARCHAR(255) NULL,
    portfolio VARCHAR(255) NULL,
    desired_salary VARCHAR(100),
    salary_type VARCHAR(50) DEFAULT 'monthly',
    availability VARCHAR(100),
    state_id INT UNSIGNED NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (state_id) REFERENCES states(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS jobs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employer_id INT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NULL,
    description TEXT,
    job_type ENUM('full-time','part-time','remote','contract','internship', 'temporary') DEFAULT 'full-time',
    state_id INT UNSIGNED NULL,
    location_type VARCHAR(50) DEFAULT 'on-site',
    salary_type VARCHAR(50) DEFAULT 'fixed',
    salary_period VARCHAR(50) DEFAULT 'monthly',
    salary DECIMAL(15,2) NULL,
    salary_max DECIMAL(15,2) NULL,
    salary_details TEXT NULL,
    industry_id INT UNSIGNED NULL,
    category_id INT UNSIGNED NULL,
    education_level VARCHAR(100) NULL,
    experience_level VARCHAR(100) NULL,
    experience TEXT NULL,
    skills TEXT NULL,
    requirements TEXT NULL,
    application_method VARCHAR(50) DEFAULT 'internal',
    application_access ENUM('authenticated', 'guest', 'any') DEFAULT 'any',
    whatsapp_link VARCHAR(255) NULL,
    application_email VARCHAR(150) NULL,
    external_url VARCHAR(255) NULL,
    application_deadline DATETIME NULL,
    start_date DATE NULL,
    contact_email VARCHAR(150) NULL,
    contact_phone VARCHAR(50) NULL,
    featured_until DATETIME NULL,
    status ENUM('open','closed','draft','expired') DEFAULT 'open',
    is_featured TINYINT(1) DEFAULT 0,
    is_anonymous TINYINT(1) DEFAULT 0,
    network_blast TINYINT(1) DEFAULT 0,
    views INT UNSIGNED DEFAULT 0,
    accommodation TINYINT(1) DEFAULT 0,
    notification_preferences TEXT NULL,
    admin_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    admin_reviewed_at DATETIME NULL,
    admin_notes TEXT NULL,
    is_verified TINYINT(1) DEFAULT 0,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    FOREIGN KEY (employer_id) REFERENCES employers(id) ON DELETE CASCADE,
    FOREIGN KEY (state_id) REFERENCES states(id) ON DELETE SET NULL,
    FOREIGN KEY (industry_id) REFERENCES industries(id) ON DELETE SET NULL,
    FOREIGN KEY (category_id) REFERENCES job_categories(id) ON DELETE SET NULL,
    INDEX (slug),
    INDEX (status)
);

CREATE TABLE IF NOT EXISTS job_applications (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    job_id INT UNSIGNED NOT NULL,
    job_seeker_id INT UNSIGNED NOT NULL,
    status ENUM('pending','shortlisted','rejected','hired') DEFAULT 'pending',
    cover_letter TEXT NULL,
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    FOREIGN KEY (job_seeker_id) REFERENCES job_seekers(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    full_name VARCHAR(150),
    role VARCHAR(100) DEFAULT 'super_admin',
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS blogs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_id INT UNSIGNED NOT NULL,
    title VARCHAR(155),
    slug VARCHAR(155) UNIQUE,
    content TEXT,
    thumbnail VARCHAR(155) NULL,
    status ENUM('draft','published') DEFAULT 'draft',
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS employer_industries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employer_id INT UNSIGNED NOT NULL,
    industry_id INT UNSIGNED NOT NULL,
    FOREIGN KEY (employer_id) REFERENCES employers(id) ON DELETE CASCADE,
    FOREIGN KEY (industry_id) REFERENCES industries(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS job_seeker_industries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    job_seeker_id INT UNSIGNED NOT NULL,
    industry_id INT UNSIGNED NOT NULL,
    FOREIGN KEY (job_seeker_id) REFERENCES job_seekers(id) ON DELETE CASCADE,
    FOREIGN KEY (industry_id) REFERENCES industries(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS job_seeker_categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    job_seeker_id INT UNSIGNED NOT NULL,
    category_id INT UNSIGNED NOT NULL,
    FOREIGN KEY (job_seeker_id) REFERENCES job_seekers(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES job_categories(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS job_alerts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    job_seeker_id INT UNSIGNED NOT NULL,
    keyword VARCHAR(150) NULL,
    location_id INT UNSIGNED NULL,
    frequency ENUM('daily','weekly','instant') DEFAULT 'daily',
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (job_seeker_id) REFERENCES job_seekers(id) ON DELETE CASCADE,
    FOREIGN KEY (location_id) REFERENCES states(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS password_resets (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    token VARCHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- ADVANCED FEATURES AND MIGRATIONS
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS newsletters (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    status ENUM('draft', 'sent') DEFAULT 'draft',
    sent_at DATETIME NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
);

CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    user_id INT UNSIGNED NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME NULL
);

CREATE TABLE IF NOT EXISTS webinars (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    speaker_name VARCHAR(255),
    scheduled_at DATETIME,
    meeting_link VARCHAR(255),
    status ENUM('upcoming', 'ongoing', 'completed', 'cancelled') DEFAULT 'upcoming',
    created_at DATETIME NULL,
    updated_at DATETIME NULL
);

CREATE TABLE IF NOT EXISTS webinar_registrations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    webinar_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    created_at DATETIME NULL
);

CREATE TABLE IF NOT EXISTS job_questions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    job_id INT UNSIGNED NOT NULL,
    question_text TEXT NOT NULL,
    question_type ENUM('text', 'yes_no', 'multiple_choice') DEFAULT 'text',
    is_required TINYINT(1) DEFAULT 1,
    created_at DATETIME NULL,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS job_application_answers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    application_id INT UNSIGNED NOT NULL,
    question_id INT UNSIGNED NOT NULL,
    answer_text TEXT,
    created_at DATETIME NULL,
    FOREIGN KEY (application_id) REFERENCES job_applications(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES job_questions(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS referrals (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    referrer_id INT UNSIGNED NOT NULL,
    referee_id INT UNSIGNED NULL,
    code VARCHAR(50) NOT NULL,
    status ENUM('pending', 'completed', 'rewarded') DEFAULT 'pending',
    reward_amount DECIMAL(15,2) DEFAULT 0.00,
    created_at DATETIME NULL,
    FOREIGN KEY (referrer_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS job_reports (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    job_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NULL,
    reason VARCHAR(255) NOT NULL,
    details TEXT NULL,
    status ENUM('pending', 'reviewed', 'dismissed', 'acted') DEFAULT 'pending',
    created_at DATETIME NULL,
    updated_at DATETIME NULL
);

CREATE TABLE IF NOT EXISTS courses (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    instructor VARCHAR(150) NOT NULL,
    price DECIMAL(15,2) DEFAULT 0.00,
    thumbnail VARCHAR(255) NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME NULL,
    duration VARCHAR(100) NULL,
    level VARCHAR(50) DEFAULT 'beginner',
    content_source VARCHAR(20) DEFAULT 'none',
    youtube_url VARCHAR(255) NULL,
    content_file VARCHAR(255) NULL,
    is_featured TINYINT(1) DEFAULT 0,
    updated_at DATETIME NULL,
    UNIQUE KEY courses_slug_unique (slug),
    KEY courses_is_active_idx (is_active),
    KEY courses_is_featured_idx (is_featured)
);

CREATE TABLE IF NOT EXISTS course_enrollments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    payment_reference VARCHAR(100) NULL,
    status VARCHAR(20) DEFAULT 'enrolled',
    created_at DATETIME NULL,
    amount DECIMAL(15,2) DEFAULT 0.00,
    KEY course_enrollments_user_id_idx (user_id),
    KEY course_enrollments_status_idx (status),
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS resumes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    summary TEXT NULL,
    template_id VARCHAR(50) DEFAULT 'classic',
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS resume_experiences (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    resume_id INT UNSIGNED NOT NULL,
    company VARCHAR(255) NOT NULL,
    position VARCHAR(255) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NULL,
    description TEXT NULL,
    is_current TINYINT(1) DEFAULT 0,
    created_at DATETIME NULL,
    FOREIGN KEY (resume_id) REFERENCES resumes(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS resume_education (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    resume_id INT UNSIGNED NOT NULL,
    institution VARCHAR(255) NOT NULL,
    degree VARCHAR(255) NOT NULL,
    field_of_study VARCHAR(255) NULL,
    graduation_date DATE NULL,
    created_at DATETIME NULL,
    FOREIGN KEY (resume_id) REFERENCES resumes(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS resume_skills (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    resume_id INT UNSIGNED NOT NULL,
    skill_name VARCHAR(100) NOT NULL,
    proficiency_level ENUM('beginner', 'intermediate', 'advanced', 'expert') DEFAULT 'intermediate',
    created_at DATETIME NULL,
    FOREIGN KEY (resume_id) REFERENCES resumes(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS affiliate_settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(100) UNIQUE NOT NULL,
    `value` TEXT NOT NULL,
    description VARCHAR(255) NULL,
    updated_at DATETIME NULL
);

CREATE TABLE IF NOT EXISTS queue_jobs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    queue VARCHAR(100) DEFAULT 'default',
    payload LONGTEXT NOT NULL,
    attempts TINYINT UNSIGNED DEFAULT 0,
    status ENUM('pending', 'processing', 'completed', 'failed') DEFAULT 'pending',
    error TEXT NULL,
    available_at DATETIME NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    INDEX status_idx (status)
);

CREATE TABLE IF NOT EXISTS mock_interview_sessions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    application_id INT UNSIGNED NULL,
    user_id INT UNSIGNED NOT NULL,
    job_title VARCHAR(255) NOT NULL,
    difficulty VARCHAR(20) NOT NULL DEFAULT 'medium',
    question_pack VARCHAR(50) NOT NULL DEFAULT 'general',
    interview_mode VARCHAR(20) NOT NULL DEFAULT 'chat',
    webcam_enabled TINYINT(1) NOT NULL DEFAULT 0,
    duration_seconds INT UNSIGNED NOT NULL DEFAULT 0,
    overall_score INT UNSIGNED NULL,
    star_average INT UNSIGNED NULL,
    transcript_json LONGTEXT NOT NULL,
    evaluation_json LONGTEXT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    KEY mock_interview_sessions_application_id_idx (application_id),
    KEY mock_interview_sessions_user_id_idx (user_id),
    KEY mock_interview_sessions_created_at_idx (created_at),
    CONSTRAINT mock_interview_sessions_application_fk
        FOREIGN KEY (application_id) REFERENCES job_applications(id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT mock_interview_sessions_user_fk
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
);

SET FOREIGN_KEY_CHECKS = 1;
