<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\JobModel;
use App\Models\BlogModel;
use App\Models\EmployerModel;

class SitemapController extends Controller
{
    public function index()
    {
        $jobModel     = model(JobModel::class);
        $blogModel    = model(BlogModel::class);
        $employerModel = model(EmployerModel::class);

        // Static pages
        $staticPages = [
            ['loc' => base_url(),                  'priority' => '1.0',   'changefreq' => 'daily'],
            ['loc' => base_url('jobs'),             'priority' => '0.9',   'changefreq' => 'daily'],
            ['loc' => base_url('jobs/featured'),    'priority' => '0.8',   'changefreq' => 'daily'],
            ['loc' => base_url('candidates'),       'priority' => '0.8',   'changefreq' => 'weekly'],
            ['loc' => base_url('about-us'),         'priority' => '0.7',   'changefreq' => 'monthly'],
            ['loc' => base_url('contact-us'),       'priority' => '0.7',   'changefreq' => 'monthly'],
            ['loc' => base_url('blog'),             'priority' => '0.8',   'changefreq' => 'daily'],
            ['loc' => base_url('privacy-policy'),   'priority' => '0.5',   'changefreq' => 'yearly'],
            ['loc' => base_url('terms-and-conditions'), 'priority' => '0.5', 'changefreq' => 'yearly'],
            ['loc' => base_url('faq'),              'priority' => '0.6',   'changefreq' => 'monthly'],
        ];

        // State Hubs (Programmatic)
        $states = model('App\Models\StateModel')->findAll();
        foreach ($states as $state) {
            if (!empty($state->slug)) {
                $staticPages[] = [
                    'loc'        => base_url("jobs-in-{$state->slug}"),
                    'priority'   => '0.9',
                    'changefreq' => 'daily'
                ];
            }
        }

        // Industry Hubs (Programmatic)
        $industries = model('App\Models\IndustryModel')->findAll();
        foreach ($industries as $industry) {
            if (!empty($industry->slug)) {
                $staticPages[] = [
                    'loc'        => base_url("{$industry->slug}-jobs"),
                    'priority'   => '0.9',
                    'changefreq' => 'daily'
                ];
            }
        }

        // Active Jobs (only non-expired)
        $jobs = $jobModel
            ->where('status', 'open')
            ->orderBy('updated_at', 'DESC')
            ->findAll(500);

        foreach ($jobs as $job) {
            $staticPages[] = [
                'loc'        => base_url("jobs/{$job->slug}"),
                'lastmod'    => date('Y-m-d', strtotime($job->updated_at ?? $job->created_at)),
                'priority'   => '0.8',
                'changefreq' => 'weekly'
            ];
        }

        // Blog Posts
        $blogs = $blogModel->orderBy('created_at', 'DESC')->findAll(100);

        foreach ($blogs as $blog) {
            $staticPages[] = [
                'loc'        => base_url("blog/{$blog->slug}"),
                'lastmod'    => date('Y-m-d', strtotime($blog->updated_at ?? $blog->created_at)),
                'priority'   => '0.7',
                'changefreq' => 'weekly'
            ];
        }

        // Public Employer Profiles
        $employers = $employerModel
            ->where('is_verified', 1) // Only verified companies
            ->findAll(200);

        foreach ($employers as $employer) {
            $staticPages[] = [
                'loc'        => base_url("employer/{$employer->id}"),
                'priority'   => '0.6',
                'changefreq' => 'weekly'
            ];
        }

        // Set headers first
        $this->response->setContentType('application/xml');

        // Start output with XML declaration
        $output = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $output .= view('sitemap/index', ['pages' => $staticPages]);

        return $this->response->setBody($output);
    }
}
