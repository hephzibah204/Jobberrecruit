<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMessagingTables extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'employer_id' => ['type' => 'INT', 'unsigned' => true],
            'job_seeker_id' => ['type' => 'INT', 'unsigned' => true],
            'job_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'last_message' => ['type' => 'TEXT', 'null' => true],
            'last_message_at' => ['type' => 'DATETIME', 'null' => true],
            'employer_last_read' => ['type' => 'DATETIME', 'null' => true],
            'seeker_last_read' => ['type' => 'DATETIME', 'null' => true],
            'is_active' => ['type' => 'TINYINT', 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('employer_id', 'employers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('job_seeker_id', 'job_seekers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('job_id', 'jobs', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('conversations', true);

        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'conversation_id' => ['type' => 'INT', 'unsigned' => true],
            'sender_id' => ['type' => 'INT', 'unsigned' => true],
            'sender_type' => ['type' => 'VARCHAR', 'constraint' => '20'],
            'message' => ['type' => 'TEXT'],
            'is_read' => ['type' => 'TINYINT', 'default' => 0],
            'read_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('conversation_id', 'conversations', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('messages', true);
    }

    public function down()
    {
        $this->forge->dropTable('messages');
        $this->forge->dropTable('conversations');
    }
}
