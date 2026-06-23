<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMockInterviewSessions extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('mock_interview_sessions')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'application_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'user_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'job_title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'difficulty' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'medium',
            ],
            'question_pack' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'general',
            ],
            'interview_mode' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'chat',
            ],
            'webcam_enabled' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'duration_seconds' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'default'    => 0,
            ],
            'overall_score' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'star_average' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'transcript_json' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'evaluation_json' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('application_id');
        $this->forge->addKey('user_id');
        $this->forge->addKey('created_at');
        $this->forge->addForeignKey('application_id', 'job_applications', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('mock_interview_sessions', true);
    }

    public function down()
    {
        $this->forge->dropTable('mock_interview_sessions', true);
    }
}
