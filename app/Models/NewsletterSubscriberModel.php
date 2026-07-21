<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsletterSubscriberModel extends Model
{
    protected $table            = 'newsletter_subscribers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'email', 'user_id', 'is_active', 'first_name', 'last_name',
        'phone', 'type', 'status', 'tags', 'custom_fields', 'engagement_score',
        'last_opened_at', 'last_clicked_at', 'signup_source', 'timezone',
        'language_preference', 'gdpr_consent', 'consent_date', 'ip_address'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
