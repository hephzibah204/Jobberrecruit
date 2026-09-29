<?php
// Fix employer/job_seeker accounts with missing status field
// Uses raw PDO to avoid CI4 bootstrap issues

$db = new PDO('mysql:host=127.0.0.1;dbname=jobberrecruit;charset=utf8', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Fix active users with empty/null status
$stmt = $db->prepare("UPDATE users SET status='active' WHERE active=1 AND user_type IN ('employer','job_seeker') AND (status IS NULL OR status='')");
$stmt->execute();
echo "Fixed {$stmt->rowCount()} user(s) with empty status." . PHP_EOL;

// Verify all users now
$rows = $db->query("SELECT id, username, user_type, active, status FROM users ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
echo PHP_EOL . "Current user statuses:" . PHP_EOL;
foreach ($rows as $u) {
    $status = $u['status'] === null ? 'NULL' : "'{$u['status']}'";
    echo "  id={$u['id']} {$u['username']} ({$u['user_type']}) active={$u['active']} status={$status}" . PHP_EOL;
}
