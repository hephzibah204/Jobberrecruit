<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCandidateAlerts extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'employer_id'  => ['type' => 'INT', 'unsigned' => true],
            'name'         => ['type' => 'VARCHAR', 'constraint' => 255],
            'criteria'     => ['type' => 'TEXT', 'null' => true],
            'frequency'    => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'daily'],
            'email_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'active'       => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'last_sent_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['employer_id', 'active']);
        $this->forge->addForeignKey('employer_id', 'employers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('candidate_alerts', true);
    }

    public function down()
    {
        $this->forge->dropTable('candidate_alerts', true);
    }
}
