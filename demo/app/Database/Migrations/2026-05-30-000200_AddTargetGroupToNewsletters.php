<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTargetGroupToNewsletters extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('newsletters') && !$this->db->fieldExists('target_group', 'newsletters')) {
            $fields = [
                'target_group' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '30',
                    'default'    => 'all',
                    'null'       => false,
                    'after'      => 'content'
                ]
            ];
            $this->forge->addColumn('newsletters', $fields);
        }
    }

    public function down()
    {
        if ($this->db->getPlatform() === 'SQLite3') {
            return;
        }

        if ($this->db->tableExists('newsletters') && $this->db->fieldExists('target_group', 'newsletters')) {
            $this->forge->dropColumn('newsletters', 'target_group');
        }
    }
}
