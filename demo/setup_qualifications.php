<?php
$db = new SQLite3('writable/database.sqlite');

// Create qualifications table
$db->exec("CREATE TABLE IF NOT EXISTS qualifications (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    order_index INTEGER DEFAULT 0,
    is_active TINYINT DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// Insert default qualifications in the requested order
$defaults = [
    'BA/BSc/HND',
    'First School Leaving Certificate',
    'MBA/MSc/MA',
    'NCE',
    'OND',
    'Others',
    'PhD/Fellowship',
    'Professional Certificate',
    'Secondary School (SSCE)',
    'Vocational'
];

foreach ($defaults as $index => $name) {
    $stmt = $db->prepare("INSERT INTO qualifications (name, order_index) VALUES (:name, :order)");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':order', $index + 1, SQLITE3_INTEGER);
    $stmt->execute();
}

echo "Qualifications table created and seeded successfully.";
