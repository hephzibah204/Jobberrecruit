<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsUrgentToJobs extends Migration
{
    public function up()
    {
        $this->forge->addColumn('jobs', [
            'is_urgent' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'is_featured'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('jobs', ['is_urgent']);
    }
}
