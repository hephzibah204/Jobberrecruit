<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddReadAtToMessagingTables extends Migration
{
    public function up()
    {
        // Add read_at to messages table if it doesn't exist
        if ($this->db->tableExists('messages')) {
            if (!$this->db->fieldExists('read_at', 'messages')) {
                $this->forge->addColumn('messages', [
                    'read_at' => [
                        'type' => 'DATETIME',
                        'null' => true,
                    ],
                ]);
            }
        }

        // Add read_at to job_notifications table if it doesn't exist
        if ($this->db->tableExists('job_notifications')) {
            if (!$this->db->fieldExists('read_at', 'job_notifications')) {
                $this->forge->addColumn('job_notifications', [
                    'read_at' => [
                        'type' => 'DATETIME',
                        'null' => true,
                    ],
                ]);
            }
        }
        
        // Just in case there is a notifications table
        if ($this->db->tableExists('notifications')) {
            if (!$this->db->fieldExists('read_at', 'notifications')) {
                $this->forge->addColumn('notifications', [
                    'read_at' => [
                        'type' => 'DATETIME',
                        'null' => true,
                    ],
                ]);
            }
        }
    }

    public function down()
    {
        if ($this->db->tableExists('messages') && $this->db->fieldExists('read_at', 'messages')) {
            $this->forge->dropColumn('messages', 'read_at');
        }
        if ($this->db->tableExists('job_notifications') && $this->db->fieldExists('read_at', 'job_notifications')) {
            $this->forge->dropColumn('job_notifications', 'read_at');
        }
        if ($this->db->tableExists('notifications') && $this->db->fieldExists('read_at', 'notifications')) {
            $this->forge->dropColumn('notifications', 'read_at');
        }
    }
}
