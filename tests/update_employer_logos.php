<?php
define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
chdir(FCPATH);
require 'app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';
CodeIgniter\Boot::bootSpark($paths);
$db = \Config\Database::connect();

$logos = [
    'Moniepoint Inc.' => 'https://www.google.com/s2/favicons?domain=moniepoint.com&sz=128',
    'Flutterwave' => 'https://www.google.com/s2/favicons?domain=flutterwave.com&sz=128',
    'Interswitch Group' => 'https://www.google.com/s2/favicons?domain=interswitchgroup.com&sz=128',
    'OPay Digital Services' => 'https://www.google.com/s2/favicons?domain=opaycheckout.com&sz=128',
    'Dangote Group' => 'https://www.google.com/s2/favicons?domain=dangote.com&sz=128',
    'MTN Nigeria' => 'https://www.google.com/s2/favicons?domain=mtn.ng&sz=128',
    'Access Bank Plc' => 'https://www.google.com/s2/favicons?domain=accessbankplc.com&sz=128',
    'TotalEnergies EP Nigeria' => 'https://www.google.com/s2/favicons?domain=totalenergies.com&sz=128',
    'Nigeria LNG Limited (NLNG)' => 'https://www.google.com/s2/favicons?domain=nlng.com&sz=128',
    'Deloitte Nigeria' => 'https://www.google.com/s2/favicons?domain=deloitte.com&sz=128',
    'KPMG Nigeria' => 'https://www.google.com/s2/favicons?domain=kpmg.com&sz=128',
    'First City Monument Bank (FCMB)' => 'https://www.google.com/s2/favicons?domain=fcmb.com&sz=128',
    'M-KOPA Nigeria' => 'https://www.google.com/s2/favicons?domain=m-kopa.com&sz=128',
    'Smartflow Technologies' => 'https://www.google.com/s2/favicons?domain=smartflowtech.com&sz=128',
    'Eden Solutions & Resources' => 'https://www.google.com/s2/favicons?domain=edensr.com&sz=128',
    'Paystack' => 'https://www.google.com/s2/favicons?domain=paystack.com&sz=128',
    'Piggyvest' => 'https://www.google.com/s2/favicons?domain=piggyvest.com&sz=128',
    'Kuda Technologies' => 'https://www.google.com/s2/favicons?domain=kuda.com&sz=128',
    'International Rescue Committee (IRC)' => 'https://www.google.com/s2/favicons?domain=rescue.org&sz=128',
    'Nestlé Nigeria Plc' => 'https://www.google.com/s2/favicons?domain=nestle.com&sz=128',
    'Jobber Recruit LTD' => 'images/favicon.png',
    'Nexus Innovations International Plc' => 'images/favicon.png',
];

foreach ($logos as $companyName => $logoUrl) {
    $db->table('employers')->where('company_name', $companyName)->update([
        'logo' => $logoUrl,
        'is_verified' => 1,
    ]);
    echo "Updated {$companyName} with logo: {$logoUrl}\n";
}
