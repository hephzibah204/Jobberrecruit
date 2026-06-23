<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CourseDemoSeeder extends Seeder
{
    public function run()
    {
        $courses = [
            [
                'title'          => 'AI Career Launchpad',
                'slug'           => 'ai-career-launchpad',
                'description'    => '<p>Learn how to use AI tools to refine your CV, practice job interviews, and structure a stronger career growth plan. This starter course is perfect for first-time candidates using JobberRecruit.</p>',
                'instructor'     => 'JobberRecruit Academy',
                'price'          => 0,
                'duration'       => '1h 20m',
                'level'          => 'beginner',
                'content_source' => 'youtube',
                'youtube_url'    => 'https://www.youtube.com/watch?v=ysz5S6PUM-U',
                'content_file'   => null,
                'thumbnail'      => null,
                'is_featured'    => 1,
                'is_active'      => 1,
            ],
            [
                'title'          => 'Interview Confidence Toolkit',
                'slug'           => 'interview-confidence-toolkit',
                'description'    => '<p>A practical free toolkit with structured answer templates, confidence-building prompts, and an interview checklist candidates can apply immediately.</p>',
                'instructor'     => 'Talent Success Team',
                'price'          => 0,
                'duration'       => '35m',
                'level'          => 'beginner',
                'content_source' => 'upload',
                'youtube_url'    => null,
                'content_file'   => 'courses/interview-confidence-toolkit.html',
                'thumbnail'      => null,
                'is_featured'    => 1,
                'is_active'      => 1,
            ],
            [
                'title'          => 'CV Rewrite Masterclass',
                'slug'           => 'cv-rewrite-masterclass',
                'description'    => '<p>A premium course for professionals who want to reposition their experience, quantify achievements, and build recruiter-ready CVs for competitive roles.</p>',
                'instructor'     => 'Career Strategy Desk',
                'price'          => 15000,
                'duration'       => '2h 10m',
                'level'          => 'intermediate',
                'content_source' => 'youtube',
                'youtube_url'    => 'https://www.youtube.com/watch?v=jNQXAC9IVRw',
                'content_file'   => null,
                'thumbnail'      => null,
                'is_featured'    => 0,
                'is_active'      => 1,
            ],
            [
                'title'          => 'Employer Hiring Playbook',
                'slug'           => 'employer-hiring-playbook',
                'description'    => '<p>A paid employer-focused course covering job post structure, interview scorecards, and candidate communication workflows for better hiring outcomes.</p>',
                'instructor'     => 'Recruitment Operations Team',
                'price'          => 25000,
                'duration'       => '1h 45m',
                'level'          => 'advanced',
                'content_source' => 'upload',
                'youtube_url'    => null,
                'content_file'   => 'courses/employer-hiring-playbook.html',
                'thumbnail'      => null,
                'is_featured'    => 1,
                'is_active'      => 1,
            ],
        ];

        $builder = $this->db->table('courses');

        foreach ($courses as $course) {
            $existing = $builder->where('slug', $course['slug'])->get()->getRowArray();
            $timestamp = date('Y-m-d H:i:s');

            if ($existing) {
                $builder->where('id', $existing['id'])->update(array_merge($course, [
                    'updated_at' => $timestamp,
                ]));
                continue;
            }

            $builder->insert(array_merge($course, [
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]));
        }
    }
}
