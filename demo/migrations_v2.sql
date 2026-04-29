-- JobberRecruit Advanced Features Migration v2

-- 1. Newsletter & Webinar Tools
CREATE TABLE IF NOT EXISTS newsletters (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    status ENUM('draft', 'sent', 'scheduled') DEFAULT 'draft',
    scheduled_at DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) UNIQUE NOT NULL,
    user_id INT UNSIGNED NULL, -- Optional linking to existing user
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS webinars (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    speaker VARCHAR(150),
    webinar_date DATETIME NOT NULL,
    link VARCHAR(255),
    image VARCHAR(255),
    status ENUM('upcoming', 'live', 'ended', 'cancelled') DEFAULT 'upcoming',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS webinar_registrations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    webinar_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (webinar_id) REFERENCES webinars(id) ON DELETE CASCADE
);

-- 2. ATS Pre-screening Questions
CREATE TABLE IF NOT EXISTS job_questions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    job_id INT UNSIGNED NOT NULL,
    question TEXT NOT NULL,
    type ENUM('text', 'select', 'radio', 'checkbox') DEFAULT 'text',
    options TEXT NULL, -- JSON formatted options for select/radio
    is_required TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS application_answers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    application_id INT UNSIGNED NOT NULL,
    question_id INT UNSIGNED NOT NULL,
    answer TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (application_id) REFERENCES job_applications(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES job_questions(id) ON DELETE CASCADE
);

-- 3. Referral & Affiliate Program
CREATE TABLE IF NOT EXISTS referrals (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    referrer_id INT UNSIGNED NOT NULL,
    referee_id INT UNSIGNED NULL, -- Set when referee signs up
    code VARCHAR(50) NOT NULL,
    status ENUM('pending', 'completed', 'rewarded') DEFAULT 'pending',
    reward_amount DECIMAL(15,2) DEFAULT 0.00,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (referrer_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 4. Fraud/Scam Reporting System
CREATE TABLE IF NOT EXISTS job_reports (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    job_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    reason VARCHAR(255) NOT NULL,
    details TEXT,
    status ENUM('pending', 'investigating', 'resolved', 'dismissed') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
);

-- 5. E-Learning Marketplace
CREATE TABLE IF NOT EXISTS courses (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    instructor VARCHAR(150),
    price DECIMAL(15,2) DEFAULT 0.00,
    image VARCHAR(255),
    category VARCHAR(100),
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS course_enrollments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    status ENUM('enrolled', 'completed', 'cancelled') DEFAULT 'enrolled',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);
