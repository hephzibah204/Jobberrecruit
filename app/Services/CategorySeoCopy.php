<?php

namespace App\Services;

/** Approved category headings and copy from Category_SEO_Copy.docx. */
final class CategorySeoCopy
{
    private const ALIASES = [
        'finance-accounting' => 'accounting-finance-banking',
        'finance-banking' => 'accounting-finance-banking',
        'banking-finance' => 'accounting-finance-banking',
        'accounting-finance' => 'accounting-finance-banking',
        'administration' => 'administration-office-support',
        'agriculture-farming' => 'agriculture-agribusiness',
        'customer-service' => 'customer-service-call-center',
        'oil-gas' => 'energy-oil-gas',
        'engineering' => 'engineering-technical',
        'graduate-trainee' => 'graduate-trainee-nysc-internships',
        'healthcare' => 'healthcare-pharmaceuticals',
        'healthcare-medical' => 'healthcare-pharmaceuticals',
        'healthcare-medical-pharmaceuticals' => 'healthcare-pharmaceuticals',
        'hospitality-tourism' => 'hospitality-travel-tourism',
        'human-resources' => 'human-resources-recruitment',
        'information-technology' => 'information-technology-software',
        'information-technology-it-software' => 'information-technology-software',
        'it-software' => 'information-technology-software',
        'legal' => 'legal-compliance',
        'logistics-transportation' => 'logistics-supply-chain-procurement',
        'marketing-sales' => 'marketing-advertising-communications',
        'media-communications' => 'media-creative-design',
        'design-creative' => 'media-creative-design',
        'ngo-charity' => 'ngo-non-profit',
        'sales-marketing' => 'sales-business-development',
    ];

    public static function find(string $slug, string $name = ''): ?array
    {
        static $entries;
        $entries ??= json_decode(file_get_contents(APPPATH . 'Config/category_seo.json'), true, 512, JSON_THROW_ON_ERROR);

        foreach ([$slug, $name] as $candidate) {
            $key = strtolower(trim($candidate));
            $key = trim(preg_replace('/[^a-z0-9]+/', '-', $key), '-');
            $key = preg_replace('/-jobs$/', '', $key);
            $key = self::ALIASES[$key] ?? $key;
            if (isset($entries[$key])) {
                $entry = $entries[$key];
                // Use a complete, category-specific sentence for search previews.
                $entry['meta_description'] = preg_split('/(?<=\.)\s+/', $entry['body'], 2)[0];
                return $entry;
            }
        }

        return null;
    }
}
