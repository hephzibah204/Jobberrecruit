<?php
function base_url($path) { return 'https://example.test/' . $path; }
function esc($value, $context = 'html') { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function csrf_field() { return '<input type="hidden" name="csrf_test_name" value="test-token">'; }
function session() { return new class { function getFlashdata($key) { return null; } }; }
$employer = (object) ['id' => 44];
$account = [
 'balance' => 12500.5, 'credits' => 10,
 'subscription' => (object) ['plan_name' => 'Employer Pro', 'ends_at' => date('Y-m-d H:i:s', strtotime('+30 days'))],
 'plans' => [(object) ['id' => 1, 'name' => 'Employer Pro', 'monthly_job_credits' => 5]],
 'bundles' => [(object) ['id' => 3, 'name' => 'Ten jobs', 'job_credits' => 10]],
 'history' => [(object) ['created_at' => '2026-10-28 12:00:00', 'description' => '<script>alert(1)</script>', 'meta' => json_encode(['admin_id' => 2, 'amount' => '12500.50'])]],
];
ob_start();
require dirname(__DIR__) . '/app/Views/admin/partials/employer_account.php';
$html = ob_get_clean();
if (substr_count($html, '<form method="post" action="https://example.test/admin/employers/account/44">') !== 4 || substr_count($html, 'value="test-token"') !== 4) throw new RuntimeException('All account actions must target this employer with a CSRF field.');
preg_match_all('/name="request_key" value="([a-f0-9]{32})"/', $html, $keys);
if (count(array_unique($keys[1])) !== 4) throw new RuntimeException('Each form must have a distinct replay protection key.');
if (str_contains($html, '<script>alert(1)</script>') || !str_contains($html, '&lt;script&gt;')) throw new RuntimeException('Audit descriptions must be escaped.');
if (!str_contains($html, '12,500.50') || !str_contains($html, 'Employer Pro')) throw new RuntimeException('Current account state is missing.');
echo "PASS: four employer forms, CSRF tokens, distinct replay keys, current balances, and escaped audit history.\n";