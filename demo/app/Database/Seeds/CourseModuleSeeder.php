<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CourseModuleSeeder extends Seeder
{
    public function run()
    {
        $courses = $this->db->table('courses')->get()->getResultArray();
        if (empty($courses)) {
            echo "No courses found. Please run CourseDemoSeeder first.\n";
            return;
        }

        $builder = $this->db->table('course_modules');
        
        foreach ($courses as $course) {
            $existingModules = $builder->where('course_id', $course['id'])->countAllResults();
            if ($existingModules > 0) {
                echo "Modules already exist for course: " . $course['title'] . "\n";
                continue;
            }

            $modules = [
                [
                    'course_id' => $course['id'],
                    'title' => 'Introduction to ' . $course['title'],
                    'description' => '<p>Welcome to the first module of ' . $course['title'] . '. In this module, we will cover the basics.</p>',
                    'content_source' => 'youtube',
                    'youtube_url' => 'https://www.youtube.com/watch?v=ysz5S6PUM-U',
                    'content_file' => null,
                    'order_index' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'course_id' => $course['id'],
                    'title' => 'Core Concepts & Curriculum',
                    'description' => '<p>Dive deep into the curriculum. We explore essential strategies to succeed.</p>',
                    'content_source' => 'text',
                    'youtube_url' => null,
                    'content_file' => null,
                    'order_index' => 2,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'course_id' => $course['id'],
                    'title' => 'Final Assessment & Completion',
                    'description' => '<p>This is the final module. Complete the assessment to earn your certificate.</p>',
                    'content_source' => 'youtube',
                    'youtube_url' => 'https://www.youtube.com/watch?v=jNQXAC9IVRw',
                    'content_file' => null,
                    'order_index' => 3,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]
            ];

            $builder->insertBatch($modules);
            echo "Inserted 3 modules for course: " . $course['title'] . "\n";
        }
    }
}
