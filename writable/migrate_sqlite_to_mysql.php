<?php
/**
 * Database Migration Tool: SQLite to MySQL
 * Copies all tables and data from demo/writable/database.sqlite to MySQL jobberrecruit database.
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

$sqlitePath = __DIR__ . '/database.sqlite';
$mysqlHost = '127.0.0.1';
$mysqlUser = 'root';
$mysqlPass = 'WaitOnGod2026';
$mysqlDb   = 'jobberrecruit';

if (!file_exists($sqlitePath)) {
    die("Error: SQLite database not found at $sqlitePath\n");
}

echo "Opening SQLite database at $sqlitePath...\n";
try {
    $sqlite = new PDO('sqlite:' . $sqlitePath);
    $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("SQLite connection failed: " . $e->getMessage() . "\n");
}

echo "Connecting to MySQL database $mysqlDb on $mysqlHost...\n";
$mysql = new mysqli($mysqlHost, $mysqlUser, $mysqlPass, $mysqlDb);
if ($mysql->connect_error) {
    die("MySQL connection failed: " . $mysql->connect_error . "\n");
}

// Disable foreign key constraints to allow truncating and inserting in any order
echo "Disabling MySQL foreign key checks...\n";
$mysql->query("SET FOREIGN_KEY_CHECKS = 0;");

// Get all tables from SQLite
$tablesRes = $sqlite->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
$tables = [];
while ($row = $tablesRes->fetch(PDO::FETCH_ASSOC)) {
    $tables[] = $row['name'];
}

echo "Found " . count($tables) . " tables in SQLite.\n";

foreach ($tables as $table) {
    // Skip the CI4 migrations table to avoid messing up migration execution logs
    if ($table === 'migrations') {
        echo "Skipping table: $table (handled by CodeIgniter migration runner)\n";
        continue;
    }

    echo "Migrating table: $table... ";

    // Check if table exists in MySQL
    $checkRes = $mysql->query("SHOW TABLES LIKE '{$table}'");
    if ($checkRes->num_rows === 0) {
        echo "does not exist in MySQL. Skipping.\n";
        continue;
    }

    // Truncate the table in MySQL to ensure clean copy
    if (!$mysql->query("TRUNCATE TABLE `{$table}`")) {
        echo "Failed to truncate table in MySQL: " . $mysql->error . "\n";
        continue;
    }

    // Fetch all data from SQLite table
    $sqliteStmt = $sqlite->query("SELECT * FROM `{$table}`");
    $rows = $sqliteStmt->fetchAll(PDO::FETCH_ASSOC);
    $rowCount = count($rows);

    if ($rowCount === 0) {
        echo "0 rows copied (empty table).\n";
        continue;
    }

    // Prepare insert statement
    $columns = array_keys($rows[0]);
    $escapedColumns = array_map(function($col) {
        return "`{$col}`";
    }, $columns);
    
    $placeholders = array_fill(0, count($columns), '?');
    $insertQuery = "INSERT INTO `{$table}` (" . implode(', ', $escapedColumns) . ") VALUES (" . implode(', ', $placeholders) . ")";

    $mysqlStmt = $mysql->prepare($insertQuery);
    if (!$mysqlStmt) {
        echo "Failed to prepare insert statement in MySQL: " . $mysql->error . "\n";
        continue;
    }

    // Bind parameters dynamically
    // In mysqli, bind_param needs references to values
    $types = str_repeat('s', count($columns)); // Bind everything as string (MySQL handles conversions)
    
    $successCount = 0;
    foreach ($rows as $row) {
        $values = array_values($row);
        
        // Bind parameters
        $bindParams = [&$types];
        for ($i = 0; $i < count($values); $i++) {
            $bindParams[] = &$values[$i];
        }
        
        call_user_func_array([$mysqlStmt, 'bind_param'], $bindParams);
        
        if ($mysqlStmt->execute()) {
            $successCount++;
        } else {
            echo "\nError executing insert in row for table $table: " . $mysqlStmt->error . "\n";
        }
    }

    $mysqlStmt->close();
    echo "$successCount/$rowCount rows successfully migrated.\n";
}

// Re-enable foreign key constraints
echo "Re-enabling MySQL foreign key checks...\n";
$mysql->query("SET FOREIGN_KEY_CHECKS = 1;");

$mysql->close();
echo "Data migration completed successfully!\n";
?>
