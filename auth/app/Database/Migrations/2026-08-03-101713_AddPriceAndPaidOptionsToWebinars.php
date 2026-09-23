<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPriceAndPaidOptionsToWebinars extends Migration
{
    public function up()
    {
        $fields = [
            'access_type' => [
                'type'       => 'ENUM',
                'constraint' => ['free', 'paid'],
                'default'    => 'free',
                'after'      => 'meeting_link',
            ],
            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
                'after'      => 'access_type',
            ],
        ];

        $this->forge->addColumn('webinars', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('webinars', ['access_type', 'price']);
    }
}
