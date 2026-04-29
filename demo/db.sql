CREATE TABLE countries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) UNIQUE,
    iso_code VARCHAR(10) UNIQUE, -- e.g., NG, US, UK
    created_at DATETIME,
    updated_at DATETIME
);

CREATE TABLE states (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    country_id INT UNSIGNED NOT NULL,
    name VARCHAR(150),
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE CASCADE
);

CREATE TABLE industries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parent_id INT UNSIGNED NULL,
    name VARCHAR(150),
    slug VARCHAR(150) UNIQUE,
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (parent_id) REFERENCES industries(id) ON DELETE SET NULL
);

CREATE TABLE job_categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parent_id INT UNSIGNED NULL,
    name VARCHAR(150),
    slug VARCHAR(150) UNIQUE,
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (parent_id) REFERENCES job_categories(id) ON DELETE SET NULL
);

CREATE TABLE employers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    company_name VARCHAR(255),
    logo VARCHAR(255) NULL,
    company_size VARCHAR(50),
    website VARCHAR(255),
    company_address VARCHAR(255),
    state_id INT UNSIGNED NULL,
    description TEXT,
    contact_name VARCHAR(150),
    contact_email VARCHAR(150),
    contact_phone VARCHAR(50),
    verification_doc VARCHAR(255) NULL,
    is_verified TINYINT(1) DEFAULT 0,
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (state_id) REFERENCES states(id) ON DELETE SET NULL
);

CREATE TABLE job_seekers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    full_name VARCHAR(150),
    profile_picture VARCHAR(255) NULL,
    dob DATE NULL,
    gender ENUM('male','female','other') NULL,
    phone VARCHAR(50),
    state_id INT UNSIGNED NULL,
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
    availability VARCHAR(100),
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (state_id) REFERENCES states(id) ON DELETE SET NULL
);

CREATE TABLE jobs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employer_id INT UNSIGNED NOT NULL,
    category_id INT UNSIGNED NOT NULL,
    state_id INT UNSIGNED NULL,
    title VARCHAR(255),
    description TEXT,
    job_type ENUM('full-time','part-time','remote','contract','internship'),
    salary_range VARCHAR(100) NULL,
    status ENUM('open','closed') DEFAULT 'open',
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (employer_id) REFERENCES employers(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES job_categories(id) ON DELETE CASCADE,
    FOREIGN KEY (state_id) REFERENCES states(id) ON DELETE SET NULL
);

CREATE TABLE job_applications (
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

CREATE TABLE admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    full_name VARCHAR(150),
    role VARCHAR(100) DEFAULT 'super_admin', -- or 'moderator'
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE blogs (
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

CREATE TABLE employer_industries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employer_id INT UNSIGNED NOT NULL,
    industry_id INT UNSIGNED NOT NULL,
    FOREIGN KEY (employer_id) REFERENCES employers(id) ON DELETE CASCADE,
    FOREIGN KEY (industry_id) REFERENCES industries(id) ON DELETE CASCADE
);

CREATE TABLE job_seeker_industries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    job_seeker_id INT UNSIGNED NOT NULL,
    industry_id INT UNSIGNED NOT NULL,
    FOREIGN KEY (job_seeker_id) REFERENCES job_seekers(id) ON DELETE CASCADE,
    FOREIGN KEY (industry_id) REFERENCES industries(id) ON DELETE CASCADE
);

CREATE TABLE job_seeker_categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    job_seeker_id INT UNSIGNED NOT NULL,
    category_id INT UNSIGNED NOT NULL,
    FOREIGN KEY (job_seeker_id) REFERENCES job_seekers(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES job_categories(id) ON DELETE CASCADE
);

CREATE TABLE job_alerts (
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

CREATE TABLE password_resets (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    token VARCHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
