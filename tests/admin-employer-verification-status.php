<?php
// Render the actual verification badge branch with the document model's array format.
$source = file_get_contents(dirname(__DIR__) . '/app/Views/admin/employer.php');
$start = strpos($source, '<?php if ($employer->is_verified): ?>', strpos($source, '>Verification Status<'));
$end = strpos($source, '<?php endif; ?>', $start) + strlen('<?php endif; ?>');
$template = substr($source, $start, $end - $start);
set_error_handler(static function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});
try {
    foreach ([
        [false, ['status' => 'pending'], 'Pending Verification'],
        [false, ['status' => 'rejected'], 'Verification Failed'],
        [false, ['status' => 'approved'], 'Not Verified'],
        [false, null, 'Not Verified'],
        [false, [], 'Not Verified'],
        [true, ['status' => 'pending'], 'Verified Company'],
        [true, null, 'Verified Company'],
    ] as [$verified, $cacDocument, $expected]) {
        $employer = (object) ['is_verified' => $verified];
        ob_start();
        try {
            eval('?>' . $template);
            $html = ob_get_contents();
        } finally {
            ob_end_clean();
        }
        if (!str_contains($html, $expected)) {
            throw new RuntimeException('Incorrect verification status: expected ' . $expected);
        }
    }
} finally {
    restore_error_handler();
}
echo "PASS: employer verification panel renders array documents, missing documents, and verified employers without warnings.\n";
