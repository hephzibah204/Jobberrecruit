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
            $nameParam = 'U';
            if (!empty($name)) {
                $nameParam = urlencode(trim($name));
            } else {
                $nameParam = $type === 'candidate' ? 'User' : 'Company';
            }
            
            // Generate initials using ui-avatars.com
            return "https://ui-avatars.com/api/?name={$nameParam}&background=random&color=fff&size=128";
        }

        // Check if it's already an absolute HTTP/HTTPS URL
        if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
            return $path;
        }

        return base_url($path);
    }
}
