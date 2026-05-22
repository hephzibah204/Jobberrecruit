<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlignTestimonialsSchema extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('testimonials')) {
            $this->forge->dropTable('testimonials', true);
        }

        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 150],
            'role'        => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'company'     => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'message'     => ['type' => 'TEXT'],
            'rating'      => ['type' => 'TINYINT', 'default' => 5],
            'avatar'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'status'      => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'active'],
            'is_featured' => ['type' => 'TINYINT', 'default' => 0],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('testimonials', true);
    }

    public function down()
    {
        if ($this->db->tableExists('testimonials')) {
            $this->forge->dropTable('testimonials', true);
        }

        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id'      => ['type' => 'INT', 'unsigned' => true],
            'content'      => ['type' => 'TEXT'],
            'rating'       => ['type' => 'TINYINT', 'default' => 5],
            'is_published' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('testimonials', true);
    }
}
