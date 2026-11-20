<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SecureAssessmentAndSubscriptionWorkflows extends Migration
{
    public function up()
    {
        foreach ([
            'subscription_code' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'email_token' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'authorization' => ['type' => 'TEXT', 'null' => true],
            'will_not_renew' => ['type' => 'TINYINT', 'default' => 0],
        ] as $field => $definition) {
            if (!$this->db->fieldExists($field, 'user_subscriptions')) $this->forge->addColumn('user_subscriptions', [$field => $definition]);
        }
        foreach (['paystack_subscription_code', 'paystack_subscription_id'] as $legacy) {
            if ($this->db->fieldExists($legacy, 'user_subscriptions')) {
                $this->db->query("UPDATE user_subscriptions SET subscription_code = {$legacy} WHERE (subscription_code IS NULL OR subscription_code = '') AND {$legacy} LIKE 'SUB_%'");
            }
        }
        if ($this->db->fieldExists('paystack_email_token', 'user_subscriptions')) {
            $this->db->query("UPDATE user_subscriptions SET email_token = paystack_email_token WHERE email_token IS NULL");
        }
        if (!$this->db->fieldExists('test_questions', 'courses')) $this->forge->addColumn('courses', ['test_questions' => ['type' => 'TEXT', 'null' => true]]);
        foreach (['test_attempts' => ['type' => 'INT', 'default' => 0], 'test_score' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true]] as $field => $definition) {
            if (!$this->db->fieldExists($field, 'course_enrollments')) $this->forge->addColumn('course_enrollments', [$field => $definition]);
        }
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true],
            'user_id' => ['type' => 'INT'], 'course_id' => ['type' => 'INT'], 'enrollment_id' => ['type' => 'INT'],
            'request_key' => ['type' => 'VARCHAR', 'constraint' => 64],
            'questions' => ['type' => 'TEXT'], 'answers' => ['type' => 'TEXT'],
            'score' => ['type' => 'DECIMAL', 'constraint' => '5,2'], 'created_at' => ['type' => 'DATETIME'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['user_id', 'course_id', 'request_key']);
        $this->forge->createTable('course_assessment_submissions', true);

        // Resolve legacy profile IDs only when recipient email is unambiguous.
        foreach ($this->db->table('aptitude_test_invitations')->get()->getResult() as $invitation) {
            if (empty($invitation->email)) continue;
            $users = $this->db->table('auth_identities')->select('user_id')->where('type', 'email_password')
                ->where('LOWER(secret)', strtolower(trim($invitation->email)))->get()->getResult();
            if (count($users) !== 1) continue;
            $userId = (int) $users[0]->user_id;
            $profile = $this->db->table('job_seekers')->where('user_id', $userId)->get()->getRow();
            if (!(int) $invitation->candidate_id || ($profile && (int) $invitation->candidate_id === (int) $profile->id)) {
                $this->db->table('aptitude_test_invitations')->where('id', $invitation->id)->update(['candidate_id' => $userId]);
            }
        }
    }

    public function down()
    {
        // Preserve financial linkage, canonical invitation ownership, and grading evidence.
    }
}
