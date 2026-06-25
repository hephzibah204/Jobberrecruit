<?php
$mysqli = new mysqli('127.0.0.1', 'root', 'WaitOnGod2026', 'jobberrecruit', 3306);
if ($mysqli->connect_error) { echo 'FAIL: ' . $mysqli->connect_error . "\n"; exit(1); }
echo "Connected OK\n";

// Test 1: Simple count
$t0 = microtime(true);
$r = $mysqli->query('SELECT COUNT(*) as c FROM jobs WHERE status="open"');
$row = $r->fetch_assoc();
echo 'Simple count: ' . round(microtime(true)-$t0, 4) . 's  Result: ' . $row['c'] . "\n";

// Test 2: The complex query
$t0 = microtime(true);
$r = $mysqli->query("SELECT jobs.*, job_categories.name as category_name, industries.name as industry_name, states.name as location, employers.user_id as employer_user_id, employers.logo as company_logo, employers.is_verified FROM jobs LEFT JOIN states ON states.id = jobs.state_id LEFT JOIN job_categories ON job_categories.id = jobs.category_id LEFT JOIN industries ON industries.id = jobs.industry_id LEFT JOIN employers ON employers.id = jobs.employer_id WHERE status = 'open' ORDER BY jobs.is_featured DESC, jobs.featured_until DESC LIMIT 18");
echo 'Complex query: ' . round(microtime(true)-$t0, 4) . 's  Rows: ' . $r->num_rows . "\n";

echo "Done\n";
