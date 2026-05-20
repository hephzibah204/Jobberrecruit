<?php
// Fix industries table by inserting basic records
require_once __DIR__ . '/app/Config/Services.php';

use CodeIgniter\Database\ConnectionInterface;

// Get database connection
$db = \Config\Database::connect();

// Check if industries table has data
$count = $db->table('industries')->countAllResults();
echo "Current industries count: " . $count . "\n";

if ($count == 0) {
    // Insert basic industry records
    $industries = [
        ['name' => 'Information Technology', 'slug' => 'information-technology'],
        ['name' => 'Software Development', 'slug' => 'software-development'],
        ['name' => 'Finance & Banking', 'slug' => 'finance-banking'],
        ['name' => 'Healthcare & Medical', 'slug' => 'healthcare-medical'],
        ['name' => 'Education & Training', 'slug' => 'education-training'],
        ['name' => 'Manufacturing & Engineering', 'slug' => 'manufacturing-engineering'],
        ['name' => 'Retail & E-Commerce', 'slug' => 'retail-ecommerce'],
        ['name' => 'Hospitality & Tourism', 'slug' => 'hospitality-tourism'],
        ['name' => 'Construction & Real Estate', 'slug' => 'construction-real-estate'],
        ['name' => 'Media & Communications', 'slug' => 'media-communications']
    ];

    foreach ($industries as $industry) {
        $db->table('industries')->insert($industry);
        echo "Inserted: {$industry['name']}\n";
    }

    echo "Successfully added " . count($industries) . " industries.\n";
} else {
    echo "Industries already exist. Skipping insertion.\n";
    
    // Show first 5 industries
    $result = $db->table('industries')->select('id, name, slug')->limit(5)->get();
    foreach ($result->getResult() as $industry) {
        echo "- {$industry->name} (slug: {$industry->slug})\n";
    }
}
?>