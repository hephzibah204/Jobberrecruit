<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ReorderEducationQualifications extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        if (!$db->tableExists('qualifications')) {
            $this->forge->addField([
                'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'name' => ['type' => 'VARCHAR', 'constraint' => '150', 'unique' => true],
                'order_index' => ['type' => 'INT', 'default' => 0],
                'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->createTable('qualifications', true);
        }
        $builder = $db->table('qualifications');
        
        $order = [
            'Secondary School/High School' => 1,
            'OND' => 2,
            'HND' => 3,
            'Bachelor\'s Degree' => 4,
            'Master\'s Degree' => 5,
            'MBA' => 6,
            'PhD/Doctorate' => 7
        ];

        // Insert missing ones or update existing ones
        foreach ($order as $name => $index) {
            $query = $builder->where('name', $name)->get();
            $existing = $query ? $query->getRow() : null;
            if ($existing) {
                $builder->where('id', $existing->id)->update(['order_index' => $index, 'is_active' => 1]);
            } else {
                $builder->insert([
                    'name' => $name,
                    'order_index' => $index,
                    'is_active' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
    }

    public function down()
    {
        // No down migration
    }
}
