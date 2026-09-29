<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddWeeklyDigestToJobSeekers extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('job_seekers') && !$this->db->fieldExists('notify_weekly_digest', 'job_seekers')) {
            $this->forge->addColumn('job_seekers', [
                'notify_weekly_digest' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 1,
                    'null'       => false,
                    'after'      => 'notify_job_alerts',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('job_seekers') && $this->db->fieldExists('notify_weekly_digest', 'job_seekers')) {
            $this->forge->dropColumn('job_seekers', 'notify_weekly_digest');
        }
    }
}