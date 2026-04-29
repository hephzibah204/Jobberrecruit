<?php
mysqli_report(MYSQLI_REPORT_OFF);
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$db   = 'jobber_db';

echo "Connecting to $host...\n";
$conn = mysqli_init();
$conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 2);
if (!$conn->real_connect($host, $user, $pass, $db)) {
    die("Connect Error (" . mysqli_connect_errno() . ") " . mysqli_connect_error() . "\n");
}

echo "Connected successfully\n";
$res = $conn->query("SHOW TABLES");
while ($row = $res->fetch_row()) {
    echo $row[0] . "\n";
}
$conn->close();
