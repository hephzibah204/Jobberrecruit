<?php

namespace App\Controllers;

use App\Models\IndustryModel;
use CodeIgniter\Controller;

class DebugController extends BaseController
{
    public function index()
    {
        $industryModel = new IndustryModel();
        $count = $industryModel->countAllResults();
        
        $industries = $industryModel->findAll(10);
        
        $output = "<h1>Industries Debug Info</h1>";
        $output .= "<p>Total industries: {$count}</p>";
        
        if ($count > 0) {
            $output .= "<h2>First 10 Industries:</h2><ul>";
            foreach ($industries as $industry) {
                $output .= "<li><strong>{$industry->name}</strong> (slug: {$industry->slug})</li>";
            }
            $output .= "</ul>";
        } else {
            $output .= "<p style='color:red;'>No industries found!</p>";
            $output .= "<p>This causes the industry_hub route to return 404.</p>";
        }
        
        $output .= "<p><a href='/' class='btn btn-primary'>Go to Home</a></p>";
        
        return $this->response->setBody($output);
    }
    
    public function seed()
    {
        // Only allow in development
        if (ENVIRONMENT !== 'development') {
            return redirect()->to('/');
        }
        
        $industryModel = new IndustryModel();
        $count = $industryModel->countAllResults();
        
        if ($count > 0) {
            return redirect()->to('/debug')->with('info', 'Industries already exist.');
        }
        
        // Insert basic industries
        $industries = [
            ['name' => 'Information Technology', 'slug' => 'information-technology'],
            ['name' => 'Software Development', 'slug' => 'software-development'],
            ['name' => 'Finance & Banking', 'slug' => 'finance-banking'],
            ['name' => 'Healthcare & Medical', 'slug' => 'healthcare-medical'],
            ['name' => 'Education & Training', 'slug' => 'education-training'],
            ['name' => 'Manufacturing & Engineering', 'slug' => 'manufacturing-engineering'],
            ['name' => 'Retail & E-Commerce', 'slug' => 'retail-ecommerce'],
            ['name' => 'Hospitality & Tourism', 'slug' => 'hospitality-tourism'],
            ['name' => 'Construction & Real Estate', 'slug' => 'construction-real-estate'],
            ['name' => 'Media & Communications', 'slug' => 'media-communications']
        ];
        
        foreach ($industries as $industry) {
            $industryModel->insert($industry);
        }
        
        return redirect()->to('/debug')->with('success', 'Seeded ' . count($industries) . ' industries.');
    }
}

?>