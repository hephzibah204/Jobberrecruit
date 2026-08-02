<?php

namespace App\Models;

use CodeIgniter\Model;

class AutomationStepModel extends Model
{
    protected $table            = 'automation_steps';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = ['automation_id', 'step_order', 'template_id', 'delay_minutes', 'condition_json'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
