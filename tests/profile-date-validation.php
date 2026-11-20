<?php
require __DIR__ . '/../app/Helpers/profile_dates_helper.php';
$cases = [
    [[], false],
    [['dob' => '1995-02-28'], false],
    [['dob' => '1995-02-30'], true],
    [['dob' => '2999-01-01'], true],
    [['exp_job_title' => ['Engineer'], 'exp_start' => ['2020-13']], true],
    [['exp_job_title' => ['Engineer'], 'exp_start' => ['2020-04'], 'exp_end' => ['2019-04']], true],
    [['exp_job_title' => ['Engineer'], 'exp_start' => ['2020-04'], 'exp_end' => ['2021-04']], false],
    [['exp_job_title' => ['Engineer'], 'exp_start' => ['05/2020'], 'exp_end' => ['2021-04']], false],
    [['exp_job_title' => ['Engineer'], 'exp_start' => ['2020'], 'exp_end' => ['2021']], false],
    [['exp_job_title' => ['Engineer'], 'exp_start' => ['2020-5'], 'exp_end' => ['2021-1']], false],
    [['edu_start_year' => ['2020'], 'edu_end_year' => ['2019']], true],
    [['edu_start_year' => [' 2016 '], 'edu_end_year' => [' 2020 ']], false],
    [['cert_issue_year' => ['275760']], true],
    [['cert_name' => ['QA Certification'], 'cert_issue_month' => ['June'], 'cert_issue_year' => ['2025'], 'cert_no_expire' => [1]], false],
    [['cert_name' => ['QA Certification'], 'cert_issue_month' => ['6'], 'cert_issue_year' => ['2025'], 'cert_no_expire' => [1]], false],
    [['cert_name' => ['QA Certification'], 'cert_issue_month' => ['NotAMonth'], 'cert_issue_year' => ['2025']], true],
    [['cert_name' => ['QA Certification'], 'cert_issue_month' => ['June'], 'cert_issue_year' => ['2025'], 'cert_exp_month' => ['May'], 'cert_exp_year' => ['2025']], true],
];
foreach ($cases as [$input, $invalid]) {
    if ((bool) profile_date_errors($input) !== $invalid) throw new RuntimeException('Unexpected date validation result');
}
echo "PASS: valid dates, impossible dates, future birth dates, work ordering and year bounds.\n";
