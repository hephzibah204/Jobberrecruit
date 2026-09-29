<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAutomationsTables extends Migration
{
    public function up()
    {
        // 1. Automations Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'trigger_event' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['draft', 'active', 'paused'],
                'default'    => 'draft',
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
        $this->forge->createTable('automations', true);

        // 2. Automation Steps Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'automation_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'step_order' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'template_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'delay_minutes' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0, // 0 = immediately
            ],
            'condition_json' => [
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
        // Using basic index instead of foreign key constraints to prevent simple drop errors later
        $this->forge->addKey('automation_id');
        $this->forge->createTable('automation_steps', true);

        // 3. Automation Subscribers (Enrollments)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'automation_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'subscriber_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'current_step_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['in_progress', 'completed', 'cancelled'],
                'default'    => 'in_progress',
            ],
            'enrolled_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'next_step_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'completed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['automation_id', 'subscriber_id']);
        $this->forge->createTable('automation_subscribers', true);
    }

    public function down()
    {
        $this->forge->dropTable('automation_subscribers', true);
        $this->forge->dropTable('automation_steps', true);
        $this->forge->dropTable('automations', true);
    }
}
