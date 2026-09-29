<?php
$mysqli = new mysqli("127.0.0.1", "root", "", "jobberrecruit");

$userId = 6;
$res = $mysqli->query("SELECT * FROM job_seekers WHERE user_id = {$userId}");
$candidate = $res->fetch_assoc();

if (!$candidate) {
    $mysqli->query("INSERT INTO job_seekers (user_id, full_name, phone, job_title, skills, resume, created_at) VALUES ({$userId}, 'Test Candidate', '08012345678', 'Software Engineer', 'PHP, JavaScript, SQL', 'uploads/resumes/sample.pdf', NOW())");
    echo "Created complete candidate profile for candidate@test.com\n";
} else {
    $mysqli->query("UPDATE job_seekers SET full_name = 'Test Candidate', phone = '08012345678', job_title = 'Software Engineer', skills = 'PHP, JavaScript, SQL', resume = 'uploads/resumes/sample.pdf' WHERE user_id = {$userId}");
    echo "Updated candidate profile for candidate@test.com to complete\n";
}
