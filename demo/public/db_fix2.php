<?php
// Standalone Database Fix Script
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';

$db = \Config\Database::connect();
echo "<pre>";
echo "<h2>Database Fixer</h2>\n";

try {
    // Drop the problematic foreign key
    echo "Dropping fk_conversation_employer constraint...\n";
    $db->query("ALTER TABLE `conversations` DROP FOREIGN KEY `fk_conversation_employer`");
    echo "Success!\n\n";

    // Recreate it with exact data type match
    echo "Recreating fk_conversation_employer constraint...\n";
    $db->query("ALTER TABLE `conversations` ADD CONSTRAINT `fk_conversation_employer` FOREIGN KEY (`employer_id`) REFERENCES `employers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE");
    echo "Success! The constraint has been repaired.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "\nIf it says it cannot add the foreign key, it means the column data types in 'conversations' and 'employers' do not match (e.g., one is UNSIGNED and the other is not).\n";
    
    // Attempt to force fix the column data types
    try {
        echo "\nAttempting to synchronize column data types...\n";
        $db->query("ALTER TABLE `employers` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT");
        $db->query("ALTER TABLE `conversations` MODIFY `employer_id` INT(11) UNSIGNED NOT NULL");
        echo "Data types synchronized! Trying to add constraint again...\n";
        $db->query("ALTER TABLE `conversations` ADD CONSTRAINT `fk_conversation_employer` FOREIGN KEY (`employer_id`) REFERENCES `employers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE");
        echo "Success! Constraint recreated.\n";
    } catch (\Exception $e2) {
        echo "Error fixing constraint: " . $e2->getMessage() . "\n";
    }
}

echo "</pre>";
