<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLocationSEOColumnsToStates extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('slug', 'states')) {
            $this->forge->addColumn('states', ['slug' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true]]);
        }
        if (!$this->db->fieldExists('capital', 'states')) {
            $this->forge->addColumn('states', ['capital' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true]]);
        }
        if (!$this->db->fieldExists('region', 'states')) {
            $this->forge->addColumn('states', ['region' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true]]);
        }
        if (!$this->db->fieldExists('is_active', 'states')) {
            $this->forge->addColumn('states', ['is_active' => ['type' => 'TINYINT', 'default' => 1]]);
        }
        if (!$this->db->fieldExists('description', 'states')) {
            $this->forge->addColumn('states', ['description' => ['type' => 'TEXT', 'null' => true]]);
        }
        if (!$this->db->fieldExists('meta_description', 'states')) {
            $this->forge->addColumn('states', ['meta_description' => ['type' => 'TEXT', 'null' => true]]);
        }
        if (!$this->db->fieldExists('seo_h1', 'states')) {
            $this->forge->addColumn('states', ['seo_h1' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true]]);
        }
    }

    public function down()
    {
        foreach (['slug', 'capital', 'region', 'is_active', 'description', 'meta_description', 'seo_h1'] as $col) {
            if ($this->db->fieldExists($col, 'states')) {
                $this->forge->dropColumn('states', $col);
            }
        }
    }
}
