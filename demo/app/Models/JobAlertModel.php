<?php

namespace App\Models;

use CodeIgniter\Model;

class JobAlertModel extends Model
{
    protected $table      = 'job_alerts';
    protected $primaryKey = 'id';
    protected $returnType = \App\Entities\JobAlert::class;

    protected $allowedFields = [
        'job_seeker_id',
        'keyword',
        'location_id',
        'frequency',
        'delivery_time',
        'channel',
        'opens',
        'clicks',
        'last_sent_at',
        'is_active',
        'is_paused',
        'snooze_until',
    ];

    protected $useTimestamps = true;
}
