<?php

namespace App\Models;

use CodeIgniter\Model;

class AutomationSubscriberModel extends Model
{
    protected $table            = 'automation_subscribers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = ['automation_id', 'subscriber_id', 'current_step_id', 'status', 'enrolled_at', 'next_step_at', 'completed_at'];

    // Timestamps handled manually for these custom fields, except if we want CI to handle created/updated
    // But since the schema has enrolled_at, we can let CI handle nothing and do it manually.
    protected $useTimestamps = false;
}
