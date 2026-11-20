<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddQueueLeases extends Migration
{
    public function up()
    {
        foreach ([
            'claim_token' => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => true],
            'reserved_until' => ['type' => 'DATETIME', 'null' => true],
        ] as $name => $definition) {
            if (!$this->db->fieldExists($name, 'queue_jobs')) {
                $this->forge->addColumn('queue_jobs', [$name => $definition]);
            }
        }
    }

    public function down()
    {
        $this->forge->dropColumn('queue_jobs', ['claim_token', 'reserved_until']);
    }
}
