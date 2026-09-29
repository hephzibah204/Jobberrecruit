<?php
$mysqli = new mysqli("127.0.0.1", "root", "", "jobberrecruit");

foreach ([2, 34] as $userId) {
    $email = ($userId === 2) ? 'employer@test.com' : 'demo.employer@example.com';
    $res = $mysqli->query("SELECT * FROM employers WHERE user_id = {$userId}");
    $employer = $res->fetch_assoc();

    if (!$employer) {
        $mysqli->query("INSERT INTO employers (user_id, company_name, company_size, contact_email, contact_phone, is_verified, created_at) VALUES ({$userId}, 'Test Employer Ltd', '11-50', '{$email}', '08099998888', 1, NOW())");
        echo "Created complete employer record for user {$userId}\n";
    } else {
        $mysqli->query("UPDATE employers SET company_name = 'Test Employer Ltd', company_size = '11-50', contact_email = '{$email}', contact_phone = '08099998888', is_verified = 1 WHERE user_id = {$userId}");
        echo "Updated employer record for user {$userId} to complete\n";
    }

    $resCredit = $mysqli->query("SELECT * FROM job_credit_wallets WHERE user_id = {$userId}");
    if ($resCredit && $resCredit->num_rows > 0) {
        $mysqli->query("UPDATE job_credit_wallets SET credits = 50, expires_at = DATE_ADD(NOW(), INTERVAL 30 DAY) WHERE user_id = {$userId}");
    } else {
        $mysqli->query("INSERT INTO job_credit_wallets (user_id, credits, source, created_at, updated_at, expires_at) VALUES ({$userId}, 50, 'bundle', NOW(), NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY))");
    }
    echo "Ensured 50 unexpired job credits for user {$userId}\n";
}
