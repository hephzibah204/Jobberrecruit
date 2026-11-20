<?php

/** Return a display-only copy so private salary values cannot enter public markup. */
function public_job($job)
{
    $copy = clone $job;
    if (isset($copy->show_salary) && (int) $copy->show_salary === 0) {
        foreach (['salary', 'salary_max', 'min_salary', 'max_salary'] as $field) $copy->$field = null;
        $copy->salary_details = 'Not disclosed';
        $copy->salary_range = 'Not disclosed';
    }
    return $copy;
}
