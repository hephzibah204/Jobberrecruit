<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddReviewedAtToJobApplications extends Migration
{
    public function up()
    {
        $fields = $this->db->getFieldNames('job_applications');

        if (! in_array('reviewed_at', $fields, true)) {
            $this->forge->addColumn('job_applications', [
                'reviewed_at' => [
                    'type'  => 'DATETIME',
                    'null'  => true,
                    'after' => 'status_message',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->getPlatform() !== 'SQLite3') {
            $this->forge->dropColumn('job_applications', 'reviewed_at');
        }
    }
}
