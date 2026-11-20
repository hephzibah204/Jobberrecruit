<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJobRepostRequests extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'request_key' => ['type' => 'VARCHAR', 'constraint' => 64],
            'employer_id' => ['type' => 'INT'],
            'source_job_id' => ['type' => 'INT'],
        ]);
        $this->forge->addKey('request_key', true);
        $this->forge->createTable('job_repost_requests', true);
    }

    public function down()
    {
        $this->forge->dropTable('job_repost_requests', true);
    }
}
