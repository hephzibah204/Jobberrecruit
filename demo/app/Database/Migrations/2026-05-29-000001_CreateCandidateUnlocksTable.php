<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCandidateUnlocksTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'employer_id' => ['type' => 'INT', 'unsigned' => true],
            'job_seeker_id' => ['type' => 'INT', 'unsigned' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('employer_id', 'employers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('job_seeker_id', 'job_seekers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addUniqueKey(['employer_id', 'job_seeker_id'], 'unique_unlock');
        $this->forge->createTable('candidate_unlocks', true);
    }

    public function down()
    {
        $this->forge->dropTable('candidate_unlocks');
    }
}
