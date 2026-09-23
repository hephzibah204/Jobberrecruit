<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlignCoursesSchema extends Migration
{
    public function up()
    {
        $courseColumns = [];

        if (! $this->db->fieldExists('duration', 'courses')) {
            $courseColumns['duration'] = ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true];
        }

        if (! $this->db->fieldExists('level', 'courses')) {
            $courseColumns['level'] = ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'beginner'];
        }

        if (! $this->db->fieldExists('content_source', 'courses')) {
            $courseColumns['content_source'] = ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'none'];
        }

        if (! $this->db->fieldExists('youtube_url', 'courses')) {
            $courseColumns['youtube_url'] = ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true];
        }

        if (! $this->db->fieldExists('content_file', 'courses')) {
            $courseColumns['content_file'] = ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true];
        }

        if (! $this->db->fieldExists('is_featured', 'courses')) {
            $courseColumns['is_featured'] = ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0];
        }

        if (! $this->db->fieldExists('updated_at', 'courses')) {
            $courseColumns['updated_at'] = ['type' => 'DATETIME', 'null' => true];
        }

        if ($courseColumns !== []) {
            $this->forge->addColumn('courses', $courseColumns);
        }

        if (! $this->db->fieldExists('amount', 'course_enrollments')) {
            $this->forge->addColumn('course_enrollments', [
                'amount' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            ]);
        }
    }

    public function down()
    {
        // Intentionally left non-destructive to avoid dropping populated course data.
    }
}
