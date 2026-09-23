<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixMissingColumns extends Migration
{
    public function up()
    {
        $this->db->resetDataCache();
        // 1. payments.paid_at
        if ($this->db->tableExists('payments')) {
            if (!$this->db->fieldExists('paid_at', 'payments')) {
                $this->forge->addColumn('payments', [
                    'paid_at' => [
                        'type' => 'DATETIME',
                        'null' => true,
                    ],
                ]);
            }
        }

        // 2. webinars.flyer_image
        if ($this->db->tableExists('webinars')) {
            if (!$this->db->fieldExists('flyer_image', 'webinars')) {
                $this->forge->addColumn('webinars', [
                    'flyer_image' => [
                        'type' => 'VARCHAR',
                        'constraint' => 255,
                        'null' => true,
                    ],
                ]);
            }
        }

        // 3. blogs.tags
        if ($this->db->tableExists('blogs')) {
            if (!$this->db->fieldExists('tags', 'blogs')) {
                $this->forge->addColumn('blogs', [
                    'tags' => [
                        'type' => 'VARCHAR',
                        'constraint' => 255,
                        'null' => true,
                    ],
                ]);
            }
        }

        // 4. job_seekers.consent
        if ($this->db->tableExists('job_seekers')) {
            if (!$this->db->fieldExists('consent', 'job_seekers')) {
                $this->forge->addColumn('job_seekers', [
                    'consent' => [
                        'type' => 'TINYINT',
                        'constraint' => 1,
                        'default' => 1,
                        'null' => false,
                    ],
                ]);
            }
        }

        // 5. users.is_archived
        if ($this->db->tableExists('users')) {
            if (!$this->db->fieldExists('is_archived', 'users')) {
                $this->forge->addColumn('users', [
                    'is_archived' => [
                        'type' => 'TINYINT',
                        'constraint' => 1,
                        'default' => 0,
                        'null' => false,
                    ],
                ]);
            }
        }
    }

    public function down()
    {
        if ($this->db->getPlatform() === 'SQLite3') {
            return; // SQLite has limited dropColumn support, skip on testing
        }

        if ($this->db->fieldExists('paid_at', 'payments')) {
            $this->forge->dropColumn('payments', 'paid_at');
        }
        if ($this->db->fieldExists('flyer_image', 'webinars')) {
            $this->forge->dropColumn('webinars', 'flyer_image');
        }
        if ($this->db->fieldExists('tags', 'blogs')) {
            $this->forge->dropColumn('blogs', 'tags');
        }
        if ($this->db->fieldExists('consent', 'job_seekers')) {
            $this->forge->dropColumn('job_seekers', 'consent');
        }
        if ($this->db->fieldExists('is_archived', 'users')) {
            $this->forge->dropColumn('users', 'is_archived');
        }
    }
}
