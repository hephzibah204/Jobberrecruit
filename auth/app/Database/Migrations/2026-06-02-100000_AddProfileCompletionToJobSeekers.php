<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProfileCompletionToJobSeekers extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('job_seekers') && !$this->db->fieldExists('profile_completion', 'job_seekers')) {
            $this->forge->addColumn('job_seekers', [
                'profile_completion' => [
                    'type'       => 'TINYINT',
                    'constraint' => 3,
                    'unsigned'   => true,
                    'default'    => 0,
                    'after'      => 'is_verified',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->getPlatform() !== 'SQLite3') {
            $this->forge->dropColumn('job_seekers', 'profile_completion');
        }
    }
}
