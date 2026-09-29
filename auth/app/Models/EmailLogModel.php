<?php

namespace App\Models;

use CodeIgniter\Model;

class EmailLogModel extends Model
{
    protected $table            = 'email_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'campaign_id', 'subscriber_id', 'email_address', 'sent_at', 'delivered_at',
        'opened_at', 'open_count', 'last_opened_at', 'clicked_at', 'click_count',
        'last_clicked_at', 'links_clicked', 'ip_address', 'user_agent', 'device_type',
        'unsubscribe_at', 'bounce_reason', 'complaint_type'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
