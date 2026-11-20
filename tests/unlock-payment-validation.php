<?php
require __DIR__ . '/../app/Services/PaymentFulfillmentService.php';
use App\Services\PaymentFulfillmentService as Payments;
$meta = ['type' => 'unlock', 'user_id' => 12, 'candidate_id' => 24];
$order = ['reference' => 'unlock-test', 'user_id' => 12, 'amount' => 5000, 'currency' => 'NGN', 'metadata' => json_encode($meta)];
$data = ['reference' => 'unlock-test', 'status' => 'success', 'amount' => 500000, 'currency' => 'NGN',
    'customer' => ['email' => 'employer@example.com'], 'metadata' => ['app_data' => $meta]];
Payments::validateUnlock($data, $order, 'employer@example.com', 24);
$cases = [
    'amount' => ['amount' => 100], 'currency' => ['currency' => 'USD'],
    'reference' => ['reference' => 'another-payment'], 'status' => ['status' => 'failed'],
    'payer' => ['customer' => ['email' => 'other@example.com']],
    'owner' => ['metadata' => ['app_data' => array_replace($meta, ['user_id' => 99])]],
    'candidate' => ['metadata' => ['app_data' => array_replace($meta, ['candidate_id' => 99])]],
    'purchase' => ['metadata' => ['app_data' => array_replace($meta, ['type' => 'subscription'])]],
];
foreach ($cases as $name => $changes) {
    try {
        Payments::validateUnlock(array_replace($data, $changes), $order, 'employer@example.com', 24);
    } catch (InvalidArgumentException $e) { echo "$name rejected\n"; continue; }
    throw new RuntimeException("Invalid $name accepted");
}
try { Payments::validateUnlock($data, [], 'employer@example.com', 24); }
catch (InvalidArgumentException $e) { echo "Missing order rejected; valid payment accepted.\n"; exit(0); }
throw new RuntimeException('Missing order accepted');
