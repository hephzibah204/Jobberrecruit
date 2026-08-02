<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAudienceSegments extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['dynamic', 'static'],
                'default' => 'dynamic',
            ],
            'criteria_json' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'user_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'last_synced_at' => [
                'type' => 'DATETIME',
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
        $this->forge->createTable('audience_segments');
    }

    public function down()
    {
        $this->forge->dropTable('audience_segments');
    }
}
