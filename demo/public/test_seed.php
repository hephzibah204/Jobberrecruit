<?php
// Simple script to seed industries via web access
require_once __DIR__ . '/../app/Config/Services.php';

try {
    // Get the industry model
    $industryModel = new \App\Models\IndustryModel();
    
    // Check current count
    $count = $industryModel->countAllResults();
    echo "Current industries count: " . $count . "<br>";
    
    if ($count == 0) {
        // Seed some basic industries
        $industries = [
            [
                'name' => 'Information Technology',
                'slug' => 'information-technology',
                'parent_id' => null
            ],
            [
                'name' => 'Software Development',
                'slug' => 'software-development',
                'parent_id' => null // Will be set after inserting parent
            ],
            [
                'name' => 'Finance & Banking',
                'slug' => 'finance-banking',
                'parent_id' => null
            ],
            [
                'name' => 'Healthcare & Medical',
                'slug' => 'healthcare-medical',
                'parent_id' => null
            ]
        ];
        
        $inserted = [];
        foreach ($industries as $industry) {
            // Insert and get ID
            $id = $industryModel->insert($industry, true);
            $industry['id'] = $id;
            $inserted[] = $industry;
            echo "Inserted: {$industry['name']} (ID: {$id})<br>";
        }
        
        // Now update child industries with correct parent_id
        foreach ($inserted as $industry) {
            if ($industry['name'] == 'Software Development') {
                // Find parent IT industry
                $itIndustry = null;
                foreach ($inserted as $ind) {
                    if ($ind['name'] == 'Information Technology') {
                        $itIndustry = $ind;
                        break;
                    }
                }
                if ($itIndustry) {
                    $industryModel->update($industry['id'], ['parent_id' => $itIndustry['id']]);
                    echo "Updated Software Development parent to IT (ID: {$itIndustry['id']})<br>";
                }
            }
        }
        
        echo "<strong>Seeding completed!</strong><br>";
        echo "Total industries now: " . $industryModel->countAllResults();
    } else {
        echo "Industries already exist. Seeding skipped.<br>";
        echo "Showing first 5 industries:<br>";
        $industries = $industryModel->findAll(5);
        foreach ($industries as $industry) {
            echo "- {$industry->name} (slug: {$industry->slug})<br>";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>