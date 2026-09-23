<?php
$mysqli = new mysqli("127.0.0.1", "root", "", "jobberrecruit");

function exportTableSql($mysqli, $tableName) {
    $res = $mysqli->query("SHOW CREATE TABLE `{$tableName}`");
    if (!$res) return "";
    $row = $res->fetch_row();
    $createSql = $row[1] . ";\n\n";

    $dataRes = $mysqli->query("SELECT * FROM `{$tableName}`");
    if (!$dataRes || $dataRes->num_rows === 0) return $createSql;

    $fields = [];
    while ($f = $dataRes->fetch_field()) {
        $fields[] = "`" . $f->name . "`";
    }

    $inserts = [];
    while ($row = $dataRes->fetch_assoc()) {
        $vals = [];
        foreach ($row as $val) {
            if ($val === null) {
                $vals[] = "NULL";
            } else {
                $vals[] = "'" . $mysqli->real_escape_string($val) . "'";
            }
        }
        $inserts[] = "(" . implode(", ", $vals) . ")";
    }

    $insertSql = "INSERT IGNORE INTO `{$tableName}` (" . implode(", ", $fields) . ") VALUES\n" . implode(",\n", $inserts) . ";\n\n";
    return $createSql . $insertSql;
}

$tables = ['tests', 'questions', 'question_options', 'test_attempts', 'attempt_answers', 'candidate_test_results'];

$fullSql = "-- ========================================================\n";
$fullSql .= "-- JOBBERRECRUIT APTITUDE TESTS DB EXPORT FOR CPANEL (phpMyAdmin)\n";
$fullSql .= "-- ========================================================\n\n";
$fullSql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

foreach ($tables as $t) {
    $fullSql .= "-- --------------------------------------------------------\n";
    $fullSql .= "-- Table structure & data for `{$t}`\n";
    $fullSql .= "-- --------------------------------------------------------\n";
    $fullSql .= exportTableSql($mysqli, $t);
}

$fullSql .= "SET FOREIGN_KEY_CHECKS = 1;\n";

file_put_contents('scratch/aptitude_export.sql', $fullSql);
echo "SQL generated successfully: " . strlen($fullSql) . " bytes\n";
