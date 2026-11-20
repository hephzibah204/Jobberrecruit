<?php

if (!function_exists('format_job_url')) {
    /**
     * Formats job URLs cleanly for internal vs external jobs (including existing DB records).
     */
    function format_job_url($job): string
    {
        $slug   = trim((string)(is_array($job) ? ($job['slug'] ?? '') : ($job->slug ?? '')));
        $extUrl = trim((string)(is_array($job) ? ($job['external_url'] ?? $job['external_link'] ?? '') : ($job->external_url ?? $job->external_link ?? '')));
        $method = is_array($job) ? ($job['application_method'] ?? 'form') : ($job->application_method ?? 'form');

        $identifier = !empty($slug) ? $slug : (is_array($job) ? ($job['id'] ?? '') : ($job->id ?? ''));

        // If the slug happens to be a full URL (legacy corrupted slug), fall back to ID
        if (preg_match('#^https?://#i', $slug)) {
            $identifier = is_array($job) ? ($job['id'] ?? '') : ($job->id ?? '');
        }

        // Always route to internal job details page first
        return base_url('jobs/' . $identifier);
    }
}
