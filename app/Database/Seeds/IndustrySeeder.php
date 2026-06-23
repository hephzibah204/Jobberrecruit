<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class IndustrySeeder extends Seeder
{
    public function run()
    {
        $industryModel = model(\App\Models\IndustryModel::class);

        $industries = [
            [
                'name' => 'Information Technology',
                'slug' => 'information-technology',
                'children' => [
                    ['name' => 'Software Development', 'slug' => 'software-development'],
                    ['name' => 'Cybersecurity', 'slug' => 'cybersecurity'],
                    ['name' => 'Data Science', 'slug' => 'data-science'],
                    ['name' => 'Cloud Computing', 'slug' => 'cloud-computing'],
                    ['name' => 'Artificial Intelligence', 'slug' => 'artificial-intelligence'],
                    ['name' => 'Web Development', 'slug' => 'web-development'],
                    ['name' => 'Mobile Development', 'slug' => 'mobile-development'],
                ]
            ],
            [
                'name' => 'Finance & Banking',
                'slug' => 'finance-banking',
                'children' => [
                    ['name' => 'Accounting', 'slug' => 'accounting'],
                    ['name' => 'Investment Banking', 'slug' => 'investment-banking'],
                    ['name' => 'Insurance', 'slug' => 'insurance'],
                    ['name' => 'Financial Planning', 'slug' => 'financial-planning'],
                    ['name' => 'FinTech', 'slug' => 'fintech'],
                ]
            ],
            [
                'name' => 'Healthcare & Medical',
                'slug' => 'healthcare-medical',
                'children' => [
                    ['name' => 'Nursing', 'slug' => 'nursing'],
                    ['name' => 'Pharmacy', 'slug' => 'pharmacy'],
                    ['name' => 'Public Health', 'slug' => 'public-health'],
                    ['name' => 'Medical Research', 'slug' => 'medical-research'],
                    ['name' => 'Telemedicine', 'slug' => 'telemedicine'],
                ]
            ],
            [
                'name' => 'Education & Training',
                'slug' => 'education-training',
                'children' => [
                    ['name' => 'Primary & Secondary Education', 'slug' => 'primary-secondary-education'],
                    ['name' => 'Higher Education', 'slug' => 'higher-education'],
                    ['name' => 'E-Learning', 'slug' => 'e-learning'],
                    ['name' => 'Corporate Training', 'slug' => 'corporate-training'],
                ]
            ],
            [
                'name' => 'Manufacturing & Engineering',
                'slug' => 'manufacturing-engineering',
                'children' => [
                    ['name' => 'Automotive', 'slug' => 'automotive'],
                    ['name' => 'Aerospace', 'slug' => 'aerospace'],
                    ['name' => 'Civil Engineering', 'slug' => 'civil-engineering'],
                    ['name' => 'Mechanical Engineering', 'slug' => 'mechanical-engineering'],
                    ['name' => 'Electrical Engineering', 'slug' => 'electrical-engineering'],
                ]
            ],
            [
                'name' => 'Retail & E-Commerce',
                'slug' => 'retail-ecommerce',
                'children' => [
                    ['name' => 'Fashion Retail', 'slug' => 'fashion-retail'],
                    ['name' => 'Supermarkets', 'slug' => 'supermarkets'],
                    ['name' => 'Online Stores', 'slug' => 'online-stores'],
                    ['name' => 'Luxury Goods', 'slug' => 'luxury-goods'],
                ]
            ],
            [
                'name' => 'Hospitality & Tourism',
                'slug' => 'hospitality-tourism',
                'children' => [
                    ['name' => 'Hotels & Lodging', 'slug' => 'hotels-lodging'],
                    ['name' => 'Food & Beverage', 'slug' => 'food-beverage'],
                    ['name' => 'Travel Agencies', 'slug' => 'travel-agencies'],
                    ['name' => 'Recreation Services', 'slug' => 'recreation-services'],
                ]
            ],
            [
                'name' => 'Construction & Real Estate',
                'slug' => 'construction-real-estate',
                'children' => [
                    ['name' => 'Building Construction', 'slug' => 'building-construction'],
                    ['name' => 'Architecture', 'slug' => 'architecture'],
                    ['name' => 'Real Estate Development', 'slug' => 'real-estate-development'],
                    ['name' => 'Property Management', 'slug' => 'property-management'],
                ]
            ],
            [
                'name' => 'Media & Communications',
                'slug' => 'media-communications',
                'children' => [
                    ['name' => 'Advertising', 'slug' => 'advertising'],
                    ['name' => 'Public Relations', 'slug' => 'public-relations'],
                    ['name' => 'Journalism', 'slug' => 'journalism'],
                    ['name' => 'Digital Marketing', 'slug' => 'digital-marketing'],
                    ['name' => 'Broadcasting', 'slug' => 'broadcasting'],
                ]
            ],
            [
                'name' => 'Agriculture & Environment',
                'slug' => 'agriculture-environment',
                'children' => [
                    ['name' => 'Farming', 'slug' => 'farming'],
                    ['name' => 'Fisheries', 'slug' => 'fisheries'],
                    ['name' => 'Environmental Science', 'slug' => 'environmental-science'],
                    ['name' => 'Forestry', 'slug' => 'forestry'],
                    ['name' => 'AgroTech', 'slug' => 'agrotech'],
                ]
            ],
            [
                'name' => 'Transportation & Logistics',
                'slug' => 'transportation-logistics',
                'children' => [
                    ['name' => 'Supply Chain Management', 'slug' => 'supply-chain-management'],
                    ['name' => 'Freight & Shipping', 'slug' => 'freight-shipping'],
                    ['name' => 'Aviation', 'slug' => 'aviation'],
                    ['name' => 'Warehousing', 'slug' => 'warehousing'],
                    ['name' => 'Public Transport', 'slug' => 'public-transport'],
                ]
            ],
            [
                'name' => 'Government & Nonprofit',
                'slug' => 'government-nonprofit',
                'children' => [
                    ['name' => 'Public Administration', 'slug' => 'public-administration'],
                    ['name' => 'Defense', 'slug' => 'defense'],
                    ['name' => 'NGO/Charity', 'slug' => 'ngo-charity'],
                    ['name' => 'Policy & Research', 'slug' => 'policy-research'],
                ]
            ],
            [
                'name' => 'Arts, Entertainment & Sports',
                'slug' => 'arts-entertainment-sports',
                'children' => [
                    ['name' => 'Music', 'slug' => 'music'],
                    ['name' => 'Film & Television', 'slug' => 'film-television'],
                    ['name' => 'Performing Arts', 'slug' => 'performing-arts'],
                    ['name' => 'Sports Management', 'slug' => 'sports-management'],
                    ['name' => 'Gaming', 'slug' => 'gaming'],
                ]
            ]
        ];

        foreach ($industries as $industry) {
            // Insert the parent industry first
            $parentId = $industryModel->insert([
                'name' => $industry['name'],
                'slug' => $industry['slug'],
                'parent_id' => null
            ], true); // true = return inserted ID

            // Insert child industries if present
            if (!empty($industry['children']) && is_array($industry['children'])) {
                foreach ($industry['children'] as $child) {
                    $industryModel->insert([
                        'name' => $child['name'],
                        'slug' => $child['slug'],
                        'parent_id' => $parentId
                    ]);
                }
            }
        }
    }
}
