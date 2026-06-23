<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsVisibleToJobSeekers extends Migration
{
    public function up()
    {
        $fields = [
            'is_visible' => [
                'type'       => 'BOOLEAN',
                'default'    => true,
                'null'       => false,
            ],
        ];
        $this->forge->addColumn('job_seekers', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('job_seekers', 'is_visible');
    }
}
