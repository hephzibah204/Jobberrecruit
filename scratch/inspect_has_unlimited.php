<?php
$content = file_get_contents('app/Services/CreditService.php');
$pos = strpos($content, 'function hasUnlimitedAccess');
echo substr($content, $pos, 800);
