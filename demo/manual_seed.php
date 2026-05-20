<?php
// Manual database connection to seed industries
$host = 'localhost';
$dbname = 'jobberrecruit';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check current count
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM industries");
    $count = $stmt->fetchColumn();
    echo "Current industries count: $count\n";
    
    if ($count == 0) {
        // Insert basic industries
        $industries = [
            ['name' => 'Information Technology', 'slug' => 'information-technology'],
            ['name' => 'Software Development', 'slug' => 'software-development'],
            ['name' => 'Finance & Banking', 'slug' => 'finance-banking'],
            ['name' => 'Healthcare & Medical', 'slug' => 'healthcare-medical'],
            ['name' => 'Education & Training', 'slug' => 'education-training']
        ];
        
        foreach ($industries as $industry) {
            $stmt = $pdo->prepare("INSERT INTO industries (name, slug, parent_id, is_active) VALUES (?, ?, NULL, 1)");
            $stmt->execute([$industry['name'], $industry['slug']]);
            echo "Inserted: {$industry['name']}\n";
        }
        
        echo "Seeding completed!\n";
    } else {
        echo "Industries already exist. Skipping seed.\n";
        
        // Show first 5 industries
        $stmt = $pdo->query("SELECT id, name, slug FROM industries LIMIT 5");
        $industries = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "First 5 industries:\n";
        foreach ($industries as $industry) {
            echo "- {$industry['name']} (slug: {$industry['slug']})\n";
        }
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
?>