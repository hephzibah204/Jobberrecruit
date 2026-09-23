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

        // CRITICAL FIX FOR EXISTING JOBS: 
        // If slug starts with http:// or https://, return it directly without base_url()
        if (preg_match('#^https?://#i', $slug)) {
            return $slug;
        }

        // If external_url exists and is a valid URL
        if (!empty($extUrl) && preg_match('#^https?://#i', $extUrl)) {
            if ($method === 'external' || empty($slug)) {
                return $extUrl;
            }
        }

        // Standard internal job URL
        $identifier = !empty($slug) ? $slug : (is_array($job) ? $job['id'] : $job->id);
        return base_url('jobs/' . $identifier);
    }
}
