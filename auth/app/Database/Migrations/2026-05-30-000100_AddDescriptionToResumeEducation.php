<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDescriptionToResumeEducation extends Migration
{
    public function up()
    {
        $fields = [
            'description' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'graduation_date'
            ]
        ];
        $this->forge->addColumn('resume_education', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('resume_education', 'description');
    }
}
