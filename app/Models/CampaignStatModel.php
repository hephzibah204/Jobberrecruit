<?php

namespace App\Models;

use CodeIgniter\Model;

class CampaignStatModel extends Model
{
    protected $table            = 'campaign_stats';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'campaign_id', 'total_recipients', 'delivered', 'bounced', 'complained',
        'opens_unique', 'opens_total', 'clicks_unique', 'clicks_total', 'unsubscribes',
        'device_breakdown', 'client_breakdown', 'geo_breakdown', 'hourly_open_heatmap'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
