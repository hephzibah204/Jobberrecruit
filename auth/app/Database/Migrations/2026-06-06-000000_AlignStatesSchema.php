<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlignStatesSchema extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('states')) {
            $fields = [
                'slug' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '150',
                    'null'       => true,
                    'after'      => 'name'
                ],
                'capital' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'null'       => true,
                    'after'      => 'slug'
                ],
                'region' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'null'       => true,
                    'after'      => 'capital'
                ],
                'is_active' => [
                    'type'       => 'TINYINT',
                    'constraint' => '1',
                    'default'    => 1,
                    'after'      => 'region'
                ],
                'description' => [
                    'type'       => 'TEXT',
                    'null'       => true,
                    'after'      => 'is_active'
                ],
                'meta_description' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                    'null'       => true,
                    'after'      => 'description'
                ],
                'seo_h1' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                    'null'       => true,
                    'after'      => 'meta_description'
                ]
            ];

            // Add columns that don't exist
            $this->db->resetDataCache();
            foreach ($fields as $name => $def) {
                if (!$this->db->fieldExists($name, 'states')) {
                    $this->forge->addColumn('states', [$name => $def]);
                }
            }
        }
    }

    public function down()
    {
        // Don't drop columns in SQLite to avoid Forge bugs
        if ($this->db->getPlatform() === 'SQLite3') {
            return;
        }

        $fields = ['slug', 'capital', 'region', 'is_active', 'description', 'meta_description', 'seo_h1'];
        foreach ($fields as $field) {
            if ($this->db->tableExists('states') && $this->db->fieldExists($field, 'states')) {
                $this->forge->dropColumn('states', $field);
            }
        }
    }
}
