<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAiReviewColumnsToCvReviews extends Migration
{
    public function up()
    {
        $this->forge->addColumn('cv_reviews', [
            'ai_review'          => ['type' => 'LONGTEXT', 'null' => true, 'after' => 'admin_notes'],
            'review_mode'        => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'semi', 'after' => 'ai_review'],
            'reviewed_at'        => ['type' => 'DATETIME', 'null' => true, 'after' => 'review_mode'],
            'admin_id'           => ['type' => 'INT', 'unsigned' => true, 'null' => true, 'after' => 'reviewed_at'],
            'feedback_delivered' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'admin_id'],
            'delivered_at'       => ['type' => 'DATETIME', 'null' => true, 'after' => 'feedback_delivered'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('cv_reviews', ['ai_review', 'review_mode', 'reviewed_at', 'admin_id', 'feedback_delivered', 'delivered_at']);
    }
}
