<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateResumeAutosaves extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'resume_id' => ['type' => 'INT', 'null' => true],
            'user_id' => ['type' => 'INT', 'null' => false],
            'payload' => ['type' => 'LONGTEXT', 'null' => false],
            'metadata' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => false]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('resume_autosaves');
    }

    public function down()
    {
        $this->forge->dropTable('resume_autosaves');
    }
}
