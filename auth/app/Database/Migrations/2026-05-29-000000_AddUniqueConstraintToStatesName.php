<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUniqueConstraintToStatesName extends Migration
{
    public function up()
    {
        // SQLite-compatible duplicate removal
        $db = $this->db;
        $tableName = $db->prefixTable('states');
        $subquery = $db->query("SELECT MIN(id) as min_id, name FROM {$tableName} GROUP BY name HAVING COUNT(*) > 1")->getResultArray();
        
        foreach ($subquery as $row) {
            $db->query("DELETE FROM {$tableName} WHERE name = ? AND id != ?", [$row['name'], $row['min_id']]);
        }

        // Add unique constraint on name
        if ($this->db->getPlatform() === 'SQLite3') {
            $tableName = $this->db->prefixTable('states');
            $this->db->query("CREATE UNIQUE INDEX IF NOT EXISTS unique_state_name ON {$tableName} (name)");
            $this->db->query("CREATE UNIQUE INDEX IF NOT EXISTS unique_state_slug ON {$tableName} (slug)");
        } else {
            $this->forge->addUniqueKey('name', 'unique_state_name');
            $this->forge->addUniqueKey('slug', 'unique_state_slug');
            $this->forge->processIndexes('states');
        }
    }

    public function down()
    {
        $this->forge->dropKey('states', 'unique_state_name');
        $this->forge->dropKey('states', 'unique_state_slug');
    }
}
