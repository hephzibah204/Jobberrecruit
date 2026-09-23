<?php
$mysqli = new mysqli("127.0.0.1", "root", "", "jobberrecruit");

$qCount = 0;
$res = $mysqli->query("SELECT id, body, explanation FROM questions");
while ($row = $res->fetch_assoc()) {
    $id = (int)$row['id'];
    $body = $row['body'];
    $expl = $row['explanation'];

    $newBody = preg_replace('/\?([0-9])/', '₦$1', $body);
    $newExpl = preg_replace('/\?([0-9])/', '₦$1', $expl ?? '');

    if ($newBody !== $body || $newExpl !== $expl) {
        $bEsc = $mysqli->real_escape_string($newBody);
        $eEsc = $mysqli->real_escape_string($newExpl);
        $mysqli->query("UPDATE questions SET body = '{$bEsc}', explanation = '{$eEsc}' WHERE id = {$id}");
        $qCount++;
    }
}

$oCount = 0;
$res2 = $mysqli->query("SELECT id, body FROM question_options");
while ($row = $res2->fetch_assoc()) {
    $id = (int)$row['id'];
    $body = $row['body'];

    $newBody = preg_replace('/\?([0-9])/', '₦$1', $body);

    if ($newBody !== $body) {
        $bEsc = $mysqli->real_escape_string($newBody);
        $mysqli->query("UPDATE question_options SET body = '{$bEsc}' WHERE id = {$id}");
        $oCount++;
    }
}

echo "Direct query update: {$qCount} questions, {$oCount} options.\n";
