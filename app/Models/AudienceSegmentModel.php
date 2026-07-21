<?php

namespace App\Models;

use CodeIgniter\Model;

class AudienceSegmentModel extends Model
{
    protected $table            = 'audience_segments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name', 'description', 'type', 'criteria_json', 'user_count', 'last_synced_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
