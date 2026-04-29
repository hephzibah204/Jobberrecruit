<?php

// In app/Helpers/category_helper.php
if (!function_exists('getCategoryIcon')) {
    function getCategoryIcon($categoryName)
    {
        $iconMap = [
            'Marketing & Sales' => 'assets/imgs/page/homepage1/marketing.svg',
            'Customer Service' => 'assets/imgs/page/homepage1/customer.svg',
            'Finance' => 'assets/imgs/page/homepage1/finance.svg',
            'Software' => 'assets/imgs/page/homepage1/lightning.svg',
            'Human Resources' => 'assets/imgs/page/homepage1/human.svg',
            'Management' => 'assets/imgs/page/homepage1/management.svg',
            'Retail' => 'assets/imgs/page/homepage1/retail.svg',
            'Security' => 'assets/imgs/page/homepage1/security.svg',
            'Content Writing' => 'assets/imgs/page/homepage1/content.svg',
            'Research' => 'assets/imgs/page/homepage1/research.svg',
            // Add more mappings as needed
        ];

        return $iconMap[$categoryName] ?? 'assets/imgs/template/favicon.png';
    }
}

if (!function_exists('applicationBadge')) {
    function applicationBadge(string $status): string
    {
        return match ($status) {
            'applied'     => 'bg-secondary',
            'reviewed'    => 'bg-info',
            'shortlisted' => 'bg-primary',
            'hired'       => 'bg-success',
            'rejected'    => 'bg-danger',
            default       => 'bg-light',
        };
    }
}

function planFeatures(array $features): array
{
    return array_map(
        fn($value) => (bool) $value,
        $features
    );
}
