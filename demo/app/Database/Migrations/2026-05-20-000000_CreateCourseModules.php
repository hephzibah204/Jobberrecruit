<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCourseModules extends Migration
{
    public function up()
    {
        // Add item_type to courses table
        if (!$this->db->fieldExists('item_type', 'courses')) {
            $this->forge->addColumn('courses', [
                'item_type' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'default'    => 'course',
                    'null'       => false,
                    'after'      => 'title'
                ],
            ]);
        }

        // Create course_modules table
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
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'content_source' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'none', // none, youtube, upload, text
            ],
            'youtube_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'content_file' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'order_index' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
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
        $this->forge->addForeignKey('course_id', 'courses', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('course_modules', true);
    }

    public function down()
    {
        if ($this->db->fieldExists('item_type', 'courses')) {
            $this->forge->dropColumn('courses', 'item_type');
        }
        $this->forge->dropTable('course_modules', true);
    }
}
