<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmailTracking extends Migration
{
    public function up()
    {
        // campaign_stats
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'campaign_id' => ['type' => 'INT', 'unsigned' => true],
            'total_recipients' => ['type' => 'INT', 'default' => 0],
            'delivered' => ['type' => 'INT', 'default' => 0],
            'bounced' => ['type' => 'INT', 'default' => 0],
            'complained' => ['type' => 'INT', 'default' => 0],
            'opens_unique' => ['type' => 'INT', 'default' => 0],
            'opens_total' => ['type' => 'INT', 'default' => 0],
            'clicks_unique' => ['type' => 'INT', 'default' => 0],
            'clicks_total' => ['type' => 'INT', 'default' => 0],
            'unsubscribes' => ['type' => 'INT', 'default' => 0],
            'device_breakdown' => ['type' => 'TEXT', 'null' => true], // JSON
            'client_breakdown' => ['type' => 'TEXT', 'null' => true], // JSON
            'geo_breakdown' => ['type' => 'TEXT', 'null' => true], // JSON
            'hourly_open_heatmap' => ['type' => 'TEXT', 'null' => true], // JSON
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('campaign_id');
        $this->forge->createTable('campaign_stats');

        // email_logs
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'campaign_id' => ['type' => 'INT', 'unsigned' => true],
            'subscriber_id' => ['type' => 'INT', 'unsigned' => true],
            'email_address' => ['type' => 'VARCHAR', 'constraint' => 255],
            'sent_at' => ['type' => 'DATETIME', 'null' => true],
            'delivered_at' => ['type' => 'DATETIME', 'null' => true],
            'opened_at' => ['type' => 'DATETIME', 'null' => true],
            'open_count' => ['type' => 'INT', 'default' => 0],
            'last_opened_at' => ['type' => 'DATETIME', 'null' => true],
            'clicked_at' => ['type' => 'DATETIME', 'null' => true],
            'click_count' => ['type' => 'INT', 'default' => 0],
            'last_clicked_at' => ['type' => 'DATETIME', 'null' => true],
            'links_clicked' => ['type' => 'TEXT', 'null' => true], // JSON array
            'ip_address' => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => true],
            'user_agent' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'device_type' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'unsubscribe_at' => ['type' => 'DATETIME', 'null' => true],
            'bounce_reason' => ['type' => 'TEXT', 'null' => true],
            'complaint_type' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('campaign_id');
        $this->forge->addKey('subscriber_id');
        $this->forge->createTable('email_logs');
    }

    public function down()
    {
        $this->forge->dropTable('email_logs');
        $this->forge->dropTable('campaign_stats');
    }
}
