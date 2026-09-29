<?php
$mysqli = new mysqli("127.0.0.1", "root", "", "jobberrecruit");

echo "=== TESTS TABLE ===\n";
$res = $mysqli->query("SELECT id, category_id, title, slug, is_active FROM tests");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo "ID: {$row['id']} | Title: {$row['title']} | Slug: {$row['slug']} | Active: {$row['is_active']}\n";
    }
} else {
    echo "Error: " . $mysqli->error . "\n";
}

echo "\n=== QUESTIONS COUNT PER TEST ===\n";
$res2 = $mysqli->query("SELECT test_id, COUNT(*) as cnt FROM questions GROUP BY test_id");
if ($res2) {
    while ($row = $res2->fetch_assoc()) {
        echo "Test ID: {$row['test_id']} | Questions: {$row['cnt']}\n";
    }
} else {
    echo "Error: " . $mysqli->error . "\n";
}
