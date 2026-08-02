<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNotificationPrefsToJobSeekers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('job_seekers', [
            'notify_job_alerts' => [
                'type'    => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'null'    => false,
                'after'   => 'is_visible',
            ],
            'notify_application_updates' => [
                'type'    => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'null'    => false,
                'after'   => 'notify_job_alerts',
            ],
            'notify_messages' => [
                'type'    => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'null'    => false,
                'after'   => 'notify_application_updates',
            ],
            'notify_marketing' => [
                'type'    => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'null'    => false,
                'after'   => 'notify_messages',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('job_seekers', [
            'notify_job_alerts',
            'notify_application_updates',
            'notify_messages',
            'notify_marketing',
        ]);
    }
}
