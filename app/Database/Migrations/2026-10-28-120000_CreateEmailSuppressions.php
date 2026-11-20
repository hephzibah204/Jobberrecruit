<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmailSuppressions extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'email_hash' => ['type' => 'VARCHAR', 'constraint' => 64],
            'email' => ['type' => 'VARCHAR', 'constraint' => 254],
            'reason' => ['type' => 'VARCHAR', 'constraint' => 40],
            'event_id' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at' => ['type' => 'DATETIME'],
        ]);
        $this->forge->addKey('email_hash', true);
        $this->forge->createTable('email_suppressions', true);
    }
    public function down() { $this->forge->dropTable('email_suppressions', true); }
}
