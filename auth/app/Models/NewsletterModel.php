<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsletterModel extends Model
{
    protected $table            = 'newsletters';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title', 'subject', 'target_group', 'content', 'status', 'sent_at',
        'preheader_text', 'content_text', 'template_id', 'brand_id', 'scheduled_at', 'completed_at',
        'created_by', 'utm_campaign', 'utm_source', 'utm_medium', 'ab_test_enabled', 'ab_test_variant_a',
        'ab_test_variant_b', 'winner_criteria', 'winner_percentage'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
