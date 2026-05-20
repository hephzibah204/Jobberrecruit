<?php
// Simple script to check industries via web access
require_once __DIR__ . '/../app/Config/Services.php';

try {
    // Get the industry model
    $industryModel = new \App\Models\IndustryModel();
    
    // Check current count
    $count = $industryModel->countAllResults();
    echo "<h1>Industries Check</h1>";
    echo "<p>Current industries count: " . $count . "</p>";
    
    if ($count == 0) {
        echo "<p style='color:red;'>No industries found in database!</p>";
        echo "<p>This would cause the industry_hub route to return 404.</p>";
    } else {
        echo "<p style='color:green;'>Industries found:</p>";
        echo "<ul>";
        $industries = $industryModel->findAll(20);
        foreach ($industries as $industry) {
            echo "<li><strong>{$industry->name}</strong> (slug: {$industry->slug})</li>";
        }
        echo "</ul>";
    }
} catch (Exception $e) {
    echo "<h1>Error</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>