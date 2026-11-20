<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmailTemplatesTable extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('email_templates')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'template_key' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'unique'     => true,
                ],
                'name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                ],
                'category' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                ],
                'subject' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                ],
                'greeting_type' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '20',
                    'default'    => 'first_name',
                ],
                'body_html' => [
                    'type' => 'TEXT',
                ],
                'body_text' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'available_variables' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'description' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                    'null'       => true,
                ],
                'is_active' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 1,
                ],
                'is_system' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 1,
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
            $this->forge->createTable('email_templates', true);
        }
    }

    public function down()
    {
        $this->forge->dropTable('email_templates', true);
    }
}
