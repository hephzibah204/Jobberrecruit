<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

if (!function_exists('resolve_image_url')) {
    /**
     * Resolves an image URL safely.
     * If the path is an absolute URL (e.g., Cloudinary, CDN), returns it directly.
     * Otherwise, wraps it in base_url() for local files, or returns a branded initials avatar.
     *
     * @param string|null $path
     * @param string $type
     * @param string $name
     * @return string
     */
    function resolve_image_url(?string $path, string $type = 'company', string $name = ''): string
    {
        $nameParam = 'Company';
        if (!empty($name)) {
            $nameParam = urlencode(trim($name));
        } else {
            $nameParam = $type === 'candidate' ? 'User' : 'Company';
        }

        $fallback = "https://ui-avatars.com/api/?name={$nameParam}&background=0A2F57&color=fff&size=128&bold=true";

        if (empty($path)) {
            return $fallback;
        }

        // Check if it's already an absolute HTTP/HTTPS URL (e.g. Cloudinary, S3, external)
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Normalize relative path
        $cleanPath = ltrim($path, '/\\');

        // Check if file exists directly
        if (file_exists(FCPATH . $cleanPath)) {
            return base_url($cleanPath);
        }

        // If path doesn't start with uploads/ but exists in uploads/
        if (!str_starts_with($cleanPath, 'uploads/')) {
            if (file_exists(FCPATH . 'uploads/' . $cleanPath)) {
                return base_url('uploads/' . $cleanPath);
            }
        }

        // If path doesn't start with images/ but exists in images/
        if (!str_starts_with($cleanPath, 'images/')) {
            if (file_exists(FCPATH . 'images/' . $cleanPath)) {
                return base_url('images/' . $cleanPath);
            }
        }

        // File does not exist on disk — fallback to clean branded initials avatar
        return $fallback;
    }
}

if (!function_exists('get_site_setting')) {
    /**
     * Get a site setting from database (CodeIgniter Settings), with fallback to env() or default.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function get_site_setting(string $key, $default = null)
    {
        if (function_exists('setting')) {
            $val = setting('Site.' . $key);
            if ($val === null) {
                $val = setting('App.' . $key);
            }
            if ($val !== null) {
                if ($val === 'true' || $val === '1' || $val === 1 || $val === true) {
                    return true;
                }
                if ($val === 'false' || $val === '0' || $val === 0 || $val === false) {
                    return false;
                }
                return $val;
            }
        }

        // Fallback to env()
        $envVal = env($key, null);
        if ($envVal !== null) {
            if ($envVal === 'true' || $envVal === '1' || $envVal === 1 || $envVal === true) {
                return true;
            }
            if ($envVal === 'false' || $envVal === '0' || $envVal === 0 || $envVal === false) {
                return false;
            }
            return $envVal;
        }

        return $default;
    }
}

if (!function_exists('set_site_setting')) {
    /**
     * Save a site setting to database (CodeIgniter Settings).
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    function set_site_setting(string $key, $value): void
    {
        if (function_exists('setting')) {
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }
            setting('Site.' . $key, (string)$value);
        }
    }
}

if (!function_exists('is_site_free_mode')) {
    /**
     * Check whether the site is in Free Access Mode (reads from Database Configuration).
     *
     * @return bool
     */
    function is_site_free_mode(): bool
    {
        return (bool) get_site_setting('site_free_mode', false);
    }
}

if (!function_exists('is_ai_tools_paid_mode')) {
    /**
     * Check whether AI tools require paid subscription (reads from Database Configuration).
     *
     * @return bool
     */
    function is_ai_tools_paid_mode(): bool
    {
        return (bool) get_site_setting('ai_tools_paid_mode', false);
    }
}
