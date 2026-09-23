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
        if (empty($path)) {
            $nameParam = 'Company';
            if (!empty($name)) {
                $nameParam = urlencode(trim($name));
            } else {
                $nameParam = $type === 'candidate' ? 'User' : 'Company';
            }
            
            // Generate clean branded initials avatar
            return "https://ui-avatars.com/api/?name={$nameParam}&background=0A2F57&color=fff&size=128&bold=true";
        }

        // Check if it's already an absolute HTTP/HTTPS URL (e.g. Cloudinary, S3, external)
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Normalize relative path
        $cleanPath = ltrim($path, '/');

        // If path doesn't start with uploads/ or images/ but exists in uploads/
        if (!str_starts_with($cleanPath, 'uploads/') && !str_starts_with($cleanPath, 'images/')) {
            if (file_exists(FCPATH . 'uploads/' . $cleanPath)) {
                return base_url('uploads/' . $cleanPath);
            }
        }

        return base_url($cleanPath);
    }
}
