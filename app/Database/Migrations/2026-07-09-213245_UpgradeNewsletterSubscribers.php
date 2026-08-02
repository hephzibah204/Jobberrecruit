<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpgradeNewsletterSubscribers extends Migration
{
    public function up()
    {
        $fields = [
            'phone' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'type' => ['type' => 'ENUM', 'constraint' => ['candidate', 'employer', 'general', 'lead'], 'default' => 'general'],
            'status' => ['type' => 'ENUM', 'constraint' => ['active', 'unsubscribed', 'bounced', 'complained', 'inactive'], 'default' => 'active'],
            'tags' => ['type' => 'TEXT', 'null' => true], // JSON
            'custom_fields' => ['type' => 'TEXT', 'null' => true], // JSON
            'engagement_score' => ['type' => 'INT', 'constraint' => 3, 'default' => 0],
            'last_opened_at' => ['type' => 'DATETIME', 'null' => true],
            'last_clicked_at' => ['type' => 'DATETIME', 'null' => true],
            'signup_source' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'timezone' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'language_preference' => ['type' => 'VARCHAR', 'constraint' => 10, 'default' => 'en'],
            'gdpr_consent' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'consent_date' => ['type' => 'DATETIME', 'null' => true],
            'ip_address' => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ];

        $this->forge->addColumn('newsletter_subscribers', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('newsletter_subscribers', [
            'phone', 'type', 'status', 'tags', 'custom_fields', 'engagement_score',
            'last_opened_at', 'last_clicked_at', 'signup_source', 'timezone',
            'language_preference', 'gdpr_consent', 'consent_date', 'ip_address', 'updated_at'
        ]);
    }
}
