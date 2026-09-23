<?php
$content = file_get_contents('app/Views/layouts/employer.php');
$icons = ['i-eye', 'i-edit', 'i-external-link', 'i-refresh', 'i-pause', 'i-trash', 'i-check-circle', 'i-x-circle', 'i-clock'];
foreach ($icons as $ic) {
    echo "$ic exists: " . (strpos($content, 'id="' . $ic . '"') !== false ? "YES" : "NO") . "\n";
}
