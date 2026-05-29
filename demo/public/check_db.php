<?php
$dbPath = 'C:\Users\Abiodun Emmanuel\Documents\CODEBASE\Jobberrecruit\demo\writable\database.sqlite';

if (!file_exists($dbPath)) {
    die("Database file not found at: $dbPath\n");
}

echo "Database file exists ($dbPath)\n";

$db = new PDO('sqlite:' . $dbPath);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$tables = $db->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name")->fetchAll(PDO::FETCH_COLUMN);
echo "Tables (" . count($tables) . "): " . implode(', ', $tables) . "\n";

$cols = $db->query("PRAGMA table_info(users)")->fetchAll(PDO::FETCH_ASSOC);
echo "\nUsers columns:\n";
foreach ($cols as $c) {
    echo "  {$c['name']} ({$c['type']})\n";
}
$users = $db->query('SELECT * FROM users LIMIT 3')->fetchAll(PDO::FETCH_ASSOC);
echo "\nUsers (" . count($users) . "):\n";
foreach ($users as $u) {
    echo "  #{$u['id']} status={$u['status']} type={$u['user_type']} " . json_encode($u) . "\n";
}

$identities = $db->query('SELECT user_id, type, secret FROM auth_identities LIMIT 5')->fetchAll(PDO::FETCH_ASSOC);
echo "\nIdentities (" . count($identities) . "):\n";
foreach ($identities as $i) {
    echo "  user_id={$i['user_id']} type={$i['type']} secret={$i['secret']}\n";
}

// Check auth tables specifically
$authTables = ['auth_identities', 'auth_logins', 'auth_token_logins'];
foreach ($authTables as $tbl) {
    $exists = $db->query("SELECT COUNT(*) FROM sqlite_master WHERE type='table' AND name='$tbl'")->fetchColumn();
    echo "\n$tbl: " . ($exists ? 'EXISTS' : 'MISSING');
    if ($exists) {
        $cnt = $db->query("SELECT COUNT(*) FROM $tbl")->fetchColumn();
        echo " ($cnt rows)";
    }
}
echo "\n";
