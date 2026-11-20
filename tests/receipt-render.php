<?php
// Standalone receipt rendering checks; no database or payment mutations.
function esc($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function base_url($path = '') { return 'http://localhost/' . $path; }
foreach (['completed', 'pending', 'failed'] as $status) {
    $receipt = ['reference' => '<unsafe>', 'status' => $status, 'amount' => 1500];
    ob_start();
    require __DIR__ . '/../app/Views/receipt.php';
    $html = ob_get_clean();
    if (!str_contains($html, '&lt;unsafe&gt;') || str_contains($html, 'Ref: <unsafe>')) {
        throw new RuntimeException('Receipt reference was not escaped');
    }
    $paid = $status === 'completed';
    if (str_contains($html, '<span>Total Paid</span>') !== $paid
        || str_contains($html, 'not proof of completed payment') === $paid) {
        throw new RuntimeException('Incorrect payment status wording: ' . $status);
    }
    if (!str_contains($html, 'window.print()')) throw new RuntimeException('Missing print action');
    echo $status . ": rendering and status checks passed\n";
}
