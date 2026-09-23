<?php

namespace App\Models;

use CodeIgniter\Model;

class ReferralModel extends Model
{
    protected $table            = 'referrals';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'referrer_id',
        'referee_id',
        'code',
        'status',
        'reward_amount',
        'created_at',
    ];

    protected $useTimestamps = false;
}
