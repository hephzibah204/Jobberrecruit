<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddActionUrlToJobNotifications extends Migration
{
    public function up()
    {
        // Add action_url column if not already present
        $fields = $this->db->getFieldNames('job_notifications');
        if (!in_array('action_url', $fields)) {
            $this->forge->addColumn('job_notifications', [
                'action_url' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 512,
                    'null'       => true,
                    'after'      => 'read_at',
                ],
            ]);
        }
    }

    public function down()
    {
        $fields = $this->db->getFieldNames('job_notifications');
        if (in_array('action_url', $fields)) {
            $this->forge->dropColumn('job_notifications', 'action_url');
        }
    }
}