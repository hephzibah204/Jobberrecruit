<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCertificateTemplatesTable extends Migration
{
    public function up()
    {
        // Only create if not already present
        if ($this->db->tableExists('certificate_templates')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'course_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'default'    => null,
            ],
            'template_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
            ],
            'background_image' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
                'default'    => null,
            ],
            'primary_color' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
                'default'    => '#0D609E',
            ],
            'secondary_color' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
                'default'    => '#F3921D',
            ],
            'text_color' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
                'default'    => '#15233a',
            ],
            'show_qr_code' => [
                'type'    => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'show_signature' => [
                'type'    => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'show_logo' => [
                'type'    => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'is_full_image_mode' => [
                'type'    => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'template_mode' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
                'default'    => 'builder',
            ],
            'custom_html' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'layout_json' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'additional_text' => [
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
        $this->forge->addKey('course_id');
        $this->forge->createTable('certificate_templates');
    }

    public function down()
    {
        $this->forge->dropTable('certificate_templates', true);
    }
}
