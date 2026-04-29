<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'jobber_db';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$queries = [
    "ALTER TABLE jobs 
    ADD COLUMN industry_id INT UNSIGNED NULL AFTER category_id,
    ADD COLUMN location_type VARCHAR(50) NULL,
    ADD COLUMN salary_type VARCHAR(50) NULL,
    ADD COLUMN salary_period VARCHAR(50) NULL,
    ADD COLUMN salary DECIMAL(15,2) NULL,
    ADD COLUMN salary_max DECIMAL(15,2) NULL,
    ADD COLUMN salary_details TEXT NULL,
    ADD COLUMN education_level VARCHAR(100) NULL,
    ADD COLUMN experience_level VARCHAR(100) NULL,
    ADD COLUMN experience TEXT NULL,
    ADD COLUMN requirements TEXT NULL,
    ADD COLUMN application_method VARCHAR(50) NULL,
    ADD COLUMN application_access VARCHAR(50) NULL,
    ADD COLUMN whatsapp_link VARCHAR(255) NULL,
    ADD COLUMN application_email VARCHAR(150) NULL,
    ADD COLUMN external_url VARCHAR(255) NULL,
    ADD COLUMN application_deadline DATETIME NULL,
    ADD COLUMN start_date DATE NULL,
    ADD COLUMN contact_email VARCHAR(150) NULL,
    ADD COLUMN contact_phone VARCHAR(50) NULL,
    ADD COLUMN is_featured TINYINT(1) DEFAULT 0,
    ADD COLUMN featured_until DATETIME NULL,
    ADD COLUMN is_anonymous TINYINT(1) DEFAULT 0,
    ADD COLUMN views INT DEFAULT 0,
    ADD COLUMN admin_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'",

    "ALTER TABLE employers 
    ADD COLUMN industry_id_new INT UNSIGNED NULL AFTER logo,
    ADD COLUMN verification_status ENUM('pending', 'verified', 'rejected', 'document_required') DEFAULT 'pending',
    ADD COLUMN verified_at DATETIME NULL,
    ADD COLUMN verified_by INT UNSIGNED NULL,
    ADD COLUMN unlimited_access TINYINT(1) DEFAULT 0,
    ADD COLUMN unlimited_until DATETIME NULL",

    "ALTER TABLE job_seekers 
    ADD COLUMN bio TEXT NULL,
    ADD COLUMN location VARCHAR(255) NULL,
    ADD COLUMN salary_type VARCHAR(50) NULL"
];

foreach ($queries as $query) {
    if ($conn->query($query) === TRUE) {
        echo "Query executed successfully\n";
    } else {
        echo "Error: " . $conn->error . "\n";
    }
}

// Special case for employers industry_id if it already existed under a different name or to avoid conflicts
$conn->query("ALTER TABLE employers CHANGE COLUMN industry_id_new industry_id INT UNSIGNED NULL");

$conn->close();
echo "Database patch completed.\n";
