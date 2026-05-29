<?php
echo "START\n";
$db = new PDO('sqlite:C:\Users\Abiodun Emmanuel\Documents\CODEBASE\Jobberrecruit\demo\writable\database.sqlite');
echo "DB OK\n";
$i = $db->query("SELECT * FROM auth_identities WHERE type='email_password'")->fetch(PDO::FETCH_ASSOC);
if ($i) {
    echo "Identity OK: user_id={$i['user_id']} email={$i['secret']}\n";
    echo "Hash length: " . strlen($i['secret2']) . "\n";
} else {
    echo "No identity found\n";
}
echo "DONE\n";
