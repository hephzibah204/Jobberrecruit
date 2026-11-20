<?php
// Exercise the actual repost persistence block without charging an account.
$source = file_get_contents(__DIR__ . '/../app/Controllers/EmployerController.php');
$method = substr($source, strpos($source, 'public function processRepostJob('));
$start = strpos($method, '            if (!$jobModel->update(');
$end = strpos($method, "            return \$this->response->setJSON([", $start);
if ($start === false || $end === false) {
    throw new RuntimeException('Repost persistence block not found');
}
$block = substr($method, $start, $end - $start);
function log_message($level, $message, $context = []) {}
$save = eval('return function ($jobModel, $db) { $jobId = 12; $updateData = ["status" => "open"]; $employer = (object) ["id" => 7]; ' . $block . ' };');
foreach ([[true, true, true], [false, true, true], [true, false, true], [true, true, false]] as [$update, $status, $commit]) {
    $model = new class($update) {
        public function __construct(public bool $result) {}
        public function update($id, $data) { return $this->result; }
    };
    $db = new class($status, $commit) {
        public bool $committed = false;
        public function __construct(public bool $status, public bool $result) {}
        public function transStatus() { return $this->status; }
        public function transCommit() { $this->committed = true; return $this->result; }
    };
    $failed = false;
    try { $save($model, $db); } catch (RuntimeException $e) { $failed = true; }
    if ($failed !== !($update && $status && $commit)) {
        throw new RuntimeException('Incorrect repost save result');
    }
    if ((!$update || !$status) && $db->committed) {
        throw new RuntimeException('Failed save must not commit a charge');
    }
}
echo "PASS: repost saves successfully and rejects failed writes, transactions, and commits.\n";
