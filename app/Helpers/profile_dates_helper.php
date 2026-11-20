<?php

/** Normalize flexible month date input into YYYY-MM */
function normalize_profile_month($v): string
{
    if (!is_scalar($v)) return '';
    $v = trim((string) $v);
    if ($v === '') return '';
    if (preg_match('/^(\d{4})-(\d{1,2})$/', $v, $m)) {
        return sprintf('%04d-%02d', (int) $m[1], (int) $m[2]);
    }
    if (preg_match('/^(\d{1,2})[\/\-](\d{4})$/', $v, $m)) {
        return sprintf('%04d-%02d', (int) $m[2], (int) $m[1]);
    }
    if (preg_match('/^(\d{4})[\/\-](\d{1,2})$/', $v, $m)) {
        return sprintf('%04d-%02d', (int) $m[1], (int) $m[2]);
    }
    if (preg_match('/^(\d{4})$/', $v, $m)) {
        return sprintf('%04d-01', (int) $m[1]);
    }
    $ts = strtotime($v);
    if ($ts !== false) {
        return date('Y-m', $ts);
    }
    return $v;
}

/** Validate profile dates before any existing profile rows are replaced. */
function profile_date_errors(array $data): array
{
    $errors = [];
    $year = (int) date('Y');
    $month = static function ($value) use ($year): bool {
        if (!is_string($value) || !preg_match('/^\d{4}-\d{2}$/D', $value)) return false;
        $y = (int) substr($value, 0, 4);
        $m = (int) substr($value, 5, 2);
        return $y >= 1920 && $y <= ($year + 1) && $m >= 1 && $m <= 12 && checkdate($m, 1, $y);
    };

    $dob = trim((string) ($data['dob'] ?? ''));
    if ($dob !== '') {
        $valid = preg_match('/^\d{4}-\d{2}-\d{2}$/D', $dob)
            && checkdate((int) substr($dob, 5, 2), (int) substr($dob, 8, 2), (int) substr($dob, 0, 4))
            && $dob <= date('Y-m-d') && (int) substr($dob, 0, 4) >= $year - 120;
        if (!$valid) $errors['dob'] = 'Choose a valid date of birth that is not in the future.';
    }

    foreach ((array) ($data['exp_job_title'] ?? []) as $i => $title) {
        if (trim((string) $title) === '') continue;
        $rawStart = $data['exp_start'][$i] ?? '';
        $rawEnd   = !empty($data['exp_is_current'][$i]) ? '' : ($data['exp_end'][$i] ?? '');
        $start    = normalize_profile_month($rawStart);
        $end      = normalize_profile_month($rawEnd);

        if ($rawStart !== '' && ($start === '' || !$month($start) || $start > date('Y-m'))) {
            $errors['exp_start[]'] = 'Work dates must be valid, not in the future, and end on or after the start date.';
        }
        if ($rawEnd !== '' && empty($data['exp_is_current'][$i]) && ($end === '' || !$month($end) || $end > date('Y-m'))) {
            $errors['exp_start[]'] = 'Work dates must be valid, not in the future, and end on or after the start date.';
        }
        if ($start !== '' && $end !== '' && empty($data['exp_is_current'][$i]) && $end < $start) {
            $errors['exp_start[]'] = 'Work dates must be valid, not in the future, and end on or after the start date.';
        }
    }

    foreach (['edu_start_year' => 0, 'edu_end_year' => 15, 'cert_issue_year' => 0, 'cert_exp_year' => 15] as $field => $future) {
        foreach ((array) ($data[$field] ?? []) as $i => $value) {
            if ($field === 'cert_exp_year' && !empty($data['cert_no_expire'][$i])) continue;
            $v = trim((string) $value);
            if ($v !== '' && (!preg_match('/^\d{4}$/D', $v) || (int) $v < 1920 || (int) $v > $year + $future)) {
                $errors[$field . '[]'] = 'Choose a valid year.';
            }
        }
    }

    foreach ((array) ($data['edu_start_year'] ?? []) as $i => $start) {
        $startVal = trim((string) $start);
        $endVal   = trim((string) ($data['edu_end_year'][$i] ?? ''));
        if ($startVal !== '' && $endVal !== '' && preg_match('/^\d{4}$/', $startVal) && preg_match('/^\d{4}$/', $endVal) && (int) $endVal < (int) $startVal) {
            $errors['edu_end_year[]'] = 'Education end year must be on or after start year.';
        }
    }

    foreach ((array) ($data['cert_name'] ?? []) as $i => $name) {
        if (trim((string) $name) === '') continue;
        $dates = [];
        foreach (['cert_issue', 'cert_exp'] as $prefix) {
            if ($prefix === 'cert_exp' && !empty($data['cert_no_expire'][$i])) continue;
            $m = trim((string) ($data[$prefix . '_month'][$i] ?? ''));
            $y = trim((string) ($data[$prefix . '_year'][$i] ?? ''));
            if (($m === '') !== ($y === '')) $errors[$prefix . '_year[]'] = 'Choose both month and year.';
            // The profile form and previously issued credentials use month names.
            $months = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'];
            $namedMonth = array_search(strtolower($m), $months, true);
            if ($namedMonth !== false) $m = (string) ($namedMonth + 1);
            if ($m !== '' && !in_array($m, array_map('strval', range(1, 12)), true)) $errors[$prefix . '_month[]'] = 'Choose a valid month.';
            if ($m !== '' && $y !== '' && preg_match('/^\d{4}$/', $y)) $dates[$prefix] = sprintf('%04d-%02d', (int) $y, (int) $m);
        }
        if (isset($dates['cert_issue']) && $dates['cert_issue'] > date('Y-m')) $errors['cert_issue_year[]'] = 'Issue date cannot be in the future.';
        if (isset($dates['cert_issue'], $dates['cert_exp']) && $dates['cert_exp'] < $dates['cert_issue']) $errors['cert_exp_year[]'] = 'Expiry must be on or after the issue date.';
    }

    return $errors;
}
