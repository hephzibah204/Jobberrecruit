<?php
// Script to import/upsert all 23 SEO-optimized categories from industry_seo_migration.sql into SQLite database
try {
    $dbPath = __DIR__ . '/writable/database.sqlite';
    if (!file_exists($dbPath)) {
        throw new Exception("SQLite database file not found at: {$dbPath}");
    }

    $db = new PDO("sqlite:{$dbPath}");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sqlFile = __DIR__ . '/industry_seo_migration.sql';
    if (!file_exists($sqlFile)) {
        throw new Exception("SEO migration SQL file not found at: {$sqlFile}");
    }

    $sql = file_get_contents($sqlFile);

    // Strip single-line SQL comments before parsing
    $sql = preg_replace('/^\s*--.*$/m', '', $sql);

    // Strip multi-line C-style comments before parsing
    $sql = preg_replace('!/\*.*?\*/!s', '', $sql);

    // Find the position of VALUES keyword
    $valuesPos = stripos($sql, 'VALUES');
    if ($valuesPos === false) {
        throw new Exception("Could not find VALUES clause in industry_seo_migration.sql");
    }

    // Take everything after 'VALUES'
    $valuesBlock = substr($sql, $valuesPos + 6);

    // Find the position of 'ON DUPLICATE KEY UPDATE'
    $onDuplicatePos = stripos($valuesBlock, 'ON DUPLICATE');
    if ($onDuplicatePos !== false) {
        $valuesBlock = substr($valuesBlock, 0, $onDuplicatePos);
    }

    // Split the VALUES block by rows. Since each row is enclosed in parentheses, let's parse them.
    // A robust way to parse PHP-style double/single quoted SQL strings inside parentheses:
    // We'll use a regex that matches individual rows: `('Name', 'Slug', NULL, 1, 'H1', 'Meta', 'Desc')`
    // Wait, the description/H1 can have escaped single quotes like `\'` or nested commas.
    // Let's use preg_match_all to capture everything inside parentheses.
    preg_match_all('/\((.*?)\)(?:,|\s*$)/is', $valuesBlock, $rowMatches);

    if (empty($rowMatches[1])) {
        throw new Exception("No rows found in the VALUES block of industry_seo_migration.sql");
    }

    $stmt = $db->prepare("
        INSERT INTO industries (name, slug, parent_id, is_active, seo_h1, meta_description, description)
        VALUES (:name, :slug, :parent_id, :is_active, :seo_h1, :meta_description, :description)
        ON CONFLICT(slug) DO UPDATE SET
            name = excluded.name,
            parent_id = excluded.parent_id,
            is_active = excluded.is_active,
            seo_h1 = excluded.seo_h1,
            meta_description = excluded.meta_description,
            description = excluded.description
    ");

    $count = 0;
    foreach ($rowMatches[1] as $index => $rowText) {
        // Let's parse the fields of the row using a custom parser that respects SQL strings and escape sequences
        $fields = [];
        $currentField = '';
        $inString = false;
        $stringChar = '';
        $escaped = false;

        for ($i = 0; $i < strlen($rowText); $i++) {
            $char = $rowText[$i];

            if ($escaped) {
                $currentField .= $char;
                $escaped = false;
                continue;
            }

            if ($char === '\\') {
                $escaped = true;
                $currentField .= $char; // Keep it so we can strip or process it later, or strip it now
                continue;
            }

            if ($inString) {
                if ($char === $stringChar) {
                    $inString = false;
                } else {
                    $currentField .= $char;
                }
            } else {
                if ($char === "'" || $char === '"') {
                    $inString = true;
                    $stringChar = $char;
                } elseif ($char === ',') {
                    $fields[] = trim($currentField);
                    $currentField = '';
                } else {
                    $currentField .= $char;
                }
            }
        }
        $fields[] = trim($currentField);

        // Map and clean up fields
        // Field 0: Name (string)
        // Field 1: Slug (string)
        // Field 2: Parent ID (NULL or int)
        // Field 3: Is Active (int)
        // Field 4: SEO H1 (string)
        // Field 5: Meta Description (string)
        // Field 6: Description (string)
        
        if (count($fields) < 7) {
            echo "Warning: Row " . ($index + 1) . " has insufficient fields, skipping: " . substr($rowText, 0, 50) . "...\n";
            continue;
        }

        $name = str_replace(["\\'", '\\"'], ["'", '"'], $fields[0]);
        $slug = str_replace(["\\'", '\\"'], ["'", '"'], $fields[1]);
        $parent_id = ($fields[2] === 'NULL' || $fields[2] === 'null' || empty($fields[2])) ? null : (int)$fields[2];
        $is_active = (int)$fields[3];
        $seo_h1 = str_replace(["\\'", '\\"'], ["'", '"'], $fields[4]);
        $meta_description = str_replace(["\\'", '\\"'], ["'", '"'], $fields[5]);
        $description = str_replace(["\\'", '\\"'], ["'", '"'], $fields[6]);

        $stmt->execute([
            ':name' => $name,
            ':slug' => $slug,
            ':parent_id' => $parent_id,
            ':is_active' => $is_active,
            ':seo_h1' => $seo_h1,
            ':meta_description' => $meta_description,
            ':description' => $description
        ]);
        $count++;
    }

    echo "Successfully imported/upserted {$count} categories into industries table in SQLite.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
