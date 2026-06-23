<?php

// Migration: AddUserTypeToUsers.php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserTypeToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'user_type' => [
                'type'       => 'ENUM',
                'constraint' => ['job_seeker', 'employer', 'admin'],
                'default'    => 'job_seeker',
                'after'      => 'active' // place after active column
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'user_type');
    }
}
