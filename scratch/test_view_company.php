<?php
// Let's test the slug resolution in viewCompany
$identifier = 'jobber-recruit-ltd';
$slugTarget = strtolower(trim((string)$identifier));
$slugClean = str_replace('-', ' ', $slugTarget);
echo "slugTarget: $slugTarget\n";
echo "slugClean: $slugClean\n";

// Let's check company_profile.php view for any errors or missing variables
$viewContent = file_get_contents('app/Views/company_profile.php');
echo "company_profile.php length: " . strlen($viewContent) . " bytes\n";
