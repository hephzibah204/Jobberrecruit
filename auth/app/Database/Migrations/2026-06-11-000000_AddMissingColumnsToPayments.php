<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMissingColumnsToPayments extends Migration
{
    public function up()
    {
        $this->db->resetDataCache();
        $fields = [
            'amount_paid' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
                'after'      => 'amount'
            ],
            'currency' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'default'    => 'NGN',
                'after'      => 'amount_paid'
            ],
            'paid_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'metadata'
            ],
        ];

        // Check each column individually to avoid failure if some exist
        foreach ($fields as $name => $config) {
            if (!$this->db->fieldExists($name, 'payments')) {
                $this->forge->addColumn('payments', [$name => $config]);
            }
        }
    }

    public function down()
    {
        if ($this->db->getPlatform() === 'SQLite3') {
            return;
        }
        $this->forge->dropColumn('payments', ['amount_paid', 'currency', 'paid_at']);
    }
}
