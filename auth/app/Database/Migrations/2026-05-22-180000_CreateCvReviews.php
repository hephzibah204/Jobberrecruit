<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCvReviews extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id'           => ['type' => 'INT', 'unsigned' => true],
            'plan'              => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'basic'],
            'amount'            => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'payment_reference' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'payment_status'    => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'pending'],
            'file_path'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'full_name'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'email'             => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'phone'             => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'industry'          => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'target_role'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'feedback_request'  => ['type' => 'TEXT', 'null' => true],
            'admin_notes'       => ['type' => 'TEXT', 'null' => true],
            'status'            => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'pending'],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('payment_reference');
        $this->forge->createTable('cv_reviews');
    }

    public function down()
    {
        $this->forge->dropTable('cv_reviews');
    }
}
