<?php

if (!function_exists('resolve_image_url')) {
    /**
     * Resolves an image URL safely.
     * If the path is an absolute URL (e.g., Cloudinary), returns it directly.
     * Otherwise, wraps it in base_url() for local files.
     *
     * @param string|null $path
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
