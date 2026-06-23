<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFlyerToWebinars extends Migration
{
    public function up()
    {
        $this->forge->addColumn('webinars', [
            'flyer_path' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'meeting_link'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('webinars', 'flyer_path');
    }
}
