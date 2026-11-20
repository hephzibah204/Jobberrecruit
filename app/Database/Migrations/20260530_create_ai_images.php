<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAiImages extends Migration
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
            'origin_url' => [
                'type' => 'VARCHAR',
                'constraint' => 2048,
                'null' => false,
            ],
            'proxied_path' => [
                'type' => 'VARCHAR',
                'constraint' => 1024,
                'null' => true,
                'default' => null,
            ],
            'checksum' => [
                'type' => 'VARCHAR',
                'constraint' => 64,
                'null' => true,
                'default' => null,
            ],
            'mime' => [
                'type' => 'VARCHAR',
                'constraint' => 64,
                'null' => true,
                'default' => null,
            ],
            'size' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'default' => null,
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
                'default' => 'pending',
            ],
            'error' => [
                'type' => 'VARCHAR',
                'constraint' => 1024,
                'null' => true,
                'default' => null,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'processed_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('checksum');
        $this->forge->addKey('origin_url');
        $this->forge->addKey('status');
        $this->forge->createTable('ai_images', true);
    }

    public function down()
    {
        $this->forge->dropTable('ai_images', true);
    }
}
