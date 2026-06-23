<?php
$db = new mysqli('127.0.0.1', 'root', 'WaitOnGod2026', 'jobberrecruit');
if ($db->connect_error) die("Connection failed: " . $db->connect_error);

$res = $db->query('SELECT id, name, plan_type, is_active FROM plans');
while ($row = $res->fetch_assoc()) {
    print_r($row);
}
