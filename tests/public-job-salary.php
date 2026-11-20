<?php
require __DIR__ . '/../app/Helpers/public_job_helper.php';
$private = (object) ['show_salary' => 0, 'salary' => '250000', 'salary_max' => '400000', 'salary_details' => '250000 - 400000', 'salary_range' => '250000 - 400000'];
$display = public_job($private);
if ($display->salary !== null || $display->salary_max !== null || $display->salary_range !== 'Not disclosed') throw new RuntimeException('Private salary leaked');
if ($private->salary !== '250000') throw new RuntimeException('Original salary changed');
$visible = clone $private;
$visible->show_salary = 1;
if (public_job($visible)->salary !== '250000') throw new RuntimeException('Visible salary hidden');
$legacy = clone $visible;
unset($legacy->show_salary);
if (public_job($legacy)->salary !== '250000') throw new RuntimeException('Legacy default changed');
echo "PASS: hidden, visible, and legacy salaries; original values preserved.\n";
