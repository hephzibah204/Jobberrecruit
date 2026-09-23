<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WebinarSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'title'         => 'Landing Your Dream Job in Tech: 2024 Guide',
                'description'   => 'Learn the latest strategies for breaking into the Nigerian tech ecosystem, from CV optimization to networking.',
                'speaker_name'  => 'John Doe',
                'speaker_bio'   => 'Senior Software Engineer at JobberRecruit with 10+ years experience.',
                'scheduled_at'  => date('Y-m-d H:i:s', strtotime('+2 days')),
                'meeting_link'  => 'https://zoom.us/j/123456789',
                'status'        => 'upcoming',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'title'         => 'Mastering the AI Interview: Tips & Tricks',
                'description'   => 'AI is changing how we interview. Join us to learn how to prepare for automated video interviews.',
                'speaker_name'  => 'Jane Smith',
                'speaker_bio'   => 'HR Director at Global Talent Hub.',
                'scheduled_at'  => date('Y-m-d H:i:s', strtotime('+5 days')),
                'meeting_link'  => 'https://meet.google.com/abc-defg-hij',
                'status'        => 'upcoming',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'title'         => 'Salary Negotiation for Early Career Professionals',
                'description'   => 'Don\'t leave money on the table. Learn how to negotiate your first or second big salary.',
                'speaker_name'  => 'Michael Adenuga',
                'speaker_bio'   => 'Career Coach & Salary Expert.',
                'scheduled_at'  => date('Y-m-d H:i:s', strtotime('-1 day')),
                'meeting_link'  => 'https://zoom.us/j/987654321',
                'status'        => 'completed',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
        ];

        // Using Query Builder
        $this->db->table('webinars')->insertBatch($data);
    }
}
