<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PatchProductionPayments extends Migration
{
    public function up()
    {
        $this->db->resetDataCache();
        if ($this->db->tableExists('payments')) {
            $fields = [
                'payment_method' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'null'       => true,
                    'after'      => 'status'
                ],
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
                    'type'  => 'DATETIME',
                    'null'  => true,
                    'after' => 'metadata'
                ],
            ];

            foreach ($fields as $name => $config) {
                if (!$this->db->fieldExists($name, 'payments')) {
                    $this->forge->addColumn('payments', [$name => $config]);
                }
            }
        }
    }

    public function down()
    {
        // No down migration for safety in production
    }
}
