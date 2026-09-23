<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddReferrerIdToTestAttempts extends Migration
{
    public function up()
    {
        $this->forge->addColumn('test_attempts', [
            'referrer_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'job_id',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('test_attempts', 'referrer_id');
    }
}
