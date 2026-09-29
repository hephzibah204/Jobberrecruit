<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAffiliateSettings extends Migration
{
    public function up()
    {
        // 1. Affiliate Settings
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'key' => ['type' => 'VARCHAR', 'constraint' => '100', 'unique' => true],
            'value' => ['type' => 'TEXT'],
            'description' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('affiliate_settings', true);

        // Seed default settings
        $db = \Config\Database::connect();
        $db->table('affiliate_settings')->insertBatch([
            [
                'key' => 'referral_reward_employer',
                'value' => '1000', // 1000 NGN or credits
                'description' => 'Reward amount for referring a new employer who makes a payment',
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'referral_reward_candidate',
                'value' => '200',
                'description' => 'Reward amount for referring a new candidate who completes their profile',
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'referral_program_active',
                'value' => '1',
                'description' => 'Is the referral program globally enabled?',
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ]);

        // 2. Add referred_by to users table
        $fields = [
            'referred_by' => ['type' => 'INT', 'unsigned' => true, 'null' => true, 'after' => 'id'],
            'referral_code' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true, 'after' => 'referred_by'],
        ];

        // SQLite doesn't support UNIQUE in ALTER TABLE ADD COLUMN
        if ($this->db->getPlatform() !== 'SQLite3') {
            $fields['referral_code']['unique'] = true;
        }

        $this->forge->addColumn('users', $fields);

        if ($this->db->getPlatform() === 'SQLite3') {
            $this->db->query('CREATE UNIQUE INDEX IF NOT EXISTS idx_referral_code ON users (referral_code)');
        }

        if ($this->db->getPlatform() !== 'SQLite3') {
            $this->forge->addForeignKey('referred_by', 'users', 'id', 'SET NULL', 'CASCADE');
        }
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['referred_by', 'referral_code']);
        $this->forge->dropTable('affiliate_settings', true);
    }
}
