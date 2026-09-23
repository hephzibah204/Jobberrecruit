<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUpdatedAtToMessages extends Migration
{
    public function up()
    {
        // First check if 'updated_at' exists in messages
        $db = \Config\Database::connect();
        
        if (!$db->fieldExists('updated_at', 'messages')) {
            $this->forge->addColumn('messages', [
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
        }
        
        // Also check created_at just in case
        if (!$db->fieldExists('created_at', 'messages')) {
            $this->forge->addColumn('messages', [
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();
        
        if ($db->fieldExists('updated_at', 'messages')) {
            $this->forge->dropColumn('messages', 'updated_at');
        }
        
        if ($db->fieldExists('created_at', 'messages')) {
            $this->forge->dropColumn('messages', 'created_at');
        }
    }
}
