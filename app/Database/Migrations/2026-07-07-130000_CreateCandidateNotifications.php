<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCandidateNotifications extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'candidate_id'   => ['type' => 'INT', 'unsigned' => true],
            'application_id' => ['type' => 'INT', 'unsigned' => true],
            'type'           => ['type' => 'VARCHAR', 'constraint' => 40],
            'title'          => ['type' => 'VARCHAR', 'constraint' => 255],
            'message'        => ['type' => 'TEXT'],
            'is_read'        => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'read_at'        => ['type' => 'DATETIME', 'null' => true],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['candidate_id', 'is_read', 'created_at']);
        $this->forge->addForeignKey('candidate_id', 'job_seekers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('application_id', 'job_applications', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('candidate_notifications', true);
    }

    public function down()
    {
        $this->forge->dropTable('candidate_notifications', true);
    }
}
