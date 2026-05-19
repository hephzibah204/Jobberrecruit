<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddOptionsToJobQuestions extends Migration
{
    public function up()
    {
        $this->forge->addColumn('job_questions', [
            'options' => ['type' => 'TEXT', 'null' => true, 'after' => 'question_type'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('job_questions', 'options');
    }
}
