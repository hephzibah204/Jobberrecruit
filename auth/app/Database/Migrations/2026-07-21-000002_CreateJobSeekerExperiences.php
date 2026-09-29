<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJobSeekerExperiences extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'job_seeker_id' => ['type' => 'INT', 'unsigned' => true],
            'job_title'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'company'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'location'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'start_date'    => ['type' => 'DATE', 'null' => true],
            'end_date'      => ['type' => 'DATE', 'null' => true],
            'is_current'    => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'description'   => ['type' => 'TEXT', 'null' => true],
            'sort_order'    => ['type' => 'INT', 'default' => 0],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['job_seeker_id', 'sort_order']);
        $this->forge->addForeignKey('job_seeker_id', 'job_seekers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('job_seeker_experiences', true);
    }

    public function down()
    {
        $this->forge->dropTable('job_seeker_experiences', true);
    }
}
