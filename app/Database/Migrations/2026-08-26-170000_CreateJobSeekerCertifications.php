<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJobSeekerCertifications extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('job_seeker_certifications')) {
            $this->forge->addField([
                'id'                   => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'job_seeker_id'        => ['type' => 'INT', 'unsigned' => true],
                'name'                 => ['type' => 'VARCHAR', 'constraint' => 255],
                'issuing_organization' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'credential_id'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'credential_url'       => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
                'issue_month'          => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
                'issue_year'           => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
                'does_not_expire'      => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
                'expiry_month'         => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
                'expiry_year'          => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
                'sort_order'           => ['type' => 'INT', 'default' => 0],
                'created_at'           => ['type' => 'DATETIME', 'null' => true],
                'updated_at'           => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey(['job_seeker_id', 'sort_order']);
            $this->forge->addForeignKey('job_seeker_id', 'job_seekers', 'id', 'CASCADE', 'CASCADE');
            $this->forge->createTable('job_seeker_certifications', true);
        }
    }

    public function down()
    {
        $this->forge->dropTable('job_seeker_certifications', true);
    }
}
