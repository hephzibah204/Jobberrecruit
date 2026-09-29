<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProfileFieldsToEmployers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('employers', [
            'tagline'        => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true, 'after' => 'company_name'],
            'company_type'   => ['type' => 'VARCHAR', 'constraint' => 60, 'null' => true, 'after' => 'company_size'],
            'founded_year'   => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true, 'after' => 'company_type'],
            'remote_policy'  => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true, 'after' => 'founded_year'],
            'whatsapp'       => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true, 'after' => 'contact_phone'],
            'benefits'       => ['type' => 'TEXT', 'null' => true, 'after' => 'description'],
            'hiring_process' => ['type' => 'TEXT', 'null' => true, 'after' => 'benefits'],
            'rc_number'      => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true, 'after' => 'tin_number'],
            'linkedin'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'twitter'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'facebook'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'instagram'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('employers', [
            'tagline', 'company_type', 'founded_year', 'remote_policy', 'whatsapp',
            'benefits', 'hiring_process', 'rc_number', 'linkedin', 'twitter', 'facebook', 'instagram',
        ]);
    }
}
