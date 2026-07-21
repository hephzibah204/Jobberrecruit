<?php

namespace App\Models;

use CodeIgniter\Model;

class AutomationModel extends Model
{
    protected $table            = 'automations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'trigger_event', 'status'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
