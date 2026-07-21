<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlignLocalDatabaseSchema extends Migration
{
    public function up()
    {
        $this->db->resetDataCache();

        // 1. payments table columns
        if ($this->db->tableExists('payments')) {
            $paymentFields = [];
            if (!$this->db->fieldExists('plan_id', 'payments')) {
                $paymentFields['plan_id'] = ['type' => 'INT', 'constraint' => 11, 'null' => true, 'after' => 'user_id'];
            }
            if (!$this->db->fieldExists('gateway_response', 'payments')) {
                $paymentFields['gateway_response'] = ['type' => 'TEXT', 'null' => true, 'after' => 'status'];
            }
            if (!$this->db->fieldExists('channel', 'payments')) {
                $paymentFields['channel'] = ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true, 'after' => 'gateway_response'];
            }
            if (!$this->db->fieldExists('ip_address', 'payments')) {
                $paymentFields['ip_address'] = ['type' => 'VARCHAR', 'constraint' => 45, 'null' => true, 'after' => 'channel'];
            }
            if (!empty($paymentFields)) {
                $this->forge->addColumn('payments', $paymentFields);
            }
        }

        // 2. users table columns
        if ($this->db->tableExists('users')) {
            $userFields = [];
            if (!$this->db->fieldExists('certificate_name', 'users')) {
                $userFields['certificate_name'] = ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true];
            }
            if (!$this->db->fieldExists('engagement_score', 'users')) {
                $userFields['engagement_score'] = ['type' => 'INT', 'constraint' => 11, 'default' => 0];
            }
            if (!$this->db->fieldExists('predictive_status', 'users')) {
                $userFields['predictive_status'] = ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true];
            }
            if (!$this->db->fieldExists('paystack_customer_code', 'users')) {
                $userFields['paystack_customer_code'] = ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true];
            }
            if (!empty($userFields)) {
                $this->forge->addColumn('users', $userFields);
            }
        }

        // 3. wallet_transactions table columns
        if ($this->db->tableExists('wallet_transactions')) {
            $walletFields = [];
            if (!$this->db->fieldExists('balance_before', 'wallet_transactions')) {
                $walletFields['balance_before'] = ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00];
            }
            if (!$this->db->fieldExists('balance_after', 'wallet_transactions')) {
                $walletFields['balance_after'] = ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00];
            }
            if (!$this->db->fieldExists('source', 'wallet_transactions')) {
                $walletFields['source'] = ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true];
            }
            if (!$this->db->fieldExists('source_id', 'wallet_transactions')) {
                $walletFields['source_id'] = ['type' => 'INT', 'constraint' => 11, 'null' => true];
            }
            if (!empty($walletFields)) {
                $this->forge->addColumn('wallet_transactions', $walletFields);
            }
        }

        // 4. job_seekers table columns
        if ($this->db->tableExists('job_seekers')) {
            if (!$this->db->fieldExists('share_contact', 'job_seekers')) {
                $this->forge->addColumn('job_seekers', [
                    'share_contact' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0]
                ]);
            }
        }

        // 5. saved_jobs table columns
        if ($this->db->tableExists('saved_jobs')) {
            if (!$this->db->fieldExists('job_seeker_id', 'saved_jobs')) {
                $this->forge->addColumn('saved_jobs', [
                    'job_seeker_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true, 'after' => 'user_id']
                ]);
            }
        }

        // 6. blogs table columns
        if ($this->db->tableExists('blogs')) {
            $blogFields = [];
            if (!$this->db->fieldExists('preview_token', 'blogs')) {
                $blogFields['preview_token'] = ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true];
            }
            if (!$this->db->fieldExists('views', 'blogs')) {
                $blogFields['views'] = ['type' => 'INT', 'constraint' => 11, 'default' => 0];
            }
            if (!empty($blogFields)) {
                $this->forge->addColumn('blogs', $blogFields);
            }
        }

        // 7. courses table columns
        if ($this->db->tableExists('courses')) {
            $courseFields = [];
            if (!$this->db->fieldExists('external_url', 'courses')) {
                $courseFields['external_url'] = ['type' => 'VARCHAR', 'constraint' => 2048, 'null' => true];
            }
            if (!$this->db->fieldExists('status', 'courses')) {
                $courseFields['status'] = ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true, 'default' => 'active'];
            }
            if (!empty($courseFields)) {
                $this->forge->addColumn('courses', $courseFields);
            }
        }

        // 8. job_alerts table columns
        if ($this->db->tableExists('job_alerts')) {
            $alertFields = [];
            if (!$this->db->fieldExists('delivery_time', 'job_alerts')) {
                $alertFields['delivery_time'] = ['type' => 'TIME', 'null' => true];
            }
            if (!$this->db->fieldExists('channel', 'job_alerts')) {
                $alertFields['channel'] = ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true, 'default' => 'email'];
            }
            if (!$this->db->fieldExists('opens', 'job_alerts')) {
                $alertFields['opens'] = ['type' => 'INT', 'constraint' => 11, 'default' => 0];
            }
            if (!$this->db->fieldExists('clicks', 'job_alerts')) {
                $alertFields['clicks'] = ['type' => 'INT', 'constraint' => 11, 'default' => 0];
            }
            if (!$this->db->fieldExists('last_sent_at', 'job_alerts')) {
                $alertFields['last_sent_at'] = ['type' => 'DATETIME', 'null' => true];
            }
            if (!$this->db->fieldExists('is_active', 'job_alerts')) {
                $alertFields['is_active'] = ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1];
            }
            if (!$this->db->fieldExists('is_paused', 'job_alerts')) {
                $alertFields['is_paused'] = ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0];
            }
            if (!$this->db->fieldExists('snooze_until', 'job_alerts')) {
                $alertFields['snooze_until'] = ['type' => 'DATETIME', 'null' => true];
            }
            if (!empty($alertFields)) {
                $this->forge->addColumn('job_alerts', $alertFields);
            }
        }

        // 9. resumes table columns
        if ($this->db->tableExists('resumes')) {
            $resumeFields = [];
            if (!$this->db->fieldExists('ai_optimization_meta', 'resumes')) {
                $resumeFields['ai_optimization_meta'] = ['type' => 'TEXT', 'null' => true];
            }
            if (!$this->db->fieldExists('target_job_description', 'resumes')) {
                $resumeFields['target_job_description'] = ['type' => 'TEXT', 'null' => true];
            }
            if (!empty($resumeFields)) {
                $this->forge->addColumn('resumes', $resumeFields);
            }
        }
    }

    public function down()
    {
        // Down migration can be left empty
    }
}
