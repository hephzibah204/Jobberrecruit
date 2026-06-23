<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEditCountToJobs extends Migration
{
    public function up()
    {
        $this->forge->addColumn('jobs', [
            'edit_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'null' => false,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('jobs', 'edit_count');
    }
}
