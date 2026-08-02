<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpgradeNewslettersToCampaigns extends Migration
{
    public function up()
    {
        $fields = [
            'preheader_text' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'content_text' => ['type' => 'TEXT', 'null' => true],
            'template_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'brand_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'scheduled_at' => ['type' => 'DATETIME', 'null' => true],
            'completed_at' => ['type' => 'DATETIME', 'null' => true],
            'created_by' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'utm_campaign' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'utm_source' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'utm_medium' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'ab_test_enabled' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'ab_test_variant_a' => ['type' => 'TEXT', 'null' => true],
            'ab_test_variant_b' => ['type' => 'TEXT', 'null' => true],
            'winner_criteria' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'winner_percentage' => ['type' => 'INT', 'constraint' => 3, 'default' => 50],
        ];
        
        // Only add columns if they don't already exist
        $existingCols = $this->db->getFieldNames('newsletters');
        $fieldsToAdd  = [];
        foreach ($fields as $col => $def) {
            if (!in_array($col, $existingCols)) {
                $fieldsToAdd[$col] = $def;
            }
        }
        if (!empty($fieldsToAdd)) {
            $this->forge->addColumn('newsletters', $fieldsToAdd);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('newsletters', [
            'preheader_text', 'content_text', 'template_id', 'brand_id', 'scheduled_at', 'completed_at',
            'created_by', 'utm_campaign', 'utm_source', 'utm_medium', 'ab_test_enabled', 'ab_test_variant_a',
            'ab_test_variant_b', 'winner_criteria', 'winner_percentage'
        ]);
    }
}
