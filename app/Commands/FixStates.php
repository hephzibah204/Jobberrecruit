<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class FixStates extends BaseCommand
{
    protected $group       = 'Maintenance';
    protected $name        = 'states:fix';
    protected $description = 'Fixes all states, descriptions, and seeds realistic job distribution including mockup Lagos jobs.';

    public function run(array $params)
    {
        $db = db_connect();

        CLI::write("1. Verifying all 37 states...", 'yellow');
        $states = $db->table('states')->get()->getResult();

        foreach ($states as $s) {
            $update = [];
            // Clean up double spaces in meta_description
            if (!empty($s->meta_description)) {
                $cleanMeta = preg_replace('/\s+/', ' ', str_replace(', ,', ',', $s->meta_description));
                if ($cleanMeta !== $s->meta_description) {
                    $update['meta_description'] = $cleanMeta;
                }
            }
            // Clean up double spaces in seo_h1
            if (!empty($s->seo_h1)) {
                $cleanH1 = preg_replace('/\s+/', ' ', $s->seo_h1);
                if ($cleanH1 !== $s->seo_h1) {
                    $update['seo_h1'] = $cleanH1;
                }
            }
            // Ensure is_active = 1
            if ($s->is_active != 1) {
                $update['is_active'] = 1;
            }

            if (!empty($update)) {
                $db->table('states')->where('id', $s->id)->update($update);
            }
        }
        CLI::write("States meta and active flags verified.", 'green');

        CLI::write("2. Ensuring 'Jobber Recruit LTD' employer exists...", 'yellow');
        $employer = $db->table('employers')->where('company_name', 'Jobber Recruit LTD')->get()->getRow();
        if (!$employer) {
            // Find an employer user or default user
            $user = $db->table('users')->where('user_type', 'employer')->get()->getRow()
                 ?? $db->table('users')->get()->getRow();
            $userId = $user ? $user->id : 1;

            $db->table('employers')->insert([
                'user_id'             => $userId,
                'company_name'        => 'Jobber Recruit LTD',
                'contact_email'       => 'careers@jobberrecruit.com',
                'contact_phone'       => '+2348000000000',
                'website'             => 'https://www.jobberrecruit.com',
                'is_verified'         => 1,
                'verification_status' => 'verified',
                'state_id'            => 25, // Lagos
                'created_at'          => date('Y-m-d H:i:s'),
                'updated_at'          => date('Y-m-d H:i:s'),
            ]);
            $employerId = $db->insertID();
            CLI::write("Created 'Jobber Recruit LTD' (ID: {$employerId})", 'green');
        } else {
            $employerId = $employer->id;
            $db->table('employers')->where('id', $employerId)->update([
                'is_verified'         => 1,
                'verification_status' => 'verified',
            ]);
            CLI::write("Updated 'Jobber Recruit LTD' (ID: {$employerId}) to verified", 'green');
        }

        CLI::write("3. Adding/Updating the 8 featured jobs from the HTML mockup...", 'yellow');
        $featuredJobs = [
            [
                'title'            => '2026 AP Graduate Trainee Programme – Graduate Jobs in Nigeria',
                'slug'             => '2026-ap-graduate-trainee-programme',
                'employer_id'      => $employerId,
                'category_id'      => 1,
                'industry_id'      => 1,
                'state_id'         => 25,
                'job_type'         => 'full-time',
                'location_type'    => 'on-site',
                'salary_type'      => 'negotiable',
                'salary_period'    => 'monthly',
                'salary'           => 'Negotiable',
                'salary_details'   => 'Negotiable',
                'experience_level' => 'Entry Level (0–2 yrs)',
                'is_featured'      => 1,
                'featured_until'   => date('Y-m-d H:i:s', strtotime('+180 days')),
                'is_anonymous'     => 0,
                'is_verified'      => 1,
                'status'           => 'open',
                'admin_status'     => 'approved',
                'application_deadline' => date('Y-m-d', strtotime('+180 days')),
                'application_method'   => 'external',
                'external_url'         => 'https://cutt.ly/ArdovaPlcGTP',
                'description'      => '<p>Join the 2026 AP Graduate Trainee Programme. Accelerate your career across multidisciplinary functions in Nigeria\'s leading corporate organisations.</p>',
            ],
            [
                'title'            => 'WE ARE HIRING: HR/People & Operations Manager',
                'slug'             => 'we-are-hiring-hr-people-operations-manager-lagos',
                'employer_id'      => $employerId,
                'category_id'      => 6,
                'industry_id'      => 1,
                'state_id'         => 25,
                'job_type'         => 'full-time',
                'location_type'    => 'on-site',
                'salary_type'      => 'negotiable',
                'salary_period'    => 'monthly',
                'salary'           => 'Negotiable',
                'salary_details'   => 'Negotiable',
                'experience_level' => 'Mid Level (2–5 yrs)',
                'is_featured'      => 1,
                'featured_until'   => date('Y-m-d H:i:s', strtotime('+180 days')),
                'is_anonymous'     => 1,
                'is_verified'      => 1,
                'status'           => 'open',
                'admin_status'     => 'approved',
                'application_deadline' => date('Y-m-d', strtotime('+180 days')),
                'application_method'   => 'external',
                'external_url'         => 'https://forms.gle/FNNC59N2uPVVquhV6',
                'description'      => '<p>We are seeking an experienced HR/People & Operations Manager to lead talent strategy, people operations, and organizational efficiency in Lagos.</p>',
            ],
            [
                'title'            => 'Nigerian Agip Exploration Limited (NAE) – 2026/2027 Postgraduate Scholarship Award Scheme',
                'slug'             => 'nigerian-agip-nae-postgraduate-scholarship',
                'employer_id'      => $employerId,
                'category_id'      => 8,
                'industry_id'      => 1,
                'state_id'         => 25,
                'job_type'         => 'full-time',
                'location_type'    => 'on-site',
                'salary_type'      => 'negotiable',
                'salary_period'    => 'monthly',
                'salary'           => 'Negotiable',
                'salary_details'   => 'Negotiable',
                'experience_level' => 'Entry Level (0–2 yrs)',
                'is_featured'      => 1,
                'featured_until'   => date('Y-m-d H:i:s', strtotime('+180 days')),
                'is_anonymous'     => 1,
                'is_verified'      => 1,
                'status'           => 'open',
                'admin_status'     => 'approved',
                'application_deadline' => date('Y-m-d', strtotime('+180 days')),
                'application_method'   => 'internal',
                'description'      => '<p>Nigerian Agip Exploration Limited (NAE) announces its 2026/2027 Postgraduate Scholarship Scheme for outstanding Nigerian graduates.</p>',
            ],
            [
                'title'            => 'Sales and Procurement Manager at Jobber Recruit LTD',
                'slug'             => 'sales-and-procurement-manager',
                'employer_id'      => $employerId,
                'category_id'      => 3,
                'industry_id'      => 9,
                'state_id'         => 25,
                'job_type'         => 'full-time',
                'location_type'    => 'on-site',
                'salary_type'      => 'fixed',
                'salary_period'    => 'month',
                'salary'           => '250000',
                'salary_details'   => '₦250,000 / month',
                'experience_level' => 'Mid Level (2–5 yrs)',
                'is_featured'      => 1,
                'featured_until'   => date('Y-m-d H:i:s', strtotime('+180 days')),
                'is_anonymous'     => 0,
                'is_verified'      => 1,
                'status'           => 'open',
                'admin_status'     => 'approved',
                'application_deadline' => date('Y-m-d', strtotime('+180 days')),
                'application_method'   => 'internal',
                'description'      => '<p>Oversee procurement lifecycles, vendor negotiations, and revenue generation strategies for key enterprise accounts in Lagos.</p>',
            ],
            [
                'title'            => 'SOCIAL MEDIA MANAGER & CREATIVE ASSISTANT',
                'slug'             => 'social-media-manager-creative-assistant',
                'employer_id'      => $employerId,
                'category_id'      => 3,
                'industry_id'      => 1,
                'state_id'         => 25,
                'job_type'         => 'full-time',
                'location_type'    => 'on-site',
                'salary_type'      => 'fixed',
                'salary_period'    => 'month',
                'salary'           => '300000',
                'salary_details'   => '₦300,000 / month',
                'experience_level' => 'Entry Level (0–2 yrs)',
                'is_featured'      => 1,
                'featured_until'   => date('Y-m-d H:i:s', strtotime('+180 days')),
                'is_anonymous'     => 0,
                'is_verified'      => 1,
                'status'           => 'open',
                'admin_status'     => 'approved',
                'application_deadline' => date('Y-m-d', strtotime('+180 days')),
                'application_method'   => 'internal',
                'description'      => '<p>Drive social engagement, brand storytelling, and visual creative asset design across digital channels in Lagos.</p>',
            ],
            [
                'title'            => 'Multiple Vacancies at Renaissance Africa Energy Company – Finance, HR & Supply Chain Roles (Nigeria)',
                'slug'             => 'renaissance-africa-energy-vacancies',
                'employer_id'      => $employerId,
                'category_id'      => 2,
                'industry_id'      => 9,
                'state_id'         => 25,
                'job_type'         => 'full-time',
                'location_type'    => 'on-site',
                'salary_type'      => 'negotiable',
                'salary_period'    => 'monthly',
                'salary'           => 'Negotiable',
                'salary_details'   => 'Negotiable',
                'experience_level' => 'Mid Level (2–5 yrs)',
                'is_featured'      => 1,
                'featured_until'   => date('Y-m-d H:i:s', strtotime('+180 days')),
                'is_anonymous'     => 0,
                'is_verified'      => 1,
                'status'           => 'open',
                'admin_status'     => 'approved',
                'application_deadline' => date('Y-m-d', strtotime('+180 days')),
                'application_method'   => 'external',
                'external_url'         => 'https://renaissanceafrica.com/careers/',
                'description'      => '<p>Renaissance Africa Energy Company is recruiting top professionals across Finance, Human Resources, and Supply Chain roles in Lagos.</p>',
            ],
            [
                'title'            => 'Administrative Officer',
                'slug'             => 'administrative-officer',
                'employer_id'      => $employerId,
                'category_id'      => 5,
                'industry_id'      => 1,
                'state_id'         => 25,
                'job_type'         => 'full-time',
                'location_type'    => 'on-site',
                'salary_type'      => 'fixed',
                'salary_period'    => 'month',
                'salary'           => '250000',
                'salary_details'   => '₦250,000 / month',
                'experience_level' => 'Entry Level (0–2 yrs)',
                'is_featured'      => 1,
                'featured_until'   => date('Y-m-d H:i:s', strtotime('+180 days')),
                'is_anonymous'     => 0,
                'is_verified'      => 1,
                'status'           => 'open',
                'admin_status'     => 'approved',
                'application_deadline' => date('Y-m-d', strtotime('+180 days')),
                'application_method'   => 'internal',
                'description'      => '<p>Coordinate administrative workflows, office logistics, scheduling, and documentation in our Lagos office.</p>',
            ],
            [
                'title'            => 'Halliburton 2026 Graduate Internship Program in Port Harcourt | Apply Now',
                'slug'             => 'halliburton-2026-graduate-internship',
                'employer_id'      => $employerId,
                'category_id'      => 1,
                'industry_id'      => 9,
                'state_id'         => 33, // Rivers (Port Harcourt)
                'job_type'         => 'full-time',
                'location_type'    => 'on-site',
                'salary_type'      => 'negotiable',
                'salary_period'    => 'monthly',
                'salary'           => 'Negotiable',
                'salary_details'   => 'Negotiable',
                'experience_level' => 'Entry Level (0–2 yrs)',
                'is_featured'      => 1,
                'featured_until'   => date('Y-m-d H:i:s', strtotime('+180 days')),
                'is_anonymous'     => 0,
                'is_verified'      => 1,
                'status'           => 'open',
                'admin_status'     => 'approved',
                'application_deadline' => date('Y-m-d', strtotime('+180 days')),
                'application_method'   => 'external',
                'external_url'         => 'https://jobs.halliburton.com/job/Port-Harcourt-Nigeria_Port-Harcourt_Graduate-Intern-RI/1373673800/#',
                'description'      => '<p>Apply now for the Halliburton 2026 Graduate Internship Program in Port Harcourt, Nigeria. Hands-on experience across engineering, operations, and geoscience.</p>',
            ],
        ];

        $mockupSlugs = array_column($featuredJobs, 'slug');

        foreach ($featuredJobs as $fj) {
            $existing = $db->table('jobs')->where('slug', $fj['slug'])->get()->getRow();
            if ($existing) {
                $db->table('jobs')->where('id', $existing->id)->update($fj);
                CLI::write("Updated featured job: {$fj['title']}", 'green');
            } else {
                $fj['created_at'] = date('Y-m-d H:i:s');
                $fj['updated_at'] = date('Y-m-d H:i:s');
                $db->table('jobs')->insert($fj);
                CLI::write("Inserted featured job: {$fj['title']}", 'green');
            }
        }

        CLI::write("4. Distributing other jobs across major Nigerian states...", 'yellow');
        // Major states: 15 (FCT), 33 (Rivers), 31 (Oyo), 20 (Kano), 28 (Ogun), 10 (Delta), 19 (Kaduna), 12 (Edo), 4 (Anambra), 14 (Enugu), 1 (Abia), 2 (Adamawa)
        $targetStates = [15, 33, 31, 20, 28, 10, 19, 12, 4, 14, 1, 2];
        $allOtherJobs = $db->table('jobs')
            ->where('state_id !=', 25)
            ->whereNotIn('slug', $mockupSlugs)
            ->get()->getResult();

        $idx = 0;
        foreach ($allOtherJobs as $j) {
            $assignedState = $targetStates[$idx % count($targetStates)];
            $db->table('jobs')->where('id', $j->id)->update([
                'state_id'             => $assignedState,
                'status'               => 'open',
                'admin_status'         => 'approved',
                'application_deadline' => date('Y-m-d', strtotime('+180 days')),
            ]);
            $idx++;
        }
        CLI::write("Distributed {$idx} jobs across target states.", 'green');

        CLI::write("All states and jobs fixed successfully!", 'cyan');
    }
}
