<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJobSalaryVisibility extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('show_salary', 'jobs')) {
            $this->forge->addColumn('jobs', ['show_salary' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1]]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('show_salary', 'jobs')) $this->forge->dropColumn('jobs', 'show_salary');
    }
}
