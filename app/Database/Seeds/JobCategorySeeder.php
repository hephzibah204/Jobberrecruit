<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class JobCategorySeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'Information Technology', 'slug' => 'information-technology'],
            ['name' => 'Finance & Accounting', 'slug' => 'finance-accounting'],
            ['name' => 'Marketing & Sales', 'slug' => 'marketing-sales'],
            ['name' => 'Engineering', 'slug' => 'engineering'],
            ['name' => 'Customer Service', 'slug' => 'customer-service'],
            ['name' => 'Human Resources', 'slug' => 'human-resources'],
            ['name' => 'Healthcare & Medical', 'slug' => 'healthcare-medical'],
            ['name' => 'Education & Training', 'slug' => 'education-training'],
            ['name' => 'Construction & Real Estate', 'slug' => 'construction-real-estate'],
            ['name' => 'Hospitality & Tourism', 'slug' => 'hospitality-tourism'],
            ['name' => 'Legal', 'slug' => 'legal'],
            ['name' => 'Manufacturing & Production', 'slug' => 'manufacturing-production'],
            ['name' => 'Logistics & Transportation', 'slug' => 'logistics-transportation'],
            ['name' => 'Media & Communications', 'slug' => 'media-communications'],
            ['name' => 'Agriculture & Farming', 'slug' => 'agriculture-farming'],
            ['name' => 'Security & Safety', 'slug' => 'security-safety'],
            ['name' => 'Design & Creative', 'slug' => 'design-creative'],
            ['name' => 'Science & Research', 'slug' => 'science-research'],
            ['name' => 'Procurement & Supply Chain', 'slug' => 'procurement-supply-chain'],
            ['name' => 'NGO & Non-Profit', 'slug' => 'ngo-non-profit'],
        ];

        // Insert batch into the job_categories table
        $this->db->table('job_categories')->insertBatch($data);
    }
}
