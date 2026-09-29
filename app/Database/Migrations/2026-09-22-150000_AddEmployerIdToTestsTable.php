<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEmployerIdToTestsTable extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('employer_id', 'tests')) {
            $fields = [
                'employer_id' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'unsigned'   => true,
                    'null'       => true,
                    'default'    => null,
                    'after'      => 'id',
                ],
            ];
            $this->forge->addColumn('tests', $fields);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('employer_id', 'tests')) {
            $this->forge->dropColumn('tests', 'employer_id');
        }
    }
}
