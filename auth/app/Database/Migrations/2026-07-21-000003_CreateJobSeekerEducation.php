<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJobSeekerEducation extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'job_seeker_id'  => ['type' => 'INT', 'unsigned' => true],
            'degree'         => ['type' => 'VARCHAR', 'constraint' => 255],
            'field_of_study' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'school'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'start_year'     => ['type' => 'VARCHAR', 'constraint' => 4, 'null' => true],
            'end_year'       => ['type' => 'VARCHAR', 'constraint' => 4, 'null' => true],
            'grade'          => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'sort_order'     => ['type' => 'INT', 'default' => 0],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['job_seeker_id', 'sort_order']);
        $this->forge->addForeignKey('job_seeker_id', 'job_seekers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('job_seeker_education', true);
    }

    public function down()
    {
        $this->forge->dropTable('job_seeker_education', true);
    }
}
