<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateApplicationStatusHistory extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                 => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'application_id'     => ['type' => 'INT', 'unsigned' => true],
            'old_status'         => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'new_status'         => ['type' => 'VARCHAR', 'constraint' => 20],
            'changed_by_user_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'message'            => ['type' => 'TEXT', 'null' => true],
            'created_at'         => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['application_id', 'created_at']);
        $this->forge->addForeignKey('application_id', 'job_applications', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('job_application_status_history', true);
    }

    public function down()
    {
        $this->forge->dropTable('job_application_status_history', true);
    }
}
